<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/vendor/autoload.php";

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

$success = "";
$error = "";

function clean($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
        $error = "Invalid request. Please refresh the page.";
    } else {
        require_once __DIR__ . "/db.php";

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (
            $name === "" ||
            !filter_var($email, FILTER_VALIDATE_EMAIL) ||
            $message === ""
        ) {
            $error = "Please enter valid details.";
        } else {
            try {
                // Save message in database
                $stmt = $pdo->prepare(
                    "INSERT INTO messages (name, email, subject, message)
                     VALUES (?, ?, ?, ?)"
                );

                $stmt->execute([
                    $name,
                    $email,
                    $subject,
                    $message
                ]);

                // Send email using PHPMailer
                $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'your mail';
                $mail->Password = 'yous pass';
                $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->CharSet = 'UTF-8';

                $mail->setFrom(
                    'your mail',
                    'Ayush Portfolio'
                );

                $mail->addAddress('your main');
                $mail->addReplyTo($email, $name);
                $mail->isHTML(true);

                $mail->Subject = "Portfolio Contact: " . $subject;

                $mail->Body = "
                    <h2>New Portfolio Message</h2>
                    <p><strong>Name:</strong> " . clean($name) . "</p>
                    <p><strong>Email:</strong> " . clean($email) . "</p>
                    <p><strong>Subject:</strong> " . clean($subject) . "</p>
                    <p><strong>Message:</strong></p>
                    <p>" . nl2br(clean($message)) . "</p>
                ";

                $mail->AltBody =
                    "Name: $name\n" .
                    "Email: $email\n" .
                    "Subject: $subject\n" .
                    "Message: $message";

                $mail->send();
                $success = "Your message has been sent successfully!";

            } catch (PDOException $e) {
                error_log($e->getMessage());
                $error = "Unable to save message. Please try again.";
            } catch (\PHPMailer\PHPMailer\Exception $e) {
                error_log($e->getMessage());
                $error = "Message saved, but email could not be sent. Please check Gmail settings.";
            } catch (Exception $e) {
                error_log($e->getMessage());
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayush Kandoliya | Portfolio</title>
    <meta name="description" content="Ayush Bharatbhai Kandoliya - Portfolio">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>

<!-- NAVBAR -->
<header class="navbar">
    <div class="container nav-content">
        <a href="#home" class="logo">AYUSH<span>.</span></a>
        <button class="menu-btn"
                id="menuBtn"
                aria-label="Toggle menu"
                aria-expanded="false">☰</button>
        <nav id="navLinks">
            <a href="#home">Home</a>
            <a href="#about">About</a>
            <a href="#skills">Skills</a>
            <a href="#projects">Projects</a>
            <a href="#experience">Experience</a>
            <a href="#education">Education</a>
            <a href="#qualifications">Qualifications</a>
            <a href="#achievements">Achievements</a>
            <a href="#contact" class="nav-button">Let's Talk ↗</a>
        </nav>
    </div>
</header>