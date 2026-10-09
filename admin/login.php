<?php
/**
 * VPS Digital Services - Admin Authentication Login
 */
require_once __DIR__ . '/../include/classes/session.php';
require_once __DIR__ . '/../include/functions.php';

// If already logged in as admin, redirect to dashboard
if ($session->logged_in && $session->isAdmin()) {
    header("Location: index.php");
    exit;
}

$error = '';
$msg = trim($_GET['msg'] ?? '');
if ($msg === 'logged_out') {
    $info_msg = "You have been securely logged out.";
} elseif ($msg === 'auth_required') {
    $info_msg = "Please log in with administrative credentials.";
}

// Throttling attempts
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt_time'] = time();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $error = "Session verification failed. Please try again.";
    } elseif ($_SESSION['login_attempts'] >= 5 && (time() - $_SESSION['last_attempt_time']) < 300) {
        $remaining = 300 - (time() - $_SESSION['last_attempt_time']);
        $error = "Too many failed attempts. Please wait {$remaining} seconds.";
    } else {
        $user = trim($_POST['username'] ?? '');
        $pass = trim($_POST['password'] ?? '');

        if (empty($user) || empty($pass)) {
            $error = "Please enter both username and password.";
        } else {
            $login_ok = $session->login($user, $pass);
            if ($login_ok && $session->isAdmin()) {
                $_SESSION['login_attempts'] = 0;
                session_regenerate_id(true);
                header("Location: index.php");
                exit;
            } else {
                $_SESSION['login_attempts']++;
                $_SESSION['last_attempt_time'] = time();
                $session->logout();
                $error = "Invalid username, password, or insufficient permissions.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Sign In | VPS Management Portal</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: '#2F54EB',
            navy: '#0A1128',
            cyan: '#14C8E8',
            cta: '#FF7A1A',
            soft: '#F5F7FB',
            ink: '#1B2236',
            muted: '#6B7690'
          },
          fontFamily: {
            display: ['Plus Jakarta Sans', 'sans-serif'],
            sans: ['Inter', 'sans-serif']
          }
        }
      }
    }
  </script>
</head>
<body class="bg-[#0A1128] font-sans text-slate-100 min-h-screen flex items-center justify-center p-4">
  <div class="w-full max-w-md">
    
    <!-- Brand Logo -->
    <div class="text-center mb-8">
      <a href="../index.php" class="inline-flex items-center gap-3">
        <svg class="h-10 w-10" viewBox="0 0 40 40">
          <defs>
            <linearGradient id="vpsGradL" x1="0" y1="0" x2="1" y2="1">
              <stop offset="0" stop-color="#2F54EB"/>
              <stop offset="1" stop-color="#14C8E8"/>
            </linearGradient>
          </defs>
          <polygon points="4,4 14,4 20,13.75 20,30" fill="#fff"/>
          <polygon points="36,4 26,4 20,13.75 20,30" fill="url(#vpsGradL)"/>
          <circle cx="20" cy="35" r="3.2" fill="#FF7A1A"/>
        </svg>
        <span class="text-left leading-none">
          <span class="block font-display text-2xl font-extrabold tracking-tight text-white">VPS</span>
          <span class="block text-[8px] font-semibold tracking-[.25em] text-cyan">ADMIN PORTAL</span>
        </span>
      </a>
      <p class="mt-3 text-xs text-slate-400">Secure Company Management Dashboard</p>
    </div>

    <!-- Login Box -->
    <div class="rounded-3xl border border-white/10 bg-[#121936] p-8 shadow-2xl backdrop-blur-xl">
      <h2 class="font-display text-xl font-bold text-white mb-2">Staff Authentication</h2>
      <p class="text-xs text-slate-400 mb-6">Enter your administrative credentials to continue.</p>

      <?php if (!empty($info_msg)): ?>
        <div class="mb-5 rounded-xl border border-cyan/30 bg-cyan/10 p-3.5 text-xs text-cyan">
          <?= e($info_msg) ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($error)): ?>
        <div class="mb-5 rounded-xl border border-red-500/30 bg-red-500/10 p-3.5 text-xs text-red-400">
          <?= e($error) ?>
        </div>
      <?php endif; ?>

      <form action="login.php" method="post" class="space-y-5">
        <?= csrf_field() ?>

        <div>
          <label class="block text-xs font-semibold text-slate-300 mb-2">Username</label>
          <div class="relative">
            <span class="material-symbols-outlined absolute left-3.5 top-3 text-slate-400 text-lg">person</span>
            <input type="text" name="username" required value="<?= e($_POST['username'] ?? '') ?>" placeholder="admin" class="w-full rounded-xl border border-white/10 bg-white/5 pl-10 pr-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-cyan focus:outline-none focus:ring-1 focus:ring-cyan">
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-300 mb-2">Password</label>
          <div class="relative">
            <span class="material-symbols-outlined absolute left-3.5 top-3 text-slate-400 text-lg">lock</span>
            <input type="password" name="password" required placeholder="••••••••" class="w-full rounded-xl border border-white/10 bg-white/5 pl-10 pr-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-cyan focus:outline-none focus:ring-1 focus:ring-cyan">
          </div>
        </div>

        <button type="submit" class="w-full rounded-xl bg-brand py-3 text-sm font-semibold text-white shadow-lg shadow-brand/30 transition hover:bg-cyan hover:text-navy mt-2">
          Sign In to Dashboard
        </button>
      </form>

      <div class="mt-6 border-t border-white/5 pt-4 text-center">
        <a href="../index.php" class="text-xs text-slate-400 hover:text-cyan transition-colors">
          &larr; Return to Public Website
        </a>
      </div>
    </div>

  </div>
</body>
</html>
