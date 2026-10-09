<?php
/**
 * VPS Digital Services - Admin Dashboard Overview
 * Computes live metrics from MySQL database records
 */
$admin_title = "Executive Overview";
require_once __DIR__ . '/header.php';

try {
    $db = get_db();

    // 1. Project Metrics
    $total_projects     = (int)$db->query("SELECT COUNT(*) FROM projects")->fetchColumn();
    $pending_projects   = (int)$db->query("SELECT COUNT(*) FROM projects WHERE status = 'Pending Review'")->fetchColumn();
    $inprogress_projects= (int)$db->query("SELECT COUNT(*) FROM projects WHERE status = 'In Progress'")->fetchColumn();
    $completed_projects = (int)$db->query("SELECT COUNT(*) FROM projects WHERE status = 'Completed'")->fetchColumn();
    $onhold_projects    = (int)$db->query("SELECT COUNT(*) FROM projects WHERE status = 'On Hold'")->fetchColumn();
    $cancelled_projects = (int)$db->query("SELECT COUNT(*) FROM projects WHERE status = 'Cancelled'")->fetchColumn();

    // 2. Content & Team Metrics
    $active_services    = (int)$db->query("SELECT COUNT(*) FROM services WHERE is_active = 1")->fetchColumn();
    $portfolio_count    = (int)$db->query("SELECT COUNT(*) FROM portfolio WHERE is_published = 1")->fetchColumn();
    $team_count         = (int)$db->query("SELECT COUNT(*) FROM team WHERE is_active = 1")->fetchColumn();
    $blog_count         = (int)$db->query("SELECT COUNT(*) FROM blog_posts WHERE is_published = 1")->fetchColumn();
    $unread_msgs        = (int)$db->query("SELECT COUNT(*) FROM contact_messages WHERE is_read = 0")->fetchColumn();

    // 3. Recent Project Inquiries (Top 5)
    $stmt_recent_proj = $db->query("SELECT * FROM projects ORDER BY id DESC LIMIT 5");
    $recent_projects = $stmt_recent_proj->fetchAll();

    // 4. Recent Contact Inquiries (Top 5)
    $stmt_recent_msgs = $db->query("SELECT * FROM contact_messages ORDER BY id DESC LIMIT 5");
    $recent_messages = $stmt_recent_msgs->fetchAll();

    // 5. Recent Status Activities
    $stmt_recent_hist = $db->query("SELECT h.*, p.reference_no, p.title as project_title FROM project_status_history h JOIN projects p ON h.project_id = p.id ORDER BY h.id DESC LIMIT 5");
    $recent_history = $stmt_recent_hist->fetchAll();

} catch (Exception $e) {
    error_log("Dashboard query error: " . $e->getMessage());
    $total_projects = 0; $pending_projects = 0; $inprogress_projects = 0;
    $completed_projects = 0; $onhold_projects = 0; $cancelled_projects = 0;
    $active_services = 0; $portfolio_count = 0; $team_count = 0; $blog_count = 0; $unread_msgs = 0;
    $recent_projects = []; $recent_messages = []; $recent_history = [];
}
?>

