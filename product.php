<?php

require_once 'common.php';

if (!empty($_POST['title']) && !empty($_POST['description']) && !empty($_POST['price']) && !empty($_FILES['image'])) {
    $sql = 'SELECT * FROM products WHERE image_path like ?';
    $stmt = $conn->prepare($sql);
    $stmt->execute(array($_FILES['image']['name']));
    $result = $stmt->fetch();
    if (!$result && is_numeric($_POST['price'])) {
        $sql = 'INSERT INTO products(title,description,price,image_path) VALUES  (? ,?, ?,?)';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($_POST['title'], $_POST['description'], $_POST['price'], $_FILES['image']['name']));
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES['image']['name']);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES['image']['tmp_name']);

        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
        if (file_exists($target_file)) {
            $uploadOk = 0;
        }
        if ($_FILES['image']['size'] > 500000) {
            $uploadOk = 0;
        }
        if ($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg'
            && $imageFileType != 'gif') {
            $uploadOk = 0;
        }
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file) && $uploadOk) {
            echo 'The file ' . basename($_FILES['image']['name']) . ' has been uploaded.';
        } else {
            echo 'Sorry, there was an error uploading your file.';
        }
    }
    header('Location: products.php');
    exit();
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


