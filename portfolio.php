<?php
/**
 * VPS Digital Services - Portfolio / Case Studies Overview
 */
require_once __DIR__ . '/include/functions.php';

$page_title = "Our Portfolio & Case Studies | VPS Digital Services";
$page_desc  = "Discover verified case studies delivered by VPS across custom web development, mobile applications, brand identities, ecommerce systems, and 3D architectural visualization.";
$is_solid_header = false;

try {
    $db = get_db();
    $stmt = $db->query("SELECT * FROM portfolio WHERE is_published = 1 ORDER BY is_featured DESC, id DESC");
    $projects = $stmt->fetchAll();
} catch (Exception $e) {
    error_log("Portfolio query error: " . $e->getMessage());
    $projects = [];
}

include __DIR__ . '/include/header.php';
?>

<!-- Header Banner -->
<section class="hero relative overflow-hidden pb-20 pt-32 text-white lg:pb-28 lg:pt-36">
  <div class="grid-bg absolute inset-0"></div>
  <div class="relative mx-auto max-w-[1280px] px-5 text-center lg:px-8">
    <span class="glass inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold uppercase tracking-[.16em] text-cyan">
      Delivered Case Studies
    </span>
    <h1 class="mt-6 font-display text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
      Engineered for Performance &amp; Results
    </h1>
    <p class="mx-auto mt-4 max-w-2xl text-lg text-slate-300">
      Every project represents a commitment to clean code, user-centered craft, and measurable business outcomes for our global clientele.
    </p>
  </div>
</section>

