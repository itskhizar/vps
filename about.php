<?php
/**
 * VPS Digital Services - About VPS Page
 */
require_once __DIR__ . '/include/functions.php';

$page_title = "About Us | VPS Digital Services - Global Technology & Creative Partner";
$page_desc  = "Learn about VPS (V Provide Services), registered in Pakistan and delivering world-class web, mobile, creative branding, ecommerce, and architectural design to clients across the globe.";
$is_solid_header = false;

try {
    $db = get_db();
    $stmt = $db->query("SELECT * FROM team WHERE is_active = 1 ORDER BY display_order ASC LIMIT 4");
    $team = $stmt->fetchAll();
} catch (Exception $e) {
    $team = [];
}

include __DIR__ . '/include/header.php';
?>

<!-- Header Banner -->
<section class="hero relative overflow-hidden pb-20 pt-32 text-white lg:pb-28 lg:pt-36">
  <div class="grid-bg absolute inset-0"></div>
  <div class="relative mx-auto max-w-[1280px] px-5 text-center lg:px-8">
    <span class="glass inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold uppercase tracking-[.16em] text-cyan">
      About V Provide Services
    </span>
    <h1 class="mt-6 font-display text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
      Engineering Digital Excellence Globally
    </h1>
    <p class="mx-auto mt-4 max-w-2xl text-lg text-slate-300">
      Registered in Pakistan and serving visionary clients worldwide, VPS combines elite software development, creative design, and architectural engineering under one roof.
    </p>
  </div>
</section>

<!-- Company Introduction & Mission Section -->
<section class="bg-white py-16 lg:py-24">
  <div class="mx-auto max-w-[1280px] px-5 lg:px-8">
    <div class="grid items-center gap-14 lg:grid-cols-12">
      <div class="lg:col-span-6">
        <span class="text-xs font-bold uppercase tracking-[.2em] text-brand">Our Philosophy</span>
        <h2 class="mt-3 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">
          One Trusted Partner for Every Stage of Your Digital Evolution
        </h2>
        <p class="mt-5 text-base leading-relaxed text-slate-600">
          At VPS, we believe companies waste immense resources and momentum attempting to coordinate fragmented agencies—one for web development, another for design, and yet another for data or technical consulting.
        </p>
        <p class="mt-4 text-base leading-relaxed text-slate-600">
          We formed VPS to eliminate that friction. By unifying senior developers, brand designers, data specialists, and architectural modelers into one cohesive team, we ensure your code, brand identity, and physical environments speak the exact same language of precision and quality.
        </p>

        <div class="mt-8 grid grid-cols-2 gap-6 border-t border-slate-100 pt-6">
          <div>
            <p class="font-display text-3xl font-extrabold text-brand">100%</p>
            <p class="mt-1 text-xs font-semibold text-muted uppercase tracking-wider">Client IP Ownership</p>
          </div>
          <div>
            <p class="font-display text-3xl font-extrabold text-cyan">24 Hours</p>
            <p class="mt-1 text-xs font-semibold text-muted uppercase tracking-wider">Fast Turnaround Quotes</p>
          </div>
        </div>
      </div>

      <!-- Right Feature Card -->
      <div class="lg:col-span-6">
        <div class="relative rounded-3xl bg-navy p-8 text-white shadow-2xl lg:p-12">
          <div class="h-2 w-16 bg-gradient-to-r from-brand to-cyan rounded-full mb-6"></div>
          <h3 class="font-display text-2xl font-bold">Our Core Commitments</h3>
          <ul class="mt-6 space-y-4 text-sm text-slate-300">
            <li class="flex items-start gap-3">
              <span class="material-symbols-outlined text-cyan shrink-0">check_circle</span>
              <span><strong>Transparent Communication:</strong> Regular milestone reviews, direct access to leads, and no technical jargon barriers.</span>
            </li>
            <li class="flex items-start gap-3">
              <span class="material-symbols-outlined text-cyan shrink-0">check_circle</span>
              <span><strong>Production-Grade Engineering:</strong> Security audits, prepared statements, optimized queries, and clean maintainable code.</span>
            </li>
            <li class="flex items-start gap-3">
              <span class="material-symbols-outlined text-cyan shrink-0">check_circle</span>
              <span><strong>Total Asset Transfer:</strong> All source code, Figma design files, and documentation belong completely to the client.</span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Mission, Vision & Values -->
