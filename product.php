<?php

require_once 'common.php';
//echo
function uploadImage($target_file)
{
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $check = getimagesize($_FILES['image']['tmp_name']);
    if ($check !== false && !file_exists($target_file) && !($_FILES['image']['size'] > 500000)) {
        if ($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg' && $imageFileType != 'gif') {
            return 0;
        }
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            return 1;
        }
    }
}

if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit();
}
if (!empty($_POST['title']) && !empty($_POST['description']) && !empty($_POST['price']) && !empty($_FILES['image'])) {
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $sql = 'SELECT * FROM products WHERE id = ?';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($_GET['id']));
        $result = $stmt->fetch();
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES['image']['name']);
        if ($result && uploadImage($target_file)) {
            $sql = 'UPDATE products SET title = ?,description = ?, price = ?, image_path = ? WHERE id = ?';
            $stmt = $conn->prepare($sql);
            $stmt->execute(
                array($_POST['title'], $_POST['description'], $_POST['price'], $_FILES['image']['name'], $_GET['id'])
            );
        }
        header('Location: product.php');
        exit();
    } else {
        $sql = 'SELECT * FROM products WHERE image_path like ?';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($_FILES['image']['name']));
        $result = $stmt->fetch();
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES['image']['name']);

        if (!$result && is_numeric($_POST['price']) && uploadImage($target_file)) {
            $sql = 'INSERT INTO products(title,description,price,image_path) VALUES  (? ,?, ?, ?)';
            $stmt = $conn->prepare($sql);
            $stmt->execute(array($_POST['title'], $_POST['description'], $_POST['price'], $_FILES['image']['name']));
        }
            header('Location: products.php');
            exit();
    }

}
?>
<html>
<head>
</head>
<body>
<div class="slide-content">
    <div class="img-description">
        <form class="form" action="product.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Title"><br><br>
            <input type="text" name="description" placeholder="Description"><br><br>
            <input type="text" name="price" placeholder="Price"><br><br>
            <input type="file" name="image" placeholder="Image"><br><br>
            <button type="submit" value="save"><?= translate('Save') ?></button>
        </form>
    </div>
</div>

<a href="/products.php"><?= translate('Products') ?></a>


</body>
</html>


