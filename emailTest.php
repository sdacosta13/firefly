<?php
    // Import PHPMailer classes into the global namespace
    // These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    // Load Composer's autoloader
    require 'vendor/autoload.php';

    // Variables passed from e-ver page
    $email = $_POST['email'];

    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'WebMailerY1@gmail.com';                // SMTP username
        $mail->Password   = 'y1webmailer';                          // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('WebMailerY1@gmail.com', 'Firefly');
        $mail->addAddress($email, 'User');                          // Add a recipient
        // $mail->addAddress('ellen@example.com');                        // Name is optional
        // $mail->addReplyTo('info@example.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        // Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');           // Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');      // Optional name

        // Content
        $mail->isHTML(true);                                // Set email format to HTML
        $mail->Subject = 'Mailer Test';
        $mail->Body    = 'This is a test <b>in bold!</b>';
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        echo '<script type="text/javascript">alert("Message sent")</script>';
    } catch (Exception $e) {
        echo "<script type='text/javascript'>alert('Could not be sent. Mailer Error: {$mail->ErrorInfo}')</script>";
    }