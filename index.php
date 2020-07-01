<?php

require_once 'common.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
if (!isset($products)) {
    $products = [];
}
if (empty($_SESSION['cart'])) {
    $sql = 'SELECT * FROM products';
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_NAMED);
    $products = $result;
} elseif (!empty($_SESSION['cart'])) {
    $possibleValues = [];
    foreach ($_SESSION['cart'] as $value) {
        $possibleValues[] = $value;
    }
    $in = rtrim(str_repeat('?,', count($possibleValues)), ',');
    $sql = 'SELECT * FROM products WHERE id NOT IN (' . $in . ')';
    $stmt = $conn->prepare($sql);
    $stmt->execute($possibleValues);
    $result = $stmt->fetchAll(PDO::FETCH_NAMED);
    $products = $result;
}
if (isset($_POST['id'])) {
    $productId = $_POST['id'];
    if (is_numeric($productId)) {
        $_SESSION['cart'][] = $productId;
        header('Location: index.php');
    }
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
    </style>
</head>
<body>

<?php
foreach ($products as $value): ?>
    <div class="slide-content">
        <img class="image" src="/images/<?= $value['image_path']; ?>">
        <div class="img-description">
            <form class="form" action="index.php" method="POST">
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
                <button type="submit" value="add"><?= translate('add') ?></button>
            </form>
        </div>
    </div>
<?php endforeach; ?>
<a href="/cart.php"><?= translate('Go to cart') ?></a>
</body>
</html>



