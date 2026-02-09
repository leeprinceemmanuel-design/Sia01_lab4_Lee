<?php
/**
 * Email Service Class
 * Uses Mailtrap SMTP for sending emails
 */

class EmailService {
    private $host;
    private $port;
    private $username;
    private $password;
    private $from_email;
    private $from_name;

    public function __construct() {
        require_once __DIR__ . '/../config/mailtrap.php';
        
        $this->host = MAILTRAP_HOST;
        $this->port = MAILTRAP_PORT;
        $this->username = MAILTRAP_USERNAME;
        $this->password = MAILTRAP_PASSWORD;
        $this->from_email = MAILTRAP_FROM_EMAIL;
        $this->from_name = MAILTRAP_FROM_NAME;
    }

    /**
     * Send order confirmation email
     */
    public function sendOrderConfirmation($customer_email, $customer_name, $order_id, $order_data) {
        $subject = "Order Confirmation - #" . $order_id;
        
        $html_content = $this->getOrderConfirmationTemplate($customer_name, $order_id, $order_data);
        
        return $this->sendEmail($customer_email, $customer_name, $subject, $html_content);
    }

    /**
     * Send order confirmation to admin
     */
    public function sendAdminOrderNotification($order_id, $order_data, $customer_email) {
        $subject = "New Order Received - #" . $order_id;
        
        $html_content = $this->getAdminOrderTemplate($order_id, $order_data, $customer_email);
        
        return $this->sendEmail(ADMIN_EMAIL, 'Store Admin', $subject, $html_content);
    }

    /**
     * Send contact form email
     */
    public function sendContactEmail($name, $email, $subject, $message) {
        $email_subject = "Contact Form: " . $subject;
        
        $html_content = "<p><strong>From:</strong> {$name} ({$email})</p>";
        $html_content .= "<p><strong>Subject:</strong> {$subject}</p>";
        $html_content .= "<p><strong>Message:</strong></p>";
        $html_content .= "<p>" . nl2br(htmlspecialchars($message)) . "</p>";
        
        return $this->sendEmail(SUPPORT_EMAIL, 'Store Support', $email_subject, $html_content);
    }

    /**
     * Main email sending method using Mailtrap SMTP
     */
    private function sendEmail($recipient_email, $recipient_name, $subject, $html_content) {
        // If an API token is defined, prefer Mailtrap HTTP API (no local SMTP required)
        if (defined('MAILTRAP_API_TOKEN') && MAILTRAP_API_TOKEN !== 'YOUR_MAILTRAP_API_TOKEN' && !empty(MAILTRAP_API_TOKEN)) {
            return $this->sendViaApi($recipient_email, $recipient_name, $subject, $html_content);
        }

        // Fallback: attempt PHP mail() (may fail on Windows without SMTP configured)
        $headers = "From: " . $this->from_name . " <" . $this->from_email . ">\r\n";
        $headers .= "Reply-To: " . $this->from_email . "\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

        $to = $recipient_name . " <" . $recipient_email . ">";
        $result = @mail($to, $subject, $html_content, $headers);

        if ($result) {
            error_log("Email sent successfully to: {$recipient_email} (php mail)");
            return true;
        } else {
            error_log("Failed to send email to: {$recipient_email} (php mail). Recommend configuring Mailtrap API token in config/mailtrap.php or installing PHPMailer.");
            return false;
        }
    }

    /**
     * Send using Mailtrap HTTP API (no SMTP required)
     */
    private function sendViaApi($recipient_email, $recipient_name, $subject, $html_content) {
        $payload = [
            'from' => ['email' => $this->from_email, 'name' => $this->from_name],
            'to' => [[
                'email' => $recipient_email,
                'name' => $recipient_name
            ]],
            'subject' => $subject,
            'html' => $html_content
        ];

        $ch = curl_init('https://send.api.mailtrap.io/api/send');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . MAILTRAP_API_TOKEN,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Detailed logging to file for debugging
        $logEntry = date('c') . " | Mailtrap API Request to: {$recipient_email}\n";
        $logEntry .= "Payload: " . json_encode($payload) . "\n";
        $logEntry .= "HTTP Code: " . var_export($httpCode, true) . "\n";
        $logEntry .= "Response: " . var_export($response, true) . "\n";
        $logEntry .= "Curl Error: " . var_export($err, true) . "\n";
        $logEntry .= "-------------------------\n";
        @file_put_contents(__DIR__ . '/../logs/mailtrap_api.log', $logEntry, FILE_APPEND);

        if ($response !== false && $httpCode >= 200 && $httpCode < 300) {
            error_log("Email sent via Mailtrap API to: {$recipient_email}");
            return true;
        } else {
            error_log("Mailtrap API send failed (code: {$httpCode}): {$err} Response: {$response}");
            return false;
        }
    }

