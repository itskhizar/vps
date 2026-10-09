<?php
/**
 * VPS Digital Services - Services CRUD Management
 */
$admin_title = "Services Management";
require_once __DIR__ . '/header.php';

$db = get_db();
$edit_id = (int)($_GET['edit'] ?? 0);

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        set_flash('error', 'CSRF verification failed.');
        header("Location: services.php");
        exit;
    }

    $action = trim($_POST['action'] ?? '');

    // Add or Edit Service
    if ($action === 'save_service') {
        $svc_id      = (int)($_POST['service_id'] ?? 0);
        $title       = trim($_POST['title'] ?? '');
        $slug        = slugify($_POST['slug'] ?? $title);
        $short_desc  = trim($_POST['short_desc'] ?? '');
        $full_desc   = trim($_POST['full_desc'] ?? '');
        $icon        = trim($_POST['icon'] ?? 'code');
        $category    = trim($_POST['category'] ?? 'Development');
        $features    = trim($_POST['features'] ?? '');
        $disp_order  = (int)($_POST['display_order'] ?? 0);
        $is_feat     = isset($_POST['is_featured']) ? 1 : 0;
        $is_act      = isset($_POST['is_active']) ? 1 : 0;
        $m_title     = trim($_POST['meta_title'] ?? '');
        $m_desc      = trim($_POST['meta_desc'] ?? '');

        if (empty($title)) {
            set_flash('error', 'Service title is required.');
        } else {
            try {
                if ($svc_id > 0) {
                    $stmt = $db->prepare("UPDATE services SET title = ?, slug = ?, short_desc = ?, full_desc = ?, icon = ?, category = ?, features = ?, display_order = ?, is_featured = ?, is_active = ?, meta_title = ?, meta_desc = ? WHERE id = ?");
                    $stmt->execute([$title, $slug, $short_desc, $full_desc, $icon, $category, $features, $disp_order, $is_feat, $is_act, $m_title, $m_desc, $svc_id]);
                    set_flash('success', "Service '{$title}' updated successfully.");
                } else {
                    $stmt = $db->prepare("INSERT INTO services (title, slug, short_desc, full_desc, icon, category, features, display_order, is_featured, is_active, meta_title, meta_desc) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$title, $slug, $short_desc, $full_desc, $icon, $category, $features, $disp_order, $is_feat, $is_act, $m_title, $m_desc]);
                    set_flash('success', "Service '{$title}' created successfully.");
                }
            } catch (Exception $e) {
                error_log("Service save error: " . $e->getMessage());
                set_flash('error', 'Database error: ' . $e->getMessage());
            }
        }
        header("Location: services.php");
        exit;
    }

    // Toggle Active Status
    if ($action === 'toggle_active') {
        $svc_id = (int)($_POST['service_id'] ?? 0);
        if ($svc_id > 0) {
            $curr = (int)$db->query("SELECT is_active FROM services WHERE id = {$svc_id}")->fetchColumn();
            $new = $curr ? 0 : 1;
            $db->prepare("UPDATE services SET is_active = ? WHERE id = ?")->execute([$new, $svc_id]);
            set_flash('success', "Service status updated.");
        }
        header("Location: services.php");
        exit;
    }

    // Safe Deletion with Referential Check
    if ($action === 'delete_service') {
        $svc_id = (int)($_POST['service_id'] ?? 0);
        if ($svc_id > 0) {
            // Check if existing client projects reference this service
            $proj_refs = (int)$db->query("SELECT COUNT(*) FROM projects WHERE service_id = {$svc_id}")->fetchColumn();
            if ($proj_refs > 0) {
                set_flash('error', "Cannot delete this service because {$proj_refs} active project(s) reference it. Please deactivate the service instead.");
            } else {
                $db->prepare("DELETE FROM services WHERE id = ?")->execute([$svc_id]);
                set_flash('success', "Service deleted successfully.");
            }
        }
        header("Location: services.php");
        exit;
    }
}

