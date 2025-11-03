<?php
// login.php
session_start();
header('Content-Type: application/json');
require __DIR__ . '/db.php';

// Get POSTed credentials (login can be username OR email)
$login = trim($_POST['login'] ?? '');
$password = $_POST['password'] ?? '';

if ($login === '' || $password === '') {
  echo json_encode(['success' => false, 'message' => 'Please enter your credentials.']);
  exit;
}

// Query by username OR email; *only fetch the row*, as in notes
$stmt = $conn->prepare('SELECT user_id, username, email, password_hash FROM Users WHERE username = ? OR email = ? LIMIT 1');
$stmt->bind_param('ss', $login, $login);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
  // Verify hashed password (password_verify as per lecture)
  if (password_verify($password, $user['password_hash'])) {
    $_SESSION['username'] = $user['username']; // store in session
    echo json_encode(['success' => true, 'message' => 'Login successful.']);
  } else {
    echo json_encode(['success' => false, 'message' => 'Invalid password.']);
  }
} else {
  echo json_encode(['success' => false, 'message' => 'User not found.']);
}