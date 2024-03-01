<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f2f2f2;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            width: 300px; /* Set the width as needed */
            background-color: #a5c5fe;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        p {
            font-size: 18px;
            color: #333; /* Change text color to ensure readability */
            margin: 0; /* Remove default margin for the <p> element */
        }

        /* Add more styles as needed */
    </style>
</head>
<body>
    <div class="container">
        <p>Your OTP for email verification is: {{ $otp }}</p>
    </div>
</body>
</html>
