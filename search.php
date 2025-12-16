<?php
// /Applications/MAMP/htdocs/project/search.php
session_start();
require __DIR__ . '/db.php';

$q = trim($_GET['q'] ?? '');
$results = [];

if ($q !== '') {
    $like = '%' . $q . '%';
    $sql = "
        (SELECT 'movie' AS type, movie_id AS id, title, image_url
         FROM Movies
         WHERE title LIKE ?)
        UNION ALL
        (SELECT 'game' AS type, game_id AS id, title, image_url
         FROM Games
         WHERE title LIKE ?)
        ORDER BY title
        LIMIT 40
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $like, $like);
    $stmt->execute();
    $res = $stmt->get_result();

    while ($row = $res->fetch_assoc()) {
        $results[] = $row;
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Search results</title>

    <link rel="icon" type="image/jpg" href="images/favicon.jpg">
    <link rel="stylesheet" href="css/style.css">

   
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="results">
    <h2>Results for “<?= htmlspecialchars($q) ?>”</h2>

    <?php if (!$q): ?>
        <p>Please enter a search term.</p>

    <?php elseif (!$results): ?>
        <p>No results found.</p>

    <?php else: ?>
        <div class="res-grid">
            <?php foreach ($results as $r): ?>
                <div class="card">

                    <?php if (!empty($r['image_url'])): ?>
                        <img src="<?= htmlspecialchars($r['image_url']) ?>" alt="">
                    <?php endif; ?>

                    <div class="meta">
                        <span class="pill"><?= htmlspecialchars($r['type']) ?></span><br>
                        <strong><?= htmlspecialchars($r['title']) ?></strong>

                        <div class="actions">
                            <?php if ($r['type'] === 'movie'): ?>
                                <a href="add_review.php?movie_id=<?= (int)$r['id'] ?>" class="btn">
                                    Add Review
                                </a>
                            <?php else: ?>
                                <a href="add_review.php?game_id=<?= (int)$r['id'] ?>" class="btn">
                                    Add Review
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>

