<?php

require_once 'common.php';

if(!isset($_SESSION['login'])){
    header('Location: login.php');
    exit();
}
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $sql = 'DELETE FROM products WHERE id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->execute(array($_POST['id']));
    header('Location: products.php');
    exit();
}


$sql = 'SELECT * FROM products';
$stmt = $conn->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll();
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

<?php foreach ($products as $value): ?>
    <div class="slide-content">
        <img class="image" src="/images/<?= $value['image_path']; ?>">
        <div class="img-description">
            <form class="form" action="products.php" method="POST">
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
<!--                <button type="submit" value="edit">--><?//= translate('Edit') ?><!--</button>-->
                <button type="submit" value="delete"><?= translate('Delete') ?></button>
                <a href = "/product.php?id=<?=$value['id']?>"><?=translate('Edit')?></a>

            </form>
        </div>
    </div>
<?php endforeach; ?>

<a href="/product.php"><?= translate('Add') ?></a>
<a href="/product.php"><?= translate('Logout') ?></a>


</body>
</html>


