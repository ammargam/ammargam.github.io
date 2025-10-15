<?php
// استدعاء مكتبة PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// التحقق من وجود بيانات مرسلة
if (!$_POST) exit;

// استقبال بيانات الفورم
$name     = $_POST['name'] ?? '';
$email    = $_POST['email'] ?? '';
$phone    = $_POST['phone'] ?? '';
$comments = $_POST['comments'] ?? '';

// التحقق من الحقول
if (trim($name) == '' || trim($email) == '' || trim($phone) == '' || trim($comments) == '') {
    echo '<div class="alert alert-error">Please fill all fields!</div>';
    exit();
}

// التحقق من الإيميل
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo '<div class="alert alert-error">You must enter a valid email address.</div>';
    exit();
}

// إنشاء كائن PHPMailer
$mail = new PHPMailer(true);

try {
    // إعدادات SMTP
    $mail->isSMTP();
    $mail->Host       = 'mail.yourdomain.com';   // غيرها حسب بيانات الـ cPanel
    $mail->SMTPAuth   = true;
    $mail->Username   = 'no-reply@yourdomain.com'; // الإيميل اللي أنشأته في cPanel
    $mail->Password   = 'yourpassword';           // باسورد الإيميل
    $mail->SMTPSecure = 'ssl'; // أو 'tls' حسب إعدادات Namecheap
    $mail->Port       = 465;   // 465 للـ SSL أو 587 للـ TLS

    // من و إلى
    $mail->setFrom('no-reply@yourdomain.com', 'Website Contact');
    $mail->addAddress('ammargamal2005@gmail.com', 'Ammar'); // إيميلك اللي هتستقبل عليه

    // المحتوى
    $mail->isHTML(true);
    $mail->Subject = 'Contact Form Submission';
    $mail->Body    = "
        <h3>You have a new message from your website contact form</h3>
        <p><strong>Name:</strong> {$name}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Phone:</strong> {$phone}</p>
        <p><strong>Message:</strong><br>{$comments}</p>
    ";

    $mail->send();
    echo "<div class='alert alert-success'><h3>Email Sent Successfully.</h3>
          <p>Thank you <strong>$name</strong>, your message has been submitted to us.</p></div>";

} catch (Exception $e) {
    echo "<div class='alert alert-error'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</div>";
}
?>
