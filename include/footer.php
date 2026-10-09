<?php
/**
 * VPS Footer Component
 */
require_once __DIR__ . '/functions.php';

$contact_phone = get_setting('contact_phone', '03328912706');
$contact_whatsapp = get_setting('contact_whatsapp', '+92 332 8912706');
$contact_email = get_setting('contact_email', 'info@vprovideservices.com');
$clean_whatsapp = preg_replace('/[^0-9]/', '', $contact_whatsapp);
$current_year = date('Y');

// Fetch top services for footer links
try {
    $db = get_db();
    $footer_services = $db->query("SELECT title, slug FROM services WHERE is_active = 1 ORDER BY display_order ASC LIMIT 8")->fetchAll();
} catch (Exception $e) {
    $footer_services = [];
}
?>
  </main>

  <!-- Global Footer -->
  <footer class="bg-navy text-slate-300">
    <div class="h-0.5 bg-gradient-to-r from-brand via-cyan to-cta"></div>
    <div class="mx-auto grid max-w-[1280px] gap-10 px-5 py-16 sm:grid-cols-2 lg:grid-cols-12 lg:px-8">
      
      <!-- Brand & Mission -->
      <div class="lg:col-span-4">
        <a href="index.php" class="flex items-center gap-2.5" aria-label="VPS Home">
          <svg class="h-9 w-9" style="--logo-left:#fff" viewBox="0 0 40 40" aria-hidden="true"><use href="#vps-mark"/></svg>
          <span class="leading-none">
            <span class="block font-display text-2xl font-extrabold tracking-tight text-white">VPS</span>
            <span class="mt-1 block text-[9px] font-semibold tracking-[.22em] text-cyan">V PROVIDE SERVICES</span>
          </span>
        </a>
        <p class="mt-5 text-sm font-semibold text-white">Everything Digital. One Trusted Provider.</p>
        <p class="mt-2 text-sm leading-6">VPS delivers elite web and app development, graphic design, e-commerce management, data science, architecture and interior visualization for businesses worldwide.</p>
        
        <!-- Newsletter Form -->
        <form id="newsletterForm" action="newsletter-subscribe.php" method="post" class="mt-5 flex gap-2">
          <?= csrf_field() ?>
          <input type="email" name="email" required placeholder="Enter your email" aria-label="Email for newsletter" class="min-w-0 flex-1 rounded-lg border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-cyan focus:outline-none">
          <button type="submit" class="rounded-lg bg-brand px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-cyan hover:text-navy">Subscribe</button>
        </form>
        <div id="newsletterMsg" class="mt-2 text-xs hidden"></div>

        <!-- Social Icons -->
        <div class="mt-5 flex gap-2">
          <a href="<?= e(get_setting('social_facebook', '#')) ?>" target="_blank" rel="noopener" aria-label="Facebook" class="grid h-9 w-9 place-items-center rounded-lg bg-white/10 text-sm font-bold text-white transition hover:bg-brand">f</a>
          <a href="<?= e(get_setting('social_linkedin', '#')) ?>" target="_blank" rel="noopener" aria-label="LinkedIn" class="grid h-9 w-9 place-items-center rounded-lg bg-white/10 text-sm font-bold text-white transition hover:bg-brand">in</a>
          <a href="<?= e(get_setting('social_instagram', '#')) ?>" target="_blank" rel="noopener" aria-label="Instagram" class="grid h-9 w-9 place-items-center rounded-lg bg-white/10 text-sm font-bold text-white transition hover:bg-brand">ig</a>
          <a href="https://wa.me/<?= e($clean_whatsapp) ?>" target="_blank" rel="noopener" aria-label="WhatsApp" class="grid h-9 w-9 place-items-center rounded-lg bg-white/10 text-sm font-bold text-white transition hover:bg-green-600">wa</a>
        </div>
      </div>

      <!-- Services List -->
      <div class="lg:col-span-3">
        <h3 class="text-sm font-bold uppercase tracking-wider text-white">Services</h3>
        <ul class="mt-5 space-y-3 text-sm">
          <?php if (!empty($footer_services)): ?>
            <?php foreach ($footer_services as $fs): ?>
              <li><a href="service-details.php?slug=<?= urlencode($fs['slug']) ?>" class="transition-colors hover:text-cyan"><?= e($fs['title']) ?></a></li>
            <?php endforeach; ?>
          <?php else: ?>
            <li><a href="services.php" class="transition-colors hover:text-cyan">Web Development</a></li>
            <li><a href="services.php" class="transition-colors hover:text-cyan">Graphic Designing</a></li>
            <li><a href="services.php" class="transition-colors hover:text-cyan">App Development</a></li>
            <li><a href="services.php" class="transition-colors hover:text-cyan">Logo Designing</a></li>
            <li><a href="services.php" class="transition-colors hover:text-cyan">Ecommerce Management</a></li>
            <li><a href="services.php" class="transition-colors hover:text-cyan">Data Science</a></li>
            <li><a href="services.php" class="transition-colors hover:text-cyan">Architecture Design</a></li>
            <li><a href="services.php" class="transition-colors hover:text-cyan">Interior Design</a></li>
          <?php endif; ?>
        </ul>
      </div>

      <!-- Company Links -->
      <div class="lg:col-span-2">
        <h3 class="text-sm font-bold uppercase tracking-wider text-white">Company</h3>
        <ul class="mt-5 space-y-3 text-sm">
          <li><a href="about.php" class="transition-colors hover:text-cyan">About Us</a></li>
          <li><a href="portfolio.php" class="transition-colors hover:text-cyan">Portfolio</a></li>
          <li><a href="team.php" class="transition-colors hover:text-cyan">Team</a></li>
          <li><a href="blog.php" class="transition-colors hover:text-cyan">Blog & Insights</a></li>
          <li><a href="contact.php" class="transition-colors hover:text-cyan">Contact Us</a></li>
          <li><a href="start-project.php" class="transition-colors text-cta hover:underline">Start a Project</a></li>
          <li><a href="admin/login.php" class="transition-colors text-slate-500 hover:text-slate-300">Staff Portal</a></li>
        </ul>
      </div>

      <!-- Get in Touch -->
      <div class="lg:col-span-3">
        <h3 class="text-sm font-bold uppercase tracking-wider text-white">Get in Touch</h3>
        <ul class="mt-5 space-y-3 text-sm">
          <li class="flex items-center gap-2">
            <span class="material-symbols-outlined text-cyan text-lg">mail</span>
            <a href="mailto:<?= e($contact_email) ?>" class="hover:text-white"><?= e($contact_email) ?></a>
          </li>
          <li class="flex items-center gap-2">
            <span class="material-symbols-outlined text-cyan text-lg">call</span>
            <a href="tel:<?= e($contact_phone) ?>" class="hover:text-white"><?= e($contact_phone) ?></a>
          </li>
          <li class="flex items-center gap-2">
            <span class="material-symbols-outlined text-green-400 text-lg">chat</span>
            <a href="https://wa.me/<?= e($clean_whatsapp) ?>" target="_blank" rel="noopener" class="hover:text-white">WhatsApp: <?= e($contact_whatsapp) ?></a>
          </li>
          <li class="flex items-center gap-2">
            <span class="material-symbols-outlined text-cyan text-lg">public</span>
            <span>Serving clients worldwide</span>
          </li>
          <li class="flex items-center gap-2">
            <span class="material-symbols-outlined text-cyan text-lg">schedule</span>
            <span>Mon to Sat, 9:00 to 18:00 (PKT)</span>
          </li>
        </ul>
      </div>

    </div>

    <!-- Bottom Bar with Legal Links -->
    <div class="border-t border-white/10">
      <div class="mx-auto flex max-w-[1280px] flex-col items-center justify-between gap-3 px-5 py-6 text-sm sm:flex-row lg:px-8">
        <p>&copy; <?= $current_year ?> V Provide Services (VPS). Registered in Pakistan. All rights reserved.</p>
        <div class="flex flex-wrap justify-center gap-5">
          <a href="privacy-policy.php" class="hover:text-cyan">Privacy Policy</a>
          <a href="terms-and-conditions.php" class="hover:text-cyan">Terms & Conditions</a>
          <a href="refund-policy.php" class="hover:text-cyan">Refunds</a>
          <a href="cookie-policy.php" class="hover:text-cyan">Cookie Policy</a>
          <a href="project-terms.php" class="hover:text-cyan">Project Terms</a>
          <a href="disclaimer.php" class="hover:text-cyan">Disclaimer</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- Floating WhatsApp Quick Button -->
  <a href="https://wa.me/<?= e($clean_whatsapp) ?>" target="_blank" rel="noopener" aria-label="Chat on WhatsApp" class="fixed bottom-5 right-5 z-40 grid h-14 w-14 place-items-center rounded-full bg-green-500 text-white shadow-2xl transition hover:scale-110 hover:bg-green-600">
    <svg class="h-8 w-8 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.312.045-.694.074-1.995-.465-1.667-.688-2.73-2.39-2.812-2.502-.084-.112-.676-.901-.676-1.718 0-.816.425-1.218.577-1.385.151-.167.33-.21.441-.21.111 0 .222.002.319.006.104.004.243-.039.38.291.144.347.491 1.2.534 1.288.043.088.072.191.014.305-.058.115-.088.188-.174.288-.088.101-.184.225-.264.303-.088.086-.18.18-.077.357.103.176.458.756.983 1.224.675.602 1.244.788 1.421.876.176.088.28.073.383-.045.104-.117.442-.515.56-.692.119-.176.237-.147.399-.088.163.059 1.033.487 1.21.575.176.088.293.132.336.206.044.073.044.426-.1 1.031zM12 2C6.477 2 2 6.477 2 12c0 1.891.524 3.66 1.436 5.176L2 22l4.981-1.309A9.957 9.957 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2z"/></svg>
  </a>

  <!-- Floating Scroll To Top -->
  <button id="toTop" aria-label="Back to top" class="fixed bottom-24 right-5 z-40 hidden h-12 w-12 place-items-center rounded-full bg-brand text-white shadow-xl transition hover:bg-navy">
    <span class="material-symbols-outlined">arrow_upward</span>
  </button>

  <!-- Cookie Consent Banner -->
  <div id="ck" class="fixed inset-x-4 bottom-4 z-50 hidden max-w-xl rounded-xl bg-white p-4 shadow-2xl ring-1 ring-slate-200 sm:left-6">
    <p class="text-sm text-muted">We use cookies to ensure optimal functionality on our website. Review our <a href="cookie-policy.php" class="font-semibold text-brand underline">Cookie Policy</a>.</p>
    <div class="mt-3 flex gap-2">
      <button data-ck="1" class="rounded-lg bg-brand px-4 py-2 text-sm font-semibold text-white transition hover:bg-navy">Accept Cookies</button>
      <button data-ck="0" class="rounded-lg bg-soft px-4 py-2 text-sm font-semibold text-ink transition hover:bg-slate-200">Decline</button>
    </div>
  </div>

  <!-- jQuery & Global Script -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script>
    const $q = (s, r = document) => r.querySelector(s);
    const $$q = (s, r = document) => [...r.querySelectorAll(s)];
    
    // Header & To-top behavior
    const hdr = $q('#hdr');
    const toTop = $q('#toTop');
    const menuBtn = $q('#menuBtn');
    const mobMenu = $q('#mobileMenu');
    
    const handleScroll = () => {
      if (hdr && !hdr.classList.contains('always-solid')) {
        hdr.classList.toggle('solid', window.scrollY > 20 || (mobMenu && !mobMenu.classList.contains('hidden')));
      }
      if (toTop) {
        toTop.classList.toggle('hidden', window.scrollY < 400);
        toTop.classList.toggle('grid', window.scrollY >= 400);
      }
    };
    window.addEventListener('scroll', handleScroll);
    handleScroll();

    if (menuBtn && mobMenu) {
      menuBtn.onclick = () => {
        mobMenu.classList.toggle('hidden');
        menuBtn.setAttribute('aria-expanded', !mobMenu.classList.contains('hidden'));
        handleScroll();
      };
    }

    if (toTop) {
      toTop.onclick = () => window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Scroll reveal observer
    const ioReveal = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('in');
          ioReveal.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });
    $$q('.reveal').forEach(el => ioReveal.observe(el));

    // Stats counter animation
    const statsSec = $q('#stats');
    if (statsSec) {
      const co = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (!entry.isIntersecting) return;
          $$q('[data-count]', entry.target).forEach(el => {
            const target = +el.dataset.count;
            const t0 = performance.now();
            (function loop(t) {
              const p = Math.min((t - t0) / 1400, 1);
              el.textContent = Math.round(target * (1 - Math.pow(1 - p, 3)));
              if (p < 1) requestAnimationFrame(loop);
            })(t0);
          });
          co.unobserve(entry.target);
        });
      }, { threshold: 0.3 });
      co.observe(statsSec);
    }

    // Cookie consent storage
    const ckEl = $q('#ck');
    if (ckEl) {
      try {
        if (!localStorage.getItem('vps_cookie_consent')) {
          ckEl.classList.remove('hidden');
        }
      } catch (e) {}
      $$q('[data-ck]', ckEl).forEach(btn => {
        btn.onclick = () => {
          try { localStorage.setItem('vps_cookie_consent', btn.dataset.ck); } catch (e) {}
          ckEl.classList.add('hidden');
        };
      });
    }

    // Newsletter AJAX handler
    $('#newsletterForm').on('submit', function(e) {
      e.preventDefault();
      const $form = $(this);
      const $msg = $('#newsletterMsg');
      $.ajax({
        url: $form.attr('action'),
        type: 'POST',
        data: $form.serialize(),
        dataType: 'json',
        success: function(resp) {
          $msg.removeClass('hidden text-red-400 text-green-400');
          if (resp.success) {
            $msg.addClass('text-green-400').text(resp.message || 'Thank you for subscribing!').show();
            $form[0].reset();
          } else {
            $msg.addClass('text-red-400').text(resp.message || 'Subscription failed.').show();
          }
        },
        error: function() {
          $msg.removeClass('hidden text-green-400').addClass('text-red-400').text('Server error. Please try again later.').show();
        }
      });
    });
  </script>
</body>
</html>
