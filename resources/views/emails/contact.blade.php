<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Contact Form Submission</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #6366f1; color: white; padding: 20px; border-radius: 8px 8px 0 0; }
        .content { background: #f8f9ff; padding: 20px; border-radius: 0 0 8px 8px; }
        .info-row { margin: 10px 0; padding: 10px; background: white; border-left: 4px solid #6366f1; }
        .label { font-weight: bold; color: #6366f1; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>📬 New Contact Form Submission</h2>
        </div>
        <div class="content">
            <p>You have received a new message from the contact form.</p>
            
            <div class="info-row">
                <span class="label">Name:</span> {{ $data['name'] }}
            </div>
            
            <div class="info-row">
                <span class="label">Email:</span> {{ $data['email'] }}
            </div>
            
            <div class="info-row">
                <span class="label">Subject:</span> {{ $data['subject'] }}
            </div>
            
            <div class="info-row">
                <span class="label">Message:</span><br>
                {{ $data['message'] }}
            </div>
        </div>
    </div>
</body>
</html>