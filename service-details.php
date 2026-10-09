<?php
/**
 * VPS Digital Services - Service Detail Page
 */
require_once __DIR__ . '/include/functions.php';

$slug = trim($_GET['slug'] ?? '');
$id   = (int)($_GET['id'] ?? 0);

if (empty($slug) && $id <= 0) {
    header("Location: services.php");
    exit;
}

try {
    $db = get_db();
    if (!empty($slug)) {
        $stmt = $db->prepare("SELECT * FROM services WHERE slug = ? AND is_active = 1 LIMIT 1");
        $stmt->execute([$slug]);
    } else {
        $stmt = $db->prepare("SELECT * FROM services WHERE id = ? AND is_active = 1 LIMIT 1");
        $stmt->execute([$id]);
    }
    $service = $stmt->fetch();

    if (!$service) {
        http_response_code(404);
        include __DIR__ . '/404.php';
        exit;
    }

    // Related services
    $stmt_related = $db->prepare("SELECT * FROM services WHERE id != ? AND is_active = 1 ORDER BY (category = ?) DESC, display_order ASC LIMIT 3");
    $stmt_related->execute([$service['id'], $service['category']]);
    $related_services = $stmt_related->fetchAll();

} catch (Exception $e) {
    error_log("Service detail error: " . $e->getMessage());
    header("Location: services.php");
    exit;
}

$page_title = (!empty($service['meta_title'])) ? $service['meta_title'] : $service['title'] . " | VPS Digital Services";
$page_desc  = (!empty($service['meta_desc'])) ? $service['meta_desc'] : $service['short_desc'];
$is_solid_header = false;

include __DIR__ . '/include/header.php';
?>

<!-- Service Header Banner -->
<section class="hero relative overflow-hidden pb-20 pt-32 text-white lg:pb-24 lg:pt-36">
  <div class="grid-bg absolute inset-0"></div>
  <div class="relative mx-auto max-w-[1280px] px-5 lg:px-8">
    
    <!-- Breadcrumb -->
    <nav class="mb-6 flex items-center gap-2 text-xs font-semibold text-slate-400">
      <a href="index.php" class="hover:text-cyan transition-colors">Home</a>
      <span class="material-symbols-outlined text-sm">chevron_right</span>
      <a href="services.php" class="hover:text-cyan transition-colors">Services</a>
      <span class="material-symbols-outlined text-sm">chevron_right</span>
      <span class="text-white"><?= e($service['title']) ?></span>
    </nav>

    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
      <div>
        <span class="glass inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold uppercase tracking-[.16em] text-cyan">
          <?= e($service['category']) ?>
        </span>
        <h1 class="mt-4 font-display text-3xl font-extrabold tracking-tight sm:text-4xl lg:text-5xl">
          <?= e($service['title']) ?>
        </h1>
        <p class="mt-4 max-w-2xl text-lg text-slate-300">
          <?= e($service['short_desc']) ?>
        </p>
      </div>

      <div class="shrink-0">
        <a href="start-project.php?service=<?= urlencode($service['slug']) ?>" class="btn-cta inline-flex items-center gap-2 rounded-xl px-8 py-4 font-semibold text-white shadow-xl">
          Start a Project for <?= e($service['title']) ?> <span class="material-symbols-outlined text-lg">arrow_forward</span>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Content Body & Sidebar -->
