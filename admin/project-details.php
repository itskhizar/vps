<?php
/**
 * VPS Digital Services - Project Details View & Management
 */
$admin_title = "Project Details";
require_once __DIR__ . '/header.php';

$db = get_db();
$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    set_flash('error', 'Invalid project ID.');
    header("Location: projects.php");
    exit;
}

// Handle Status Update or Internal Note POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        set_flash('error', 'CSRF verification failed.');
        header("Location: project-details.php?id={$id}");
        exit;
    }

    $action = trim($_POST['action'] ?? '');

    // 1. Update Project Status with Audit History
    if ($action === 'change_status') {
        $new_status = trim($_POST['new_status'] ?? '');
        $comment    = trim($_POST['comment'] ?? '');

        if (!empty($new_status)) {
            try {
                $curr_status = $db->query("SELECT status FROM projects WHERE id = {$id}")->fetchColumn();
                if ($curr_status && $curr_status !== $new_status) {
                    $db->beginTransaction();

                    $stmt_u = $db->prepare("UPDATE projects SET status = ? WHERE id = ?");
                    $stmt_u->execute([$new_status, $id]);

                    $stmt_h = $db->prepare("INSERT INTO project_status_history (project_id, previous_status, new_status, changed_by, comment) VALUES (?, ?, ?, ?, ?)");
                    $stmt_h->execute([$id, $curr_status, $new_status, $admin_user, $comment ?: 'Status changed']);

                    $db->commit();
                    set_flash('success', "Status updated to '{$new_status}'.");
                }
            } catch (Exception $e) {
                $db->rollBack();
                set_flash('error', 'Failed to update status.');
            }
        }
        header("Location: project-details.php?id={$id}");
        exit;
    }

    // 2. Add Internal Admin Note
    if ($action === 'add_note') {
        $note = trim($_POST['note'] ?? '');
        if (!empty($note)) {
            try {
                $stmt_n = $db->prepare("INSERT INTO project_notes (project_id, admin_username, note) VALUES (?, ?, ?)");
                $stmt_n->execute([$id, $admin_user, $note]);
                set_flash('success', 'Internal note added.');
            } catch (Exception $e) {
                set_flash('error', 'Failed to save note.');
            }
        }
        header("Location: project-details.php?id={$id}");
        exit;
    }
}

// Load Project Record
try {
    $stmt_p = $db->prepare("SELECT * FROM projects WHERE id = ? LIMIT 1");
    $stmt_p->execute([$id]);
    $project = $stmt_p->fetch();

    if (!$project) {
        set_flash('error', 'Project not found.');
        header("Location: projects.php");
        exit;
    }

    // Load Attachments
    $stmt_att = $db->prepare("SELECT * FROM project_attachments WHERE project_id = ? ORDER BY id DESC");
    $stmt_att->execute([$id]);
    $attachments = $stmt_att->fetchAll();

    // Load Status History
    $stmt_hist = $db->prepare("SELECT * FROM project_status_history WHERE project_id = ? ORDER BY id DESC");
    $stmt_hist->execute([$id]);
    $history = $stmt_hist->fetchAll();

    // Load Internal Notes
    $stmt_notes = $db->prepare("SELECT * FROM project_notes WHERE project_id = ? ORDER BY id DESC");
    $stmt_notes->execute([$id]);
    $notes = $stmt_notes->fetchAll();

} catch (Exception $e) {
    error_log("Project details error: " . $e->getMessage());
    set_flash('error', 'Error querying project record.');
    header("Location: projects.php");
    exit;
}

$status_colors = [
    'Pending Review' => 'bg-amber-100 text-amber-800 border-amber-200',
    'In Progress'    => 'bg-emerald-100 text-emerald-800 border-emerald-200',
    'Completed'      => 'bg-blue-100 text-blue-800 border-blue-200',
    'On Hold'        => 'bg-purple-100 text-purple-800 border-purple-200',
    'Cancelled'      => 'bg-red-100 text-red-800 border-red-200',
];
$badge_class = $status_colors[$project['status']] ?? 'bg-slate-100 text-slate-800 border-slate-200';
?>

