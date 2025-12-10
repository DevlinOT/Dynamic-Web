<?php
session_start();
require __DIR__ . '/db.php';

$sort = $_GET['sort'] ?? 'newest';
$mode = $_GET['mode'] ?? 'all'; // all | movies | games

// Sorting logic
$orderBy = match($sort) {
    'rating_high' => 'rating DESC',
    'rating_low'  => 'rating ASC',
    'oldest'      => 'created_at ASC',
    default       => 'created_at DESC'
};

// Base query: pull both movies and games, plus a type label
$sql = "
    SELECT r.*,
           COALESCE(m.title, g.title) AS item_title,
           COALESCE(m.image_url, g.image_url) AS item_image,
           CASE
             WHEN r.movie_id IS NOT NULL THEN 'movie'
             ELSE 'game'
           END AS item_type
    FROM reviews r
    LEFT JOIN Movies m ON r.movie_id = m.movie_id
    LEFT JOIN Games  g ON r.game_id  = g.game_id
";

// Filter by mode
if ($mode === 'movies') {
    $sql .= " WHERE r.movie_id IS NOT NULL";
} elseif ($mode === 'games') {
    $sql .= " WHERE r.game_id IS NOT NULL";
}

// Add sort
$sql .= " ORDER BY $orderBy";

$reviews = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/jpg" href="images/favicon.jpg">
    <meta charset="utf-8">
    <title>Reviews</title>
    <!-- make sure this path matches your actual file: project/css/style.css -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="reviews-container">

    <!-- Sort dropdown -->
    <form class="sort-bar" method="get">
        <label>Sort</label>
        <!-- keep current mode when changing sort -->
        <input type="hidden" name="mode" value="<?= htmlspecialchars($mode) ?>">
        <select name="sort" onchange="this.form.submit()">
            <option value="newest"      <?= $sort=='newest'?'selected':'' ?>>Newest</option>
            <option value="oldest"      <?= $sort=='oldest'?'selected':'' ?>>Oldest</option>
            <option value="rating_high" <?= $sort=='rating_high'?'selected':'' ?>>Rating (High → Low)</option>
            <option value="rating_low"  <?= $sort=='rating_low'?'selected':'' ?>>Rating (Low → High)</option>
        </select>
    </form>

    <!-- Review list -->
    <?php foreach ($reviews as $r): ?>
        <div class="review-card">

            <div class="review-image">
                <img src="<?= htmlspecialchars($r['item_image'] ?: 'https://via.placeholder.com/80') ?>" alt="">
            </div>

            <div class="review-content">
                <h3>
                    <?= htmlspecialchars($r['item_title']) ?>
                    <?php if ($r['item_type'] === 'movie'): ?>
                        <span style="font-size:12px; opacity:.7;">(Movie)</span>
                    <?php else: ?>
                        <span style="font-size:12px; opacity:.7;">(Game)</span>
                    <?php endif; ?>
                </h3>
                <p><?= nl2br(htmlspecialchars($r['review_text'])) ?></p>
            </div>

            <div class="review-meta">
                <div class="review-label">Review</div>
                <div class="rating">
                    <?php for ($i=1; $i<=5; $i++): ?>
                        <span><?= $i <= round($r['rating']/2) ? '★' : '☆' ?></span>
                    <?php endfor; ?>
                </div>
            </div>

        </div>
    <?php endforeach; ?>

    <?php if (!$reviews): ?>
        <p>No reviews found for this filter.</p>
    <?php endif; ?>

</div>

<!-- Bottom filter bar -->
<div class="review-filter-bar">

    <a href="reviews.php?sort=<?= urlencode($sort) ?>&mode=all"
       class="<?= $mode === 'all' ? 'selected' : '' ?>">
        <?= $mode === 'all' ? '★ All' : '☆ All' ?>
    </a>

    <a href="reviews.php?sort=<?= urlencode($sort) ?>&mode=movies"
       class="<?= $mode === 'movies' ? 'selected' : '' ?>">
        <?= $mode === 'movies' ? '★ Watching' : '☆ Watching' ?>
    </a>

    <a href="reviews.php?sort=<?= urlencode($sort) ?>&mode=games"
       class="<?= $mode === 'games' ? 'selected' : '' ?>">
        <?= $mode === 'games' ? '★ Playing' : '☆ Playing' ?>
    </a>

    <a href="reviews.php?sort=<?= urlencode($sort) ?>&mode=all">
        ☆ Completed
    </a>

</div>


</body>
</html>


