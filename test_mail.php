<?php
require_once __DIR__ . '/includes/EmailService.php';

// Simple test script to send an email via EmailService
$emailService = new EmailService();

// Update recipient as needed
$recipient = 'agustin.johnmichael@student.auf.edu.ph';
$recipientName = 'Test Recipient';
$subject = 'Mailtrap Test from Local';

// Use the contact helper to compose a simple message
$sent = $emailService->sendContactEmail('Local Tester', 'tester@local.test', $subject, "This is a test email sent at " . date('c'));

if ($sent) {
    echo "OK: Email send attempted. Check your Mailtrap inbox.";
} else {
    echo "FAIL: Email send failed. Check PHP logs and `config/mailtrap.php` settings.";
}
