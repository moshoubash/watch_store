<?php
    include '../config/database.php';
    $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
    $pdo = new Database();
    $query = "SELECT * FROM products WHERE name LIKE :keyword";
    $stmt = $conn->prepare($query);
    $stmt->execute(['keyword' => '%' . $keyword . '%']);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Search Results for "<?php echo htmlspecialchars($keyword); ?>"</h1>
        <?php if (count($results) > 0): ?>
            <ul>
                <?php foreach ($results as $watch): ?>
                    <li><?php echo htmlspecialchars($watch['name']); ?> - <?php echo htmlspecialchars($watch['price']); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No results found.</p>
        <?php endif; ?>
    </div>
</body>
</html>