<div class="space-y-8">
  
  <!-- Header Bar -->
  <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <div class="flex items-center gap-3">
        <a href="projects.php" class="text-xs font-semibold text-muted hover:text-brand">&larr; Back to Projects</a>
        <span class="text-slate-300">|</span>
        <span class="font-mono text-sm font-bold text-brand"><?= e($project['reference_no']) ?></span>
      </div>
      <h2 class="mt-2 font-display text-2xl font-bold text-ink"><?= e($project['title']) ?></h2>
    </div>

    <!-- Current Status Badge & Action -->
    <div class="flex items-center gap-3">
      <span class="rounded-full px-4 py-1.5 text-xs font-bold border <?= $badge_class ?>">
        <?= e($project['status']) ?>
      </span>
      <button onclick="document.getElementById('changeStatusModal').classList.remove('hidden')" class="btn-cta rounded-xl px-4 py-2 text-xs font-bold text-white shadow-md">
        Update Status
      </button>
    </div>
  </div>

  <!-- Main Grid: Left Details & Right Side Panels -->
  <div class="grid gap-8 lg:grid-cols-12">
    
    <!-- Left Column (8 cols): Brief & Specs -->
    <div class="space-y-6 lg:col-span-8">
      
      <!-- Overview Card -->
      <div class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm">
        <h3 class="font-display text-base font-bold text-ink pb-3 border-b border-slate-100">Project Requirements &amp; Scope</h3>
        
        <div class="mt-5 space-y-4 text-xs leading-relaxed text-slate-700">
          <div>
            <span class="font-bold text-muted uppercase text-[11px] block mb-1">Detailed Description</span>
            <div class="rounded-xl bg-soft p-4 border border-slate-100 text-slate-800 whitespace-pre-wrap font-sans"><?= e($project['description']) ?></div>
          </div>

          <?php if (!empty($project['objectives'])): ?>
            <div>
              <span class="font-bold text-muted uppercase text-[11px] block mb-1">Business Objectives</span>
              <div class="rounded-xl bg-soft p-4 border border-slate-100 text-slate-800 whitespace-pre-wrap"><?= e($project['objectives']) ?></div>
            </div>
          <?php endif; ?>

          <?php if (!empty($project['scope_features'])): ?>
            <div>
              <span class="font-bold text-muted uppercase text-[11px] block mb-1">Scope &amp; Required Features</span>
              <div class="rounded-xl bg-soft p-4 border border-slate-100 text-slate-800 whitespace-pre-wrap"><?= e($project['scope_features']) ?></div>
            </div>
          <?php endif; ?>

          <?php if (!empty($project['additional_notes'])): ?>
            <div>
              <span class="font-bold text-muted uppercase text-[11px] block mb-1">Additional Client Notes</span>
              <p class="text-slate-600"><?= nl2br(e($project['additional_notes'])) ?></p>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Attachments Card -->
      <div class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm">
        <h3 class="font-display text-base font-bold text-ink pb-3 border-b border-slate-100">Requirement Documents &amp; Files</h3>
        
        <?php if (!empty($attachments)): ?>
          <ul class="mt-4 space-y-2.5">
            <?php foreach ($attachments as $att): ?>
              <li class="flex items-center justify-between rounded-xl border border-slate-100 p-3 bg-soft text-xs">
                <div class="flex items-center gap-3 truncate">
                  <span class="material-symbols-outlined text-brand text-2xl">description</span>
                  <div>
                    <span class="font-bold text-ink block truncate"><?= e($att['original_name']) ?></span>
                    <span class="text-[10px] text-muted"><?= round($att['file_size'] / 1024, 1) ?> KB • <?= e($att['mime_type']) ?></span>
                  </div>
                </div>
                <a href="../<?= e($att['file_path']) ?>" target="_blank" download class="rounded-lg bg-brand px-3 py-1.5 font-bold text-white hover:bg-navy transition text-[11px] shrink-0">
                  Download
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p class="mt-4 text-xs text-muted">No external files attached to this project brief.</p>
        <?php endif; ?>
      </div>

      <!-- Internal Admin Notes (Private) -->
      <div class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
          <div>
            <h3 class="font-display text-base font-bold text-ink">Private Internal Notes</h3>
            <p class="text-[11px] text-muted">Confidential admin notes (never visible to the client)</p>
          </div>
        </div>

        <!-- Add Note Form -->
        <form action="project-details.php?id=<?= $id ?>" method="post" class="mt-4">
          <?= csrf_field() ?>
          <input type="hidden" name="action" value="add_note">
          <textarea name="note" rows="3" required placeholder="Add technical notes, quote breakdown, or client call summary..." class="w-full rounded-xl border border-slate-200 p-3 text-xs text-ink focus:outline-none focus:border-brand"></textarea>
          <div class="mt-2 flex justify-end">
            <button type="submit" class="rounded-xl bg-brand px-4 py-2 text-xs font-bold text-white hover:bg-navy transition">
              Save Note
            </button>
          </div>
        </form>

        <!-- Notes List -->
        <?php if (!empty($notes)): ?>
          <div class="mt-6 space-y-3 border-t border-slate-100 pt-5">
            <?php foreach ($notes as $n): ?>
              <div class="rounded-xl border border-slate-100 bg-soft p-3.5 text-xs">
                <div class="flex items-center justify-between text-muted mb-1 text-[10px]">
                  <span class="font-bold text-ink">By <?= e($n['admin_username']) ?></span>
                  <span><?= date('M j, Y • g:i A', strtotime($n['created_at'])) ?></span>
                </div>
                <p class="text-slate-800 leading-relaxed"><?= nl2br(e($n['note'])) ?></p>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

    </div>

    <!-- Right Column (4 cols): Client Meta & History -->
    <div class="space-y-6 lg:col-span-4">
      
      <!-- Client Information Card -->
      <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm text-xs">
        <h3 class="font-display text-base font-bold text-ink pb-3 border-b border-slate-100 mb-4">Client Contact</h3>
        
        <ul class="space-y-3 divide-y divide-slate-100">
          <li class="pt-2">
            <span class="text-muted block text-[11px]">Full Name</span>
            <span class="font-bold text-ink text-sm"><?= e($project['client_name']) ?></span>
          </li>
          <li class="pt-2">
            <span class="text-muted block text-[11px]">Email</span>
            <a href="mailto:<?= e($project['client_email']) ?>" class="font-semibold text-brand underline truncate block"><?= e($project['client_email']) ?></a>
          </li>
          <?php if (!empty($project['client_phone'])): ?>
            <li class="pt-2">
              <span class="text-muted block text-[11px]">Phone</span>
              <a href="tel:<?= e($project['client_phone']) ?>" class="font-semibold text-ink"><?= e($project['client_phone']) ?></a>
            </li>
          <?php endif; ?>
          <?php if (!empty($project['client_whatsapp'])): ?>
            <li class="pt-2">
              <span class="text-muted block text-[11px]">WhatsApp</span>
              <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $project['client_whatsapp']) ?>" target="_blank" class="font-semibold text-green-600 underline"><?= e($project['client_whatsapp']) ?></a>
            </li>
          <?php endif; ?>
          <?php if (!empty($project['company_name'])): ?>
            <li class="pt-2">
              <span class="text-muted block text-[11px]">Company</span>
              <span class="font-semibold text-ink"><?= e($project['company_name']) ?></span>
            </li>
          <?php endif; ?>
          <?php if (!empty($project['country'])): ?>
            <li class="pt-2">
              <span class="text-muted block text-[11px]">Country</span>
              <span class="font-semibold text-ink"><?= e($project['country']) ?></span>
            </li>
          <?php endif; ?>
          <li class="pt-2">
            <span class="text-muted block text-[11px]">Preferred Channel</span>
            <span class="font-semibold uppercase text-brand"><?= e($project['preferred_contact']) ?></span>
          </li>
        </ul>
      </div>

      <!-- Scope & Commercial Terms -->
      <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm text-xs">
        <h3 class="font-display text-base font-bold text-ink pb-3 border-b border-slate-100 mb-4">Commercial Overview</h3>
        
        <ul class="space-y-3 divide-y divide-slate-100">
          <li class="pt-2">
            <span class="text-muted block text-[11px]">Selected Service</span>
            <span class="font-bold text-ink"><?= e($project['service_name'] ?: 'Custom Technical Project') ?></span>
          </li>
          <li class="pt-2">
            <span class="text-muted block text-[11px]">Budget Model</span>
            <span class="font-semibold text-ink capitalize"><?= e($project['budget_type']) ?></span>
          </li>
          <li class="pt-2">
            <span class="text-muted block text-[11px]">Estimated Amount</span>
            <span class="font-mono font-bold text-brand text-sm">
              <?= e($project['budget_amount'] ? ($project['currency'] . ' ' . $project['budget_amount']) : 'Open / Unspecified') ?>
            </span>
          </li>
          <li class="pt-2">
            <span class="text-muted block text-[11px]">Expected Timeline</span>
            <span class="font-semibold text-ink"><?= e($project['timeline'] ?: 'Flexible') ?></span>
          </li>
          <li class="pt-2">
            <span class="text-muted block text-[11px]">Intake Timestamp</span>
            <span class="text-slate-600"><?= date('M j, Y • g:i A', strtotime($project['created_at'])) ?></span>
          </li>
        </ul>
      </div>

      <!-- Status Change History (Chronological) -->
      <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm text-xs">
        <h3 class="font-display text-base font-bold text-ink pb-3 border-b border-slate-100 mb-4">Status History</h3>
        
        <?php if (!empty($history)): ?>
          <div class="space-y-3 relative before:absolute before:inset-0 before:left-2 before:w-0.5 before:bg-slate-200 pl-6">
            <?php foreach ($history as $h): ?>
              <div class="relative">
                <span class="absolute -left-6 top-1.5 h-2 w-2 rounded-full bg-brand ring-4 ring-white"></span>
                <p class="font-bold text-ink"><?= e($h['new_status']) ?></p>
                <p class="text-[10px] text-muted">
                  <?= e($h['changed_by']) ?> • <?= date('M j, Y • g:i A', strtotime($h['created_at'])) ?>
                </p>
                <?php if (!empty($h['comment'])): ?>
                  <p class="text-[11px] text-slate-600 mt-1"><?= e($h['comment']) ?></p>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p class="text-muted">No historical status records.</p>
        <?php endif; ?>
      </div>

    </div>

  </div>