<section class="bg-soft py-16 lg:py-24">
  <div class="mx-auto max-w-[1280px] px-5 lg:px-8">
    <div class="grid gap-8 md:grid-cols-3">
      <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-100">
        <div class="grid h-12 w-12 place-items-center rounded-xl bg-brand/10 text-brand">
          <span class="material-symbols-outlined">flag</span>
        </div>
        <h3 class="mt-5 font-display text-xl font-bold text-ink">Our Mission</h3>
        <p class="mt-3 text-sm leading-6 text-muted">
          To empower businesses globally with dependable software, distinctive design systems, and data-driven insight that scale their revenue and operations.
        </p>
      </div>

      <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-100">
        <div class="grid h-12 w-12 place-items-center rounded-xl bg-brand/10 text-brand">
          <span class="material-symbols-outlined">visibility</span>
        </div>
        <h3 class="mt-5 font-display text-xl font-bold text-ink">Our Vision</h3>
        <p class="mt-3 text-sm leading-6 text-muted">
          To become the premier full-spectrum digital partner recognized internationally for integrity, technical excellence, and rapid project delivery.
        </p>
      </div>

      <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-100">
        <div class="grid h-12 w-12 place-items-center rounded-xl bg-brand/10 text-brand">
          <span class="material-symbols-outlined">verified</span>
        </div>
        <h3 class="mt-5 font-display text-xl font-bold text-ink">Our Values</h3>
        <p class="mt-3 text-sm leading-6 text-muted">
          Integrity in billing, craft in execution, strict intellectual property respect, and long-term client accountability past deployment.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Team Preview on About -->
<?php if (!empty($team)): ?>
<section class="bg-white py-16 lg:py-24">
  <div class="mx-auto max-w-[1280px] px-5 lg:px-8">
    <div class="mb-12 flex flex-col justify-between gap-4 md:flex-row md:items-end">
      <div>
        <span class="text-xs font-bold uppercase tracking-[.2em] text-brand">Leadership</span>
        <h2 class="mt-3 font-display text-3xl font-bold tracking-tight text-ink md:text-4xl">Meet the VPS Core Team</h2>
      </div>
      <a href="team.php" class="inline-flex items-center gap-1 font-semibold text-brand">Full Team Directory <span class="material-symbols-outlined text-base">arrow_forward</span></a>
    </div>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
      <?php foreach ($team as $m): ?>
        <a href="team-member.php?slug=<?= urlencode($m['slug']) ?>" class="group overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-1 hover:shadow-xl">
          <div class="grid aspect-square place-items-center bg-gradient-to-br from-navy to-brand font-display text-6xl font-extrabold text-white/90">
            <?= e($m['initials'] ?: substr($m['name'], 0, 2)) ?>
          </div>
          <div class="p-6">
            <h3 class="font-display text-lg font-semibold text-ink"><?= e($m['name']) ?></h3>
            <p class="text-sm font-medium text-brand"><?= e($m['title']) ?></p>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- CTA Banner -->
<section class="hero relative overflow-hidden py-24 text-center text-white">
  <div class="grid-bg absolute inset-0"></div>
  <div class="relative mx-auto max-w-3xl px-5">
    <h2 class="font-display text-3xl font-extrabold tracking-tight md:text-5xl">Partner with VPS on Your Next Project</h2>
    <p class="mx-auto mt-4 max-w-xl text-lg text-slate-300">Let us discuss your goals and show you how our multi-disciplinary approach saves time and improves quality.</p>
    <div class="mt-8 flex flex-wrap justify-center gap-4">
      <a href="start-project.php" class="btn-cta inline-flex items-center gap-2 rounded-xl px-8 py-4 font-semibold">
        Start Your Project <span class="material-symbols-outlined text-lg">arrow_forward</span>
      </a>
      <a href="contact.php" class="glass inline-flex items-center gap-2 rounded-xl px-8 py-4 font-semibold transition hover:bg-white/15">
        Contact Our Office
      </a>
    </div>
  </div>
</section>

<?php include __DIR__ . '/include/footer.php'; ?>
