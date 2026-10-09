<?php
/**
 * VPS Digital Services - Team Overview
 */
require_once __DIR__ . '/include/functions.php';

$page_title = "Our Leadership & Core Engineering Team | VPS";
$page_desc  = "Meet the software architects, creative directors, data scientists, and architectural engineers who power VPS client engagements worldwide.";
$is_solid_header = false;

try {
    $db = get_db();
    $stmt = $db->query("SELECT * FROM team WHERE is_active = 1 ORDER BY display_order ASC");
    $team = $stmt->fetchAll();
} catch (Exception $e) {
    error_log("Team query error: " . $e->getMessage());
    $team = [];
}

include __DIR__ . '/include/header.php';
?>

<!-- Header Banner -->
<section class="hero relative overflow-hidden pb-20 pt-32 text-white lg:pb-28 lg:pt-36">
  <div class="grid-bg absolute inset-0"></div>
  <div class="relative mx-auto max-w-[1280px] px-5 text-center lg:px-8">
    <span class="glass inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold uppercase tracking-[.16em] text-cyan">
      Specialist Talent
    </span>
    <h1 class="mt-6 font-display text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
      The Engineers &amp; Designers Behind VPS
    </h1>
    <p class="mx-auto mt-4 max-w-2xl text-lg text-slate-300">
      Our team brings cross-disciplinary depth across web engineering, mobile development, brand craft, data intelligence, and architectural 3D visualization.
    </p>
  </div>
</section>

<!-- Team Grid Section -->
<section class="bg-white py-16 lg:py-24">
  <div class="mx-auto max-w-[1280px] px-5 lg:px-8">
    <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
      <?php if (!empty($team)): ?>
        <?php foreach ($team as $m): ?>
          <div class="group reveal overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 transition duration-300 hover:-translate-y-1 hover:shadow-2xl flex flex-col justify-between">
            <div>
              <a href="team-member.php?slug=<?= urlencode($m['slug']) ?>" class="block">
                <?php if (!empty($m['image']) && file_exists(__DIR__ . '/' . $m['image'])): ?>
                  <div class="aspect-square overflow-hidden bg-slate-100">
                    <img src="<?= e($m['image']) ?>" alt="<?= e($m['name']) ?>" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                  </div>
                <?php else: ?>
                  <div class="grid aspect-square place-items-center bg-gradient-to-br from-navy to-brand font-display text-6xl font-extrabold text-white/90">
                    <?= e($m['initials'] ?: substr($m['name'], 0, 2)) ?>
                  </div>
                <?php endif; ?>
              </a>

              <div class="p-6">
                <span class="text-xs font-semibold text-brand"><?= e($m['title']) ?></span>
                <h3 class="mt-1 font-display text-xl font-bold text-ink transition-colors group-hover:text-brand">
                  <a href="team-member.php?slug=<?= urlencode($m['slug']) ?>"><?= e($m['name']) ?></a>
                </h3>
                <p class="mt-2 text-xs leading-5 text-muted line-clamp-3">
                  <?= e($m['short_intro']) ?>
                </p>

                <?php if (!empty($m['skills'])): 
                  $skills = array_slice(explode(',', $m['skills']), 0, 3);
                ?>
                  <div class="mt-4 flex flex-wrap gap-1.5">
                    <?php foreach ($skills as $sk): ?>
                      <span class="rounded bg-soft px-2 py-0.5 text-[10px] font-semibold text-slate-600"><?= e(trim($sk)) ?></span>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
              </div>
            </div>

            <div class="border-t border-slate-100 px-6 py-4 flex items-center justify-between">
              <a href="team-member.php?slug=<?= urlencode($m['slug']) ?>" class="text-xs font-bold text-brand inline-flex items-center gap-1 group-hover:gap-2 transition-all">
                Full Profile <span class="material-symbols-outlined text-sm">arrow_forward</span>
              </a>
              <?php if (!empty($m['social_linkedin'])): ?>
                <a href="<?= e($m['social_linkedin']) ?>" target="_blank" rel="noopener" class="text-slate-400 hover:text-brand text-xs font-bold">LinkedIn</a>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="col-span-4 text-center text-muted">No team members currently published.</p>
      <?php endif; ?>
    </div>

    <!-- Join the Team CTA -->
    <div class="mt-20 rounded-3xl bg-soft border border-slate-200 p-10 text-center">
      <h3 class="font-display text-2xl font-bold text-ink">Work With Dedicated Specialists</h3>
      <p class="mt-2 text-sm text-muted max-w-xl mx-auto">Get a team of experts committed to delivering your software, design, or architectural vision.</p>
      <div class="mt-6">
        <a href="start-project.php" class="btn-cta inline-flex items-center gap-2 rounded-xl px-8 py-3.5 font-semibold">
          Discuss Your Project <span class="material-symbols-outlined text-lg">arrow_forward</span>
        </a>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/include/footer.php'; ?>
