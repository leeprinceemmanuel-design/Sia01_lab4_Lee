# Mailtrap Email Integration Setup Guide

This document explains how to set up and configure Mailtrap for your e-commerce website.

## Step 1: Create a Mailtrap Account

1. Visit **https://mailtrap.io/**
2. Click "Sign Up"
3. Enter your AUF email address
4. Create a password
5. Confirm the email verification link

## Step 2: Create a Sandbox

1. After logging in, you'll see the dashboard
2. Click on "Email Sending" → "Sandboxes"
3. Click "Create Sandbox" if you don't have one already
4. Name it (e.g., "Development" or "Production")
5. Click "Save"

## Step 3: Get Your Credentials

1. Click on your sandbox name
2. Go to the "SMTP Settings" tab
3. You'll see the following information:
   - **Host:** smtp.mailtrap.io
   - **Port:** 465 (SSL) or 587 (TLS)
   - **Username:** Your Mailtrap username
   - **Password:** Your Mailtrap password

## Step 4: Configure Your Application

1. Open the file: `/config/mailtrap.php`

2. Replace the placeholder values with your actual Mailtrap credentials:

```php
define('MAILTRAP_HOST', 'smtp.mailtrap.io');
define('MAILTRAP_PORT', 465); // Use 465 for SSL, or 587 for TLS
define('MAILTRAP_USERNAME', 'YOUR_ACTUAL_USERNAME');
define('MAILTRAP_PASSWORD', 'YOUR_ACTUAL_PASSWORD');
define('MAILTRAP_FROM_EMAIL', 'noreply@yourstore.com');
define('MAILTRAP_FROM_NAME', 'My Online Store');
```

## Step 5: Update Email Addresses

In the same `/config/mailtrap.php` file, update:

```php
define('ADMIN_EMAIL', 'admin@yourstore.com');
define('SUPPORT_EMAIL', 'support@yourstore.com');
```

## Where Emails Are Sent

The system now sends emails for:

### 1. Order Confirmations
- **When:** Customer completes a purchase
- **Sent to:** Customer email address
- **Template:** Professional order confirmation with itemized list

### 2. Admin Notifications
- **When:** New order is placed
- **Sent to:** Admin email (from config)
- **Template:** Admin notification with order details

### 3. Contact Form Messages
- **When:** Customer submits contact form
- **Sent to:** Support email (from config)
- **Template:** Contact message details

## Testing Mailtrap

1. In your Mailtrap dashboard, go to your Sandbox
2. Click on the "Email Testing" tab
3. Any emails sent through your application will appear here
4. You can:
   - View the email content (HTML and plain text)
   - Check headers
   - See if delivery was successful

## Files Modified/Created

1. **`/config/mailtrap.php`** - Configuration file with Mailtrap credentials
2. **`/includes/EmailService.php`** - Email service class for sending emails
3. **`/checkout.php`** - Updated to send order confirmation emails
4. **`/contact.php`** - Updated to send contact form emails

## Email Templates

The EmailService class includes two beautiful HTML email templates:

1. **Order Confirmation Email** - Sent to customers with:
   - Order ID
   - Order date and time
   - Itemized product list
   - Order total
   - Next steps information

2. **Admin Order Notification** - Sent to store admin with:
   - Order ID
   - Customer email
   - Order total
   - Link to view order details

## Production vs Development

### Development (Sandbox):
- All emails are captured and logged in Mailtrap
- No real emails are sent
- Perfect for testing

### Production:
- To use real email sending, you'll need a production account
- Upgrade your Mailtrap account or use a different email service
- Update the configuration accordingly

## Troubleshooting

### Emails not sending?
1. Check that credentials are correct in `/config/mailtrap.php`
2. Check the server error logs for exceptions
3. Verify port 465 or 587 is not blocked by your firewall

### How to check logs?
Look at your server's error log (usually `/var/log/apache2/error.log` on Linux or Event Viewer on Windows)

### Email appears in draft/not sent?
1. Clear your browser cache
2. Check Mailtrap sandbox to confirm it was received
3. Verify the email address format is correct

## Security Notes

- Never commit credentials to version control
- Use environment variables for sensitive data in production
- The password in config/mailtrap.php should be treated as sensitive
- Consider moving credentials to a `.env` file using a package like `phpdotenv`

## Next Steps (Optional Enhancement)

For production use, consider:
1. Using environment variables (dotenv)
2. Adding email queuing system
3. Implementing email templates with database storage
4. Adding email tracking/analytics
5. Setting up SPF/DKIM records for authentication

## Support

- Mailtrap Documentation: https://mailtrap.io/blog/
- Email Service Support: support@mailtrap.io
