<?php
session_start();
require __DIR__ . '/db.php';

/*
|--------------------------------------------------------------------------
| Mode (All / Movies / Games) with COOKIE support
|--------------------------------------------------------------------------
*/
$mode = $_GET['mode'] ?? ($_COOKIE['reviews_mode'] ?? 'all');

// Save cookie when mode is changed via URL
if (isset($_GET['mode'])) {
    setcookie('reviews_mode', $mode, time() + (60 * 60 * 24 * 30), '/'); // 30 days
}

/*
|--------------------------------------------------------------------------
| Sorting
|--------------------------------------------------------------------------
*/
$sort = $_GET['sort'] ?? 'newest';

$orderBy = match ($sort) {
    'rating_high' => 'r.rating DESC',
    'rating_low'  => 'r.rating ASC',
    'oldest'      => 'r.created_at ASC',
    default       => 'r.created_at DESC'
};

/*
|--------------------------------------------------------------------------
| Base query (Movies + Games)
|--------------------------------------------------------------------------
*/
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

/*
|--------------------------------------------------------------------------
| Mode filtering
|--------------------------------------------------------------------------
*/
$where = [];
if ($mode === 'movies') {
    $where[] = 'r.movie_id IS NOT NULL';
} elseif ($mode === 'games') {
    $where[] = 'r.game_id IS NOT NULL';
}

if ($where) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}

$sql .= " ORDER BY $orderBy";

$reviews = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reviews</title>

    <link rel="icon" type="image/png" href="images/favicon.png">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="reviews-container">

    <!-- Sort bar -->
    <form class="sort-bar" method="get">
        <input type="hidden" name="mode" value="<?= htmlspecialchars($mode) ?>">
        <label>Sort</label>
        <select name="sort" onchange="this.form.submit()">
            <option value="newest"      <?= $sort === 'newest' ? 'selected' : '' ?>>Newest</option>
            <option value="oldest"      <?= $sort === 'oldest' ? 'selected' : '' ?>>Oldest</option>
            <option value="rating_high" <?= $sort === 'rating_high' ? 'selected' : '' ?>>Rating (High → Low)</option>
            <option value="rating_low"  <?= $sort === 'rating_low' ? 'selected' : '' ?>>Rating (Low → High)</option>
        </select>
    </form>

    <!-- Reviews list -->
    <?php if (!$reviews): ?>
        <p>No reviews found.</p>
    <?php else: ?>
        <?php foreach ($reviews as $r): ?>
            <div class="review-card">

                <div class="review-image">
                    <img src="<?= htmlspecialchars($r['item_image'] ?: 'images/placeholder.jpg') ?>" alt="">
                </div>

                <div class="review-content">
                    <h3>
                        <?= htmlspecialchars($r['item_title']) ?>
                        <span style="font-size:12px; opacity:.6;">
                            (<?= $r['item_type'] === 'movie' ? 'Movie' : 'Game' ?>)
                        </span>
                    </h3>
                    <p><?= nl2br(htmlspecialchars($r['review_text'])) ?></p>
                </div>

                <div class="review-meta">
                    <div class="review-label">Review</div>
                    <div class="rating">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span><?= $i <= round($r['rating'] / 2) ? '★' : '☆' ?></span>
                        <?php endfor; ?>
                    </div>
                </div>

            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>

<!-- Fixed bottom filter bar -->
<div class="review-filter-bar">

    <a href="reviews.php?mode=all&sort=<?= urlencode($sort) ?>"
       class="<?= $mode === 'all' ? 'selected' : '' ?>">
        <?= $mode === 'all' ? '★ All' : '☆ All' ?>
    </a>

    <a href="reviews.php?mode=movies&sort=<?= urlencode($sort) ?>"
       class="<?= $mode === 'movies' ? 'selected' : '' ?>">
        <?= $mode === 'movies' ? '★ Watching' : '☆ Watching' ?>
    </a>

    <a href="reviews.php?mode=games&sort=<?= urlencode($sort) ?>"
       class="<?= $mode === 'games' ? 'selected' : '' ?>">
        <?= $mode === 'games' ? '★ Playing' : '☆ Playing' ?>
    </a>

</div>

</body>
</html>