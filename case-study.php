<?php
/**
 * VPS Digital Services - Case Study Detail Page
 */
require_once __DIR__ . '/include/functions.php';

$slug = trim($_GET['slug'] ?? '');
$id   = (int)($_GET['id'] ?? 0);

if (empty($slug) && $id <= 0) {
    header("Location: portfolio.php");
    exit;
}

try {
    $db = get_db();
    if (!empty($slug)) {
        $stmt = $db->prepare("SELECT p.*, s.title AS service_title, s.slug AS service_slug FROM portfolio p LEFT JOIN services s ON p.service_id = s.id WHERE p.slug = ? AND p.is_published = 1 LIMIT 1");
        $stmt->execute([$slug]);
    } else {
        $stmt = $db->prepare("SELECT p.*, s.title AS service_title, s.slug AS service_slug FROM portfolio p LEFT JOIN services s ON p.service_id = s.id WHERE p.id = ? AND p.is_published = 1 LIMIT 1");
        $stmt->execute([$id]);
    }
    $project = $stmt->fetch();

    if (!$project) {
        http_response_code(404);
        include __DIR__ . '/404.php';
        exit;
    }

    // Related projects
    $stmt_related = $db->prepare("SELECT * FROM portfolio WHERE id != ? AND is_published = 1 ORDER BY (category = ?) DESC, id DESC LIMIT 3");
    $stmt_related->execute([$project['id'], $project['category']]);
    $related_projects = $stmt_related->fetchAll();

} catch (Exception $e) {
    error_log("Case study error: " . $e->getMessage());
    header("Location: portfolio.php");
    exit;
}

$page_title = (!empty($project['meta_title'])) ? $project['meta_title'] : $project['title'] . " | Case Study - VPS";
$page_desc  = (!empty($project['meta_desc'])) ? $project['meta_desc'] : $project['short_desc'];
$is_solid_header = false;

