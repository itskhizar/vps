<?php
/**
 * VPS Digital Services - Portfolio / Case Studies CRUD
 */
$admin_title = "Portfolio & Case Studies Management";
require_once __DIR__ . '/header.php';

$db = get_db();
$edit_id = (int)($_GET['edit'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        set_flash('error', 'CSRF verification failed.');
        header("Location: portfolio.php");
        exit;
    }

    $action = trim($_POST['action'] ?? '');

    // Save Portfolio
    if ($action === 'save_portfolio') {
        $p_id         = (int)($_POST['portfolio_id'] ?? 0);
        $title        = trim($_POST['title'] ?? '');
        $slug         = slugify($_POST['slug'] ?? $title);
        $category     = trim($_POST['category'] ?? 'web');
        $client_name  = trim($_POST['client_name'] ?? '');
        $industry     = trim($_POST['industry'] ?? '');
        $technologies = trim($_POST['technologies'] ?? '');
        $short_desc   = trim($_POST['short_desc'] ?? '');
        $challenge    = trim($_POST['challenge'] ?? '');
        $solution     = trim($_POST['solution'] ?? '');
        $results      = trim($_POST['results'] ?? '');
        $project_url  = trim($_POST['project_url'] ?? '');
        $is_feat      = isset($_POST['is_featured']) ? 1 : 0;
        $is_pub       = isset($_POST['is_published']) ? 1 : 0;

        // Image upload if present
        $featured_img_path = null;
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $up = safe_upload($_FILES['featured_image'], 'portfolio', ['jpg', 'jpeg', 'png', 'webp']);
            if ($up['success']) {
                $featured_img_path = $up['relative_path'];
            } else {
                set_flash('error', 'Image error: ' . $up['error']);
            }
        }

        if (empty($title)) {
            set_flash('error', 'Case study title is required.');
        } else {
            try {
                if ($p_id > 0) {
                    if ($featured_img_path) {
                        $stmt = $db->prepare("UPDATE portfolio SET title = ?, slug = ?, category = ?, client_name = ?, industry = ?, technologies = ?, short_desc = ?, challenge = ?, solution = ?, results = ?, project_url = ?, is_featured = ?, is_published = ?, featured_image = ? WHERE id = ?");
                        $stmt->execute([$title, $slug, $category, $client_name, $industry, $technologies, $short_desc, $challenge, $solution, $results, $project_url, $is_feat, $is_pub, $featured_img_path, $p_id]);
                    } else {
                        $stmt = $db->prepare("UPDATE portfolio SET title = ?, slug = ?, category = ?, client_name = ?, industry = ?, technologies = ?, short_desc = ?, challenge = ?, solution = ?, results = ?, project_url = ?, is_featured = ?, is_published = ? WHERE id = ?");
                        $stmt->execute([$title, $slug, $category, $client_name, $industry, $technologies, $short_desc, $challenge, $solution, $results, $project_url, $is_feat, $is_pub, $p_id]);
                    }
                    set_flash('success', "Case study updated.");
                } else {
                    $stmt = $db->prepare("INSERT INTO portfolio (title, slug, category, client_name, industry, technologies, short_desc, challenge, solution, results, project_url, is_featured, is_published, featured_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$title, $slug, $category, $client_name, $industry, $technologies, $short_desc, $challenge, $solution, $results, $project_url, $is_feat, $is_pub, $featured_img_path]);
                    set_flash('success', "Case study created.");
                }
            } catch (Exception $e) {
                set_flash('error', 'Database error: ' . $e->getMessage());
            }
        }
        header("Location: portfolio.php");
        exit;
    }

    // Toggle Published
    if ($action === 'toggle_published') {
        $p_id = (int)($_POST['portfolio_id'] ?? 0);
        if ($p_id > 0) {
            $curr = (int)$db->query("SELECT is_published FROM portfolio WHERE id = {$p_id}")->fetchColumn();
            $new = $curr ? 0 : 1;
            $db->prepare("UPDATE portfolio SET is_published = ? WHERE id = ?")->execute([$new, $p_id]);
            set_flash('success', "Publication status updated.");
        }
        header("Location: portfolio.php");
        exit;
    }

    // Delete
    if ($action === 'delete_portfolio') {
        $p_id = (int)($_POST['portfolio_id'] ?? 0);
        if ($p_id > 0) {
            $db->prepare("DELETE FROM portfolio WHERE id = ?")->execute([$p_id]);
            set_flash('success', "Case study removed.");
        }
        header("Location: portfolio.php");
        exit;
    }
}

