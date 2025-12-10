<?php
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

// Query by username OR email
$stmt = $conn->prepare('SELECT user_id, username, email, password_hash FROM Users WHERE username = ? OR email = ? LIMIT 1');
$stmt->bind_param('ss', $login, $login);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
  // Verify hashed password
 if (password_verify($password, $user['password_hash'])) {
    $_SESSION['user_id']  = $user['user_id'];   // NEW
    $_SESSION['username'] = $user['username'];  // keep this too
    echo json_encode(['success' => true, 'message' => 'Login successful.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid password.']);
  }
} else {
  echo json_encode(['success' => false, 'message' => 'User not found.']);
}