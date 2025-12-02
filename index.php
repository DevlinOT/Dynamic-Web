<?php
session_start();
$loggedInUser = $_SESSION['username'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Movie/Game Hub</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
 

  <header class="topbar">
    <div class="brand">🎬 Crossover Hub</div>
    <?php include 'navbar.php'; ?>
    <div class="userbox" id="userbox">
      <?php if ($loggedInUser): ?>
        <span>Hi, <strong><?= htmlspecialchars($loggedInUser) ?></strong></span>
        <a class="btn" onclick="logout()">Logout</a>
      <?php else: ?>
        <button class="btn" onclick="openLogin()">Login</button>
      <?php endif; ?>
    </div>
  </header>

  <main class="content">
    <h1>Welcome<?= $loggedInUser ? ', ' . htmlspecialchars($loggedInUser) : '' ?>!</h1>
    <p>Browse movies and games. Link pairs. Post reviews.</p>
  <section class="search-section">
  <h1 class="search-title">Search</h1>

  <form class="searchbar" action="search.php" method="get" role="search" aria-label="Site search">
    <span class="icon" aria-hidden="true">
      <!-- magnifying glass (inline SVG, no external libs) -->
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
        <circle cx="11" cy="11" r="7.5" stroke="currentColor" stroke-width="2"/>
        <line x1="16.5" y1="16.5" x2="22" y2="22" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      </svg>
    </span>
    <input
      type="text"
      name="q"
      placeholder="Search"
      aria-label="Search"
    />
  </form>

  <div class="popular">
    <div class="popular-label">Popular</div>
    <div class="popular-grid">
      <!-- placeholders; replace with real posters later -->
      <div class="thumb"></div>
      <div class="thumb"></div>
      <div class="thumb"></div>
      <div class="thumb"></div>
    </div>
  </div>
</section>

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

  <script>
const searchInput = document.querySelector('.searchbar input');
const dropdown = document.createElement('ul');
dropdown.className = 'search-dropdown';
searchInput.parentNode.appendChild(dropdown);

let controller;

searchInput.addEventListener('input', async () => {
  const q = searchInput.value.trim();
  if (controller) controller.abort();
  dropdown.innerHTML = '';
  if (!q) return;

  controller = new AbortController();
  try {
    const res = await fetch(`search_suggest.php?q=${encodeURIComponent(q)}`, {
      signal: controller.signal
    });
    const suggestions = await res.json();

    if (!Array.isArray(suggestions) || suggestions.length === 0) return;

    suggestions.forEach(title => {
      const li = document.createElement('li');
      li.textContent = title;
      li.addEventListener('mousedown', () => {
        // Fill input and submit form when clicked
        searchInput.value = title;
        searchInput.closest('form').submit();
      });
      dropdown.appendChild(li);
    });
  } catch (err) {
    if (err.name !== 'AbortError') console.error(err);
  }
});

// Hide dropdown when clicking elsewhere
document.addEventListener('click', (e) => {
  if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
    dropdown.innerHTML = '';
  }
});
</script>

</body>
</html>