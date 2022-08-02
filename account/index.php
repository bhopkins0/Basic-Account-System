<?php
session_start();
include('../resources/functions.php');
if (!isLoggedIn()) {
    header("Location: /index.php");
    die();
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
            background-color: #f5f5f5;
        }

    </style>
</head>
<body>
<div class="container-fluid p-5 bg-dark text-white">
    <div class="container my-5">
        <h1>Basic Account System</h1>
        <p>Your email address is <?php echo $_SESSION["email"]; ?>. </p>
    </div>
</div>

<div class="container">
    <div class="row align-items-md-stretch my-5">
        <div class="col-md-6">
            <div class="h-100 p-5 bg-light border rounded-3">
                <h2>This system stores a few things when you create an account</h2>
                <p>For example, the IP address used to create your account was
                    <strong><?php echo long2ip(getCreationIP($_SESSION["acc_id"])); ?></strong>. </p>
                <p>Your account was created on:<br>
                    <strong><?php echo date(DATE_RFC2822, getCreationTime($_SESSION["acc_id"])); ?></strong></p>
            </div>
        </div>
        <div class="col-md-6">
            <h1>Login Attempts</h1>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Time</th>
                        <th scope="col">IP Address</th>
                        <th scope="col">Successful?</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $loginAttempts = getLoginAttempts($_SESSION["acc_id"]);
                    foreach ($loginAttempts as $row):
                        $login_time = date(DATE_RFC2822, $row["login_time"]);
                        $login_ip = long2ip($row["login_ip"]);
                        $login_success = $row["is_successful"];
                        if ($login_success == "true") {
                            $tableColor = 'class="table-success"';
                        } else {
                            $tableColor = 'class="table-danger"';
                        }
                        echo <<<EOL
                <tr $tableColor>
                    <td>$login_time</td>
                    <td>$login_ip</td>
                    <td>$login_success</td>
                </tr>
                EOL;
                    endforeach;
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div>
    </div>

</div>

</body>
</html>
