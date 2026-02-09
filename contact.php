<?php
include "includes/db.php";
include "includes/EmailService.php";
/* Page Header and navigation */
include "includes/header.php";
include "includes/navigation.php";

$success_message = '';
$error_message = '';

// Handle contact form submission
if (isset($_POST['send_message'])) {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error_message = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address.";
    } else {
        // Send contact email via Mailtrap
        try {
            $emailService = new EmailService();
            $emailService->sendContactEmail($name, $email, $subject, $message);
            $success_message = "Thank you for your message! We'll get back to you soon.";
        } catch (Exception $e) {
            error_log("Contact email error: " . $e->getMessage());
            $error_message = "There was an error sending your message. Please try again.";
        }
    }
}
?>

<!-- Page Content -->
<div class="container" style="margin-top: 50px;">

    <div class="row">

        <!-- Contact Form Column -->
        <div class="col-md-8">

            <h1 class="page-header">
                <span class="glyphicon glyphicon-envelope"></span> Contact Us
            </h1>

            <?php if (!empty($success_message)) { ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <span class="glyphicon glyphicon-ok"></span> <?php echo $success_message; ?>
                </div>
            <?php } ?>

            <?php if (!empty($error_message)) { ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <span class="glyphicon glyphicon-exclamation-sign"></span> <?php echo $error_message; ?>
                </div>
            <?php } ?>

            <div class="well">
                <p>Have a question or need assistance? We're here to help! Fill out the form below and we'll get back to you as soon as possible.</p>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="name">Your Name *</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="subject">Subject *</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>

                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea class="form-control" id="message" name="message" rows="6" required></textarea>
                    </div>

                    <button type="submit" name="send_message" class="btn btn-primary btn-lg">
                        <span class="glyphicon glyphicon-send"></span> Send Message
                    </button>
                    <a href="index.php" class="btn btn-default btn-lg">
                        Cancel
                    </a>
                </form>
            </div>

            <hr>

            <h3>Other Ways to Reach Us</h3>

            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <span class="glyphicon glyphicon-phone"></span> Phone Support
                            </h4>
                        </div>
                        <div class="panel-body">
                            <p>
                                <strong>1-800-123-4567</strong><br>
                                <small>Monday - Friday: 9AM - 5PM EST</small><br>
                                <small>Saturday - Sunday: 10AM - 3PM EST</small>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <span class="glyphicon glyphicon-envelope"></span> Email Support
                            </h4>
                        </div>
                        <div class="panel-body">
                            <p>
                                <strong><a href="mailto:support@store.com">support@store.com</a></strong><br>
                                <small>Response time: Usually within 24 hours</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <span class="glyphicon glyphicon-time"></span> Hours of Operation
                            </h4>
                        </div>
                        <div class="panel-body">
                            <p>
                                <strong>Live Chat</strong><br>
                                <small>Available: 9AM - 10PM Daily</small>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <span class="glyphicon glyphicon-globe"></span> FAQ
                            </h4>
                        </div>
                        <div class="panel-body">
                            <p>
                                Check our <a href="#"><strong>Frequently Asked Questions</strong></a><br>
                                <small>Quick answers to common questions</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <?php
        include "includes/sidebar.php"
        ?>
    </div>
    <!-- /.row -->

    <hr>
    <?php
    /* Page Footer */
    include "includes/footer.php"
    ?>

</div> <!-- /.container -->
