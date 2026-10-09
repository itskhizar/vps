<?php
/**
 * VPS Digital Services - Team CRUD Management
 */
$admin_title = "Team Management";
require_once __DIR__ . '/header.php';

$db = get_db();
$edit_id = (int)($_GET['edit'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        set_flash('error', 'CSRF verification failed.');
        header("Location: team.php");
        exit;
    }

    $action = trim($_POST['action'] ?? '');

    // Save Member
    if ($action === 'save_member') {
        $m_id        = (int)($_POST['member_id'] ?? 0);
        $name        = trim($_POST['name'] ?? '');
        $slug        = slugify($_POST['slug'] ?? $name);
        $title       = trim($_POST['title'] ?? '');
        $short_intro = trim($_POST['short_intro'] ?? '');
        $bio         = trim($_POST['bio'] ?? '');
        $specialties = trim($_POST['specialties'] ?? '');
        $skills      = trim($_POST['skills'] ?? '');
        $disp_order  = (int)($_POST['display_order'] ?? 0);
        $is_act      = isset($_POST['is_active']) ? 1 : 0;
        $linkedin    = trim($_POST['social_linkedin'] ?? '');
        $github      = trim($_POST['social_github'] ?? '');
        $twitter     = trim($_POST['social_twitter'] ?? '');

        // Photo upload
        $photo_path = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $up = safe_upload($_FILES['image'], 'team', ['jpg', 'jpeg', 'png', 'webp']);
            if ($up['success']) {
                $photo_path = $up['relative_path'];
            }
        }

        // Initials calculation
        $words = explode(' ', $name);
        $initials = '';
        foreach (array_slice($words, 0, 2) as $w) {
            $initials .= strtoupper(substr($w, 0, 1));
        }

        if (empty($name) || empty($title)) {
            set_flash('error', 'Name and professional title are required.');
        } else {
            try {
                if ($m_id > 0) {
                    if ($photo_path) {
                        $stmt = $db->prepare("UPDATE team SET name = ?, slug = ?, title = ?, short_intro = ?, bio = ?, specialties = ?, skills = ?, display_order = ?, is_active = ?, social_linkedin = ?, social_github = ?, social_twitter = ?, initials = ?, image = ? WHERE id = ?");
                        $stmt->execute([$name, $slug, $title, $short_intro, $bio, $specialties, $skills, $disp_order, $is_act, $linkedin, $github, $twitter, $initials, $photo_path, $m_id]);
                    } else {
                        $stmt = $db->prepare("UPDATE team SET name = ?, slug = ?, title = ?, short_intro = ?, bio = ?, specialties = ?, skills = ?, display_order = ?, is_active = ?, social_linkedin = ?, social_github = ?, social_twitter = ?, initials = ? WHERE id = ?");
                        $stmt->execute([$name, $slug, $title, $short_intro, $bio, $specialties, $skills, $disp_order, $is_act, $linkedin, $github, $twitter, $initials, $m_id]);
                    }
                    set_flash('success', "Team member {$name} updated.");
                } else {
                    $stmt = $db->prepare("INSERT INTO team (name, slug, title, short_intro, bio, specialties, skills, display_order, is_active, social_linkedin, social_github, social_twitter, initials, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$name, $slug, $title, $short_intro, $bio, $specialties, $skills, $disp_order, $is_act, $linkedin, $github, $twitter, $initials, $photo_path]);
                    set_flash('success', "Team member {$name} added.");
                }
            } catch (Exception $e) {
                set_flash('error', 'Database error: ' . $e->getMessage());
            }
        }
        header("Location: team.php");
        exit;
    }

    // Toggle active
    if ($action === 'toggle_active') {
        $m_id = (int)($_POST['member_id'] ?? 0);
        if ($m_id > 0) {
            $curr = (int)$db->query("SELECT is_active FROM team WHERE id = {$m_id}")->fetchColumn();
            $new = $curr ? 0 : 1;
            $db->prepare("UPDATE team SET is_active = ? WHERE id = ?")->execute([$new, $m_id]);
            set_flash('success', "Member status updated.");
        }
        header("Location: team.php");
        exit;
    }

    // Delete
    if ($action === 'delete_member') {
        $m_id = (int)($_POST['member_id'] ?? 0);
        if ($m_id > 0) {
            $db->prepare("DELETE FROM team WHERE id = ?")->execute([$m_id]);
            set_flash('success', "Team member deleted.");
        }
        header("Location: team.php");
        exit;
    }
}

