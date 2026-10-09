<?php
/**
 * VPS Digital Services - Projects Management Module
 */
$admin_title = "Projects Management";
require_once __DIR__ . '/header.php';

$db = get_db();
$search = trim($_GET['q'] ?? '');
$status_filter = trim($_GET['status'] ?? '');
$service_filter = (int)($_GET['service_id'] ?? 0);
$page = max(1, (int)($_GET['p'] ?? 1));
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Handle State-Changing POST Requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        set_flash('error', 'CSRF verification failed.');
        header("Location: projects.php");
        exit;
    }

    $action = trim($_POST['action'] ?? '');

    // 1. Manual Project Creation
    if ($action === 'create_manual') {
        $c_name    = trim($_POST['client_name'] ?? '');
        $c_email   = filter_var(trim($_POST['client_email'] ?? ''), FILTER_VALIDATE_EMAIL);
        $c_phone   = trim($_POST['client_phone'] ?? '');
        $p_title   = trim($_POST['title'] ?? '');
        $svc_id    = (int)($_POST['service_id'] ?? 0);
        $p_desc    = trim($_POST['description'] ?? '');
        $b_type    = trim($_POST['budget_type'] ?? 'fixed');
        $b_amt     = trim($_POST['budget_amount'] ?? '');
        $cur       = trim($_POST['currency'] ?? 'USD');
        $status    = trim($_POST['status'] ?? 'Pending Review');

        if (empty($c_name) || !$c_email || empty($p_title) || empty($p_desc)) {
            set_flash('error', 'Please fill all required fields (Client name, email, title, and description).');
        } else {
            try {
                // Fetch service name
                $svc_name = null;
                if ($svc_id > 0) {
                    $st_s = $db->prepare("SELECT title FROM services WHERE id = ?");
                    $st_s->execute([$svc_id]);
                    $svc_name = $st_s->fetchColumn();
                }

                $ref_no = generate_project_ref();
                $db->beginTransaction();

                $stmt = $db->prepare("INSERT INTO projects (reference_no, client_name, client_email, client_phone, title, service_id, service_name, description, budget_type, budget_amount, currency, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$ref_no, $c_name, $c_email, $c_phone, $p_title, $svc_id ?: null, $svc_name, $p_desc, $b_type, $b_amt, $cur, $status]);
                $new_id = (int)$db->lastInsertId();

                $stmt_h = $db->prepare("INSERT INTO project_status_history (project_id, previous_status, new_status, changed_by, comment) VALUES (?, NULL, ?, ?, 'Project created manually by admin')");
                $stmt_h->execute([$new_id, $status, $admin_user]);

                $db->commit();
                set_flash('success', "Project {$ref_no} created successfully.");
                header("Location: project-details.php?id={$new_id}");
                exit;
            } catch (Exception $e) {
                $db->rollBack();
                error_log("Failed to create manual project: " . $e->getMessage());
                set_flash('error', 'Database error creating project.');
            }
        }
    }

    // 2. Quick Status Change
    if ($action === 'update_status') {
        $proj_id = (int)($_POST['project_id'] ?? 0);
        $new_stat = trim($_POST['new_status'] ?? '');

        if ($proj_id > 0 && !empty($new_stat)) {
            try {
                $curr_stat = $db->query("SELECT status FROM projects WHERE id = {$proj_id}")->fetchColumn();
                if ($curr_stat && $curr_stat !== $new_stat) {
                    $db->beginTransaction();
                    $stmt_u = $db->prepare("UPDATE projects SET status = ? WHERE id = ?");
                    $stmt_u->execute([$new_stat, $proj_id]);

                    $stmt_h = $db->prepare("INSERT INTO project_status_history (project_id, previous_status, new_status, changed_by, comment) VALUES (?, ?, ?, ?, 'Status updated from project index')");
                    $stmt_h->execute([$proj_id, $curr_stat, $new_stat, $admin_user]);
                    $db->commit();
                    set_flash('success', "Project status updated to {$new_stat}.");
                }
            } catch (Exception $e) {
                $db->rollBack();
                set_flash('error', 'Failed to update project status.');
            }
        }
        header("Location: projects.php" . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : ''));
        exit;
    }

    // 3. Delete Project
    if ($action === 'delete_project') {
        $proj_id = (int)($_POST['project_id'] ?? 0);
        if ($proj_id > 0) {
            try {
                $stmt_del = $db->prepare("DELETE FROM projects WHERE id = ?");
                $stmt_del->execute([$proj_id]);
                set_flash('success', 'Project and related records deleted permanently.');
            } catch (Exception $e) {
                set_flash('error', 'Error deleting project: ' . $e->getMessage());
            }
        }
        header("Location: projects.php");
        exit;
    }
}

