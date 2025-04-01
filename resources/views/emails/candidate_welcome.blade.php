<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Application Has Been Received</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #0066cc;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 8px 8px;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
        .highlight {
            background-color: #e6f2ff;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        h1 {
            margin: 0;
            font-size: 24px;
        }
        .button {
            display: inline-block;
            background-color: #0066cc;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 20px;
        }
        .button:hover {
            background-color: #004d99;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Application Received!</h1>
    </div>
    <div class="content">
        <p>Hello, <strong>{{ $candidate->first_name }} {{ $candidate->last_name }}</strong>!</p>

        <p>Thank you for submitting your application. We're excited to review your qualifications and experience!</p>

        <div class="highlight">
            <p><strong>Application Status:</strong> Under Review</p>
            <p>Our recruitment team is currently reviewing your resume and application details. We appreciate your patience during this process.</p>
        </div>

        <p>Here's what to expect next:</p>
        <ol>
            <li>Application review (Current stage)</li>
            <li>Pyschological test (if selected)</li>
            <li>HR interview</li>
            <li>User interview</li>
            <li>Final decision</li>
        </ol>

        <p>If you have any questions or need to update your information, please don't hesitate to contact us using the details below.</p>



        <p style="margin-top: 30px;">Best regards,</p>
        <p>The Recruitment Team</p>
    </div>
    <div class="footer">
        <p>This email was sent to {{ $candidate->email }}</p>
        <p>Contact: recruitment@company.com | Phone: (123) 456-7890</p>
    </div>
</body>
</html>
