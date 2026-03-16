<?php
session_start();

$formSubmitted = isset($_SESSION['form_submitted']) ? $_SESSION['form_submitted'] : false;

$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

unset($_SESSION['form_submitted']);
unset($_SESSION['email']);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Forgot Password</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <style>
        body {
            background-color: #f0f0f0;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: linear-gradient(45deg, #eaeaea 25%, transparent 25%), linear-gradient(-45deg, #eaeaea 25%, transparent 25%), linear-gradient(45deg, transparent 75%, #eaeaea 75%), linear-gradient(-45deg, transparent 75%, #eaeaea 75%);
            background-size: 20px 20px;
        }

        .center-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        form {
            width: 300px;
            margin: auto;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        label {
            color: #333;
            font-size: 18px;
            margin-bottom: 10px;
        }

        input[type="email"] {
            /* background-color: #f9f9f9; */
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 10px;
            width: calc(100% - 20px);
            margin-bottom: 20px;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 12px 24px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        button:hover {
            background-color: #0056b3;
        }

        h1 {
            color: #333;
            text-align: center;
            font-size: 48px;
            text-transform: uppercase;
            font-family: 'Arial Black', sans-serif;
            letter-spacing: 3px;
            margin-bottom: 40px;
        }

        .success-message {
            color: green;
            text-align: center;
        }

        .error-message {
            color: red;
            text-align: center;
        }

        .loader {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 2s linear infinite;
            margin: auto;
            margin-top: 20px;
            display: none;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>

    <div class="center-content">
        <h1>Forgot Password?</h1>

        <form method="post" action="send-password-reset.php" id="forgotPasswordForm">

            <div class="email-input">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder='Enter your Email' value="<?php echo $email; ?>">
            </div>

            <button type="submit" id="sendBtn">Send</button>
            <a href="./login.php" style="color:black; text-decoration:underline">Back</a>
            <div class="loader" id="loader"></div>

            <?php if ($formSubmitted) : ?>
                <?php
                if (isset($_GET['message'])) {
                    $message = htmlspecialchars($_GET['message']);
                    if (strpos($message, 'success') !== false) {
                        echo '<p class="success-message">' . substr($message, 8) . '</p>';
                    } elseif (strpos($message, 'error') !== false) {
                        echo '<p class="error-message">' . substr($message, 6) . '</p>';
                    }
                }
                ?>
            <?php endif; ?>

        </form>

    </div>

    <script>
        document.getElementById("forgotPasswordForm").addEventListener("submit", function() {
            document.getElementById("loader").style.display = "block";
        });
    </script>

</body>

</html>