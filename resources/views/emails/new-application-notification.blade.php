<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Application Received - {{ config('app.name') }}</title>
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
            background-color: #007bff;
            color: white;
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
        .candidate-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .job-details {
            background-color: #e3f2fd;
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
            padding: 12px 24px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 15px 0;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📧 New Application Received</h1>
    </div>

    <div class="content">
        <p>Hello,</p>

        <p>A new application has been submitted and requires your attention:</p>

        <div class="job-details">
            <h3>Position: {{ $jobPost->title }}</h3>
            <p><strong>Location:</strong> {{ $jobPost->location }}</p>
            <p><strong>Employment Type:</strong> {{ $jobPost->employment_type }}</p>
            <p><strong>Salary Range:</strong> ${{ number_format($jobPost->salary_min) }} - ${{ number_format($jobPost->salary_max) }}</p>
        </div>

        <div class="candidate-info">
            <h3>Candidate Information</h3>
            <p><strong>Name:</strong> {{ $candidate->first_name }} {{ $candidate->last_name }}</p>
            <p><strong>Email:</strong> {{ $candidate->email }}</p>
            <p><strong>Phone:</strong> {{ $candidate->phone }}</p>
            @if($candidate->linkedin_url)
                <p><strong>LinkedIn:</strong> <a href="{{ $candidate->linkedin_url }}" target="_blank">{{ $candidate->linkedin_url }}</a></p>
            @endif
            @if($candidate->github_url)
                <p><strong>GitHub:</strong> <a href="{{ $candidate->github_url }}" target="_blank">{{ $candidate->github_url }}</a></p>
            @endif
            <p><strong>Applied:</strong> {{ $application->created_at->format('F j, Y \a\t g:i A') }}</p>
        </div>

        @if($application->cover_letter)
            <div class="candidate-info">
                <h4>Cover Letter:</h4>
                <p>{{ Str::limit($application->cover_letter, 300) }}</p>
                @if(strlen($application->cover_letter) > 300)
                    <p><em>... (view full cover letter in admin panel)</em></p>
                @endif
            </div>
        @endif

        <p><strong>Next Steps:</strong></p>
        <ul>
            <li>Review the candidate's application and resume</li>
            <li>Update the application status in the admin panel</li>
            <li>Contact the candidate if they're a good fit</li>
        </ul>

        <a href="{{ config('app.url') }}/admin/applications/{{ $application->id }}" class="btn">
            View Application in Admin Panel
        </a>

        <p>Best regards,<br>
        {{ config('app.name') }} ATS System</p>
    </div>

    <div class="footer">
        <p>This is an automated notification from the ATS system.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
