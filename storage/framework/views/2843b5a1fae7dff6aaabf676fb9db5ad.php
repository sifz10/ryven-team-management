<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice <?php echo e($invoice->invoice_number); ?></title>
    <style>
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #000000;
            color: #ffffff;
            padding: 30px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            background-color: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
            border-top: none;
        }
        .invoice-details {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid #e5e7eb;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #6b7280;
        }
        .detail-value {
            color: #1f2937;
        }
        .total-amount {
            background-color: #000000;
            color: #ffffff;
            padding: 15px 20px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
        }
        .total-amount .label {
            font-size: 14px;
            opacity: 0.9;
        }
        .total-amount .amount {
            font-size: 32px;
            font-weight: bold;
            margin-top: 5px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #000000;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            text-align: center;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-size: 12px;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 8px 8px;
        }
        .contact-info {
            margin: 15px 0;
            padding: 15px;
            background-color: #ffffff;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        .contact-info p {
            margin: 5px 0;
            font-size: 14px;
        }
        .logo {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <h1>Ryven Global LLC</h1>
        </div>
        <p style="margin: 10px 0 0 0; font-size: 16px;">Payment Invoice</p>
    </div>

    <div class="content">
        <h2 style="margin-top: 0; color: #1f2937;">Hello <?php echo e($invoice->client_name); ?>,</h2>
        
        <p>We hope this email finds you well. Please find attached the invoice for the payment made by Ryven Global LLC.</p>

        <div class="invoice-details">
            <div class="detail-row">
                <span class="detail-label">Invoice Number:</span>
                <span class="detail-value"><?php echo e($invoice->invoice_number); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Invoice Date:</span>
                <span class="detail-value"><?php echo e($invoice->invoice_date->format('F d, Y')); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Due Date:</span>
                <span class="detail-value"><?php echo e($invoice->due_date->format('F d, Y')); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status:</span>
                <span class="detail-value" style="text-transform: uppercase; font-weight: 600;"><?php echo e($invoice->status); ?></span>
            </div>
        </div>

        <div class="total-amount">
            <div class="label">Total Amount</div>
            <div class="amount"><?php echo e($invoice->currency); ?> <?php echo e(number_format($invoice->total, 2)); ?></div>
        </div>

        <?php if($invoice->notes): ?>
            <div style="margin: 20px 0; padding: 15px; background-color: #ffffff; border-radius: 8px; border: 1px solid #e5e7eb;">
                <h3 style="margin-top: 0; color: #1f2937; font-size: 16px;">Notes:</h3>
                <p style="margin: 0; color: #4b5563; white-space: pre-line;"><?php echo e($invoice->notes); ?></p>
            </div>
        <?php endif; ?>

        <div style="text-align: center; margin: 30px 0;">
            <p style="margin-bottom: 15px; color: #4b5563;">The invoice PDF is attached to this email for your records.</p>
        </div>

        <div class="contact-info">
            <h3 style="margin-top: 0; color: #1f2937; font-size: 16px;">Need Help?</h3>
            <p><strong>Email:</strong> <a href="mailto:support@ryven.co" style="color: #2563eb;">support@ryven.co</a></p>
            <p><strong>WhatsApp:</strong> +1 929-988-9564</p>
            <p><strong>Support Hours:</strong> Monday to Friday (11:00 AM to 12:00 PM - GMT+6)</p>
        </div>

        <?php if($invoice->terms): ?>
            <div style="margin: 20px 0; padding: 15px; background-color: #fffbeb; border-radius: 8px; border: 1px solid #fbbf24;">
                <h3 style="margin-top: 0; color: #92400e; font-size: 14px;">Terms & Conditions:</h3>
                <p style="margin: 0; color: #78350f; font-size: 12px; white-space: pre-line;"><?php echo e($invoice->terms); ?></p>
            </div>
        <?php endif; ?>
    </div>

    <div class="footer">
        <p><strong>Ryven Global LLC</strong></p>
        <p>30 N Gould St Ste R, Sheridan, WY 82801</p>
        <p>Phone: +1 929-988-9564 | Email: hello@ryven.co</p>
        <p style="margin-top: 15px;">
            <a href="https://ryven.co/terms-and-conditions" style="color: #2563eb; text-decoration: none;">Terms & Conditions</a> | 
            <a href="https://ryven.co/privacy-policy" style="color: #2563eb; text-decoration: none;">Privacy Policy</a>
        </p>
        <p style="margin-top: 15px; color: #9ca3af;">
            This is an automated email. Please do not reply to this email directly.
        </p>
    </div>
</body>
</html>

<?php /**PATH F:\Project\salary\resources\views/emails/invoice.blade.php ENDPATH**/ ?>