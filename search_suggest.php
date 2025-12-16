<?php
// search_suggest.php — returns a small JSON list of matching movies & games
require __DIR__ . '/db.php';

$q = trim($_GET['q'] ?? '');
$suggestions = [];

if ($q !== '') {
  $like = $q . '%'; // starts-with search
  $sql = "
    (SELECT title FROM Movies WHERE title LIKE ? LIMIT 5)
    UNION
    (SELECT title FROM Games WHERE title LIKE ? LIMIT 5)
    ORDER BY title ASC
  ";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ss', $like, $like);
  $stmt->execute();
  $res = $stmt->get_result();
  while ($row = $res->fetch_assoc()) {
    $suggestions[] = $row['title'];
  }
}

header('Content-Type: application/json');
echo json_encode($suggestions);