// Fetch all studies
try {
    $studies = $db->query("SELECT * FROM portfolio ORDER BY id DESC")->fetchAll();
    $editing_item = null;
    if ($edit_id > 0) {
        $stmt_ed = $db->prepare("SELECT * FROM portfolio WHERE id = ?");
        $stmt_ed->execute([$edit_id]);
        $editing_item = $stmt_ed->fetch();
    }
} catch (Exception $e) {
    $studies = [];
    $editing_item = null;
}
?>

<div class="space-y-8">
  <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm flex items-center justify-between">
    <div>
      <h2 class="font-display text-lg font-bold text-ink">Marketing Case Studies &amp; Portfolio</h2>
      <p class="text-xs text-muted">Manage case studies showcased across the public portfolio and homepage.</p>
    </div>
    <button onclick="document.getElementById('portfolioModal').classList.remove('hidden')" class="btn-cta inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-xs font-bold text-white shadow-md">
      <span class="material-symbols-outlined text-base">add</span> Add New Case Study
    </button>
  </div>

  <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-semibold">
          <tr>
            <th class="py-3.5 px-6">Case Study</th>
            <th class="py-3.5 px-4">Category</th>
            <th class="py-3.5 px-4">Client</th>
            <th class="py-3.5 px-4">Technologies</th>
            <th class="py-3.5 px-4">Status</th>
            <th class="py-3.5 px-6 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <?php if (!empty($studies)): ?>
            <?php foreach ($studies as $st): ?>
              <tr class="hover:bg-slate-50/80 transition">
                <td class="py-4 px-6">
                  <div class="font-bold text-ink text-sm"><?= e($st['title']) ?></div>
                  <div class="text-[10px] text-muted font-mono"><?= e($st['slug']) ?></div>
                </td>
                <td class="py-4 px-4 font-semibold uppercase text-brand"><?= e($st['category']) ?></td>
                <td class="py-4 px-4 text-slate-700"><?= e($st['client_name'] ?: 'Confidential') ?></td>
                <td class="py-4 px-4 text-slate-500 text-[11px] truncate max-w-[150px]"><?= e($st['technologies']) ?></td>
                <td class="py-4 px-4">
                  <form action="portfolio.php" method="post" class="inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="toggle_published">
                    <input type="hidden" name="portfolio_id" value="<?= $st['id'] ?>">
                    <button type="submit" class="rounded-full px-2.5 py-0.5 text-[10px] font-bold border transition <?= $st['is_published'] ? 'bg-emerald-50 text-emerald-800 border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 border-slate-200 hover:bg-slate-200' ?>">
                      <?= $st['is_published'] ? 'Published' : 'Draft' ?>
                    </button>
                  </form>
                </td>
                <td class="py-4 px-6 text-right space-x-2">
                  <a href="portfolio.php?edit=<?= $st['id'] ?>" class="rounded-lg bg-soft px-2.5 py-1.5 font-bold text-brand hover:bg-brand hover:text-white transition">
                    Edit
                  </a>
                  <a href="../case-study.php?slug=<?= urlencode($st['slug']) ?>" target="_blank" class="rounded-lg bg-soft px-2.5 py-1.5 text-slate-600 hover:text-ink transition">
                    <span class="material-symbols-outlined text-sm">visibility</span>
                  </a>
                  <form action="portfolio.php" method="post" class="inline" onsubmit="return confirm('Delete this study?');">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete_portfolio">
                    <input type="hidden" name="portfolio_id" value="<?= $st['id'] ?>">
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

