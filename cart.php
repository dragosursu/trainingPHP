<?php

require_once 'common.php';

if (!isset($products)) {
    $products = [];
}
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    foreach ($_SESSION['cart'] as $key => $value) {
        if ($value == $_POST['id']) {
            unset($_SESSION['cart'][$key]);
        }
    }
}
if (!empty($_SESSION['cart'])) {
    $in = rtrim(str_repeat('?,', count($_SESSION['cart'])), ',');
    $sql = 'SELECT * FROM products WHERE id IN (' . $in . ')';
    $stmt = $conn->prepare($sql);
    $stmt->execute(array_values($_SESSION['cart']));
    $products = $stmt->fetchAll(PDO::FETCH_NAMED);
}

$nameErr = $contactErr = '';
$name = $contact = $comments = '';

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
if (isset($_POST['btnCheckout'])) {

    if (empty($_POST['name'])) {
        $nameErr = 'Name is required';
    } else {
        $name = sanitize_input($_POST['name']);
    }
    if (empty($_POST['contact'])) {
        $contactErr = 'Email is required';
    } else {
        $contact = sanitize_input($_POST['contact']);
        if (!filter_var($contact, FILTER_VALIDATE_EMAIL)) {
            $contactErr = 'Invalid email format';
        }
    }
    if (empty($_POST['comments'])) {
        $comments = '';
    } else {
        $comments = sanitize_input($_POST['comments']);
    }
    $to_email = MANAGER;
    $subject = 'Products Cart Request';
    $headers = 'From: cart@5psolutions.com'
        . "\r\n" . 'Reply-To: cart@5psolutions.com'
        . "\r\n" . 'CC: ' . $contact
        . "\r\n" . 'MIME-Version: 1.0 '
        . "\r\n" . 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
    $content = '';
    ob_start();
    include 'mail.php';
    $content .= ob_get_clean();
    if($nameErr == '' && $contactErr == ''){
        mail($to_email, $subject, $content, $headers);

    }

//    header('Location: cart.php');
//    exit();
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
                <button type="submit" value="remove" name="btnRemove"><?= translate('remove') ?></button>
            </form>
        </div>
    </div>
<?php endforeach; ?>
<form method="post" action="cart.php">
    <table>
        <tr>
            <td>Name:</td>
            <td><input type="text" name="name">
                <span class="error">* <?php echo $nameErr; ?></span>
            </td>
        </tr>
        <tr>
            <td>Contact:</td>
            <td><input type="text" name="contact">
                <span class="error">* <?php echo $contactErr; ?></span>
            </td>
        </tr>
        <tr>
            <td>Comments:</td>
            <td><textarea name="comments" rows="5" cols="40"></textarea></td>
        </tr>
        <td>
            <button type="submit" name = "btnCheckout" value="checkout"><?= translate('Checkout') ?></button>
        </td>
    </table>
</form>
<a href="/index.php"><?= translate('Go to index') ?></a>
</body>
</html>







