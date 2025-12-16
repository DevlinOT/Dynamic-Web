<?php
// /Applications/MAMP/htdocs/games.php
session_start();
require __DIR__ . '/db.php';

$genre    = trim($_GET['genre']    ?? '');
$year     = trim($_GET['year']     ?? '');
$platform = trim($_GET['platform'] ?? '');
$company  = trim($_GET['company']  ?? ''); // maps to 'director' col

$conds = [];
$params = [];
$types  = '';

if ($genre !== '')    { $conds[] = "genre = ?";          $params[] = $genre;    $types .= 's'; }
if ($year  !== '')    { $conds[] = "release_year = ?";   $params[] = $year;     $types .= 'i'; }
if ($platform !== '') { $conds[] = "platform = ?";       $params[] = $platform; $types .= 's'; }
if ($company !== '')  { $conds[] = "director = ?";       $params[] = $company;  $types .= 's'; }

$sql = "SELECT game_id AS id, title, image_url, genre, release_year, platform, director, description
        FROM Games";
if ($conds) $sql .= " WHERE " . implode(" AND ", $conds);
$sql .= " ORDER BY title ASC";

$stmt = $conn->prepare($sql);
if ($conds) { $stmt->bind_param($types, ...$params); }
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// distincts
function distincts($conn, $col) {
  $q = $conn->prepare("SELECT DISTINCT $col AS v FROM Games WHERE $col IS NOT NULL AND $col <> '' ORDER BY v");
  $q->execute();
  return array_column($q->get_result()->fetch_all(MYSQLI_ASSOC), 'v');
}
$genres    = distincts($conn, 'genre');
$years     = array_column($conn->query("SELECT DISTINCT release_year AS v FROM Games WHERE release_year IS NOT NULL ORDER BY v DESC")->fetch_all(MYSQLI_ASSOC), 'v');
$platforms = distincts($conn, 'platform');
$companies = distincts($conn, 'director'); // using director column
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Games</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="icon" type="image/jpg" href="images/favicon.jpg">
</head>
<body>
 <?php include 'navbar.php'; ?>


  <div class="page">
    <aside class="filters">
      <form method="get">
        <details class="filter">
          <summary>Genre <span class="chev">▾</span></summary>
          <div class="menu">
            <a class="clear" href="games.php">↺ Clear</a>
            <?php foreach ($genres as $g): ?>
              <a href="?<?= http_build_query(array_filter(['genre'=>$g,'year'=>$year,'platform'=>$platform,'company'=>$company])) ?>"><?= htmlspecialchars($g) ?></a>
            <?php endforeach; ?>
          </div>
        </details>

        <details class="filter">
          <summary>Year <span class="chev">▾</span></summary>
          <div class="menu">
            <a class="clear" href="?<?= http_build_query(array_filter(['genre'=>$genre,'platform'=>$platform,'company'=>$company])) ?>">↺ Clear</a>
            <?php foreach ($years as $y): ?>
              <a href="?<?= http_build_query(array_filter(['genre'=>$genre,'year'=>$y,'platform'=>$platform,'company'=>$company])) ?>"><?= htmlspecialchars($y) ?></a>
            <?php endforeach; ?>
          </div>
        </details>

        <details class="filter">
          <summary>Platform <span class="chev">▾</span></summary>
          <div class="menu">
            <a class="clear" href="?<?= http_build_query(array_filter(['genre'=>$genre,'year'=>$year,'company'=>$company])) ?>">↺ Clear</a>
            <?php foreach ($platforms as $p): ?>
              <a href="?<?= http_build_query(array_filter(['genre'=>$genre,'year'=>$year,'platform'=>$p,'company'=>$company])) ?>"><?= htmlspecialchars($p) ?></a>
            <?php endforeach; ?>
          </div>
        </details>

        <details class="filter">
          <summary>Company <span class="chev">▾</span></summary>
          <div class="menu">
            <a class="clear" href="?<?= http_build_query(array_filter(['genre'=>$genre,'year'=>$year,'platform'=>$platform])) ?>">↺ Clear</a>
            <?php foreach ($companies as $c): ?>
              <a href="?<?= http_build_query(array_filter(['genre'=>$genre,'year'=>$year,'platform'=>$platform,'company'=>$c])) ?>"><?= htmlspecialchars($c) ?></a>
            <?php endforeach; ?>
          </div>
        </details>
      </form>
    </aside>

    <main>
      <h2 style="margin:6px 0 16px;">Games <?= ($genre||$year||$platform||$company)?'— filtered':'' ?></h2>
      <div class="grid">
        <?php foreach ($items as $it): ?>
          <article class="card">
            <?php if (!empty($it['image_url'])): ?>
              <img src="<?= htmlspecialchars($it['image_url']) ?>" alt="">
            <?php else: ?>
              <img src="https://via.placeholder.com/600x400?text=No+Image" alt="">
            <?php endif; ?>
            <div class="meta">
              <div><strong><?= htmlspecialchars($it['title']) ?></strong></div>
              <div class="muted"><?= htmlspecialchars($it['description'] ?: 'Body text.') ?></div>
              <div class="bar">
                <?php if ($it['genre'])        : ?><span class="pill"><?= htmlspecialchars($it['genre']) ?></span><?php endif; ?>
                <?php if ($it['release_year']) : ?><span class="pill"><?= htmlspecialchars($it['release_year']) ?></span><?php endif; ?>
                <?php if ($it['platform'])     : ?><span class="pill"><?= htmlspecialchars($it['platform']) ?></span><?php endif; ?>
                <?php if ($it['director'])     : ?><span class="pill"><?= htmlspecialchars($it['director']) ?></span><?php endif; ?>
              </div>
               <!-- NEW: review button -->
        <div style="margin-top:10px;">
            <a href="add_review.php?game_id=<?= (int)$it['id'] ?>" class="btn">Add review</a>
        </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
      <?php if (!$items): ?><p>No games found for this filter.</p><?php endif; ?>
    </main>
  </div>
</body>
</html>

