# Email Notification System Setup Guide

## Overview
The email notification system has been successfully implemented with the following features:
- ✅ Application submission confirmation emails to candidates
- ✅ Application status change notifications to candidates
- ✅ New application alerts to HR/recruiters
- ✅ Background job processing for better performance
- ✅ Professional email templates with styling

## Files Created/Modified

### Email Templates
- `resources/views/emails/application-submitted.blade.php`
- `resources/views/emails/application-status-changed.blade.php`
- `resources/views/emails/new-application-notification.blade.php`

### Mail Classes
- `app/Mail/ApplicationSubmitted.php`
- `app/Mail/ApplicationStatusChanged.php`
- `app/Mail/NewApplicationNotification.php`

### Services & Observers
- `app/Services/NotificationService.php`
- `app/Observers/ApplicationObserver.php`
- `app/Providers/AppServiceProvider.php` (updated)

### Configuration
- `config/mail.php` (updated with HR email configuration)

## Environment Configuration

Add these lines to your `.env` file:

```env
# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

# HR Email Addresses
HR_EMAIL_1=hr@yourcompany.com
HR_EMAIL_2=recruiter@yourcompany.com
HR_EMAIL_3=admin@yourcompany.com

# Queue Configuration
QUEUE_CONNECTION=database
```

## Alternative Mail Services

### For Mailgun:
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.mailgun.org
MAILGUN_SECRET=your-mailgun-secret
```

### For SendGrid:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
```

### For Development/Testing:
```env
MAIL_MAILER=log
```

## How It Works

### 1. Application Submission
When a candidate submits an application:
- Confirmation email sent to candidate
- Notification email sent to HR team
- All emails are queued for background processing

### 2. Status Changes
When an application status is updated in the admin panel:
- Automatic email notification sent to candidate
- Email content changes based on the new status

### 3. Email Queue Processing
Run the queue worker to process emails in the background:
```bash
php artisan queue:work
```

## Testing the Email System

### 1. Test Application Submission
- Visit `/careers` page
- Apply for a job
- Check logs or email inbox

### 2. Test Status Changes
- Go to admin panel (`/admin/applications`)
- Change an application status
- Check candidate email

### 3. View Queued Jobs
```bash
php artisan queue:monitor
```

## Email Templates Features

### Professional Design
- Responsive HTML templates
- Company branding
- Status-specific styling
- Clear call-to-actions

### Dynamic Content
- Candidate information
- Job details
- Application status
- Personalized messages

### Status-Specific Messages
- **Reviewing**: "Your application is being reviewed"
- **Interviewing**: "We'd like to schedule an interview"
- **Offer Sent**: "Congratulations! We're extending an offer"
- **Hired**: "Welcome to the team!"
- **Rejected**: "Thank you for your interest"

## Troubleshooting

### 1. Emails Not Sending
- Check `.env` mail configuration
- Verify SMTP credentials
- Check Laravel logs: `storage/logs/laravel.log`

### 2. Queue Jobs Not Processing
- Run queue worker: `php artisan queue:work`
- Check failed jobs: `php artisan queue:failed`

### 3. Missing HR Notifications
- Verify HR_EMAIL_* variables in `.env`
- Check `config/mail.php` hr_emails array

## Next Steps

The email notification system is now fully functional. You can:
1. Customize email templates in `resources/views/emails/`
2. Add more notification types in `NotificationService`
3. Configure production email service (Mailgun, SendGrid, etc.)
4. Set up queue monitoring and error handling
5. Add email preferences for candidates
