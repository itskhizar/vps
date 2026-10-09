<?php
/**
 * VPS Digital Services - User Management Module
 * Enables super admins to add and manage users with designated userlevels (Admin Level = 9)
 */
$admin_title = "User & Admin Management";
require_once __DIR__ . '/header.php';

$db = get_db();
$edit_username = trim($_GET['edit'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        set_flash('error', 'CSRF verification failed.');
        header("Location: users.php");
        exit;
    }

    $action = trim($_POST['action'] ?? '');

    // 1. Create New User
    if ($action === 'create_user') {
        $new_user  = trim($_POST['username'] ?? '');
        $new_email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
        $new_pass  = trim($_POST['password'] ?? '');
        $new_level = (int)($_POST['userlevel'] ?? 9); // Defaults to Admin 9 as required

        if (empty($new_user) || !$new_email || empty($new_pass)) {
            set_flash('error', 'Username, valid email, and password are required.');
        } elseif (strlen($new_pass) < 6) {
            set_flash('error', 'Password must be at least 6 characters long.');
        } else {
            try {
                // Check if username taken
                $st_check = $db->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
                $st_check->execute([$new_user]);
                if ($st_check->fetchColumn() > 0) {
                    set_flash('error', "Username '{$new_user}' is already registered.");
                } else {
                    $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
                    $stmt_ins = $db->prepare("INSERT INTO users (username, password, userid, userlevel, email, timestamp, parent_directory) VALUES (?, ?, '0', ?, ?, UNIX_TIMESTAMP(), 'admin')");
                    $stmt_ins->execute([$new_user, $hashed, $new_level, $new_email]);
                    set_flash('success', "User '{$new_user}' successfully created with userlevel {$new_level}.");
                }
            } catch (Exception $e) {
                set_flash('error', 'Database error: ' . $e->getMessage());
            }
        }
        header("Location: users.php");
        exit;
    }

    // 2. Edit User / Change Level / Reset Password
    if ($action === 'edit_user') {
        $target_user = trim($_POST['target_username'] ?? '');
        $userlevel   = (int)($_POST['userlevel'] ?? 9);
        $email       = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
        $new_pass    = trim($_POST['new_password'] ?? '');

        if (!empty($target_user) && $email) {
            try {
                if (!empty($new_pass)) {
                    $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
                    $stmt_u = $db->prepare("UPDATE users SET userlevel = ?, email = ?, password = ? WHERE username = ?");
                    $stmt_u->execute([$userlevel, $email, $hashed, $target_user]);
                } else {
                    $stmt_u = $db->prepare("UPDATE users SET userlevel = ?, email = ? WHERE username = ?");
                    $stmt_u->execute([$userlevel, $email, $target_user]);
                }
                set_flash('success', "User '{$target_user}' updated.");
            } catch (Exception $e) {
                set_flash('error', 'Error updating user.');
            }
        }
        header("Location: users.php");
        exit;
    }

    // 3. Delete User (Safe: Prevent deleting current logged-in user)
    if ($action === 'delete_user') {
        $del_user = trim($_POST['target_username'] ?? '');
        if ($del_user === $admin_user) {
            set_flash('error', 'You cannot delete your own active administrative account.');
        } elseif (!empty($del_user)) {
            try {
                $db->prepare("DELETE FROM users WHERE username = ?")->execute([$del_user]);
                set_flash('success', "User '{$del_user}' deleted.");
            } catch (Exception $e) {
                set_flash('error', 'Error deleting user.');
            }
        }
        header("Location: users.php");
        exit;
    }
}

// Fetch all users
try {
    $users = $db->query("SELECT username, userlevel, email, timestamp FROM users ORDER BY userlevel DESC, username ASC")->fetchAll();
    $editing_user = null;
    if (!empty($edit_username)) {
        $stmt_eu = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt_eu->execute([$edit_username]);
        $editing_user = $stmt_eu->fetch();
    }
} catch (Exception $e) {
    $users = [];
    $editing_user = null;
}
?>