// Schema.org CreativeWork JSON-LD
$schema_json = json_encode([
    "@context" => "https://schema.org",
    "@type" => "CreativeWork",
    "name" => $project['title'],
    "headline" => $project['title'],
    "description" => $project['short_desc'],
    "provider" => [
        "@type" => "Organization",
        "name" => "VPS — V Provide Services",
        "url" => "https://vprovideservices.com"
    ]
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

include __DIR__ . '/include/header.php';
?>

<!-- Header Banner -->
<section class="hero relative overflow-hidden pb-20 pt-32 text-white lg:pb-24 lg:pt-36">
  <div class="grid-bg absolute inset-0"></div>
  <div class="relative mx-auto max-w-[1280px] px-5 lg:px-8">
    <nav class="mb-6 flex items-center gap-2 text-xs font-semibold text-slate-400">
      <a href="index.php" class="hover:text-cyan transition-colors">Home</a>
      <span class="material-symbols-outlined text-sm">chevron_right</span>
      <a href="portfolio.php" class="hover:text-cyan transition-colors">Portfolio</a>
      <span class="material-symbols-outlined text-sm">chevron_right</span>
      <span class="text-white"><?= e($project['title']) ?></span>
    </nav>

    <div class="max-w-3xl">
      <span class="glass inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold uppercase tracking-[.16em] text-cyan">
        <?= e(strtoupper($project['category'])) ?> Case Study
      </span>
      <h1 class="mt-4 font-display text-3xl font-extrabold tracking-tight sm:text-4xl lg:text-5xl">
        <?= e($project['title']) ?>
      </h1>
      <p class="mt-4 text-lg text-slate-300">
        <?= e($project['short_desc']) ?>
      </p>
    </div>
  </div>
</section>

<!-- Case Study Detail Content -->
<section class="bg-white py-16 lg:py-24">
  <div class="mx-auto max-w-[1280px] px-5 lg:px-8">
    <div class="grid gap-12 lg:grid-cols-12">
      
      <!-- Main Story -->
      <div class="lg:col-span-8 space-y-12">
        
        <!-- Main Image / Showcase -->
        <?php if (!empty($project['featured_image']) && file_exists(__DIR__ . '/' . $project['featured_image'])): ?>
          <div class="overflow-hidden rounded-3xl border border-slate-200 shadow-xl">
            <img src="<?= e($project['featured_image']) ?>" alt="<?= e($project['title']) ?>" class="w-full object-cover">
          </div>
        <?php else: ?>
          <div class="grid aspect-[16/9] place-items-center rounded-3xl bg-gradient-to-br from-navy to-brand text-white shadow-xl">
            <span class="material-symbols-outlined !text-9xl text-white/40">rocket_launch</span>
          </div>
        <?php endif; ?>

        <!-- Case Study Sections -->
        <div class="space-y-8">
          <?php if (!empty($project['challenge'])): ?>
            <div class="rounded-2xl border border-slate-200 bg-soft p-8">
              <span class="text-xs font-bold uppercase tracking-wider text-cta">The Challenge</span>
              <h2 class="mt-2 font-display text-2xl font-bold text-ink">What Problem Needed Solving?</h2>
              <p class="mt-3 leading-relaxed text-slate-700"><?= nl2br(e($project['challenge'])) ?></p>
            </div>
          <?php endif; ?>

          <?php if (!empty($project['solution'])): ?>
            <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
              <span class="text-xs font-bold uppercase tracking-wider text-brand">The Architecture &amp; Solution</span>
              <h2 class="mt-2 font-display text-2xl font-bold text-ink">How VPS Engineered the Outcome</h2>
              <p class="mt-3 leading-relaxed text-slate-700"><?= nl2br(e($project['solution'])) ?></p>
            </div>
          <?php endif; ?>

          <?php if (!empty($project['results'])): ?>
            <div class="rounded-2xl border border-cyan/30 bg-gradient-to-br from-slate-900 to-navy p-8 text-white shadow-xl">
              <span class="text-xs font-bold uppercase tracking-wider text-cyan">Impact &amp; Results</span>
              <h2 class="mt-2 font-display text-2xl font-bold">Measurable Business Outcomes</h2>
              <p class="mt-3 leading-relaxed text-slate-200"><?= nl2br(e($project['results'])) ?></p>
            </div>
          <?php endif; ?>

          <?php if (!empty($project['full_desc'])): ?>
            <div class="prose max-w-none text-slate-700 leading-relaxed">
              <?= $project['full_desc'] ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- Project CTA Box -->
        <div class="rounded-2xl bg-soft border border-slate-200 p-8 text-center sm:p-10">
          <h3 class="font-display text-2xl font-bold text-ink">Inspired by this project?</h3>
          <p class="mt-2 text-sm text-muted">We can build a comparable high-impact solution engineered for your requirements.</p>
          <div class="mt-6 flex flex-wrap justify-center gap-4">
            <a href="start-project.php" class="btn-cta inline-flex items-center gap-2 rounded-xl px-7 py-3.5 font-semibold">
              Start Your Project <span class="material-symbols-outlined text-lg">arrow_forward</span>
            </a>
          </div>
        </div>

      </div>

      <!-- Project Metadata Sidebar -->
      <aside class="space-y-8 lg:col-span-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm">
          <h3 class="font-display text-lg font-bold text-ink">Project Metadata</h3>
          <ul class="mt-5 space-y-4 text-sm divide-y divide-slate-100">
            <li class="flex justify-between pt-3">
              <span class="text-muted">Client / Brand</span>
              <span class="font-semibold text-ink"><?= e($project['client_name'] ?: 'Confidential Client') ?></span>
            </li>
            <li class="flex justify-between pt-3">
              <span class="text-muted">Industry</span>
              <span class="font-semibold text-ink"><?= e($project['industry'] ?: 'Digital Enterprise') ?></span>
            </li>
            <li class="flex justify-between pt-3 items-center">
              <span class="text-muted">Related Service</span>
              <?php if (!empty($project['service_slug'])): ?>
                <a href="service-details.php?slug=<?= e($project['service_slug']) ?>" class="font-bold text-brand hover:underline inline-flex items-center gap-1">
                  <span><?= e($project['service_title']) ?></span>
                  <span class="material-symbols-outlined text-xs">open_in_new</span>
                </a>
              <?php else: ?>
                <span class="font-bold text-brand uppercase"><?= e($project['category']) ?></span>
              <?php endif; ?>
            </li>
            <?php if (!empty($project['technologies'])): ?>
              <li class="pt-3">
                <span class="text-muted block mb-1">Technologies</span>
                <span class="font-semibold text-slate-800"><?= e($project['technologies']) ?></span>
              </li>
            <?php endif; ?>
            <?php if (!empty($project['project_url'])): ?>
              <li class="pt-3">
                <span class="text-muted block mb-1">Live URL</span>
                <a href="<?= e($project['project_url']) ?>" target="_blank" rel="noopener noreferrer" class="font-semibold text-brand underline truncate block"><?= e($project['project_url']) ?></a>
              </li>
            <?php endif; ?>
          </ul>
        </div>

        <!-- Related Case Studies -->
        <?php if (!empty($related_projects)): ?>
          <div class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm">
            <h3 class="font-display text-lg font-bold text-ink">Related Studies</h3>
            <div class="mt-4 space-y-4">
              <?php foreach ($related_projects as $rp): ?>
                <a href="case-study.php?slug=<?= urlencode($rp['slug']) ?>" class="group block rounded-xl border border-slate-100 p-4 transition hover:border-brand/30 hover:bg-soft">
                  <span class="text-[10px] font-bold uppercase text-brand"><?= e($rp['category']) ?></span>
                  <h4 class="font-display text-sm font-semibold text-ink group-hover:text-brand"><?= e($rp['title']) ?></h4>
                  <p class="mt-1 text-xs text-muted line-clamp-1"><?= e($rp['short_desc']) ?></p>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
      </aside>

    </div>
  </div>
</section>

<?php include __DIR__ . '/include/footer.php'; ?>