<div class="space-y-8">

  <!-- Top Metric Cards Grid -->
  <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
    
    <!-- Total Projects -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
      <div class="flex items-center justify-between">
        <span class="text-xs font-bold uppercase tracking-wider text-muted">Total Projects</span>
        <span class="grid h-10 w-10 place-items-center rounded-xl bg-brand/10 text-brand">
          <span class="material-symbols-outlined">assignment</span>
        </span>
      </div>
      <p class="mt-4 font-display text-3xl font-extrabold text-ink"><?= $total_projects ?></p>
      <div class="mt-2 flex items-center justify-between text-xs text-muted">
        <span>Inquiries &amp; contracts</span>
        <a href="projects.php" class="font-bold text-brand hover:underline">View all &rarr;</a>
      </div>
    </div>

    <!-- Pending Review -->
    <div class="rounded-2xl border border-cta/30 bg-white p-6 shadow-sm">
      <div class="flex items-center justify-between">
        <span class="text-xs font-bold uppercase tracking-wider text-cta">Pending Review</span>
        <span class="grid h-10 w-10 place-items-center rounded-xl bg-cta/10 text-cta">
          <span class="material-symbols-outlined">pending_actions</span>
        </span>
      </div>
      <p class="mt-4 font-display text-3xl font-extrabold text-cta"><?= $pending_projects ?></p>
      <div class="mt-2 flex items-center justify-between text-xs text-muted">
        <span>Requires immediate triage</span>
        <a href="projects.php?status=Pending+Review" class="font-bold text-cta hover:underline">Review &rarr;</a>
      </div>
    </div>

    <!-- In Progress -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
      <div class="flex items-center justify-between">
        <span class="text-xs font-bold uppercase tracking-wider text-muted">In Progress</span>
        <span class="grid h-10 w-10 place-items-center rounded-xl bg-blue-50 text-brand">
          <span class="material-symbols-outlined">engineering</span>
        </span>
      </div>
      <p class="mt-4 font-display text-3xl font-extrabold text-ink"><?= $inprogress_projects ?></p>
      <div class="mt-2 flex items-center justify-between text-xs text-muted">
        <span>Active development</span>
        <a href="projects.php?status=In+Progress" class="font-bold text-brand hover:underline">Track &rarr;</a>
      </div>
    </div>

    <!-- New Messages -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
      <div class="flex items-center justify-between">
        <span class="text-xs font-bold uppercase tracking-wider text-muted">New Inquiries</span>
        <span class="grid h-10 w-10 place-items-center rounded-xl bg-cyan/10 text-cyan">
          <span class="material-symbols-outlined">mail</span>
        </span>
      </div>
      <p class="mt-4 font-display text-3xl font-extrabold text-ink"><?= $unread_msgs ?></p>
      <div class="mt-2 flex items-center justify-between text-xs text-muted">
        <span>Unread contact messages</span>
        <a href="messages.php" class="font-bold text-cyan hover:underline">Open inbox &rarr;</a>
      </div>
    </div>

  </div>

  <!-- Lifecycle Status Distribution Bar -->
  <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    <div class="flex items-center justify-between mb-4">
      <h3 class="font-display text-base font-bold text-ink">Project Lifecycle Distribution</h3>
      <span class="text-xs text-muted"><?= $total_projects ?> Total Projects</span>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-6 gap-3 text-center">
      <div class="rounded-xl bg-amber-50 p-3 border border-amber-200">
        <p class="text-[11px] font-semibold text-amber-800">Pending Review</p>
        <p class="font-display text-xl font-bold text-amber-900 mt-1"><?= $pending_projects ?></p>
      </div>
      <div class="rounded-xl bg-emerald-50 p-3 border border-emerald-200">
        <p class="text-[11px] font-semibold text-emerald-800">In Progress</p>
        <p class="font-display text-xl font-bold text-emerald-900 mt-1"><?= $inprogress_projects ?></p>
      </div>
      <div class="rounded-xl bg-blue-50 p-3 border border-blue-200">
        <p class="text-[11px] font-semibold text-blue-800">Completed</p>
        <p class="font-display text-xl font-bold text-blue-900 mt-1"><?= $completed_projects ?></p>
      </div>
      <div class="rounded-xl bg-purple-50 p-3 border border-purple-200">
        <p class="text-[11px] font-semibold text-purple-800">On Hold</p>
        <p class="font-display text-xl font-bold text-purple-900 mt-1"><?= $onhold_projects ?></p>
      </div>
      <div class="rounded-xl bg-red-50 p-3 border border-red-200">
        <p class="text-[11px] font-semibold text-red-800">Cancelled</p>
        <p class="font-display text-xl font-bold text-red-900 mt-1"><?= $cancelled_projects ?></p>
      </div>
      <div class="rounded-xl bg-slate-50 p-3 border border-slate-200">
        <p class="text-[11px] font-semibold text-slate-700">Active Services</p>
        <p class="font-display text-xl font-bold text-ink mt-1"><?= $active_services ?></p>
      </div>
    </div>
  </div>

  <!-- Content & Resources Quick Summary Strip -->
  <div class="grid gap-5 sm:grid-cols-4">
    <div class="rounded-2xl border border-slate-200 bg-white p-5 flex items-center gap-4">
      <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-soft text-brand">
        <span class="material-symbols-outlined">layers</span>
      </span>
      <div>
        <span class="text-xs text-muted block">Live Services</span>
        <span class="font-display text-xl font-bold text-ink"><?= $active_services ?></span>
        <a href="services.php" class="text-[11px] text-brand block mt-0.5 hover:underline">Manage services &rarr;</a>
      </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5 flex items-center gap-4">
      <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-soft text-cyan">
        <span class="material-symbols-outlined">palette</span>
      </span>
      <div>
        <span class="text-xs text-muted block">Published Studies</span>
        <span class="font-display text-xl font-bold text-ink"><?= $portfolio_count ?></span>
        <a href="portfolio.php" class="text-[11px] text-brand block mt-0.5 hover:underline">Manage portfolio &rarr;</a>
      </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5 flex items-center gap-4">
      <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-soft text-purple-600">
        <span class="material-symbols-outlined">groups</span>
      </span>
      <div>
        <span class="text-xs text-muted block">Active Team</span>
        <span class="font-display text-xl font-bold text-ink"><?= $team_count ?></span>
        <a href="team.php" class="text-[11px] text-brand block mt-0.5 hover:underline">Manage profiles &rarr;</a>
      </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5 flex items-center gap-4">
      <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-soft text-emerald-600">
        <span class="material-symbols-outlined">article</span>
      </span>
      <div>
        <span class="text-xs text-muted block">Blog Articles</span>
        <span class="font-display text-xl font-bold text-ink"><?= $blog_count ?></span>
        <a href="blog.php" class="text-[11px] text-brand block mt-0.5 hover:underline">Manage posts &rarr;</a>
      </div>
    </div>
  </div>

  <!-- Tables Section: Recent Inquiries & Recent Messages -->
  <div class="grid gap-8 lg:grid-cols-12">
    
    <!-- Left: Recent Project Submissions (8 Cols) -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-8 flex flex-col justify-between">
      <div>
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
          <div>
            <h3 class="font-display text-base font-bold text-ink">Recently Submitted Projects</h3>
            <p class="text-xs text-muted">Latest client inquiries submitted through the intake portal</p>
          </div>
          <a href="projects.php" class="rounded-lg bg-soft px-3 py-1.5 text-xs font-semibold text-brand hover:bg-brand hover:text-white transition">
            View All Projects
          </a>
        </div>

        <?php if (!empty($recent_projects)): ?>
          <div class="overflow-x-auto mt-4">
            <table class="w-full text-left text-xs">
              <thead>
                <tr class="text-muted border-b border-slate-100">
                  <th class="py-3 font-semibold">Reference</th>
                  <th class="py-3 font-semibold">Client</th>
                  <th class="py-3 font-semibold">Service</th>
                  <th class="py-3 font-semibold">Budget</th>
                  <th class="py-3 font-semibold">Status</th>
                  <th class="py-3 font-semibold text-right">Action</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <?php foreach ($recent_projects as $rp): 
                  $status_colors = [
                    'Pending Review' => 'bg-amber-100 text-amber-800 border-amber-200',
                    'In Progress'    => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                    'Completed'      => 'bg-blue-100 text-blue-800 border-blue-200',
                    'On Hold'        => 'bg-purple-100 text-purple-800 border-purple-200',
                    'Cancelled'      => 'bg-red-100 text-red-800 border-red-200',
                  ];
                  $badge_class = $status_colors[$rp['status']] ?? 'bg-slate-100 text-slate-800 border-slate-200';
                ?>
                  <tr class="hover:bg-slate-50/80 transition">
                    <td class="py-3 font-mono font-bold text-brand">
                      <a href="project-details.php?id=<?= $rp['id'] ?>" class="hover:underline"><?= e($rp['reference_no']) ?></a>
                    </td>
                    <td class="py-3 font-medium text-ink">
                      <?= e($rp['client_name']) ?>
                      <span class="block text-[10px] text-muted"><?= e($rp['client_email']) ?></span>
                    </td>
                    <td class="py-3 text-slate-700"><?= e($rp['service_name'] ?: 'Custom') ?></td>
                    <td class="py-3 text-slate-700">
                      <?= e($rp['budget_amount'] ? ($rp['currency'] . ' ' . $rp['budget_amount']) : ucfirst($rp['budget_type'])) ?>
                    </td>
                    <td class="py-3">
                      <span class="inline-block rounded-full px-2.5 py-0.5 text-[10px] font-bold border <?= $badge_class ?>">
                        <?= e($rp['status']) ?>
                      </span>
                    </td>
                    <td class="py-3 text-right">
                      <a href="project-details.php?id=<?= $rp['id'] ?>" class="rounded-lg bg-soft px-2.5 py-1 text-xs font-semibold text-brand hover:bg-brand hover:text-white transition">
                        Details
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <div class="py-12 text-center text-muted">
            <span class="material-symbols-outlined text-4xl mb-2 text-slate-300">inbox</span>
            <p class="text-sm">No project inquiries logged yet.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Right: Recent Messages & Activity (4 Cols) -->
    <div class="space-y-6 lg:col-span-4">
      
      <!-- Recent Contact Messages -->
      <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
          <h3 class="font-display text-base font-bold text-ink">Recent Contact Messages</h3>
          <a href="messages.php" class="text-xs font-bold text-brand hover:underline">Inbox &rarr;</a>
        </div>

        <?php if (!empty($recent_messages)): ?>
          <div class="space-y-3">
            <?php foreach ($recent_messages as $rm): ?>
              <a href="messages.php?id=<?= $rm['id'] ?>" class="block rounded-xl border border-slate-100 p-3 hover:bg-soft transition">
                <div class="flex items-center justify-between text-[11px]">
                  <span class="font-bold text-ink truncate"><?= e($rm['name']) ?></span>
                  <span class="text-muted"><?= relative_time($rm['created_at']) ?></span>
                </div>
                <p class="font-semibold text-xs text-brand mt-0.5 truncate"><?= e($rm['subject']) ?></p>
                <p class="text-[11px] text-muted line-clamp-1 mt-0.5"><?= e($rm['message']) ?></p>
              </a>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p class="text-xs text-muted text-center py-6">No contact messages received.</p>
        <?php endif; ?>
      </div>

      <!-- Recent Status Activity Log -->
      <?php if (!empty($recent_history)): ?>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
          <h3 class="font-display text-base font-bold text-ink pb-3 border-b border-slate-100 mb-4">Status Activity History</h3>
          <ul class="space-y-3 text-xs">
            <?php foreach ($recent_history as $rh): ?>
              <li class="flex items-start gap-2.5">
                <span class="mt-1 h-2 w-2 rounded-full bg-brand shrink-0"></span>
                <div>
                  <span class="font-mono font-bold text-brand"><?= e($rh['reference_no']) ?></span> &rarr;
                  <span class="font-semibold text-ink"><?= e($rh['new_status']) ?></span>
                  <span class="block text-[10px] text-muted">By <?= e($rh['changed_by']) ?> • <?= relative_time($rh['created_at']) ?></span>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

    </div>

  </div>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>