<div class="space-y-8">
  <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm flex items-center justify-between">
    <div>
      <h2 class="font-display text-lg font-bold text-ink">User &amp; Role Management</h2>
      <p class="text-xs text-muted">Manage staff accounts. Administrative privilege requires Userlevel 9.</p>
    </div>
    <button onclick="document.getElementById('userModal').classList.remove('hidden')" class="btn-cta inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-xs font-bold text-white shadow-md">
      <span class="material-symbols-outlined text-base">person_add</span> Add New User
    </button>
  </div>

  <!-- Users Table -->
  <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-semibold">
          <tr>
            <th class="py-3.5 px-6">Username</th>
            <th class="py-3.5 px-4">Role / Level</th>
            <th class="py-3.5 px-4">Email</th>
            <th class="py-3.5 px-4">Created</th>
            <th class="py-3.5 px-6 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <?php if (!empty($users)): ?>
            <?php foreach ($users as $u): 
              $isAdminRole = ((int)$u['userlevel'] === 9);
            ?>
              <tr class="hover:bg-slate-50/80 transition">
                <td class="py-4 px-6">
                  <div class="flex items-center gap-2.5 font-bold text-ink text-sm">
                    <span class="material-symbols-outlined text-base <?= $isAdminRole ? 'text-brand' : 'text-slate-400' ?>">
                      <?= $isAdminRole ? 'shield_person' : 'person' ?>
                    </span>
                    <?= e($u['username']) ?>
                    <?php if ($u['username'] === $admin_user): ?>
                      <span class="rounded bg-brand/10 text-brand px-1.5 py-0.5 text-[9px] font-extrabold uppercase">You</span>
                    <?php endif; ?>
                  </div>
                </td>
                <td class="py-4 px-4">
                  <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold border <?= $isAdminRole ? 'bg-brand/10 text-brand border-brand/30' : 'bg-slate-100 text-slate-600 border-slate-200' ?>">
                    Level <?= (int)$u['userlevel'] ?> <?= $isAdminRole ? '(Admin)' : '(Member)' ?>
                  </span>
                </td>
                <td class="py-4 px-4 text-slate-700"><?= e($u['email']) ?></td>
                <td class="py-4 px-4 text-slate-500 text-[11px]">
                  <?= date('M j, Y', $u['timestamp']) ?>
                </td>
                <td class="py-4 px-6 text-right space-x-2">
                  <a href="users.php?edit=<?= urlencode($u['username']) ?>" class="rounded-lg bg-soft px-2.5 py-1.5 font-bold text-brand hover:bg-brand hover:text-white transition">
                    Edit
                  </a>

                  <?php if ($u['username'] !== $admin_user): ?>
                    <form action="users.php" method="post" class="inline" onsubmit="return confirm('Delete user <?= e($u['username']) ?>?');">
                      <?= csrf_field() ?>
                      <input type="hidden" name="action" value="delete_user">
                      <input type="hidden" name="target_username" value="<?= e($u['username']) ?>">
                      <button type="submit" class="rounded-lg bg-red-50 p-1.5 text-red-600 hover:bg-red-600 hover:text-white transition" title="Delete">
                        <span class="material-symbols-outlined text-base">delete</span>
                      </button>
                    </form>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal: Add or Edit User -->
<div id="userModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm <?= $editing_user ? '' : 'hidden' ?> flex items-center justify-center p-4">
  <div class="w-full max-w-md rounded-3xl bg-white p-7 shadow-2xl text-xs">
    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
      <h3 class="font-display text-base font-bold text-ink">
        <?= $editing_user ? 'Edit User: ' . e($editing_user['username']) : 'Add New Staff / Admin' ?>
      </h3>
      <a href="users.php" class="text-slate-400 hover:text-ink"><span class="material-symbols-outlined">close</span></a>
    </div>

    <form action="users.php" method="post" class="mt-5 space-y-4">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="<?= $editing_user ? 'edit_user' : 'create_user' ?>">
      <?php if ($editing_user): ?>
        <input type="hidden" name="target_username" value="<?= e($editing_user['username']) ?>">
      <?php endif; ?>

      <?php if (!$editing_user): ?>
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Username *</label>
          <input type="text" name="username" required placeholder="e.g. khizar_admin" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
      <?php endif; ?>

      <div>
        <label class="block font-bold text-muted uppercase mb-1">Email Address *</label>
        <input type="email" name="email" required value="<?= e($editing_user['email'] ?? '') ?>" placeholder="admin@vprovideservices.com" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
      </div>

      <div>
        <label class="block font-bold text-muted uppercase mb-1">User Level *</label>
        <select name="userlevel" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-ink bg-white font-bold">
          <option value="9" <?= ((int)($editing_user['userlevel'] ?? 9) === 9) ? 'selected' : '' ?>>Level 9 - Super Admin (Full Privileges)</option>
          <option value="8" <?= ((int)($editing_user['userlevel'] ?? 9) === 8) ? 'selected' : '' ?>>Level 8 - Master Manager</option>
          <option value="1" <?= ((int)($editing_user['userlevel'] ?? 9) === 1) ? 'selected' : '' ?>>Level 1 - Staff Member</option>
        </select>
        <p class="text-[10px] text-muted mt-1">Admin dashboard access requires Level 9.</p>
      </div>

      <div>
        <label class="block font-bold text-muted uppercase mb-1">
          <?= $editing_user ? 'New Password (leave empty to keep current)' : 'Password *' ?>
        </label>
        <input type="password" <?= $editing_user ? '' : 'required' ?> name="<?= $editing_user ? 'new_password' : 'password' ?>" placeholder="••••••••" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
      </div>

      <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
        <a href="users.php" class="rounded-xl bg-slate-100 px-4 py-2 font-bold text-slate-700 hover:bg-slate-200">Cancel</a>
        <button type="submit" class="btn-cta rounded-xl px-5 py-2 font-bold text-white shadow-md">
          <?= $editing_user ? 'Save Changes' : 'Create User' ?>
        </button>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
