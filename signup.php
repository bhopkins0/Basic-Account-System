<?php
session_start();
include('resources/functions.php');
if (isPost() && isset($_POST["email"]) && isset($_POST["pw"]) && isset($_POST["rpw"])) {
    if (preliminarySignUpCheck($_POST["email"], $_POST["pw"], $_POST["rpw"]) != "Success") {
        $errorMsg = preliminarySignUpCheck($_POST["email"], $_POST["pw"], $_POST["rpw"]);
    } elseif (isEmailUsed($_POST["email"])) {
        $errorMsg = "Error: Email is already in use";
    } elseif (createAccount($_POST["email"], $_POST["pw"])) {
        accountLogin($_POST["email"], $_POST["pw"]);
    } else {
        $errorMsg = "Error: Account creation was not successful";
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

        .form-signup {
            max-width: 330px;
            padding: 15px;
        }

        .form-signup .form-floating:focus-within {
            z-index: 2;
        }

        .form-signup input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .form-signup input[id="pw"] {
            margin-bottom: -1px;
            border-radius: 0;
        }

        .form-signup input[id="rpw"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
</head>
<body>
<main class="form-signup w-100 m-auto">
    <form action="/signup.php" method="post">
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
        <div class="form-floating">
            <input type="password" class="form-control" id="rpw" name="rpw" placeholder="Repeat Password">
            <label for="rpw">Repeat Password</label>
        </div>
        <button type="submit" class="w-100 btn btn-lg btn-primary">Sign up</button>
        <p class="text-end text-muted">Or log in to your account <a href="/index.php">here</a>!</p>
    </form>
</main>

</body>
</html>