<!-- Modal / Form Container -->
<div id="portfolioModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm <?= $editing_item ? '' : 'hidden' ?> flex items-center justify-center p-4">
  <div class="w-full max-w-2xl rounded-3xl bg-white p-8 shadow-2xl max-h-[90vh] overflow-y-auto text-xs">
    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
      <h3 class="font-display text-lg font-bold text-ink"><?= $editing_item ? 'Edit Case Study' : 'Add New Case Study' ?></h3>
      <a href="portfolio.php" class="text-slate-400 hover:text-ink"><span class="material-symbols-outlined">close</span></a>
    </div>

    <form action="portfolio.php" method="post" enctype="multipart/form-data" class="mt-6 space-y-4">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="save_portfolio">
      <input type="hidden" name="portfolio_id" value="<?= $editing_item['id'] ?? 0 ?>">

      <div class="grid gap-4 sm:grid-cols-2">
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Title *</label>
          <input type="text" name="title" required value="<?= e($editing_item['title'] ?? '') ?>" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Category *</label>
          <select name="category" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-ink bg-white font-semibold">
            <?php 
            $cats = ['web' => 'Web Development', 'app' => 'Mobile App', 'brand' => 'Branding', 'shop' => 'Ecommerce', 'data' => 'Data Science', 'arch' => 'Architecture & 3D'];
            foreach ($cats as $k => $v): 
            ?>
              <option value="<?= $k ?>" <?= ($editing_item['category'] ?? '') === $k ? 'selected' : '' ?>><?= $v ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="grid gap-4 sm:grid-cols-3">
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Client Name</label>
          <input type="text" name="client_name" value="<?= e($editing_item['client_name'] ?? '') ?>" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Industry</label>
          <input type="text" name="industry" value="<?= e($editing_item['industry'] ?? '') ?>" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Tech Stack</label>
          <input type="text" name="technologies" value="<?= e($editing_item['technologies'] ?? '') ?>" placeholder="PHP, MySQL, Flutter" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
      </div>

      <div>
        <label class="block font-bold text-muted uppercase mb-1">Short Description</label>
        <textarea name="short_desc" rows="2" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink"><?= e($editing_item['short_desc'] ?? '') ?></textarea>
      </div>

      <div>
        <label class="block font-bold text-muted uppercase mb-1">The Challenge</label>
        <textarea name="challenge" rows="2" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink"><?= e($editing_item['challenge'] ?? '') ?></textarea>
      </div>

      <div>
        <label class="block font-bold text-muted uppercase mb-1">The Solution &amp; Architecture</label>
        <textarea name="solution" rows="2" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink"><?= e($editing_item['solution'] ?? '') ?></textarea>
      </div>

      <div>
        <label class="block font-bold text-muted uppercase mb-1">Measurable Results</label>
        <textarea name="results" rows="2" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink"><?= e($editing_item['results'] ?? '') ?></textarea>
      </div>

      <div>
        <label class="block font-bold text-muted uppercase mb-1">Featured Showcase Image</label>
        <input type="file" name="featured_image" class="block w-full text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-soft file:px-4 file:py-2 file:font-semibold file:text-brand">
      </div>

      <div class="flex items-center gap-6 pt-2">
        <label class="flex items-center gap-2 font-bold text-slate-700 cursor-pointer">
          <input type="checkbox" name="is_featured" value="1" <?= (!empty($editing_item['is_featured'])) ? 'checked' : '' ?> class="rounded text-brand">
          Featured on Homepage
        </label>
        <label class="flex items-center gap-2 font-bold text-slate-700 cursor-pointer">
          <input type="checkbox" name="is_published" value="1" <?= (!isset($editing_item) || !empty($editing_item['is_published'])) ? 'checked' : '' ?> class="rounded text-brand">
          Published
        </label>
      </div>

      <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
        <a href="portfolio.php" class="rounded-xl bg-slate-100 px-5 py-2.5 font-bold text-slate-700 hover:bg-slate-200">Cancel</a>
        <button type="submit" class="btn-cta rounded-xl px-6 py-2.5 font-bold text-white shadow-md">Save Case Study</button>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
