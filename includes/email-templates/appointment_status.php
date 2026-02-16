<?php
// Variables expected in $vars passed to render: company, full_name, status_text, appt_date, appt_time, address, phone, notes
// Use extracted variables: $company, $full_name, $status_text, $appt_date, $appt_time, $address, $phone, $notes
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo htmlspecialchars($company ?? 'Governor Crest'); ?> - Appointment Notification</title>
    <style>body{font-family:Inter,Arial,Helvetica,sans-serif;color:#222} .card{background:#fff;border-radius:8px;padding:20px;border:1px solid #eee} .muted{color:#666;font-size:14px}</style>
</head>
<body>
    <div class="card">
        <h2 style="color:#f0a500;margin-top:0"><?php echo htmlspecialchars($company ?? 'Governor Crest'); ?></h2>
        <p class="muted">Dear <?php echo htmlspecialchars($full_name ?? 'Customer'); ?>,</p>
        <p>Your appointment has been <strong><?php echo htmlspecialchars(ucfirst($status_text ?? 'updated')); ?></strong>.</p>

        <h4>Appointment Details</h4>
        <p class="muted">
            Date: <?php echo htmlspecialchars($appt_date ?? 'N/A'); ?><br>
            <?php if (!empty($appt_time)): ?>Time: <?php echo htmlspecialchars($appt_time); ?><br><?php endif; ?>
            Location: <?php echo htmlspecialchars($address ?? ''); ?>
        </p>

        <?php if (!empty($notes)): ?>
            <h5>Notes</h5>
            <p class="muted"><?php echo nl2br(htmlspecialchars($notes)); ?></p>
        <?php endif; ?>

        <p class="muted">If you have questions, reply to this email or call us at <?php echo htmlspecialchars($phone ?? ''); ?>.</p>
        <p>Regards,<br><?php echo htmlspecialchars($company ?? 'Governor Crest'); ?></p>
    </div>
</body>
</html>