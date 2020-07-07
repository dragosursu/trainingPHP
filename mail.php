<html>
<body>
<?php
$products = getProducts();
foreach ($products as $value): ?>
    <br>
    <img width="100px" height="100px" class="image" src="http://localhost:81/images/<?= $value['image_path']; ?>">
    <table border="1">
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
        </tr>
        <tr>
            <td>
                <?= $value['title']; ?>
            </td>
            <td>
                <?= $value['description']; ?>
            </td>
            <td>
                <?= $value['price']; ?>
            </td>
        </tr>
    </table>
<?php endforeach; ?>
</body>
</html>




