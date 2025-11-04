<?php
require __DIR__ . '/db.php';

// Extract POST
$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm  = $_POST['confirm'] ?? '';

if ($username === '' || $email === '' || $password === '' || $confirm === '') {
  echo 'Please fill in all fields.';
  exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo 'Invalid email format.';
  exit;
}
if ($password !== $confirm) {
  echo 'Passwords do not match.';
  exit;
}
if (strlen($password) < 8) {
  echo 'Password must be at least 8 characters.';
  exit;
}

// Check username exists
$exists = false;
$stmt = $conn->prepare('SELECT 1 FROM Users WHERE username = ? LIMIT 1');
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) $exists = true;

// Check email exists
$stmt = $conn->prepare('SELECT 1 FROM Users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) $exists = true;

if ($exists) {
  echo 'Username or email already exists.';
  exit;
}

// Hash password (PASSWORD_DEFAULT per lecture)
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare('INSERT INTO Users (username, email, password_hash) VALUES (?,?,?)');
$stmt->bind_param('sss', $username, $email, $hash);
$stmt->execute();

echo 'User created successfully. You can now log in.';