// Fetch Active Services for Filter and Creation Modal
try {
    $services_list = $db->query("SELECT id, title FROM services WHERE is_active = 1 ORDER BY display_order ASC")->fetchAll();
} catch (Exception $e) {
    $services_list = [];
}

// Build Query
$where = ["1=1"];
$params = [];

if (!empty($search)) {
    $where[] = "(reference_no LIKE ? OR client_name LIKE ? OR client_email LIKE ? OR title LIKE ?)";
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
}

if (!empty($status_filter)) {
    $where[] = "status = ?";
    $params[] = $status_filter;
}

if ($service_filter > 0) {
    $where[] = "service_id = ?";
    $params[] = $service_filter;
}

$where_sql = implode(' AND ', $where);

// Total Count
$stmt_count = $db->prepare("SELECT COUNT(*) FROM projects WHERE {$where_sql}");
$stmt_count->execute($params);
$total_rows = (int)$stmt_count->fetchColumn();
$total_pages = ceil($total_rows / $per_page);

// Fetch Rows
$sql_rows = "SELECT * FROM projects WHERE {$where_sql} ORDER BY id DESC LIMIT {$per_page} OFFSET {$offset}";
$stmt_rows = $db->prepare($sql_rows);
$stmt_rows->execute($params);
$projects = $stmt_rows->fetchAll();
?>

