<?php

require_once 'common.php';

define('USER', 'test');
define('PASS', 'test');

if (isset($_POST['username']) && isset($_POST['password'])) {
    if (USER == $_POST['username'] && PASS == $_POST['password']) {
        $_SESSION['login'] = "admin";
        header('Location: index.php');
        exit();
    }
    header('Location: login.php');
    exit();
}
?>

<html>
<head>
    <style>
        form {
            text-align: center;
        }
    </style>
</head>

<body>
<form class="form" action="login.php" method="POST">
    <input class="input" type="text" name="username" placeholder="Username"><br><br>
    <input class="input" type="text" name="password" placeholder="Password"><br><br>
    <button type="submit" value="login"><?= translate('login') ?></button>
</form>
</body>
</html>