<section class="bg-white py-16 lg:py-24">
  <div class="mx-auto max-w-[1280px] px-5 lg:px-8">
    <div class="grid gap-12 lg:grid-cols-12">
      
      <!-- Main Content -->
      <div class="lg:col-span-8">
        <div class="prose max-w-none text-slate-700 leading-relaxed text-base">
          <?php if (!empty($service['full_desc'])): ?>
            <?= $service['full_desc'] // Render managed description ?>
          <?php else: ?>
            <p><?= nl2br(e($service['short_desc'])) ?></p>
          <?php endif; ?>
        </div>

        <!-- Features Checklist -->
        <?php if (!empty($service['features'])): 
          $features = array_filter(array_map('trim', explode(',', $service['features'])));
        ?>
          <div class="mt-12 rounded-2xl border border-slate-200 bg-soft p-8">
            <h3 class="font-display text-xl font-bold text-ink">Key Capabilities &amp; Inclusions</h3>
            <div class="mt-6 grid gap-4 sm:grid-cols-2">
              <?php foreach ($features as $f): ?>
                <div class="flex items-start gap-3">
                  <span class="grid h-6 w-6 shrink-0 place-items-center rounded-full bg-brand/10 text-brand">
                    <span class="material-symbols-outlined text-sm font-bold">check</span>
                  </span>
                  <span class="text-sm font-medium text-slate-800"><?= e($f) ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>

        <!-- Dedicated Project Launch Callout -->
        <div class="mt-12 rounded-2xl bg-navy p-8 text-white sm:p-10">
          <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
              <span class="text-xs font-bold uppercase tracking-[.2em] text-cyan">Tailored Proposal</span>
              <h3 class="mt-2 font-display text-2xl font-bold">Ready to get started with <?= e($service['title']) ?>?</h3>
              <p class="mt-2 text-sm text-slate-300">Share your specifications and receive a free quote within 24 hours.</p>
            </div>
            <a href="start-project.php?service=<?= urlencode($service['slug']) ?>" class="btn-cta shrink-0 rounded-xl px-7 py-3.5 text-center font-semibold">
              Get Started Now
            </a>
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <aside class="space-y-8 lg:col-span-4">
        <!-- Service Quick Summary Card -->
        <div class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm">
          <h3 class="font-display text-lg font-bold text-ink">Service Overview</h3>
          <ul class="mt-5 space-y-4 text-sm divide-y divide-slate-100">
            <li class="flex justify-between pt-3">
              <span class="text-muted">Discipline</span>
              <span class="font-semibold text-ink"><?= e($service['category']) ?></span>
            </li>
            <li class="flex justify-between pt-3">
              <span class="text-muted">Turnaround Quote</span>
              <span class="font-semibold text-cyan">Within 24 Hours</span>
            </li>
            <li class="flex justify-between pt-3">
              <span class="text-muted">Deliverables</span>
              <span class="font-semibold text-ink">Full IP &amp; Source Files</span>
            </li>
            <li class="flex justify-between pt-3">
              <span class="text-muted">Engagement</span>
              <span class="font-semibold text-ink">Fixed or Retainer</span>
            </li>
          </ul>

          <div class="mt-6 pt-2">
            <a href="start-project.php?service=<?= urlencode($service['slug']) ?>" class="btn-cta block w-full rounded-xl py-3 text-center text-sm font-semibold">
              Inquire About This Service
            </a>
          </div>
        </div>

        <!-- Direct Contact Box -->
        <div class="rounded-2xl border border-slate-200 bg-soft p-7">
          <h3 class="font-display text-lg font-bold text-ink">Prefer Direct Chat?</h3>
          <p class="mt-2 text-sm text-muted">Connect directly with our solutions lead on WhatsApp for instant inquiries.</p>
          <a href="https://wa.me/<?= e($clean_whatsapp) ?>?text=<?= urlencode("Hello VPS, I am inquiring about " . $service['title']) ?>" target="_blank" rel="noopener" class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-green-500 py-3 text-sm font-semibold text-white shadow-lg transition hover:bg-green-600">
            <span class="material-symbols-outlined text-lg">chat</span> Chat on WhatsApp
          </a>
        </div>

        <!-- Related Services -->
        <?php if (!empty($related_services)): ?>
          <div class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm">
            <h3 class="font-display text-lg font-bold text-ink">Related Services</h3>
            <div class="mt-4 space-y-4">
              <?php foreach ($related_services as $rs): ?>
                <a href="service-details.php?slug=<?= urlencode($rs['slug']) ?>" class="group block rounded-xl border border-slate-100 p-4 transition hover:border-brand/30 hover:bg-soft">
                  <h4 class="font-display text-sm font-semibold text-ink group-hover:text-brand"><?= e($rs['title']) ?></h4>
                  <p class="mt-1 text-xs text-muted line-clamp-1"><?= e($rs['short_desc']) ?></p>
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
