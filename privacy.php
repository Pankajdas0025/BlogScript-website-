
<?php
session_start();
include 'src/db.php';
include 'src/config.php';
include 'components/header.php'; ?>

<head>
    <title>Privacy Policy - BlogScript</title>
    <?php include 'components/head.php'; ?>

    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background: #f4f6f9;
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        h1, h2 {
            color: #222;
        }
        h1 {
            text-align: center;
            margin-bottom: 10px;
        }
        .date {
            text-align: center;
            color: #777;
            margin-bottom: 30px;
        }
        ul {
            padding-left: 20px;
        }
        li {
            margin-bottom: 8px;
        }
        .contact {
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>

<div class='container'>
    <h1>Privacy Policy for BlogScript</h1>
    <p class='date'>Effective Date: June 7, 2025</p>

    <h2>1. Information We Collect</h2>
    <ul>
        <li><strong>Visitors:</strong> We may collect basic information like IP address, browser type, and visit time for analytics.</li>
        <li><strong>Admins:</strong> When logging in, we may collect your name, email, and login activity for security purposes.</li>
    </ul>

    <h2>2. How We Use Information</h2>
    <ul>
        <li>To display blog content</li>
        <li>To manage admin access</li>
        <li>To protect against spam or misuse</li>
    </ul>

    <h2>3. Cookies</h2>
    <p>BlogScript may use cookies to enhance user experience, such as remembering login sessions or preferences.</p>

    <h2>4. Data Sharing</h2>
    <p>We do not sell or share your personal data with third parties, except if required by law.</p>

    <h2>5. Security</h2>
    <p>We take appropriate measures to protect your data, including encrypted admin logins and secure server hosting.</p>

    <h2>6. Your Rights</h2>
    <p>Admins may request to update or delete their personal data at any time by contacting us.</p>

    <h2>7. Changes to this Policy</h2>
    <p>We may revise this policy. Changes will be reflected here with an updated effective date.</p>

    <h2>8. Contact Us</h2>
    <p class='contact'>Email: <a href='mailto:pd5569121@gmail.com'>pd5569121@gmail.com</a></p>
</div>
<?php include 'components/footer.php'; ?>
