<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
  <h1>Categories</h1>
<ul>
    <?php foreach ($categories as $category): ?>
        <li><a href="product_view.php?category_id=<?= $category['id']; ?>"><?= $category['name']; ?></a></li>
    <?php endforeach; ?>
</ul>
</body>
</html>