    /**
     * Order Confirmation Email Template
     */
    private function getOrderConfirmationTemplate($customer_name, $order_id, $order_data) {
        $html = "
        <html>
        <body style='font-family: Arial, sans-serif; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto;'>
                <div style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0;'>
                    <h1 style='margin: 0;'>Thank You for Your Order!</h1>
                </div>
                
                <div style='border: 1px solid #ddd; border-top: none; padding: 20px; border-radius: 0 0 5px 5px;'>
                    <p>Hello <strong>" . htmlspecialchars($customer_name) . "</strong>,</p>

                    <p>We've received your order. Below are the details of your purchase:</p>

                    <div style='background: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                        <p><strong>Order ID:</strong> #" . htmlspecialchars($order_id) . "</p>
                        <p><strong>Order Date:</strong> " . (isset($order_data['order_date']) ? htmlspecialchars($order_data['order_date']) : date('M d, Y H:i A')) . "</p>
                        <p><strong>Status:</strong> <span style='color: #e74c3c; font-weight: bold;'>" . (isset($order_data['status']) ? htmlspecialchars($order_data['status']) : 'Pending') . "</span></p>
                    </div>

                    <h3>Customer Information:</h3>
                    <p>
                        <strong>Name:</strong> " . htmlspecialchars($order_data['customer_name'] ?? $customer_name) . "<br>
                        <strong>Email:</strong> " . htmlspecialchars($order_data['customer_email'] ?? '') . "
                    </p>

                    <h3>Order Summary:</h3>
                    <table style='width: 100%; border-collapse: collapse;'>
                        <tr style='background: #f0f0f0;'>
                            <th style='text-align: left; padding: 10px; border-bottom: 1px solid #ddd;'>Item</th>
                            <th style='text-align: center; padding: 10px; border-bottom: 1px solid #ddd;'>Qty</th>
                            <th style='text-align: right; padding: 10px; border-bottom: 1px solid #ddd;'>Price</th>
                            <th style='text-align: right; padding: 10px; border-bottom: 1px solid #ddd;'>Subtotal</th>
                        </tr>
                        " . (isset($order_data['items_html']) ? $order_data['items_html'] : '') . "
                        <tr style='font-weight: bold; font-size: 16px;'>
                            <td colspan='3' style='text-align: right; padding: 10px;'>Total:</td>
                            <td style='text-align: right; padding: 10px;'>$" . (isset($order_data['total']) ? number_format($order_data['total'], 2) : '0.00') . "</td>
                        </tr>
                    </table>
                    
                    <h3>Next Steps:</h3>
                    <ol>
                        <li>We'll process your order right away</li>
                        <li>Your items will ship within 2-3 business days</li>
                        <li>You'll receive a shipping confirmation email with tracking info</li>
                    </ol>
                    
                    <p>If you have any questions, please contact us at <a href='mailto:" . SUPPORT_EMAIL . "'>" . SUPPORT_EMAIL . "</a></p>
                    
                    <p>Thanks for shopping with us!</p>
                    <p><strong>" . MAILTRAP_FROM_NAME . "</strong></p>
                </div>
            </div>
        </body>
        </html>
        ";
        return $html;
    }

    /**
     * Admin Order Notification Template
     */
    private function getAdminOrderTemplate($order_id, $order_data, $customer_email) {
        $html = "
        <html>
        <body style='font-family: Arial, sans-serif; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto;'>
                <div style='background: #27ae60; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0;'>
                    <h1 style='margin: 0;'>New Order Received</h1>
                </div>
                
                <div style='border: 1px solid #ddd; border-top: none; padding: 20px; border-radius: 0 0 5px 5px;'>
                    <p><strong>A new order has been placed!</strong></p>

                    <div style='background: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                        <p><strong>Order ID:</strong> #" . htmlspecialchars($order_id) . "</p>
                        <p><strong>Customer Email:</strong> " . htmlspecialchars($customer_email) . "</p>
                        <p><strong>Order Total:</strong> $" . (isset($order_data['total']) ? number_format($order_data['total'], 2) : '0.00') . "</p>
                        <p><strong>Order Date:</strong> " . (isset($order_data['order_date']) ? htmlspecialchars($order_data['order_date']) : date('M d, Y H:i A')) . "</p>
                        <p><strong>Status:</strong> " . (isset($order_data['status']) ? htmlspecialchars($order_data['status']) : 'pending') . "</p>
                    </div>

                    <h4>Ordered Products:</h4>
                    <table style='width: 100%; border-collapse: collapse;'>
                        <tr style='background: #f0f0f0;'>
                            <th style='text-align: left; padding: 10px; border-bottom: 1px solid #ddd;'>Item</th>
                            <th style='text-align: center; padding: 10px; border-bottom: 1px solid #ddd;'>Qty</th>
                            <th style='text-align: right; padding: 10px; border-bottom: 1px solid #ddd;'>Price</th>
                            <th style='text-align: right; padding: 10px; border-bottom: 1px solid #ddd;'>Subtotal</th>
                        </tr>
                        " . (isset($order_data['items_html']) ? $order_data['items_html'] : '') . "
                    </table>

                    <p style='margin-top: 10px;'><a href='#' style='background: #27ae60; color: white; padding: 10px 20px; text-decoration: none; display: inline-block; border-radius: 5px;'>View Order Details</a></p>
                </div>
            </div>
        </body>
        </html>
        ";
        return $html;
    }
}

?>
