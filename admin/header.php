<?php
/**
 * VPS Admin Layout Header & Navigation
 */
require_once __DIR__ . '/../include/classes/session.php';
require_once __DIR__ . '/../include/functions.php';

// Authorization Guard
if (!$session->logged_in || !$session->isAdmin()) {
    header("Location: login.php?msg=auth_required");
    exit;
}

$current_admin_page = basename($_SERVER['PHP_SELF'], '.php');
$admin_user = $session->username;

// Get dynamic badge counts
try {
    $db = get_db();
    $pending_proj_count = (int)$db->query("SELECT COUNT(*) FROM projects WHERE status = 'Pending Review'")->fetchColumn();
    $unread_msg_count   = (int)$db->query("SELECT COUNT(*) FROM contact_messages WHERE is_read = 0")->fetchColumn();
} catch (Exception $e) {
    $pending_proj_count = 0;
    $unread_msg_count = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= e($admin_title ?? 'Admin Dashboard') ?> | VPS Management</title>
  
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">

  <!-- Tailwind CSS -->
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
  <style>
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400;
      line-height: 1;
      vertical-align: middle;
    }
    /* Custom scrollbar for admin tables */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
  </style>
</head>
<body class="bg-[#F4F6FA] font-sans text-ink min-h-screen flex flex-col antialiased">

  <div class="flex flex-1 overflow-hidden">

    <!-- Sidebar Navigation -->
    <aside class="w-64 bg-[#0A1128] text-slate-300 flex flex-col shrink-0 hidden md:flex transition-all duration-300">
      
      <!-- Brand Logo -->
      <div class="h-20 flex items-center px-6 border-b border-white/10 gap-3">
        <svg class="h-8 w-8" viewBox="0 0 40 40">
          <defs>
            <linearGradient id="vpsGradSB" x1="0" y1="0" x2="1" y2="1">
              <stop offset="0" stop-color="#2F54EB"/>
              <stop offset="1" stop-color="#14C8E8"/>
            </linearGradient>
          </defs>
          <polygon points="4,4 14,4 20,13.75 20,30" fill="#fff"/>
          <polygon points="36,4 26,4 20,13.75 20,30" fill="url(#vpsGradSB)"/>
          <circle cx="20" cy="35" r="3.2" fill="#FF7A1A"/>
        </svg>
        <div>
          <span class="block font-display text-xl font-extrabold tracking-tight text-white leading-none">VPS</span>
          <span class="block text-[8px] font-semibold tracking-[.25em] text-cyan mt-1">ADMIN PORTAL</span>
        </div>
      </div>

      <!-- Nav Items -->
      <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto text-sm font-medium">
        <a href="index.php" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition <?= $current_admin_page === 'index' ? 'bg-brand text-white shadow-lg shadow-brand/30' : 'hover:bg-white/5 hover:text-white' ?>">
          <span class="material-symbols-outlined text-xl">dashboard</span>
          <span>Dashboard</span>
        </a>

        <a href="projects.php" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition <?= strpos($current_admin_page, 'project') !== false ? 'bg-brand text-white shadow-lg shadow-brand/30' : 'hover:bg-white/5 hover:text-white' ?>">
          <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-xl">assignment</span>
            <span>Projects</span>
          </div>
          <?php if ($pending_proj_count > 0): ?>
            <span class="rounded-full bg-cta px-2 py-0.5 text-[10px] font-bold text-white"><?= $pending_proj_count ?></span>
          <?php endif; ?>
        </a>

        <a href="services.php" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition <?= $current_admin_page === 'services' ? 'bg-brand text-white shadow-lg shadow-brand/30' : 'hover:bg-white/5 hover:text-white' ?>">
          <span class="material-symbols-outlined text-xl">layers</span>
          <span>Services</span>
        </a>

        <a href="portfolio.php" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition <?= $current_admin_page === 'portfolio' ? 'bg-brand text-white shadow-lg shadow-brand/30' : 'hover:bg-white/5 hover:text-white' ?>">
          <span class="material-symbols-outlined text-xl">palette</span>
          <span>Portfolio / Studies</span>
        </a>

        <a href="team.php" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition <?= $current_admin_page === 'team' ? 'bg-brand text-white shadow-lg shadow-brand/30' : 'hover:bg-white/5 hover:text-white' ?>">
          <span class="material-symbols-outlined text-xl">groups</span>
          <span>Team Members</span>
        </a>

        <a href="blog.php" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition <?= $current_admin_page === 'blog' ? 'bg-brand text-white shadow-lg shadow-brand/30' : 'hover:bg-white/5 hover:text-white' ?>">
          <span class="material-symbols-outlined text-xl">edit_note</span>
          <span>Blog / Insights</span>
        </a>

        <a href="messages.php" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition <?= $current_admin_page === 'messages' ? 'bg-brand text-white shadow-lg shadow-brand/30' : 'hover:bg-white/5 hover:text-white' ?>">
          <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-xl">mail</span>
            <span>Messages</span>
          </div>
          <?php if ($unread_msg_count > 0): ?>
            <span class="rounded-full bg-cyan px-2 py-0.5 text-[10px] font-bold text-navy"><?= $unread_msg_count ?></span>
          <?php endif; ?>
        </a>

        <a href="users.php" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition <?= $current_admin_page === 'users' ? 'bg-brand text-white shadow-lg shadow-brand/30' : 'hover:bg-white/5 hover:text-white' ?>">
          <span class="material-symbols-outlined text-xl">manage_accounts</span>
          <span>Users &amp; Roles</span>
        </a>

        <a href="settings.php" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition <?= $current_admin_page === 'settings' ? 'bg-brand text-white shadow-lg shadow-brand/30' : 'hover:bg-white/5 hover:text-white' ?>">
          <span class="material-symbols-outlined text-xl">settings</span>
          <span>Website Settings</span>
        </a>

        <div class="pt-6 border-t border-white/10 mt-6 space-y-1.5">
          <a href="../index.php" target="_blank" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-slate-400 hover:text-cyan transition text-xs">
            <span class="material-symbols-outlined text-lg">open_in_new</span>
            <span>View Public Website</span>
          </a>

          <a href="logout.php" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-red-400 hover:bg-red-500/10 transition text-xs">
            <span class="material-symbols-outlined text-lg">logout</span>
            <span>Sign Out</span>
          </a>
        </div>
      </nav>

      <!-- Current Admin Footer Tag -->
      <div class="p-4 border-t border-white/10 flex items-center gap-3">
        <div class="h-9 w-9 rounded-full bg-cyan text-navy font-bold grid place-items-center text-xs">
          <?= strtoupper(substr($admin_user, 0, 2)) ?>
        </div>
        <div class="overflow-hidden">
          <p class="text-xs font-bold text-white truncate"><?= e($admin_user) ?></p>
          <p class="text-[10px] text-cyan uppercase tracking-wider">Super Administrator</p>
        </div>
      </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-y-auto">
      
      <!-- Top Bar -->
      <header class="h-20 bg-white border-b border-slate-200 px-6 lg:px-8 flex items-center justify-between shrink-0">
        <div class="flex items-center gap-4">
          <!-- Mobile Menu Toggle Button -->
          <button id="adminMobileBtn" class="p-2 rounded-lg text-slate-600 hover:bg-slate-100 md:hidden">
            <span class="material-symbols-outlined">menu</span>
          </button>
          <h1 class="font-display text-xl font-bold text-ink"><?= e($admin_title ?? 'Overview') ?></h1>
        </div>

        <div class="flex items-center gap-4">
          <a href="projects.php" class="relative p-2 text-slate-500 hover:text-brand transition" title="Pending Inquiries">
            <span class="material-symbols-outlined text-2xl">notifications</span>
            <?php if ($pending_proj_count > 0): ?>
              <span class="absolute top-1.5 right-1.5 h-2.5 w-2.5 rounded-full bg-cta ring-2 ring-white"></span>
            <?php endif; ?>
          </a>

          <div class="h-6 w-px bg-slate-200"></div>

          <div class="flex items-center gap-2.5">
            <span class="text-xs font-semibold text-slate-600 hidden sm:inline">Signed in as <b class="text-ink"><?= e($admin_user) ?></b></span>
            <a href="logout.php" class="rounded-lg bg-soft px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50 transition">
              Logout
            </a>
          </div>
        </div>
      </header>

      <!-- Flash Notification Container -->
      <?php 
      $flashes = get_flash();
      if (!empty($flashes)): 
      ?>
        <div class="px-6 lg:px-8 pt-6 space-y-3">
          <?php foreach ($flashes as $type => $msgs): 
            $alertClass = ($type === 'success') ? 'bg-green-50 border-green-200 text-green-800' : (($type === 'error') ? 'bg-red-50 border-red-200 text-red-800' : 'bg-blue-50 border-blue-200 text-blue-800');
            $icon = ($type === 'success') ? 'check_circle' : (($type === 'error') ? 'error' : 'info');
          ?>
            <?php foreach ($msgs as $m): ?>
              <div class="rounded-xl border p-4 flex items-center gap-3 text-sm <?= $alertClass ?>">
                <span class="material-symbols-outlined"><?= $icon ?></span>
                <p><?= e($m) ?></p>
              </div>
            <?php endforeach; ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <!-- Page Body Inner -->
      <main class="flex-1 p-6 lg:p-8">
