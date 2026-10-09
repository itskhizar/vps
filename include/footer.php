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
  <a href="https://wa.me/<?= e($clean_whatsapp) ?>?text=Hi%20VPS%2C%20I%20would%20like%20to%20discuss%20a%20project." target="_blank" rel="noopener noreferrer" aria-label="Chat with VPS on WhatsApp" class="group fixed bottom-5 right-5 z-40 flex items-center gap-2">
    <!-- Interactive Tooltip Pill -->
    <span class="pointer-events-none hidden rounded-full bg-navy/95 px-3.5 py-2 text-xs font-semibold text-white shadow-xl ring-1 ring-white/10 transition-all duration-300 group-hover:translate-x-0 group-hover:opacity-100 sm:flex sm:items-center sm:gap-2">
      <span class="h-2 w-2 rounded-full bg-[#25D366] animate-pulse"></span>
      <span>Chat on WhatsApp</span>
    </span>
    
    <!-- Button with Radar Pulse -->
    <div class="relative grid h-14 w-14 place-items-center rounded-full bg-[#25D366] text-white shadow-[0_10px_30px_rgba(37,211,102,0.45)] transition-all duration-300 hover:scale-110 hover:shadow-[0_12px_35px_rgba(37,211,102,0.6)]">
      <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[#25D366] opacity-30"></span>
      <!-- Official WhatsApp SVG Icon -->
      <svg class="relative h-8 w-8 fill-white" viewBox="0 0 24 24" aria-hidden="true">
        <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2zm5.79 14.07c-.24.68-1.39 1.3-1.95 1.38-.52.08-1.16.12-3.33-.78-2.77-1.15-4.55-3.99-4.69-4.17-.14-.19-1.13-1.5-1.13-2.87 0-1.37.71-2.04.96-2.32.25-.28.55-.35.73-.35.19 0 .37 0 .53.01.17.01.41-.06.63.49.24.58.82 2 .89 2.15.07.15.12.32.02.51-.1.2-.15.31-.29.48-.15.17-.31.38-.44.51-.15.15-.3.3-.13.6.17.29.76 1.26 1.64 2.04 1.13 1.01 2.08 1.32 2.37 1.47.3.15.47.12.64-.08.18-.2.76-.88.96-1.18.2-.3.4-.25.68-.15.28.1 1.77.83 2.07.98.3.15.5.22.58.35.07.13.07.72-.17 1.4z"/>
      </svg>
    </div>
  </a>

  <!-- Floating Scroll To Top -->
  <button id="toTop" aria-label="Back to top" class="fixed bottom-24 right-5 z-40 hidden h-12 w-12 place-items-center rounded-full bg-brand text-white shadow-xl transition-all duration-300 hover:bg-navy hover:scale-105">
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
    window.addEventListener('scroll', handleScroll, { passive: true });
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

    // High-performance Scroll Reveal Observer
    const reveals = $$q('.reveal');
    const ioReveal = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('in');
          ioReveal.unobserve(entry.target);
        }
      });
    }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
    
    reveals.forEach(el => {
      // If already in viewport on load, reveal immediately
      const rect = el.getBoundingClientRect();
      if (rect.top < window.innerHeight && rect.bottom > 0) {
        el.classList.add('in');
      } else {
        ioReveal.observe(el);
      }
    });

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
