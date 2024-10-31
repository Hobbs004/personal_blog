<?php
include 'db.php';

// Check if a search query is submitted
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Modify the query to search for posts if there is a search term
if ($searchQuery) {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE ? OR content LIKE ? ORDER BY created_at DESC");
    $stmt->execute(['%' . $searchQuery . '%', '%' . $searchQuery . '%']);
} else {
    $stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
}

$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Personal Blog</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Welcome to My Blog</h1>
    
    <!-- Search form -->
    <form method="get" action="index.php">
        <input type="text" name="search" placeholder="Search posts..." value="<?php echo htmlspecialchars($searchQuery); ?>">
        <button type="submit">Search</button>
    </form>
    
    <a href="create.php">Create New Post</a>
    
    <div class="posts">
        <?php if ($posts): ?>
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <h2><a href="view.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h2>
                    <p><?php echo htmlspecialchars(substr($post['content'], 0, 100)); ?>...</p>
                    <p><a href="edit.php?id=<?php echo $post['id']; ?>">Edit</a> | <a href="delete.php?id=<?php echo $post['id']; ?>">Delete</a></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No posts found.</p>
        <?php endif; ?>
    </div>
</body>
</html>