// Fetch team members
try {
    $members = $db->query("SELECT * FROM team ORDER BY display_order ASC, id ASC")->fetchAll();
    $editing_member = null;
    if ($edit_id > 0) {
        $stmt_ed = $db->prepare("SELECT * FROM team WHERE id = ?");
        $stmt_ed->execute([$edit_id]);
        $editing_member = $stmt_ed->fetch();
    }
} catch (Exception $e) {
    $members = [];
    $editing_member = null;
}
?>

<div class="space-y-8">
  <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm flex items-center justify-between">
    <div>
      <h2 class="font-display text-lg font-bold text-ink">Team Members Directory</h2>
      <p class="text-xs text-muted">Manage leadership and engineering staff profiles displayed across public team pages.</p>
    </div>
    <button onclick="document.getElementById('teamModal').classList.remove('hidden')" class="btn-cta inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-xs font-bold text-white shadow-md">
      <span class="material-symbols-outlined text-base">add</span> Add Team Member
    </button>
  </div>

  <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-semibold">
          <tr>
            <th class="py-3.5 px-6">Order</th>
            <th class="py-3.5 px-4">Member</th>
            <th class="py-3.5 px-4">Title</th>
            <th class="py-3.5 px-4">Specialties</th>
            <th class="py-3.5 px-4">Status</th>
            <th class="py-3.5 px-6 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <?php if (!empty($members)): ?>
            <?php foreach ($members as $m): ?>
              <tr class="hover:bg-slate-50/80 transition">
                <td class="py-4 px-6 font-mono font-bold text-slate-500"><?= $m['display_order'] ?></td>
                <td class="py-4 px-4">
                  <div class="flex items-center gap-3">
                    <?php if (!empty($m['image']) && file_exists(__DIR__ . '/../' . $m['image'])): ?>
                      <img src="../<?= e($m['image']) ?>" class="h-9 w-9 rounded-full object-cover">
                    <?php else: ?>
                      <div class="h-9 w-9 rounded-full bg-navy text-cyan font-bold grid place-items-center text-xs">
                        <?= e($m['initials'] ?: substr($m['name'], 0, 2)) ?>
                      </div>
                    <?php endif; ?>
                    <div>
                      <span class="font-bold text-ink text-sm block"><?= e($m['name']) ?></span>
                      <span class="text-[10px] text-muted font-mono"><?= e($m['slug']) ?></span>
                    </div>
                  </div>
                </td>
                <td class="py-4 px-4 font-semibold text-brand"><?= e($m['title']) ?></td>
                <td class="py-4 px-4 text-slate-600 truncate max-w-[180px]"><?= e($m['specialties']) ?></td>
                <td class="py-4 px-4">
                  <form action="team.php" method="post" class="inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="toggle_active">
                    <input type="hidden" name="member_id" value="<?= $m['id'] ?>">
                    <button type="submit" class="rounded-full px-2.5 py-0.5 text-[10px] font-bold border transition <?= $m['is_active'] ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200' ?>">
                      <?= $m['is_active'] ? 'Active' : 'Hidden' ?>
                    </button>
                  </form>
                </td>
                <td class="py-4 px-6 text-right space-x-2">
                  <a href="team.php?edit=<?= $m['id'] ?>" class="rounded-lg bg-soft px-2.5 py-1.5 font-bold text-brand hover:bg-brand hover:text-white transition">
                    Edit
                  </a>
                  <form action="team.php" method="post" class="inline" onsubmit="return confirm('Delete <?= e($m['name']) ?>?');">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete_member">
                    <input type="hidden" name="member_id" value="<?= $m['id'] ?>">
                    <button type="submit" class="rounded-lg bg-red-50 p-1.5 text-red-600 hover:bg-red-600 hover:text-white transition">
                      <span class="material-symbols-outlined text-base">delete</span>
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Form -->
<div id="teamModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm <?= $editing_member ? '' : 'hidden' ?> flex items-center justify-center p-4">
  <div class="w-full max-w-2xl rounded-3xl bg-white p-8 shadow-2xl max-h-[90vh] overflow-y-auto text-xs">
    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
      <h3 class="font-display text-lg font-bold text-ink"><?= $editing_member ? 'Edit Member: ' . e($editing_member['name']) : 'Add Team Member' ?></h3>
      <a href="team.php" class="text-slate-400 hover:text-ink"><span class="material-symbols-outlined">close</span></a>
    </div>

    <form action="team.php" method="post" enctype="multipart/form-data" class="mt-6 space-y-4">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="save_member">
      <input type="hidden" name="member_id" value="<?= $editing_member['id'] ?? 0 ?>">

      <div class="grid gap-4 sm:grid-cols-2">
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Full Name *</label>
          <input type="text" name="name" required value="<?= e($editing_member['name'] ?? '') ?>" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Professional Title *</label>
          <input type="text" name="title" required value="<?= e($editing_member['title'] ?? '') ?>" placeholder="e.g. Lead Full-Stack Architect" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
      </div>

      <div class="grid gap-4 sm:grid-cols-2">
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Slug</label>
          <input type="text" name="slug" value="<?= e($editing_member['slug'] ?? '') ?>" placeholder="auto-generated-name" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Display Order</label>
          <input type="number" name="display_order" value="<?= (int)($editing_member['display_order'] ?? 0) ?>" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
      </div>

      <div>
        <label class="block font-bold text-muted uppercase mb-1">Short Introduction</label>
        <textarea name="short_intro" rows="2" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink"><?= e($editing_member['short_intro'] ?? '') ?></textarea>
      </div>

      <div>
        <label class="block font-bold text-muted uppercase mb-1">Full Biography</label>
        <textarea name="bio" rows="4" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink"><?= e($editing_member['bio'] ?? '') ?></textarea>
      </div>

      <div class="grid gap-4 sm:grid-cols-2">
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Specialties (Comma-separated)</label>
          <input type="text" name="specialties" value="<?= e($editing_member['specialties'] ?? '') ?>" placeholder="System Architecture, Cloud, UI/UX" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Skills (Comma-separated)</label>
          <input type="text" name="skills" value="<?= e($editing_member['skills'] ?? '') ?>" placeholder="PHP, MySQL, Flutter, Docker" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
      </div>

      <div class="grid gap-4 sm:grid-cols-3">
        <div>
          <label class="block font-bold text-muted uppercase mb-1">LinkedIn URL</label>
          <input type="text" name="social_linkedin" value="<?= e($editing_member['social_linkedin'] ?? '') ?>" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-ink">
        </div>
        <div>
          <label class="block font-bold text-muted uppercase mb-1">GitHub URL</label>
          <input type="text" name="social_github" value="<?= e($editing_member['social_github'] ?? '') ?>" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-ink">
        </div>
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Twitter / X</label>
          <input type="text" name="social_twitter" value="<?= e($editing_member['social_twitter'] ?? '') ?>" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-ink">
        </div>
      </div>

      <div>
        <label class="block font-bold text-muted uppercase mb-1">Profile Photo</label>
        <input type="file" name="image" class="block w-full text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-soft file:px-4 file:py-2 file:font-semibold file:text-brand">
      </div>

      <div class="pt-2">
        <label class="flex items-center gap-2 font-bold text-slate-700 cursor-pointer">
          <input type="checkbox" name="is_active" value="1" <?= (!isset($editing_member) || !empty($editing_member['is_active'])) ? 'checked' : '' ?> class="rounded text-brand">
          Active / Publicly Visible
        </label>
      </div>

      <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
        <a href="team.php" class="rounded-xl bg-slate-100 px-5 py-2.5 font-bold text-slate-700 hover:bg-slate-200">Cancel</a>
        <button type="submit" class="btn-cta rounded-xl px-6 py-2.5 font-bold text-white shadow-md">Save Profile</button>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
