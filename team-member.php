<?php
/**
 * VPS Digital Services - Team Member Profile Page
 */
require_once __DIR__ . '/include/functions.php';

$slug = trim($_GET['slug'] ?? '');
$id   = (int)($_GET['id'] ?? 0);

if (empty($slug) && $id <= 0) {
    header("Location: team.php");
    exit;
}

try {
    $db = get_db();
    if (!empty($slug)) {
        $stmt = $db->prepare("SELECT * FROM team WHERE slug = ? AND is_active = 1 LIMIT 1");
        $stmt->execute([$slug]);
    } else {
        $stmt = $db->prepare("SELECT * FROM team WHERE id = ? AND is_active = 1 LIMIT 1");
        $stmt->execute([$id]);
    }
    $member = $stmt->fetch();

    if (!$member) {
        http_response_code(404);
        include __DIR__ . '/404.php';
        exit;
    }

    // Other team members
    $stmt_others = $db->prepare("SELECT * FROM team WHERE id != ? AND is_active = 1 ORDER BY display_order ASC LIMIT 3");
    $stmt_others->execute([$member['id']]);
    $other_members = $stmt_others->fetchAll();

} catch (Exception $e) {
    error_log("Team member error: " . $e->getMessage());
    header("Location: team.php");
    exit;
}

$page_title = $member['name'] . " - " . $member['title'] . " | VPS Team";
$page_desc  = (!empty($member['short_intro'])) ? $member['short_intro'] : "Meet " . $member['name'] . ", " . $member['title'] . " at VPS.";
$is_solid_header = false;

include __DIR__ . '/include/header.php';
?>