<!-- Portfolio Section -->
<section class="bg-white py-16 lg:py-24">
  <div class="mx-auto max-w-[1280px] px-5 lg:px-8">
    
    <!-- Filter Buttons -->
    <div class="mb-12 flex flex-wrap justify-center gap-2" role="group" aria-label="Filter case studies">
      <button data-f="all" aria-pressed="true" class="pf-btn rounded-full px-5 py-2 text-sm font-semibold transition bg-brand text-white shadow-md shadow-brand/30">All Projects</button>
      <button data-f="web" aria-pressed="false" class="pf-btn rounded-full px-5 py-2 text-sm font-semibold transition bg-white text-muted ring-1 ring-slate-200 hover:text-ink">Web Development</button>
      <button data-f="app" aria-pressed="false" class="pf-btn rounded-full px-5 py-2 text-sm font-semibold transition bg-white text-muted ring-1 ring-slate-200 hover:text-ink">Mobile Apps</button>
      <button data-f="brand" aria-pressed="false" class="pf-btn rounded-full px-5 py-2 text-sm font-semibold transition bg-white text-muted ring-1 ring-slate-200 hover:text-ink">Branding &amp; Identity</button>
      <button data-f="shop" aria-pressed="false" class="pf-btn rounded-full px-5 py-2 text-sm font-semibold transition bg-white text-muted ring-1 ring-slate-200 hover:text-ink">Ecommerce</button>
      <button data-f="data" aria-pressed="false" class="pf-btn rounded-full px-5 py-2 text-sm font-semibold transition bg-white text-muted ring-1 ring-slate-200 hover:text-ink">Data Analytics</button>
      <button data-f="arch" aria-pressed="false" class="pf-btn rounded-full px-5 py-2 text-sm font-semibold transition bg-white text-muted ring-1 ring-slate-200 hover:text-ink">Architecture &amp; 3D</button>
    </div>

    <!-- Portfolio Grid -->
    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
      <?php if (!empty($projects)): ?>
        <?php foreach ($projects as $p): 
          $cat = strtolower($p['category']);
          $cat_labels = [
            'web' => 'Web Development',
            'app' => 'Mobile App',
            'brand' => 'Branding & Identity',
            'shop' => 'Ecommerce System',
            'data' => 'Data Science & BI',
            'arch' => 'Architecture & 3D'
          ];
          $cat_display = $cat_labels[$cat] ?? ucfirst($cat);
        ?>
          <article data-cat="<?= e($cat) ?>" class="port-card group reveal overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 transition duration-300 hover:-translate-y-1 hover:shadow-2xl flex flex-col justify-between">
            <div>
              <!-- Image Container with Hover Animation -->
              <div class="relative aspect-[16/10] overflow-hidden bg-gradient-to-br from-navy via-brand to-cyan">
                <?php if (!empty($p['featured_image']) && file_exists(__DIR__ . '/' . $p['featured_image'])): ?>
                  <div class="tall-img-wrap h-full w-full">
                    <img src="<?= e($p['featured_image']) ?>" alt="<?= e($p['title']) ?>" class="w-full object-cover">
                  </div>
                <?php else: ?>
                  <div class="grid h-full w-full place-items-center text-white/90 transition duration-500 group-hover:scale-105">
                    <span class="material-symbols-outlined !text-7xl">
                      <?= $cat === 'web' ? 'language' : ($cat === 'app' ? 'phone_iphone' : ($cat === 'brand' ? 'draw' : ($cat === 'shop' ? 'shopping_bag' : ($cat === 'data' ? 'hub' : 'architecture')))) ?>
                    </span>
                  </div>
                <?php endif; ?>

                <!-- Overlay with details -->
                <div class="absolute inset-0 flex flex-col items-center justify-center gap-2 bg-navy/90 p-6 text-center text-white opacity-0 transition duration-300 group-hover:opacity-100">
                  <span class="text-xs font-semibold uppercase tracking-wider text-cyan"><?= e($p['technologies']) ?></span>
                  <p class="text-sm text-slate-300 line-clamp-3"><?= e($p['short_desc']) ?></p>
                  <a href="case-study.php?slug=<?= urlencode($p['slug']) ?>" class="mt-3 rounded-lg bg-white px-5 py-2 text-sm font-semibold text-brand transition hover:bg-cyan hover:text-navy">
                    Explore Case Study
                  </a>
                </div>
              </div>

              <!-- Card Content -->
              <div class="p-6">
                <div class="flex items-center justify-between text-xs">
                  <span class="font-semibold uppercase tracking-wide text-brand"><?= e($cat_display) ?></span>
                  <?php if (!empty($p['industry'])): ?>
                    <span class="text-muted"><?= e($p['industry']) ?></span>
                  <?php endif; ?>
                </div>

                <h3 class="mt-2 font-display text-xl font-bold text-ink transition-colors group-hover:text-brand">
                  <a href="case-study.php?slug=<?= urlencode($p['slug']) ?>"><?= e($p['title']) ?></a>
                </h3>

                <p class="mt-2 text-sm leading-6 text-muted line-clamp-2">
                  <?= e($p['short_desc']) ?>
                </p>
              </div>
            </div>

            <div class="border-t border-slate-100 px-6 py-4 flex items-center justify-between">
              <span class="text-xs font-medium text-slate-400"><?= e($p['client_name'] ?: 'International Client') ?></span>
              <a href="case-study.php?slug=<?= urlencode($p['slug']) ?>" class="inline-flex items-center gap-1 text-xs font-bold text-brand group-hover:gap-2 transition-all">
                Read Study <span class="material-symbols-outlined text-sm">arrow_forward</span>
              </a>
            </div>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="col-span-3 text-center text-muted">No published projects found.</p>
      <?php endif; ?>
    </div>

    <!-- Callout Banner -->
    <div class="mt-20 rounded-3xl bg-soft p-10 text-center border border-slate-200">
      <h3 class="font-display text-2xl font-bold text-ink">Have a Project Requiring a Proven Partner?</h3>
      <p class="mt-2 text-muted max-w-xl mx-auto text-sm">We take full ownership of your deliverables and adhere to enterprise standards.</p>
      <div class="mt-6">
        <a href="start-project.php" class="btn-cta inline-flex items-center gap-2 rounded-xl px-8 py-3.5 font-semibold">
          Initiate Your Project <span class="material-symbols-outlined text-lg">arrow_forward</span>
        </a>
      </div>
    </div>
  </div>
</section>

<script>
  // Filter portfolio cards
  const pfBtns = document.querySelectorAll('.pf-btn');
  const portCards = document.querySelectorAll('.port-card');
  pfBtns.forEach(btn => {
    btn.onclick = () => {
      pfBtns.forEach(b => {
        const on = (b === btn);
        b.setAttribute('aria-pressed', on);
        b.className = 'pf-btn rounded-full px-5 py-2 text-sm font-semibold transition ' + (on ? 'bg-brand text-white shadow-md shadow-brand/30' : 'bg-white text-muted ring-1 ring-slate-200 hover:text-ink');
      });
      const f = btn.dataset.f;
      portCards.forEach(card => {
        card.style.display = (f === 'all' || card.dataset.cat === f) ? '' : 'none';
      });
    };
  });
</script>

<?php include __DIR__ . '/include/footer.php'; ?>
