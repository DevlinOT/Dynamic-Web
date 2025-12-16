<?php
session_start();
$loggedInUser = $_SESSION['username'] ?? null;
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Movie/Game Hub</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <header class="topbar">
    <div class="brand">🎬 Crossover Hub</div>
    <div class="userbox">
      <?php if ($loggedInUser): ?>
        <span>Hi, <strong><?= htmlspecialchars($loggedInUser) ?></strong></span>
        <a class="btn" href="logout.php">Logout</a>
      <?php else: ?>
        <button class="btn" onclick="openLogin()">Login</button>
      <?php endif; ?>
    </div>
  </header>

  <main class="content">
    <h1>Welcome<?= $loggedInUser ? ', ' . htmlspecialchars($loggedInUser) : '' ?>!</h1>
    <p>Browse movies and games. Link pairs. Post reviews.</p>
  </main>

  <!-- Backdrop -->
  <div id="backdrop" class="backdrop" onclick="closeLogin()"></div>

  <!-- Modal -->
  <div id="login_modal" class="modal">
    <div class="modal_header">
      <h2 id="modal_title">Login</h2>
      <button class="close_btn" onclick="closeLogin()">×</button>
    </div>
    <div class="modal_body" id="modal_body">
      <!-- Default = Login form -->
      <form id="login_form" onsubmit="return loginUser(event)">
        <div class="field">
          <label>Username or Email</label>
          <input name="login" required>
        </div>
        <div class="field">
          <label>Password</label>
          <input type="password" name="password" required>
        </div>
        <div id="login_response" class="response"></div>
        <div class="actions">
          <button type="submit" class="btn">Log In</button>
          <button type="button" class="link_btn" onclick="switchToCreate()">Create account</button>
        </div>
      </form>
    </div>
  </div>

  <script src="js/utils.js"></script>
</body>
</html>