<!-- Header Banner -->
<section class="hero relative overflow-hidden pb-20 pt-32 text-white lg:pb-24 lg:pt-36">
  <div class="grid-bg absolute inset-0"></div>
  <div class="relative mx-auto max-w-[1280px] px-5 lg:px-8">
    <nav class="mb-6 flex items-center gap-2 text-xs font-semibold text-slate-400">
      <a href="index.php" class="hover:text-cyan transition-colors">Home</a>
      <span class="material-symbols-outlined text-sm">chevron_right</span>
      <a href="team.php" class="hover:text-cyan transition-colors">Team</a>
      <span class="material-symbols-outlined text-sm">chevron_right</span>
      <span class="text-white"><?= e($member['name']) ?></span>
    </nav>

    <div class="flex flex-col gap-6 md:flex-row md:items-center">
      <div>
        <span class="glass inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold uppercase tracking-[.16em] text-cyan">
          VPS Specialist Profile
        </span>
        <h1 class="mt-4 font-display text-3xl font-extrabold tracking-tight sm:text-4xl lg:text-5xl">
          <?= e($member['name']) ?>
        </h1>
        <p class="mt-2 text-xl font-medium text-cyan">
          <?= e($member['title']) ?>
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Profile Body -->
<section class="bg-white py-16 lg:py-24">
  <div class="mx-auto max-w-[1280px] px-5 lg:px-8">
    <div class="grid gap-12 lg:grid-cols-12">
      
      <!-- Left: Photo & Quick Meta -->
      <aside class="space-y-8 lg:col-span-4">
        <div class="overflow-hidden rounded-3xl border border-slate-200 shadow-xl bg-white p-4">
          <?php if (!empty($member['image']) && file_exists(__DIR__ . '/' . $member['image'])): ?>
            <div class="aspect-square overflow-hidden rounded-2xl">
              <img src="<?= e($member['image']) ?>" alt="<?= e($member['name']) ?>" class="h-full w-full object-cover">
            </div>
          <?php else: ?>
            <div class="grid aspect-square place-items-center rounded-2xl bg-gradient-to-br from-navy to-brand font-display text-8xl font-extrabold text-white/90">
              <?= e($member['initials'] ?: substr($member['name'], 0, 2)) ?>
            </div>
          <?php endif; ?>

          <div class="mt-6 p-4">
            <h3 class="font-display text-xl font-bold text-ink"><?= e($member['name']) ?></h3>
            <p class="text-xs font-semibold text-brand"><?= e($member['title']) ?></p>

            <hr class="my-4 border-slate-100">

            <div class="flex items-center gap-3">
              <?php if (!empty($member['social_linkedin'])): ?>
                <a href="<?= e($member['social_linkedin']) ?>" target="_blank" rel="noopener" class="grid h-10 w-10 place-items-center rounded-xl bg-soft text-brand transition hover:bg-brand hover:text-white">
                  <span class="font-bold text-sm">in</span>
                </a>
              <?php endif; ?>
              <?php if (!empty($member['social_github'])): ?>
                <a href="<?= e($member['social_github']) ?>" target="_blank" rel="noopener" class="grid h-10 w-10 place-items-center rounded-xl bg-soft text-ink transition hover:bg-ink hover:text-white">
                  <span class="font-bold text-sm">gh</span>
                </a>
              <?php endif; ?>
              <?php if (!empty($member['social_twitter'])): ?>
                <a href="<?= e($member['social_twitter']) ?>" target="_blank" rel="noopener" class="grid h-10 w-10 place-items-center rounded-xl bg-soft text-cyan transition hover:bg-cyan hover:text-navy">
                  <span class="font-bold text-sm">tw</span>
                </a>
              <?php endif; ?>
            </div>

            <div class="mt-6">
              <a href="start-project.php" class="btn-cta block w-full rounded-xl py-3 text-center text-xs font-semibold">
                Start a Project With Us
              </a>
            </div>
          </div>
        </div>
      </aside>

      <!-- Right: Bio & Expertise -->
      <div class="lg:col-span-8 space-y-10">
        <div>
          <h2 class="font-display text-2xl font-bold text-ink">About <?= e($member['name']) ?></h2>
          <p class="mt-4 text-base leading-relaxed text-slate-700">
            <?= nl2br(e($member['bio'] ?: $member['short_intro'])) ?>
          </p>
        </div>

        <?php if (!empty($member['specialties'])): 
          $specialties = array_filter(array_map('trim', explode(',', $member['specialties'])));
        ?>
          <div class="rounded-2xl border border-slate-200 bg-soft p-8">
            <h3 class="font-display text-lg font-bold text-ink">Core Specialties</h3>
            <ul class="mt-4 space-y-2.5">
              <?php foreach ($specialties as $sp): ?>
                <li class="flex items-center gap-3 text-sm text-slate-700">
                  <span class="material-symbols-outlined text-brand text-lg">verified</span>
                  <span class="font-medium"><?= e($sp) ?></span>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <?php if (!empty($member['skills'])): 
          $skills = array_filter(array_map('trim', explode(',', $member['skills'])));
        ?>
          <div>
            <h3 class="font-display text-lg font-bold text-ink">Technical &amp; Creative Skills</h3>
            <div class="mt-4 flex flex-wrap gap-2">
              <?php foreach ($skills as $sk): ?>
                <span class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-800 shadow-sm">
                  <?= e($sk) ?>
                </span>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>

        <!-- Other Team Members -->
        <?php if (!empty($other_members)): ?>
          <div class="border-t border-slate-100 pt-10">
            <h3 class="font-display text-lg font-bold text-ink">Other Team Leads</h3>
            <div class="mt-5 grid gap-4 sm:grid-cols-3">
              <?php foreach ($other_members as $om): ?>
                <a href="team-member.php?slug=<?= urlencode($om['slug']) ?>" class="group block rounded-xl border border-slate-200 p-4 transition hover:border-brand/40 hover:bg-soft">
                  <h4 class="font-display text-sm font-semibold text-ink group-hover:text-brand"><?= e($om['name']) ?></h4>
                  <p class="text-xs text-muted"><?= e($om['title']) ?></p>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>

      </div>

    </div>
  </div>
</section>

<?php include __DIR__ . '/include/footer.php'; ?>
