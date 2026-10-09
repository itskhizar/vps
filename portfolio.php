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
    $stmt = $db->query("SELECT p.*, s.title AS service_title, s.slug AS service_slug FROM portfolio p LEFT JOIN services s ON p.service_id = s.id WHERE p.is_published = 1 ORDER BY p.is_featured DESC, p.id DESC");
    $projects = $stmt->fetchAll();

    // Dynamically retrieve active services linked to published portfolio items
    $filter_services = $db->query("SELECT DISTINCT s.id, s.title, s.slug FROM services s JOIN portfolio p ON p.service_id = s.id WHERE p.is_published = 1 ORDER BY s.display_order ASC")->fetchAll();
} catch (Exception $e) {
    error_log("Portfolio query error: " . $e->getMessage());
    $projects = [];
    $filter_services = [];
}

// Structured Data for SEO
$schema_json = json_encode([
    "@context" => "https://schema.org",
    "@type" => "CollectionPage",
    "name" => "VPS Portfolio & Verified Client Case Studies",
    "description" => $page_desc,
    "url" => $canonical_url ?? "https://vprovideservices.com/portfolio.php"
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

include __DIR__ . '/include/header.php';
?>

<!-- Header Banner -->
<section class="hero relative overflow-hidden pb-20 pt-32 text-white lg:pb-28 lg:pt-36">
  <div class="grid-bg absolute inset-0"></div>
  <div class="relative mx-auto max-w-[1280px] px-5 text-center lg:px-8">
    <span class="glass inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold uppercase tracking-[.16em] text-cyan">
      <span class="h-1.5 w-1.5 rounded-full bg-cyan animate-pulse"></span>
      Verified Case Studies &amp; Client Work
    </span>
    <h1 class="mt-6 font-display text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
      Engineered for Performance &amp; Tangible Growth
    </h1>
    <p class="mx-auto mt-4 max-w-2xl text-lg text-slate-300">
      Every case study represents our commitment to clean architecture, user-centered craft, and measurable business outcomes across global markets.
    </p>
  </div>
</section>

<!-- Portfolio Section -->
<section class="bg-white py-16 lg:py-24">
  <div class="mx-auto max-w-[1280px] px-5 lg:px-8">
    
    <!-- Dynamic Category Filter Controls Linked to Services -->
    <div class="mb-12 flex flex-wrap justify-center gap-2" role="group" aria-label="Filter case studies by service category">
      <button data-f="all" aria-pressed="true" class="pf-btn rounded-full px-5 py-2 text-sm font-semibold transition bg-brand text-white shadow-md shadow-brand/30">
        All Projects
      </button>
      <?php if (!empty($filter_services)): ?>
        <?php foreach ($filter_services as $fs): ?>
          <button data-f="<?= e($fs['slug']) ?>" aria-pressed="false" class="pf-btn rounded-full px-5 py-2 text-sm font-semibold transition bg-white text-muted ring-1 ring-slate-200 hover:text-ink">
            <?= e($fs['title']) ?>
          </button>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- Portfolio Grid with Pixel-Perfect Cards -->
    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3 items-stretch">
      <?php if (!empty($projects)): ?>
        <?php foreach ($projects as $p): 
          $cat_slug = !empty($p['service_slug']) ? $p['service_slug'] : slugify($p['category']);
          $cat_name = !empty($p['service_title']) ? $p['service_title'] : ucfirst($p['category']);
        ?>
          <article data-cat="<?= e($cat_slug) ?>" class="port-card group reveal overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/90 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:ring-brand/30 flex flex-col justify-between h-full">
            <div>
              <!-- Image Container with Hover Animation -->
              <div class="relative aspect-[16/10] overflow-hidden bg-gradient-to-br from-navy via-brand to-cyan">
                <?php if (!empty($p['featured_image']) && file_exists(__DIR__ . '/' . $p['featured_image'])): ?>
                  <div class="tall-img-wrap h-full w-full">
                    <img src="<?= e($p['featured_image']) ?>" alt="VPS Case Study - <?= e($p['title']) ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                  </div>
                <?php else: ?>
                  <div class="grid h-full w-full place-items-center text-white/90 transition duration-500 group-hover:scale-105">
                    <span class="material-symbols-outlined !text-7xl">devices</span>
                  </div>
                <?php endif; ?>

                <!-- Overlay with details -->
                <div class="absolute inset-0 flex flex-col items-center justify-center gap-2 bg-navy/90 p-6 text-center text-white opacity-0 transition duration-300 group-hover:opacity-100 backdrop-blur-sm">
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
                  <span class="font-bold uppercase tracking-wider text-brand bg-brand/5 px-2.5 py-1 rounded-md border border-brand/10">
                    <?= e($cat_name) ?>
                  </span>
                  <?php if (!empty($p['industry'])): ?>
                    <span class="text-muted font-medium"><?= e($p['industry']) ?></span>
                  <?php endif; ?>
                </div>

                <h3 class="mt-3 font-display text-xl font-bold text-ink transition-colors group-hover:text-brand line-clamp-1">
                  <a href="case-study.php?slug=<?= urlencode($p['slug']) ?>"><?= e($p['title']) ?></a>
                </h3>

                <p class="mt-2 text-sm leading-relaxed text-muted line-clamp-2">
                  <?= e($p['short_desc']) ?>
                </p>
              </div>
            </div>

            <div class="border-t border-slate-100 px-6 py-4 flex items-center justify-between mt-auto">
              <span class="text-xs font-medium text-slate-400"><?= e($p['client_name'] ?: 'Verified Global Client') ?></span>
              <a href="case-study.php?slug=<?= urlencode($p['slug']) ?>" class="inline-flex items-center gap-1.5 text-xs font-bold text-brand group-hover:gap-2 transition-all">
                <span>View Full Study</span>
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
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
