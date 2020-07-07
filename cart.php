<?php

require_once 'common.php';

if (!isset($products)) {
    $products = [];
}
if (!isset($_SESSION['buffer'])) {
    $_SESSION['buffer'] = [];
}
if (isset($_POST['id'])) {
    if (is_numeric($_POST['id'])) {
        foreach ($_SESSION['cart'] as $key => $value) {
            if ($value == $_POST['id']) {
                unset($_SESSION['cart'][$key]);
            }
        }
    }
}
if (isset($_POST['name']) && isset($_POST['contact']) && isset($_POST['comments'])) {
    if (filter_var($_POST['contact'], FILTER_VALIDATE_EMAIL) && preg_match('/^[a-zA-Z ]*$/', $_POST['name']) && !empty($_SESSION['cart'])) {
        $to_email = MANAGER;
        $subject = 'Products Cart Request';
        $headers = 'From: ' . 'cart@5psolutions.com' . "\r\n" . 'Reply-To: ' . 'cart@5psolutions.com' . "\r\n" . 'CC: ' . $_POST['contact'] . "\r\n"
            . 'MIME-Version: 1.0 ' . "\r\n" . 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
        $content = ' Name: ' . $_POST['name'] . ' Contact: ' . $_POST['contact'] . ' Comments: ' . $_POST['comments'];
        ob_start();
        include 'mail.php';
        $content .= ob_get_clean();
        mail($to_email, $subject, $content, $headers);
    }
    header('Location: cart.php');
    exit();
}
if (!empty($_SESSION['cart'])) {
    $products = getProducts();
}
?>
<html>
<head>
    <style>
        .slide-content {
            display: flex;
        }

        .img-description {
            text-align: right;
        }

        .img-description form input {
            border: none;
        }

        .image {
            width: 60px;
            height: 60px;
        }

        ul {
            list-style: none
        }

        textarea {
            resize: none;
        }
    </style>
</head>
<body>

<?php foreach ($products as $value): ?>
    <div class="slide-content">
        <img class="image" src="http://localhost:81/images/<?= $value['image_path']; ?>">
        <div class="img-description">
            <form class="form" action="cart.php" method="POST">
                <input class="input" type="hidden" name="id"
                       value=<?= $value['id']; ?>>
                <ul>
                    <li>
                        <?= $value['title']; ?>
                    </li>
                    <li>
                        <?= $value['description']; ?>
                    </li>
                    <li>
                        <?= $value['price']; ?>
                    </li>
                </ul>
                <button type="submit" value="remove"><?= translate('remove') ?></button>
            </form>
        </div>
    </div>
<?php endforeach; ?>

<form class="form" action="cart.php" method="POST">
    <input class="input" type="text" name="name" placeholder="Name"><br><br>
    <input class="input" type="text" name="contact" placeholder="Contact details"><br><br>
    <textarea rows="4" cols="50" name="comments" placeholder="Comments"></textarea><br><br>
    <a href="/index.php"><?= translate('Go to index') ?></a>
    <button type="submit" value="checkout"><?= translate('checkout') ?></button>
</form>
</body>
</html>







