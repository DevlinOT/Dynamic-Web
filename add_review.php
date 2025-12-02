<?php
require __DIR__ . '/auth.php';   // makes sure user is logged in
require __DIR__ . '/db.php';

// figure out what they are reviewing
$movie_id = isset($_GET['movie_id']) ? (int)$_GET['movie_id'] : 0;
$game_id  = isset($_GET['game_id'])  ? (int)$_GET['game_id']  : 0;

$itemTitle = '';
$itemType  = '';

if ($movie_id) {
    $stmt = $conn->prepare("SELECT title FROM Movies WHERE movie_id = ?");
    $stmt->bind_param('i', $movie_id);
    $stmt->execute();
    $itemTitle = $stmt->get_result()->fetch_column() ?: 'Unknown movie';
    $itemType = 'Movie';
} elseif ($game_id) {
    $stmt = $conn->prepare("SELECT title FROM Games WHERE game_id = ?");
    $stmt->bind_param('i', $game_id);
    $stmt->execute();
    $itemTitle = $stmt->get_result()->fetch_column() ?: 'Unknown game';
    $itemType = 'Game';
} else {
    die('No item selected.');
}
$user_id = (int)$_SESSION['user_id'];


$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = (int)($_POST['rating'] ?? 0);
    $text   = trim($_POST['review_text'] ?? '');

    if ($rating < 1 || $rating > 10) {
        $errors[] = 'Rating must be between 1 and 10.';
    }
    if ($text === '') {
        $errors[] = 'Please write a short review.';
    }

    if (!$errors) {
        $user_id = (int)$_SESSION['user_id'];

        $stmt = $conn->prepare("
            INSERT INTO reviews (user_id, movie_id, game_id, rating, review_text)
            VALUES (?, ?, ?, ?, ?)
        ");
        // one of movie_id / game_id will be 0 → we convert 0 to NULL
        $m_id = $movie_id ?: null;
        $g_id = $game_id ?: null;

        $stmt->bind_param('iiiis', $user_id, $m_id, $g_id, $rating, $text);
        $stmt->execute();
        $success = true;
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Add Review</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="content" style="max-width:700px;margin:20px auto;">
    <h2>Add a review for <?= htmlspecialchars($itemTitle) ?> (<?= $itemType ?>)</h2>

    <?php if ($success): ?>
        <p>✅ Review saved! <a href="reviews.php">Go to reviews</a></p>
    <?php else: ?>
        <?php if ($errors): ?>
            <ul style="color:#fca5a5;">
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form method="post">
            <div class="field">
                <label>Rating (1–10)</label><br>
                <input type="number" name="rating" min="1" max="10" required>
            </div>

            <div class="field">
                <label>Your review</label><br>
                <textarea name="review_text" rows="5" style="width:100%;" required></textarea>
            </div>

            <button type="submit" class="btn">Submit review</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
