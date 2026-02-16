<?php
// Reusable mail helper. Uses PHPMailer if available; falls back to raw SMTP or mail().
use PHPMailer\PHPMailer\PHPMailer;

function send_via_raw_smtp($to, $subject, $body, $fromName, $fromEmail, $smtpSettings = []) {
    $host = $smtpSettings['host'] ?? '';
    $port = intval($smtpSettings['port'] ?? 587);
    $user = $smtpSettings['user'] ?? '';
    $pass = $smtpSettings['pass'] ?? '';
    $secure = strtolower($smtpSettings['secure'] ?? '');

    if (empty($host) || empty($fromEmail)) {
        return false;
    }

    $timeout = 30;
    $remote = ($secure === 'ssl' ? 'ssl://' . $host : $host);

    $fp = @fsockopen($remote, $port, $errno, $errstr, $timeout);
    if (!$fp) return false;

    $read = function() use ($fp) {
        $data = '';
        while ($str = fgets($fp, 515)) {
            $data .= $str;
            if (isset($str[3]) && $str[3] == ' ') break;
        }
        return $data;
    };

    $send = function($cmd) use ($fp) {
        fputs($fp, $cmd . "\r\n");
    };

    $read();
    $hostname = preg_replace('/[^a-zA-Z0-9.-]/', '', $_SERVER['SERVER_NAME'] ?? 'localhost');
    $send("EHLO " . $hostname);
    $read();

    if ($secure === 'tls') {
        $send('STARTTLS');
        $resp = $read();
        if (strpos($resp, '220') !== false) {
            stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
            $send("EHLO " . $hostname);
            $read();
        }
    }

    if (!empty($user)) {
        $send('AUTH LOGIN');
        $read();
        $send(base64_encode($user));
        $read();
        $send(base64_encode($pass));
        $read();
    }

    $send('MAIL FROM: <' . $fromEmail . '>');
    $read();
    $send('RCPT TO: <' . $to . '>');
    $read();
    $send('DATA');
    $read();

    $headers = "From: " . $fromName . " <" . $fromEmail . ">\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $message = $headers . "\r\n" . $body . "\r\n.";

    $send($message);
    $resp = $read();
    $send('QUIT');
    fclose($fp);

    return (strpos($resp, '250') !== false || strpos($resp, '354') !== false);
}

// Render an email template from includes/email-templates/<name>.php
function render_email_template($name, $vars = []) {
    $tpl = __DIR__ . '/email-templates/' . $name . '.php';
    if (!file_exists($tpl)) return '';
    extract($vars, EXTR_SKIP);
    ob_start();
    include $tpl;
    return ob_get_clean();
}

function send_mail_helper($to, $subject, $body, $fromEmail = '', $fromName = '', $settings = []) {
    // Try PHPMailer first if composer autoload present
    $autoload = __DIR__ . '/../vendor/autoload.php';
    if (file_exists($autoload)) {
        require_once $autoload;
    }

    if (class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
        try {
            $mail = new PHPMailer(true);
            $smtpHost = $settings['smtp_host'] ?? '';
            if (!empty($smtpHost)) {
                $mail->isSMTP();
                $mail->Host = $smtpHost;
                $mail->SMTPAuth = !empty($settings['smtp_user']);
                if ($mail->SMTPAuth) {
                    $mail->Username = $settings['smtp_user'] ?? '';
                    $mail->Password = $settings['smtp_pass'] ?? '';
                }
                $mail->SMTPSecure = $settings['smtp_secure'] ?? '';
                $mail->Port = intval($settings['smtp_port'] ?? 587);
            }

            $mail->setFrom($fromEmail ?: ($settings['email'] ?? 'no-reply@localhost'), $fromName ?: ($settings['company_name'] ?? ''));
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            $ok = $mail->send();
            return ['ok' => (bool)$ok, 'method' => 'phpmailer', 'error' => $ok ? '' : $mail->ErrorInfo];
        } catch (Exception $e) {
            // fall through to raw SMTP/mail, include exception message
            $lastError = $e->getMessage();
        }
    }

    // Try raw SMTP if smtp host exists
    $smtpConf = [
        'host' => $settings['smtp_host'] ?? '',
        'port' => $settings['smtp_port'] ?? '',
        'user' => $settings['smtp_user'] ?? '',
        'pass' => $settings['smtp_pass'] ?? '',
        'secure' => $settings['smtp_secure'] ?? ''
    ];

    if (!empty($smtpConf['host'])) {
        $sent = send_via_raw_smtp($to, $subject, $body, $fromName, $fromEmail, $smtpConf);
        if ($sent) return ['ok' => true, 'method' => 'raw_smtp', 'error' => ''];
    }

    // Fallback to PHP mail()
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    if (!empty($fromName) || !empty($fromEmail)) {
        $headers .= "From: " . $fromName . " <" . $fromEmail . ">\r\n";
    }
    $ok = @mail($to, $subject, $body, $headers);
    return ['ok' => (bool)$ok, 'method' => 'mail', 'error' => $ok ? '' : 'mail() failed'];
}

?>