// Fetch all services
try {
    $services = $db->query("SELECT * FROM services ORDER BY display_order ASC, id ASC")->fetchAll();
    $editing_service = null;
    if ($edit_id > 0) {
        $stmt_ed = $db->prepare("SELECT * FROM services WHERE id = ?");
        $stmt_ed->execute([$edit_id]);
        $editing_service = $stmt_ed->fetch();
    }
} catch (Exception $e) {
    $services = [];
    $editing_service = null;
}
?>

<div class="space-y-8">

  <!-- Top Action Bar -->
  <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm flex items-center justify-between">
    <div>
      <h2 class="font-display text-lg font-bold text-ink">Services Catalog Management</h2>
      <p class="text-xs text-muted">Create, edit, reorder, and publish specialized services offered to clients.</p>
    </div>
    <button onclick="document.getElementById('serviceFormModal').classList.remove('hidden')" class="btn-cta inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-xs font-bold text-white shadow-md">
      <span class="material-symbols-outlined text-base">add</span> Add New Service
    </button>
  </div>

  <!-- Services Table -->
  <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-semibold">
          <tr>
            <th class="py-3.5 px-6">Order</th>
            <th class="py-3.5 px-4">Service</th>
            <th class="py-3.5 px-4">Category</th>
            <th class="py-3.5 px-4">Slug</th>
            <th class="py-3.5 px-4">Status</th>
            <th class="py-3.5 px-6 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <?php if (!empty($services)): ?>
            <?php foreach ($services as $s): ?>
              <tr class="hover:bg-slate-50/80 transition">
                <td class="py-4 px-6 font-mono text-slate-500 font-bold"><?= $s['display_order'] ?></td>
                <td class="py-4 px-4">
                  <div class="flex items-center gap-3">
                    <span class="grid h-8 w-8 place-items-center rounded-lg bg-soft text-brand">
                      <span class="material-symbols-outlined text-lg"><?= e($s['icon'] ?: 'code') ?></span>
                    </span>
                    <div>
                      <span class="font-bold text-ink block text-sm"><?= e($s['title']) ?></span>
                      <span class="text-[10px] text-muted line-clamp-1"><?= e($s['short_desc']) ?></span>
                    </div>
                  </div>
                </td>
                <td class="py-4 px-4 font-semibold text-slate-700"><?= e($s['category']) ?></td>
                <td class="py-4 px-4 font-mono text-slate-500 text-[11px]"><?= e($s['slug']) ?></td>
                <td class="py-4 px-4">
                  <form action="services.php" method="post" class="inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="toggle_active">
                    <input type="hidden" name="service_id" value="<?= $s['id'] ?>">
                    <button type="submit" class="rounded-full px-2.5 py-0.5 text-[10px] font-bold border transition <?= $s['is_active'] ? 'bg-emerald-50 text-emerald-800 border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 border-slate-200 hover:bg-slate-200' ?>">
                      <?= $s['is_active'] ? 'Published' : 'Hidden' ?>
                    </button>
                  </form>
                </td>
                <td class="py-4 px-6 text-right space-x-2">
                  <a href="services.php?edit=<?= $s['id'] ?>" class="rounded-lg bg-soft px-2.5 py-1.5 font-bold text-brand hover:bg-brand hover:text-white transition">
                    Edit
                  </a>
                  <a href="../service-details.php?slug=<?= urlencode($s['slug']) ?>" target="_blank" class="rounded-lg bg-soft px-2.5 py-1.5 text-slate-600 hover:text-ink transition" title="Preview Public Page">
                    <span class="material-symbols-outlined text-sm">visibility</span>
                  </a>
                  <form action="services.php" method="post" class="inline" onsubmit="return confirm('Delete <?= e($s['title']) ?>?');">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete_service">
                    <input type="hidden" name="service_id" value="<?= $s['id'] ?>">
                    <button type="submit" class="rounded-lg bg-red-50 p-1.5 text-red-600 hover:bg-red-600 hover:text-white transition" title="Delete">
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

