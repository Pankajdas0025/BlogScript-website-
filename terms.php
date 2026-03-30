
<?php
session_start();
include 'src/db.php';
include 'src/config.php';
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Terms & Conditions - BlogScript</title>
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
<body>
<?php include 'components/header.php'; ?>

<div class='container'>

    <h1>Terms & Conditions for BlogScript</h1>
    <p class='date'>Effective Date: June 1, 2025</p>

    <h2>1. Introduction</h2>
    <p>
        Welcome to BlogScript. By accessing and using this website, you agree to these Terms and Conditions.
        Please read them carefully.
    </p>

    <h2>2. Use of the Website</h2>
    <ul>
        <li>Visitors may read blog content freely.</li>
        <li>Only authorized admins may access the admin panel to write or manage blog posts.</li>
        <li>You agree not to misuse the website, upload harmful content, or attempt unauthorized access.</li>
    </ul>

    <h2>3. Intellectual Property</h2>
    <p>
        All blog content, designs, and code on this website are the property of BlogScript or their respective
        owners. You may not copy or reproduce content without permission.
    </p>

    <h2>4. Admin Responsibilities</h2>
    <p>
        Admins are responsible for the content they publish. No hate speech, misleading, or harmful content is
        allowed. BlogScript reserves the right to remove any post that violates these terms.
    </p>

    <h2>5. No Warranties</h2>
    <p>
        We do not guarantee the accuracy, completeness, or availability of any content. Use the site at your own
        risk.
    </p>

    <h2>6. Changes to Terms</h2>
    <p>
        We may update these terms from time to time. Any changes will be posted on this page with a new
        effective date.
    </p>

    <h2>7. Contact</h2>
    <p class='contact'>
        Email: <a href='mailto:pd5569121@gmail.com'>pd5569121@gmail.com</a>
    </p>

</div>
<?php include 'components/footer.php'; ?>