<div class="space-y-6">

  <!-- Action Bar & Search / Filter Controls -->
  <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
      <div>
        <h2 class="font-display text-lg font-bold text-ink">Client Inquiries &amp; Active Projects</h2>
        <p class="text-xs text-muted">Manage, review, track, and update incoming client projects.</p>
      </div>

      <button onclick="document.getElementById('createProjectModal').classList.remove('hidden')" class="btn-cta inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-xs font-bold text-white shadow-md">
        <span class="material-symbols-outlined text-base">add</span> Create Project Manually
      </button>
    </div>

    <!-- Filters Form -->
    <form action="projects.php" method="get" class="mt-6 grid gap-4 sm:grid-cols-12 border-t border-slate-100 pt-5">
      <div class="sm:col-span-5">
        <label class="block text-[11px] font-bold uppercase text-muted mb-1">Search</label>
        <input type="text" name="q" value="<?= e($search) ?>" placeholder="Ref #, Client name, email, or title..." class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs text-ink focus:outline-none focus:border-brand">
      </div>

      <div class="sm:col-span-3">
        <label class="block text-[11px] font-bold uppercase text-muted mb-1">Lifecycle Status</label>
        <select name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs text-ink focus:outline-none focus:border-brand bg-white">
          <option value="">All Statuses</option>
          <?php 
          $statuses = ['Pending Review', 'In Progress', 'Completed', 'On Hold', 'Cancelled'];
          foreach ($statuses as $st): 
          ?>
            <option value="<?= e($st) ?>" <?= $status_filter === $st ? 'selected' : '' ?>><?= e($st) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="sm:col-span-3">
        <label class="block text-[11px] font-bold uppercase text-muted mb-1">Service</label>
        <select name="service_id" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs text-ink focus:outline-none focus:border-brand bg-white">
          <option value="">All Services</option>
          <?php foreach ($services_list as $sl): ?>
            <option value="<?= $sl['id'] ?>" <?= $service_filter === $sl['id'] ? 'selected' : '' ?>><?= e($sl['title']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="sm:col-span-1 flex items-end">
        <button type="submit" class="w-full rounded-xl bg-brand py-2 text-xs font-semibold text-white transition hover:bg-navy">
          Filter
        </button>
      </div>
    </form>
  </div>

  <!-- Projects Table -->
  <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-semibold">
          <tr>
            <th class="py-3.5 px-6">Ref No.</th>
            <th class="py-3.5 px-4">Client</th>
            <th class="py-3.5 px-4">Project Title</th>
            <th class="py-3.5 px-4">Service</th>
            <th class="py-3.5 px-4">Budget</th>
            <th class="py-3.5 px-4">Status</th>
            <th class="py-3.5 px-4">Submitted</th>
            <th class="py-3.5 px-6 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <?php if (!empty($projects)): ?>
            <?php foreach ($projects as $p): 
              $status_colors = [
                'Pending Review' => 'bg-amber-100 text-amber-800 border-amber-200',
                'In Progress'    => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                'Completed'      => 'bg-blue-100 text-blue-800 border-blue-200',
                'On Hold'        => 'bg-purple-100 text-purple-800 border-purple-200',
                'Cancelled'      => 'bg-red-100 text-red-800 border-red-200',
              ];
              $badge_class = $status_colors[$p['status']] ?? 'bg-slate-100 text-slate-800 border-slate-200';
            ?>
              <tr class="hover:bg-slate-50/80 transition">
                <td class="py-4 px-6 font-mono font-bold text-brand">
                  <a href="project-details.php?id=<?= $p['id'] ?>" class="hover:underline"><?= e($p['reference_no']) ?></a>
                </td>
                <td class="py-4 px-4">
                  <div class="font-bold text-ink"><?= e($p['client_name']) ?></div>
                  <div class="text-[11px] text-muted"><?= e($p['client_email']) ?></div>
                  <?php if (!empty($p['client_phone'])): ?>
                    <div class="text-[10px] text-slate-400"><?= e($p['client_phone']) ?></div>
                  <?php endif; ?>
                </td>
                <td class="py-4 px-4 max-w-[200px]">
                  <a href="project-details.php?id=<?= $p['id'] ?>" class="font-semibold text-ink hover:text-brand line-clamp-1 truncate block"><?= e($p['title']) ?></a>
                  <span class="text-[10px] text-muted"><?= e($p['company_name'] ?: 'Independent Client') ?></span>
                </td>
                <td class="py-4 px-4 text-slate-700"><?= e($p['service_name'] ?: 'Custom Scope') ?></td>
                <td class="py-4 px-4">
                  <span class="font-medium text-slate-800">
                    <?= e($p['budget_amount'] ? ($p['currency'] . ' ' . $p['budget_amount']) : ucfirst($p['budget_type'])) ?>
                  </span>
                  <?php if ($p['budget_flexible']): ?>
                    <span class="block text-[10px] text-emerald-600">Flexible</span>
                  <?php endif; ?>
                </td>
                <td class="py-4 px-4">
                  <!-- Quick Status Dropdown via POST form -->
                  <form action="projects.php" method="post" class="inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="update_status">
                    <input type="hidden" name="project_id" value="<?= $p['id'] ?>">
                    <select name="new_status" onchange="this.form.submit()" class="rounded-full px-2.5 py-0.5 text-[10px] font-bold border <?= $badge_class ?> focus:outline-none cursor-pointer">
                      <?php foreach ($statuses as $st): ?>
                        <option value="<?= $st ?>" <?= $p['status'] === $st ? 'selected' : '' ?>><?= $st ?></option>
                      <?php endforeach; ?>
                    </select>
                  </form>
                </td>
                <td class="py-4 px-4 text-slate-500 text-[11px]">
                  <?= date('M j, Y', strtotime($p['created_at'])) ?>
                </td>
                <td class="py-4 px-6 text-right space-x-2">
                  <a href="project-details.php?id=<?= $p['id'] ?>" class="rounded-lg bg-soft px-3 py-1.5 font-bold text-brand hover:bg-brand hover:text-white transition inline-block">
                    View Brief
                  </a>

                  <!-- Delete Button with Form -->
                  <form action="projects.php" method="post" class="inline" onsubmit="return confirm('Permanently delete project <?= e($p['reference_no']) ?>?');">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete_project">
                    <input type="hidden" name="project_id" value="<?= $p['id'] ?>">
                    <button type="submit" class="rounded-lg bg-red-50 p-1.5 text-red-600 hover:bg-red-600 hover:text-white transition" title="Delete Project">
                      <span class="material-symbols-outlined text-base">delete</span>
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="8" class="py-12 text-center text-muted">
                No matching projects found in database.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
      <div class="border-t border-slate-100 p-4 flex items-center justify-between text-xs text-muted">
        <span>Showing page <?= $page ?> of <?= $total_pages ?> (<?= $total_rows ?> total records)</span>
        <div class="flex gap-1.5">
          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="projects.php?p=<?= $i ?><?= !empty($search) ? '&q=' . urlencode($search) : '' ?><?= !empty($status_filter) ? '&status=' . urlencode($status_filter) : '' ?><?= $service_filter ? '&service_id=' . $service_filter : '' ?>" class="grid h-8 w-8 place-items-center rounded-lg font-bold <?= $i === $page ? 'bg-brand text-white' : 'bg-soft text-slate-700 hover:bg-slate-200' ?>">
              <?= $i ?>
            </a>
          <?php endfor; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>

</div>

<!-- Modal: Create Project Manually -->
<div id="createProjectModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
  <div class="w-full max-w-2xl rounded-3xl bg-white p-8 shadow-2xl max-h-[90vh] overflow-y-auto">
    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
      <h3 class="font-display text-lg font-bold text-ink">Create Project Manually</h3>
      <button onclick="document.getElementById('createProjectModal').classList.add('hidden')" class="text-slate-400 hover:text-ink">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>

    <form action="projects.php" method="post" class="mt-6 space-y-4 text-xs">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="create_manual">

      <div class="grid gap-4 sm:grid-cols-2">
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Client Name *</label>
          <input type="text" name="client_name" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Client Email *</label>
          <input type="email" name="client_email" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
      </div>

      <div class="grid gap-4 sm:grid-cols-2">
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Phone</label>
          <input type="text" name="client_phone" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Service</label>
          <select name="service_id" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-ink bg-white">
            <option value="">-- Select Service --</option>
            <?php foreach ($services_list as $sl): ?>
              <option value="<?= $sl['id'] ?>"><?= e($sl['title']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div>
        <label class="block font-bold text-muted uppercase mb-1">Project Title *</label>
        <input type="text" name="title" required placeholder="e.g. Corporate SaaS Redesign" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
      </div>

      <div>
        <label class="block font-bold text-muted uppercase mb-1">Description &amp; Brief *</label>
        <textarea name="description" rows="4" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink"></textarea>
      </div>

      <div class="grid gap-4 sm:grid-cols-3">
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Budget Model</label>
          <select name="budget_type" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-ink bg-white">
            <option value="fixed">Fixed</option>
            <option value="range">Range</option>
            <option value="flexible">Flexible</option>
          </select>
        </div>
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Amount</label>
          <input type="text" name="budget_amount" placeholder="e.g. 2500" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Initial Status</label>
          <select name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-ink bg-white">
            <option value="Pending Review">Pending Review</option>
            <option value="In Progress">In Progress</option>
            <option value="Completed">Completed</option>
          </select>
        </div>
      </div>

      <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
        <button type="button" onclick="document.getElementById('createProjectModal').classList.add('hidden')" class="rounded-xl bg-slate-100 px-5 py-2.5 font-bold text-slate-700 hover:bg-slate-200">
          Cancel
        </button>
        <button type="submit" class="btn-cta rounded-xl px-6 py-2.5 font-bold text-white shadow-md">
          Create &amp; Open
        </button>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