<!-- Modal / Form Container for Add or Edit -->
<div id="serviceFormModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm <?= $editing_service ? '' : 'hidden' ?> flex items-center justify-center p-4">
  <div class="w-full max-w-2xl rounded-3xl bg-white p-8 shadow-2xl max-h-[90vh] overflow-y-auto">
    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
      <h3 class="font-display text-lg font-bold text-ink">
        <?= $editing_service ? 'Edit Service: ' . e($editing_service['title']) : 'Add New Service' ?>
      </h3>
      <a href="services.php" class="text-slate-400 hover:text-ink">
        <span class="material-symbols-outlined">close</span>
      </a>
    </div>

    <form action="services.php" method="post" class="mt-6 space-y-4 text-xs">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="save_service">
      <input type="hidden" name="service_id" value="<?= $editing_service['id'] ?? 0 ?>">

      <div class="grid gap-4 sm:grid-cols-2">
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Service Title *</label>
          <input type="text" name="title" required value="<?= e($editing_service['title'] ?? '') ?>" placeholder="e.g. Website Development" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Slug (URL identifier)</label>
          <input type="text" name="slug" value="<?= e($editing_service['slug'] ?? '') ?>" placeholder="auto-generated-from-title" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
      </div>

      <div class="grid gap-4 sm:grid-cols-3">
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Category</label>
          <input type="text" name="category" value="<?= e($editing_service['category'] ?? 'Development') ?>" placeholder="e.g. Development, Creative" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Material Icon</label>
          <input type="text" name="icon" value="<?= e($editing_service['icon'] ?? 'code') ?>" placeholder="code, palette, draw, etc." class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Display Order</label>
          <input type="number" name="display_order" value="<?= (int)($editing_service['display_order'] ?? 0) ?>" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
      </div>

      <div>
        <label class="block font-bold text-muted uppercase mb-1">Short Description (for cards) *</label>
        <textarea name="short_desc" rows="2" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink"><?= e($editing_service['short_desc'] ?? '') ?></textarea>
      </div>

      <div>
        <label class="block font-bold text-muted uppercase mb-1">Full Detailed Content (HTML allowed)</label>
        <textarea name="full_desc" rows="6" placeholder="<p>Detailed service scope...</p>" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink font-mono"><?= e($editing_service['full_desc'] ?? '') ?></textarea>
      </div>

      <div>
        <label class="block font-bold text-muted uppercase mb-1">Included Features (Comma-separated)</label>
        <input type="text" name="features" value="<?= e($editing_service['features'] ?? '') ?>" placeholder="Responsive Layouts, SEO Optimization, CMS Integration" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
      </div>

      <div class="grid gap-4 sm:grid-cols-2">
        <div>
          <label class="block font-bold text-muted uppercase mb-1">SEO Meta Title</label>
          <input type="text" name="meta_title" value="<?= e($editing_service['meta_title'] ?? '') ?>" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
        <div>
          <label class="block font-bold text-muted uppercase mb-1">SEO Meta Description</label>
          <input type="text" name="meta_desc" value="<?= e($editing_service['meta_desc'] ?? '') ?>" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
      </div>

      <div class="flex items-center gap-6 pt-2">
        <label class="flex items-center gap-2 font-bold text-slate-700 cursor-pointer">
          <input type="checkbox" name="is_featured" value="1" <?= (!isset($editing_service) || !empty($editing_service['is_featured'])) ? 'checked' : '' ?> class="rounded text-brand">
          Featured Service
        </label>
        <label class="flex items-center gap-2 font-bold text-slate-700 cursor-pointer">
          <input type="checkbox" name="is_active" value="1" <?= (!isset($editing_service) || !empty($editing_service['is_active'])) ? 'checked' : '' ?> class="rounded text-brand">
          Active / Published
        </label>
      </div>

      <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
        <a href="services.php" class="rounded-xl bg-slate-100 px-5 py-2.5 font-bold text-slate-700 hover:bg-slate-200">
          Cancel
        </a>
        <button type="submit" class="btn-cta rounded-xl px-6 py-2.5 font-bold text-white shadow-md">
          Save Service
        </button>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
