<?php
/**
 * VPS Digital Services - Contact Messages & Inquiries Management
 */
$admin_title = "Contact Inquiries Management";
require_once __DIR__ . '/header.php';

$db = get_db();
$search = trim($_GET['q'] ?? '');
$view_id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        set_flash('error', 'CSRF verification failed.');
        header("Location: messages.php");
        exit;
    }

    $action = trim($_POST['action'] ?? '');

    // 1. Mark Read / Update Status
    if ($action === 'update_message') {
        $m_id   = (int)($_POST['message_id'] ?? 0);
        $status = trim($_POST['status'] ?? 'New');
        if ($m_id > 0) {
            $stmt = $db->prepare("UPDATE contact_messages SET is_read = 1, status = ? WHERE id = ?");
            $stmt->execute([$status, $m_id]);
            set_flash('success', "Message status updated to '{$status}'.");
        }
        header("Location: messages.php?id={$m_id}");
        exit;
    }

    // 2. Delete Message
    if ($action === 'delete_message') {
        $m_id = (int)($_POST['message_id'] ?? 0);
        if ($m_id > 0) {
            $db->prepare("DELETE FROM contact_messages WHERE id = ?")->execute([$m_id]);
            set_flash('success', "Message deleted.");
        }
        header("Location: messages.php");
        exit;
    }
}

// Build query
$where = ["1=1"];
$params = [];
if (!empty($search)) {
    $where[] = "(name LIKE ? OR email LIKE ? OR subject LIKE ? OR message LIKE ?)";
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
}
$where_sql = implode(' AND ', $where);

try {
    $stmt_m = $db->prepare("SELECT * FROM contact_messages WHERE {$where_sql} ORDER BY id DESC");
    $stmt_m->execute($params);
    $messages = $stmt_m->fetchAll();

    $active_message = null;
    if ($view_id > 0) {
        $stmt_v = $db->prepare("SELECT * FROM contact_messages WHERE id = ?");
        $stmt_v->execute([$view_id]);
        $active_message = $stmt_v->fetch();

        // Mark as read automatically when opened
        if ($active_message && !$active_message['is_read']) {
            $db->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?")->execute([$view_id]);
            $active_message['is_read'] = 1;
        }
    }
} catch (Exception $e) {
    $messages = [];
    $active_message = null;
}
?>