</div>

<!-- Status Update Modal -->
<div id="changeStatusModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
  <div class="w-full max-w-md rounded-3xl bg-white p-7 shadow-2xl">
    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
      <h3 class="font-display text-base font-bold text-ink">Update Project Status</h3>
      <button onclick="document.getElementById('changeStatusModal').classList.add('hidden')" class="text-slate-400 hover:text-ink">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>

    <form action="project-details.php?id=<?= $id ?>" method="post" class="mt-5 space-y-4 text-xs">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="change_status">

      <div>
        <label class="block font-bold text-muted uppercase mb-1">New Status</label>
        <select name="new_status" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-ink bg-white font-semibold">
          <?php 
          $available_statuses = ['Pending Review', 'In Progress', 'Completed', 'On Hold', 'Cancelled'];
          foreach ($available_statuses as $st): 
          ?>
            <option value="<?= $st ?>" <?= $project['status'] === $st ? 'selected' : '' ?>><?= $st ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label class="block font-bold text-muted uppercase mb-1">Admin Comment / Reason</label>
        <textarea name="comment" rows="3" placeholder="e.g. Scoping finalized, deposit confirmed, project moving to In Progress..." class="w-full rounded-xl border border-slate-200 p-3 text-ink"></textarea>
      </div>

      <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
        <button type="button" onclick="document.getElementById('changeStatusModal').classList.add('hidden')" class="rounded-xl bg-slate-100 px-4 py-2 font-bold text-slate-700 hover:bg-slate-200">
          Cancel
        </button>
        <button type="submit" class="btn-cta rounded-xl px-5 py-2 font-bold text-white shadow-md">
          Save Status
        </button>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
