<?php
require_once 'common.php';
//USING SESSION VARIABLE
//USING RELATIVE PATH PORT->EMAIL
//WHEN I EDIT PRODUCT TO PUT IN GET[] 'EDIT'/'ADD'
if (!isset($products)) {
    $products = [];
}
if (!isset($_SESSION['buffer'])) {
    $_SESSION['buffer'] = [];
}
if (isset($_POST['id'])) {
    $productId = $_POST['id'];
    if (is_numeric($_POST['id'])) {
        foreach ($_SESSION['cart'] as $key => $value) {
            if ($value == $_POST['id']) {
                unset($_SESSION['cart'][$key]);
            }
        }
    }
}
if (isset($_POST['name']) && isset($_POST['contact']) && isset($_POST['comments'])) {
    if (!empty($_POST['contact']) && !empty($_POST['name']) && !empty($_SESSION['cart'])) {
        $to_email = $_POST['contact'];
        $subject = 'Website Change Request';
        $headers = "From: " . "cart@5psolutions.com" . "\r\n";
        $headers .= "Reply-To: " . "cart@5psolutions.com" . "\r\n";
        $headers .= "CC: " . $_POST['contact'] . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        mail($to_email, $subject, $_SESSION['buffer'], $headers);
    }
}
if (!empty($_SESSION['cart'])) {
    $in = rtrim(str_repeat('?,', count($_SESSION['cart'])), ',');
    $sql = 'SELECT * FROM products WHERE id IN (' . $in . ')';
    $stmt = $conn->prepare($sql);
    $stmt->execute(array_values($_SESSION['cart']));
    $products = $stmt->fetchAll(PDO::FETCH_NAMED);
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

<?php ob_start();
foreach ($products as $value): ?>
    <div class="slide-content">
        <img class="image" src="/images/<?= $value['image_path']; ?>">
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
<?php endforeach;
$_SESSION['buffer'] = ob_get_contents();
//ob_clean();
?>
<form class="form" action="cart.php" method="POST">
    <input class="input" type="text" name="name" placeholder="Name"><br><br>
    <input class="input" type="text" name="contact" placeholder="Contact details"><br><br>
    <textarea rows="4" cols="50" name="comments" placeholder="Comments"></textarea><br><br>
    <a href="/index.php"><?= translate('Go to index') ?></a>
    <button type="submit" value="checkout"><?= translate('checkout') ?></button>
</form>
</body>
</html>







