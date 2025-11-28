<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Application Submitted - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
        }
        .job-details {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            font-size: 14px;
            color: #6c757d;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Application Submitted Successfully</h1>
    </div>

    <div class="content">
        <p>Dear {{ $candidate->first_name }} {{ $candidate->last_name }},</p>

        <p>Thank you for your application! We have successfully received your application for the following position:</p>

        <div class="job-details">
            <h3>{{ $jobPost->title }}</h3>
            <p><strong>Location:</strong> {{ $jobPost->location }}</p>
            <p><strong>Employment Type:</strong> {{ $jobPost->employment_type }}</p>
            <p><strong>Application Date:</strong> {{ $application->created_at->format('F j, Y \a\t g:i A') }}</p>
        </div>

        <p>Your application is currently being reviewed by our team. We will keep you updated on the status of your application.</p>

        <p><strong>What happens next?</strong></p>
        <ul>
            <li>Our recruitment team will review your application</li>
            <li>If your profile matches our requirements, we'll contact you for the next steps</li>
            <li>You'll receive email updates about any changes to your application status</li>
        </ul>

        <p>If you have any questions, please don't hesitate to contact us.</p>

        <p>Best regards,<br>
        The {{ config('app.name') }} Team</p>
    </div>

    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
