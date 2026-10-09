<?php
/**
 * VPS Digital Services - Services Overview
 */
require_once __DIR__ . '/include/functions.php';

$page_title = "Our Services | VPS - Web, App, Design, Data & Architecture";
$page_desc  = "Explore VPS's 10 core specialized digital services: custom website and app development, branding, graphic design, e-commerce management, and 3D architectural solutions.";
$is_solid_header = false;

try {
    $db = get_db();
    $stmt = $db->query("SELECT * FROM services WHERE is_active = 1 ORDER BY display_order ASC");
    $services = $stmt->fetchAll();
    
    // Extract unique categories
    $categories = array_unique(array_filter(array_column($services, 'category')));
} catch (Exception $e) {
    error_log("Services page error: " . $e->getMessage());
    $services = [];
    $categories = [];
}

include __DIR__ . '/include/header.php';
?>

<!-- Services Hero Header -->
<section class="hero relative overflow-hidden pb-20 pt-32 text-white lg:pb-28 lg:pt-36">
  <div class="grid-bg absolute inset-0"></div>
  <div class="relative mx-auto max-w-[1280px] px-5 text-center lg:px-8">
    <span class="glass inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold uppercase tracking-[.16em] text-cyan">
      Comprehensive Capabilities
    </span>
    <h1 class="mt-6 font-display text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
      World-Class Digital &amp; Creative Services
    </h1>
    <p class="mx-auto mt-5 max-w-2xl text-lg text-slate-300">
      From robust web and mobile engineering to high-converting branding and photorealistic 3D architectural renders, we power your growth from concept to scale.
    </p>
  </div>
</section>

<!-- Services Grid Section with Filter -->
<section class="bg-white py-16 lg:py-24">
  <div class="mx-auto max-w-[1280px] px-5 lg:px-8">
    
    <!-- Category Filter Controls -->
    <?php if (!empty($categories)): ?>
      <div class="mb-12 flex flex-wrap justify-center gap-2" role="group" aria-label="Filter Services">
        <button data-cat="all" class="sf-btn rounded-full px-5 py-2 text-sm font-semibold transition bg-brand text-white shadow-md shadow-brand/30">All Services</button>
        <?php foreach ($categories as $cat): ?>
          <button data-cat="<?= e(slugify($cat)) ?>" class="sf-btn rounded-full px-5 py-2 text-sm font-semibold transition bg-white text-muted ring-1 ring-slate-200 hover:text-ink">
            <?= e($cat) ?>
          </button>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <!-- Services Grid -->
    <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
      <?php if (!empty($services)): ?>
        <?php foreach ($services as $idx => $s): 
          $cat_slug = slugify($s['category'] ?? 'general');
        ?>
          <div data-cat="<?= e($cat_slug) ?>" class="svc-card group reveal relative flex flex-col justify-between overflow-hidden rounded-2xl border border-slate-200 bg-white p-8 transition duration-300 hover:-translate-y-1.5 hover:border-brand/40 hover:shadow-2xl hover:shadow-brand/10">
            <div>
              <div class="flex items-center justify-between">
                <div class="grid h-14 w-14 place-items-center rounded-2xl bg-gradient-to-br from-brand to-cyan text-white shadow-lg shadow-brand/30">
                  <span class="material-symbols-outlined !text-2xl"><?= e($s['icon'] ?: 'code') ?></span>
                </div>
                <span class="rounded-full bg-soft px-3 py-1 text-xs font-semibold text-brand"><?= e($s['category']) ?></span>
              </div>

              <h2 class="mt-6 font-display text-2xl font-bold text-ink group-hover:text-brand transition-colors">
                <a href="service-details.php?slug=<?= urlencode($s['slug']) ?>"><?= e($s['title']) ?></a>
              </h2>

              <p class="mt-3 text-sm leading-6 text-muted">
                <?= e($s['short_desc']) ?>
              </p>

              <?php if (!empty($s['features'])): 
                $feats = array_slice(explode(',', $s['features']), 0, 4);
              ?>
                <ul class="mt-6 space-y-2 border-t border-slate-100 pt-5 text-xs text-slate-600">
                  <?php foreach ($feats as $f): ?>
                    <li class="flex items-center gap-2">
                      <span class="material-symbols-outlined text-cyan text-sm">check</span>
                      <span><?= e(trim($f)) ?></span>
                    </li>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>
            </div>

            <div class="mt-8 flex items-center justify-between border-t border-slate-100 pt-5">
              <a href="service-details.php?slug=<?= urlencode($s['slug']) ?>" class="inline-flex items-center gap-1 text-sm font-semibold text-brand transition group-hover:gap-2">
                Details &amp; Scope <span class="material-symbols-outlined text-base">arrow_forward</span>
              </a>
              <a href="start-project.php?service=<?= urlencode($s['slug']) ?>" class="rounded-lg bg-soft px-3.5 py-1.5 text-xs font-semibold text-ink transition hover:bg-cta hover:text-white">
                Get Quote
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="col-span-3 text-center text-muted">No active services currently listed.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Banner CTA -->
<section class="bg-soft py-20">
  <div class="mx-auto max-w-[1280px] px-5 text-center lg:px-8">
    <div class="mx-auto max-w-2xl">
      <h2 class="font-display text-3xl font-bold tracking-tight text-ink md:text-4xl">Have a Custom Project in Mind?</h2>
      <p class="mt-4 text-base text-muted">Whether you need a dedicated team or a multi-disciplinary combination of design, code, and analytics, we are ready to assist.</p>
      <div class="mt-8 flex flex-wrap justify-center gap-4">
        <a href="start-project.php" class="btn-cta inline-flex items-center gap-2 rounded-xl px-8 py-3.5 font-semibold">
          Submit Project Brief <span class="material-symbols-outlined text-lg">arrow_forward</span>
        </a>
        <a href="contact.php" class="inline-flex items-center gap-2 rounded-xl bg-white px-8 py-3.5 font-semibold text-ink ring-1 ring-slate-200 transition hover:ring-brand">
          Schedule a Consultation
        </a>
      </div>
    </div>
  </div>
</section>

<script>
  // Service category filter
  const sfBtns = document.querySelectorAll('.sf-btn');
  const svcCards = document.querySelectorAll('.svc-card');
  sfBtns.forEach(btn => {
    btn.onclick = () => {
      sfBtns.forEach(b => {
        const on = (b === btn);
        b.className = 'sf-btn rounded-full px-5 py-2 text-sm font-semibold transition ' + (on ? 'bg-brand text-white shadow-md shadow-brand/30' : 'bg-white text-muted ring-1 ring-slate-200 hover:text-ink');
      });
      const cat = btn.dataset.cat;
      svcCards.forEach(card => {
        card.style.display = (cat === 'all' || card.dataset.cat === cat) ? '' : 'none';
      });
    };
  });
</script>

<?php include __DIR__ . '/include/footer.php'; ?>
