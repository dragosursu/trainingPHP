<?php

session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function listProducts()
{
    require_once 'common.php';
    global $products;

    if (empty($products)) {
        $products = array();
        $sql = "SELECT id,title,description,price,image_path FROM products";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetchAll(PDO::FETCH_NAMED)) {
            $products = $row;
        }
    }
    if (!empty($_SESSION['cart'])) {
        $products = array();
        $possibleValues = array();
        foreach ($_SESSION['cart'] as $value) {
            $possibleValues[] = $value['id'];
        }
        $in = rtrim(str_repeat('?,', count($possibleValues)), ',');
        $sql = "SELECT id,title,description,price,image_path FROM products WHERE id not IN ($in)";
        $stmt = $conn->prepare($sql);
        $stmt->execute($possibleValues);
        while ($row = $stmt->fetchAll(PDO::FETCH_NAMED)) {
            $products = $row;
        }
    }
    if ($_POST) {
        $product = $_POST;
        addToCart($product);
        unset($_POST);
        exit;
    }
    return $products;
}

function addToCart($value)
{
    $_SESSION['cart'][] = $value;
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

        #imagine {
            width: 60px;
            height: 60px;
        }

        #button {
            background: none !important;
            border: none;
            padding: 0 !important;
            font-family: arial, sans-serif;
            color: #069;
            text-decoration: underline;
            cursor: pointer;
            float: right;
        }

    </style>
</head>

<body>

<?php
$returnedProducts = listProducts();
for ($index = 0; $index < count($returnedProducts); $index++): ?>
    <div class="slide-content">
        <img id="imagine" src="/images/<?= $returnedProducts[$index]['image_path']; ?>">
        <div class="img-description">
            <form class="form" action=<?= $_SERVER['PHP_SELF']; ?> method="POST">
                <input class="input" readonly type="hidden" id="id" name="id"
                       value=<?= $returnedProducts[$index]['id']; ?>><br>
                <input class="input" readonly type="text" id="title" name="title"
                       value=<?= $returnedProducts[$index]['title']; ?>><br>
                <input class="input" readonly type="text" id="description" name="description"
                       value=<?= $returnedProducts[$index]['description']; ?>><br>
                <input class="input" readonly type="text" id="price" name="price"
                       value=<?= $returnedProducts[$index]['price']; ?>><br>

                <input id="button" type="submit" name="add" value="Add">
            </form>
        </div>
    </div>
<?php endfor; ?>
<a href="/cart.php">Go to cart</a>
</body>
</html>



