<?php
// Variables passed: $full_name, $ticket_code, $email, $phone, $ticket_url, $qr_image_url
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your Ticket - Governor Crest Real Estate Conference 2026</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px; color: #333333; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #e0e0e0; }
        .header { background-color: #2c2c2c; padding: 25px 20px; text-align: center; border-bottom: 4px solid #ffc107; }
        .header h1 { color: #ffc107; margin: 10px 0 0 0; font-size: 22px; text-transform: uppercase; letter-spacing: 1px; }
        .content { padding: 30px 25px; }
        .greeting { font-size: 18px; font-weight: bold; color: #2c2c2c; margin-bottom: 15px; }
        .ticket-card { background: #fafafa; border: 2px dashed #ffc107; border-radius: 10px; padding: 20px; text-align: center; margin: 25px 0; }
        .ticket-code { font-size: 24px; font-weight: bold; color: #2c2c2c; letter-spacing: 2px; background: #fff3cd; display: inline-block; padding: 8px 20px; border-radius: 6px; margin: 10px 0; border: 1px solid #ffc107; }
        .qr-wrapper { margin: 15px 0; }
        .qr-wrapper img { width: 180px; height: 180px; border-radius: 8px; border: 1px solid #ddd; padding: 5px; background: #fff; }
        .detail-row { display: flex; justify-content: space-between; border-bottom: 1px solid #eeeeee; padding: 10px 0; font-size: 14px; }
        .btn-view { display: inline-block; background-color: #ffc107; color: #2c2c2c; font-weight: bold; text-decoration: none; padding: 14px 28px; border-radius: 50px; text-transform: uppercase; font-size: 14px; margin-top: 15px; box-shadow: 0 4px 10px rgba(255, 193, 7, 0.4); }
        .footer { background-color: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #777777; border-top: 1px solid #eeeeee; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0; color:#ffc107;">Governor Crest Limited</h1>
            <p style="color:#ffffff; margin:5px 0 0 0; font-size:14px;">Real Estate Conference 2026</p>
        </div>
        
        <div class="content">
            <div class="greeting">Dear <?php echo htmlspecialchars($full_name); ?>,</div>
            <p style="font-size:15px; line-height:1.6; color:#555555;">
                Congratulations! Your free registration for the <strong>Governor Crest Real Estate Conference 2026</strong> has been confirmed. Below is your official entry ticket and unique QR code.
            </p>

            <div class="ticket-card">
                <span style="font-size:12px; text-transform:uppercase; color:#777; font-weight:bold; letter-spacing:1px; display:block;">Official Gate Access Pass</span>
                <div class="ticket-code"><?php echo htmlspecialchars($ticket_code); ?></div>
                
                <div class="qr-wrapper">
                    <img src="<?php echo htmlspecialchars($qr_image_url); ?>" alt="Ticket QR Code">
                </div>
                <small style="color:#666; font-size:12px; display:block;">Scan this QR code at the entrance gate for instant access</small>
            </div>

            <h3 style="color:#2c2c2c; border-bottom:2px solid #ffc107; padding-bottom:6px; margin-top:30px;">Event Details</h3>
            <table width="100%" cellpadding="8" cellspacing="0" style="font-size:14px; color:#444;">
                <tr>
                    <td width="30%" style="font-weight:bold; border-bottom:1px solid #eee;">Date:</td>
                    <td style="border-bottom:1px solid #eee;">Saturday, August 15, 2026</td>
                </tr>
                <tr>
                    <td style="font-weight:bold; border-bottom:1px solid #eee;">Time:</td>
                    <td style="border-bottom:1px solid #eee;">08:30 AM (Registration & Entry)</td>
                </tr>
                <tr>
                    <td style="font-weight:bold; border-bottom:1px solid #eee;">Venue:</td>
                    <td style="border-bottom:1px solid #eee;">E4 Resorts, Off Bauchi Club Road, Bauchi State</td>
                </tr>
                <tr>
                    <td style="font-weight:bold; border-bottom:1px solid #eee;">Organizer:</td>
                    <td style="border-bottom:1px solid #eee;">Governor Crest Limited</td>
                </tr>
            </table>

            <div style="text-center; text-align:center; margin-top:30px;">
                <a href="<?php echo htmlspecialchars($ticket_url); ?>" class="btn-view" target="_blank">
                    View & Print Digital Ticket
                </a>
            </div>
        </div>

        <div class="footer">
            <p style="margin:0 0 5px 0;"><strong>Governor Crest Limited</strong> — Bauchi State, Nigeria</p>
            <p style="margin:0;">For inquiries, contact us at <a href="mailto:info@governorcrestlimited.com" style="color:#ffc107;">info@governorcrestlimited.com</a></p>
        </div>
    </div>
</body>
</html>
