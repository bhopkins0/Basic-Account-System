<?php
session_start();
include('resources/functions.php');
if (isPost() && isset($_POST["pw"]) && isset($_POST["email"])) {
    if (!preliminaryLoginCheck($_POST["email"], $_POST["pw"]) || !isEmailUsed($_POST["email"])) {
        $errorMsg = "Error: Incorrect Email or Password";
    } elseif (!accountLogin($_POST["email"], $_POST["pw"])) {
        $errorMsg = "Error: Incorrect Email or Password";
    }
}
?>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/resources/bootstrap.min.css" rel="stylesheet">
    <style>
        html,
        body {
            height: 100%;
        }

        body {
            display: flex;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .form-signin {
            max-width: 330px;
            padding: 15px;
        }

        .form-signin .form-floating:focus-within {
            z-index: 2;
        }

        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
</head>
<body>
<main class="form-signin w-100 m-auto">
    <form action="/index.php" method="POST">
        <?php
        if (isset($errorMsg)) {
            echo <<<EOL
            <div class="alert alert-danger" role="alert">$errorMsg</div>
            EOL;
        }
        ?>
        <div class="form-floating">
            <input type="email" class="form-control" id="email" name="email" placeholder="Email">
            <label for="email">Email</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" id="pw" name="pw" placeholder="Password">
            <label for="pw">Password</label>
        </div>
        <button type="submit" class="w-100 btn btn-lg btn-primary">Sign in</button>
        <p class="text-end text-muted">Or create an account <a href="signup.php">here</a>!</p>
    </form>
</main>

</body>
</html>
