// Modal controls
function openLogin() {
  document.getElementById('backdrop').style.display = 'block';
  document.getElementById('login_modal').style.display = 'block';
  switchToLogin();
}
function closeLogin() {
  document.getElementById('backdrop').style.display = 'none';
  document.getElementById('login_modal').style.display = 'none';
}

// Switch between Login and Create User (keeps modal open)
function switchToCreate() {
  document.getElementById('modal_title').innerText = 'Create Account';
  document.getElementById('modal_body').innerHTML = `
    <form id="create_form" onsubmit="return createUser(event)">
      <div class="field">
        <label>Username</label>
        <input name="username" required>
      </div>
      <div class="field">
        <label>Email</label>
        <input type="email" name="email" required>
      </div>
      <div class="field">
        <label>Password</label>
        <input type="password" name="password" required>
      </div>
      <div class="field">
        <label>Confirm Password</label>
        <input type="password" name="confirm" required>
      </div>
      <div id="login_response2" class="response"></div>
      <div class="actions">
        <button type="submit" class="btn">Create</button>
        <button type="button" class="link_btn" onclick="switchToLogin()">Back to Login</button>
      </div>
    </form>
  `;
}

function switchToLogin() {
  document.getElementById('modal_title').innerText = 'Login';
  document.getElementById('modal_body').innerHTML = `
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
  `;
}

// Fetch: LOGIN (returns JSON)
async function loginUser(e) {
  e.preventDefault();
  const form = e.target;
  const data = new FormData(form);

  const res = await fetch('login.php', { method: 'POST', body: data });
  const json = await res.json().catch(() => ({}));

  const out = document.getElementById('login_response');
  if (json.success) {
    out.style.color = '#8ff0a4';
    out.textContent = json.message || 'Login successful';
    // Update UI: simplest is reload so header username appears
    setTimeout(() => location.reload(), 400);
  } else {
    out.style.color = '#ff9aa2';
    out.textContent = json.message || 'Login failed';
  }
  return false; // keep modal open on failure 
}

// Fetch: CREATE USER (returns text)
async function createUser(e) {
  e.preventDefault();
  const form = e.target;
  const data = new FormData(form);

  const res = await fetch('createUser.php', { method: 'POST', body: data });
  const text = await res.text();

  const out = document.getElementById('login_response2');
  out.textContent = text;  // just show the message
  // keep modal open so they can switch to Login
  return false;
}


async function logout() {
  try {
    // Send a POST request to logout.php
    const response = await fetch('logout.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      }
    });

    const data = await response.json();

    if (data.success) {
      // Update header dynamically
      const userbox = document.getElementById('userbox');
      userbox.innerHTML = `
        <button class="btn" id="loginBtn" onclick="openLogin()">Login</button>
      `;
      console.log('Logged out successfully');
    } else {
      console.error('Logout failed');
    }
  } catch (error) {
    console.error('Error:', error);
  }
}