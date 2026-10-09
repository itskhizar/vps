      </main>

      <!-- Admin Footer Bar -->
      <footer class="h-14 border-t border-slate-200 bg-white px-6 lg:px-8 flex items-center justify-between text-xs text-muted">
        <p>&copy; <?= date('Y') ?> VPS Management Portal. All rights reserved.</p>
        <p>PHP 8.4 • MySQL Database • Secure Admin Session</p>
      </footer>

    </div>
  </div>

  <!-- Mobile Drawer Backdrop & Menu -->
  <div id="mobileDrawer" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden md:hidden">
    <div class="w-64 bg-[#0A1128] text-slate-300 h-full p-6 flex flex-col justify-between">
      <div>
        <div class="flex items-center justify-between pb-6 border-b border-white/10">
          <span class="font-display font-bold text-white text-lg">VPS Admin</span>
          <button id="closeDrawerBtn" class="text-slate-400 hover:text-white">
            <span class="material-symbols-outlined">close</span>
          </button>
        </div>
        <nav class="mt-6 space-y-2 text-sm">
          <a href="index.php" class="block py-2 text-white">Dashboard</a>
          <a href="projects.php" class="block py-2 text-white">Projects</a>
          <a href="services.php" class="block py-2 text-white">Services</a>
          <a href="portfolio.php" class="block py-2 text-white">Portfolio</a>
          <a href="team.php" class="block py-2 text-white">Team</a>
          <a href="blog.php" class="block py-2 text-white">Blog</a>
          <a href="messages.php" class="block py-2 text-white">Messages</a>
          <a href="users.php" class="block py-2 text-white">Users &amp; Roles</a>
          <a href="settings.php" class="block py-2 text-white">Settings</a>
        </nav>
      </div>
      <div>
        <a href="logout.php" class="block py-2 text-red-400 font-semibold text-sm">Sign Out</a>
      </div>
    </div>
  </div>

  <!-- jQuery & Admin JS Scripts -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script>
    const adminMobBtn = document.getElementById('adminMobileBtn');
    const mobDrawer = document.getElementById('mobileDrawer');
    const closeDrawerBtn = document.getElementById('closeDrawerBtn');

    if (adminMobBtn && mobDrawer) {
      adminMobBtn.onclick = () => mobDrawer.classList.remove('hidden');
    }
    if (closeDrawerBtn && mobDrawer) {
      closeDrawerBtn.onclick = () => mobDrawer.classList.add('hidden');
    }
  </script>
</body>
</html>