<div class="grid gap-8 lg:grid-cols-12">

  <!-- Left: Messages Inbox List (5 cols) -->
  <div class="space-y-4 lg:col-span-5">
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="flex items-center justify-between pb-3 border-b border-slate-100">
        <h2 class="font-display text-base font-bold text-ink">Inquiries Inbox</h2>
        <span class="text-xs text-muted"><?= count($messages) ?> Messages</span>
      </div>

      <form action="messages.php" method="get" class="mt-4">
        <input type="text" name="q" value="<?= e($search) ?>" placeholder="Search messages, names, emails..." class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs text-ink focus:outline-none focus:border-brand">
      </form>
    </div>

    <div class="space-y-2.5">
      <?php if (!empty($messages)): ?>
        <?php foreach ($messages as $m): 
          $isSel = ($active_message && $active_message['id'] == $m['id']);
        ?>
          <a href="messages.php?id=<?= $m['id'] ?><?= !empty($search) ? '&q=' . urlencode($search) : '' ?>" class="block rounded-2xl border p-4 transition <?= $isSel ? 'border-brand bg-brand/5 shadow-md' : 'border-slate-200 bg-white hover:bg-slate-50' ?>">
            <div class="flex items-center justify-between text-xs">
              <span class="font-bold text-ink flex items-center gap-2 truncate">
                <?php if (!$m['is_read']): ?>
                  <span class="h-2 w-2 rounded-full bg-brand shrink-0"></span>
                <?php endif; ?>
                <?= e($m['name']) ?>
              </span>
              <span class="text-[10px] text-muted shrink-0"><?= relative_time($m['created_at']) ?></span>
            </div>
            <p class="font-semibold text-xs text-slate-800 mt-1 truncate"><?= e($m['subject']) ?></p>
            <p class="text-[11px] text-muted mt-1 line-clamp-1"><?= e($m['message']) ?></p>
          </a>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center text-xs text-muted">
          No inquiries found.
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Right: Message Detail View (7 cols) -->
  <div class="lg:col-span-7">
    <?php if ($active_message): ?>
      <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm space-y-6">
        
        <div class="flex items-start justify-between pb-6 border-b border-slate-100">
          <div>
            <span class="text-xs font-bold text-brand uppercase"><?= e($active_message['status']) ?></span>
            <h3 class="mt-1 font-display text-xl font-bold text-ink"><?= e($active_message['subject']) ?></h3>
            <p class="text-xs text-muted mt-1">Received <?= date('F j, Y • g:i A', strtotime($active_message['created_at'])) ?></p>
          </div>

          <!-- Status Form -->
          <form action="messages.php" method="post" class="flex items-center gap-2 text-xs">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="update_message">
            <input type="hidden" name="message_id" value="<?= $active_message['id'] ?>">
            <select name="status" onchange="this.form.submit()" class="rounded-xl border border-slate-200 px-3 py-1.5 font-bold text-ink bg-soft focus:outline-none">
              <?php foreach (['New', 'In Progress', 'Replied', 'Closed'] as $st): ?>
                <option value="<?= $st ?>" <?= $active_message['status'] === $st ? 'selected' : '' ?>><?= $st ?></option>
              <?php endforeach; ?>
            </select>
          </form>
        </div>

        <!-- Sender Meta -->
        <div class="rounded-2xl bg-soft p-5 border border-slate-100 text-xs grid gap-3 sm:grid-cols-2">
          <div>
            <span class="text-muted block text-[11px]">From</span>
            <span class="font-bold text-ink text-sm"><?= e($active_message['name']) ?></span>
          </div>
          <div>
            <span class="text-muted block text-[11px]">Email</span>
            <a href="mailto:<?= e($active_message['email']) ?>" class="font-semibold text-brand underline"><?= e($active_message['email']) ?></a>
          </div>
          <?php if (!empty($active_message['phone'])): ?>
            <div>
              <span class="text-muted block text-[11px]">Phone</span>
              <a href="tel:<?= e($active_message['phone']) ?>" class="font-semibold text-ink"><?= e($active_message['phone']) ?></a>
            </div>
          <?php endif; ?>
          <?php if (!empty($active_message['ip_address'])): ?>
            <div>
              <span class="text-muted block text-[11px]">Sender IP</span>
              <span class="font-mono text-slate-500"><?= e($active_message['ip_address']) ?></span>
            </div>
          <?php endif; ?>
        </div>

        <!-- Body -->
        <div class="text-xs text-slate-800 leading-relaxed whitespace-pre-wrap rounded-2xl border border-slate-100 p-6 bg-white min-h-[160px]">
          <?= e($active_message['message']) ?>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between pt-4 border-t border-slate-100 text-xs">
          <a href="mailto:<?= e($active_message['email']) ?>?subject=<?= urlencode("Re: " . $active_message['subject']) ?>" class="btn-cta inline-flex items-center gap-2 rounded-xl px-5 py-2.5 font-bold text-white shadow-md">
            <span class="material-symbols-outlined text-base">reply</span> Reply via Email
          </a>

          <form action="messages.php" method="post" onsubmit="return confirm('Delete this message?');">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="delete_message">
            <input type="hidden" name="message_id" value="<?= $active_message['id'] ?>">
            <button type="submit" class="rounded-xl bg-red-50 px-4 py-2 text-red-600 font-bold hover:bg-red-600 hover:text-white transition">
              Delete Message
            </button>
          </form>
        </div>

      </div>
    <?php else: ?>
      <div class="rounded-3xl border border-slate-200 bg-white p-16 text-center text-muted">
        <span class="material-symbols-outlined text-5xl mb-3 text-slate-300">mail</span>
        <h3 class="font-display text-base font-bold text-ink">Select a Message</h3>
        <p class="text-xs mt-1">Choose a message from the inbox on the left to read full details.</p>
      </div>
    <?php endif; ?>
  </div>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>
