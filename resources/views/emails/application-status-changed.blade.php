<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Application Status Update - {{ config('app.name') }}</title>
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
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            margin: 10px 0;
        }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-reviewing { background-color: #d1ecf1; color: #0c5460; }
        .status-interviewing { background-color: #d4edda; color: #155724; }
        .status-offer_sent { background-color: #f8d7da; color: #721c24; }
        .status-hired { background-color: #d1e7dd; color: #0f5132; }
        .status-rejected { background-color: #f8d7da; color: #721c24; }
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Application Status Update</h1>
    </div>

    <div class="content">
        <p>Dear {{ $candidate->first_name }} {{ $candidate->last_name }},</p>

        <p>We have an update regarding your application:</p>

        <div class="job-details">
            <h3>{{ $jobPost->title }}</h3>
            <p><strong>Location:</strong> {{ $jobPost->location }}</p>
            <p><strong>Employment Type:</strong> {{ $jobPost->employment_type }}</p>
            <p><strong>Status:</strong> 
                <span class="status-badge status-{{ $application->status->value }}">
                    {{ ucwords(str_replace('_', ' ', $application->status->value)) }}
                </span>
            </p>
            <p><strong>Updated:</strong> {{ $application->updated_at->format('F j, Y \a\t g:i A') }}</p>
        </div>

        @switch($application->status->value)
            @case('reviewing')
                <p>Great news! Your application is currently being reviewed by our recruitment team. We'll be in touch soon with next steps.</p>
                @break
            @case('interviewing')
                <p>Congratulations! We'd like to move forward with your application. Our team will contact you soon to schedule an interview.</p>
                @break
            @case('offer_sent')
                <p>Excellent news! We're pleased to extend you an offer for this position. Please check your email for detailed offer information.</p>
                @break
            @case('hired')
                <p>🎉 Congratulations! We're delighted to welcome you to our team. Our HR department will be in touch with onboarding details.</p>
                @break
            @case('rejected')
                <p>Thank you for your interest in this position. While we've decided to move forward with other candidates, we encourage you to apply for future opportunities that match your skills.</p>
                @break
            @default
                <p>Your application status has been updated. We'll keep you informed of any further changes.</p>
        @endswitch

        <p>If you have any questions about your application, please don't hesitate to contact us.</p>

        <p>Best regards,<br>
        The {{ config('app.name') }} Team</p>
    </div>

    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
