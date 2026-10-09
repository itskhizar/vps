<?php
/**
 * VPS Digital Services - Blog CRUD Management
 */
$admin_title = "Blog & Insights Management";
require_once __DIR__ . '/header.php';

$db = get_db();
$edit_id = (int)($_GET['edit'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        set_flash('error', 'CSRF verification failed.');
        header("Location: blog.php");
        exit;
    }

    $action = trim($_POST['action'] ?? '');

    // 1. Save Blog Post
    if ($action === 'save_post') {
        $p_id         = (int)($_POST['post_id'] ?? 0);
        $title        = trim($_POST['title'] ?? '');
        $slug         = slugify($_POST['slug'] ?? $title);
        $cat_name     = trim($_POST['category_name'] ?? 'General');
        $excerpt      = trim($_POST['excerpt'] ?? '');
        $content      = trim($_POST['content'] ?? '');
        $author       = trim($_POST['author'] ?? $admin_user);
        $reading_time = trim($_POST['reading_time'] ?? '5 min read');
        $icon         = trim($_POST['icon'] ?? 'article');
        $is_pub       = isset($_POST['is_published']) ? 1 : 0;

        // Image upload
        $featured_img = null;
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $up = safe_upload($_FILES['featured_image'], 'blog', ['jpg', 'jpeg', 'png', 'webp']);
            if ($up['success']) {
                $featured_img = $up['relative_path'];
            }
        }

        if (empty($title) || empty($content)) {
            set_flash('error', 'Post title and content are required.');
        } else {
            try {
                if ($p_id > 0) {
                    if ($featured_img) {
                        $stmt = $db->prepare("UPDATE blog_posts SET title = ?, slug = ?, category_name = ?, excerpt = ?, content = ?, author = ?, reading_time = ?, icon = ?, is_published = ?, featured_image = ? WHERE id = ?");
                        $stmt->execute([$title, $slug, $cat_name, $excerpt, $content, $author, $reading_time, $icon, $is_pub, $featured_img, $p_id]);
                    } else {
                        $stmt = $db->prepare("UPDATE blog_posts SET title = ?, slug = ?, category_name = ?, excerpt = ?, content = ?, author = ?, reading_time = ?, icon = ?, is_published = ? WHERE id = ?");
                        $stmt->execute([$title, $slug, $cat_name, $excerpt, $content, $author, $reading_time, $icon, $is_pub, $p_id]);
                    }
                    set_flash('success', "Post '{$title}' updated.");
                } else {
                    $stmt = $db->prepare("INSERT INTO blog_posts (title, slug, category_name, excerpt, content, author, reading_time, icon, is_published, featured_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$title, $slug, $cat_name, $excerpt, $content, $author, $reading_time, $icon, $is_pub, $featured_img]);
                    set_flash('success', "Post '{$title}' published.");
                }
            } catch (Exception $e) {
                set_flash('error', 'Database error: ' . $e->getMessage());
            }
        }
        header("Location: blog.php");
        exit;
    }

    // 2. Toggle Published
    if ($action === 'toggle_published') {
        $p_id = (int)($_POST['post_id'] ?? 0);
        if ($p_id > 0) {
            $curr = (int)$db->query("SELECT is_published FROM blog_posts WHERE id = {$p_id}")->fetchColumn();
            $new = $curr ? 0 : 1;
            $db->prepare("UPDATE blog_posts SET is_published = ? WHERE id = ?")->execute([$new, $p_id]);
            set_flash('success', "Post status updated.");
        }
        header("Location: blog.php");
        exit;
    }

    // 3. Delete Post
    if ($action === 'delete_post') {
        $p_id = (int)($_POST['post_id'] ?? 0);
        if ($p_id > 0) {
            $db->prepare("DELETE FROM blog_posts WHERE id = ?")->execute([$p_id]);
            set_flash('success', "Post deleted.");
        }
        header("Location: blog.php");
        exit;
    }
}

// Fetch posts & categories
try {
    $posts = $db->query("SELECT * FROM blog_posts ORDER BY id DESC")->fetchAll();
    $categories = $db->query("SELECT * FROM blog_categories ORDER BY name ASC")->fetchAll();
    $editing_post = null;
    if ($edit_id > 0) {
        $stmt_ed = $db->prepare("SELECT * FROM blog_posts WHERE id = ?");
        $stmt_ed->execute([$edit_id]);
        $editing_post = $stmt_ed->fetch();
    }
} catch (Exception $e) {
    $posts = [];
    $categories = [];
    $editing_post = null;
}
?>

<div class="space-y-8">
  <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm flex items-center justify-between">
    <div>
      <h2 class="font-display text-lg font-bold text-ink">Blog Articles &amp; Insights</h2>
      <p class="text-xs text-muted">Publish technical guides, industry analyses, and company announcements.</p>
    </div>
    <button onclick="document.getElementById('blogModal').classList.remove('hidden')" class="btn-cta inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-xs font-bold text-white shadow-md">
      <span class="material-symbols-outlined text-base">add</span> Write New Article
    </button>
  </div>

  <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-semibold">
          <tr>
            <th class="py-3.5 px-6">Article</th>
            <th class="py-3.5 px-4">Category</th>
            <th class="py-3.5 px-4">Author</th>
            <th class="py-3.5 px-4">Published Date</th>
            <th class="py-3.5 px-4">Status</th>
            <th class="py-3.5 px-6 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $b): ?>
              <tr class="hover:bg-slate-50/80 transition">
                <td class="py-4 px-6 max-w-[240px]">
                  <div class="font-bold text-ink text-sm truncate"><?= e($b['title']) ?></div>
                  <div class="text-[10px] text-muted font-mono truncate"><?= e($b['slug']) ?></div>
                </td>
                <td class="py-4 px-4 font-semibold text-brand"><?= e($b['category_name']) ?></td>
                <td class="py-4 px-4 text-slate-700"><?= e($b['author']) ?></td>
                <td class="py-4 px-4 text-slate-500"><?= date('M j, Y', strtotime($b['published_at'])) ?></td>
                <td class="py-4 px-4">
                  <form action="blog.php" method="post" class="inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="toggle_published">
                    <input type="hidden" name="post_id" value="<?= $b['id'] ?>">
                    <button type="submit" class="rounded-full px-2.5 py-0.5 text-[10px] font-bold border transition <?= $b['is_published'] ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200' ?>">
                      <?= $b['is_published'] ? 'Published' : 'Draft' ?>
                    </button>
                  </form>
                </td>
                <td class="py-4 px-6 text-right space-x-2">
                  <a href="blog.php?edit=<?= $b['id'] ?>" class="rounded-lg bg-soft px-2.5 py-1.5 font-bold text-brand hover:bg-brand hover:text-white transition">
                    Edit
                  </a>
                  <a href="../blog-post.php?slug=<?= urlencode($b['slug']) ?>" target="_blank" class="rounded-lg bg-soft px-2.5 py-1.5 text-slate-600 hover:text-ink transition">
                    <span class="material-symbols-outlined text-sm">visibility</span>
                  </a>
                  <form action="blog.php" method="post" class="inline" onsubmit="return confirm('Delete article?');">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete_post">
                    <input type="hidden" name="post_id" value="<?= $b['id'] ?>">
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
<div id="blogModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm <?= $editing_post ? '' : 'hidden' ?> flex items-center justify-center p-4">
  <div class="w-full max-w-3xl rounded-3xl bg-white p-8 shadow-2xl max-h-[90vh] overflow-y-auto text-xs">
    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
      <h3 class="font-display text-lg font-bold text-ink"><?= $editing_post ? 'Edit Article' : 'Write New Article' ?></h3>
      <a href="blog.php" class="text-slate-400 hover:text-ink"><span class="material-symbols-outlined">close</span></a>
    </div>

    <form action="blog.php" method="post" enctype="multipart/form-data" class="mt-6 space-y-4">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="save_post">
      <input type="hidden" name="post_id" value="<?= $editing_post['id'] ?? 0 ?>">

      <div class="grid gap-4 sm:grid-cols-2">
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Title *</label>
          <input type="text" name="title" required value="<?= e($editing_post['title'] ?? '') ?>" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Category</label>
          <input type="text" name="category_name" list="catList" value="<?= e($editing_post['category_name'] ?? 'General') ?>" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
          <datalist id="catList">
            <?php foreach ($categories as $c): ?>
              <option value="<?= e($c['name']) ?>"></option>
            <?php endforeach; ?>
          </datalist>
        </div>
      </div>

      <div class="grid gap-4 sm:grid-cols-3">
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Author</label>
          <input type="text" name="author" value="<?= e($editing_post['author'] ?? $admin_user) ?>" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Reading Time</label>
          <input type="text" name="reading_time" value="<?= e($editing_post['reading_time'] ?? '5 min read') ?>" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Icon</label>
          <input type="text" name="icon" value="<?= e($editing_post['icon'] ?? 'article') ?>" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
      </div>

      <div>
        <label class="block font-bold text-muted uppercase mb-1">Excerpt / Summary</label>
        <textarea name="excerpt" rows="2" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink"><?= e($editing_post['excerpt'] ?? '') ?></textarea>
      </div>

      <div>
        <label class="block font-bold text-muted uppercase mb-1">Article Content (HTML allowed) *</label>
        <textarea name="content" rows="8" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink font-mono"><?= e($editing_post['content'] ?? '') ?></textarea>
      </div>

      <div>
        <label class="block font-bold text-muted uppercase mb-1">Featured Cover Image</label>
        <input type="file" name="featured_image" class="block w-full text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-soft file:px-4 file:py-2 file:font-semibold file:text-brand">
      </div>

      <div class="pt-2">
        <label class="flex items-center gap-2 font-bold text-slate-700 cursor-pointer">
          <input type="checkbox" name="is_published" value="1" <?= (!isset($editing_post) || !empty($editing_post['is_published'])) ? 'checked' : '' ?> class="rounded text-brand">
          Publish Immediately
        </label>
      </div>

      <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
        <a href="blog.php" class="rounded-xl bg-slate-100 px-5 py-2.5 font-bold text-slate-700 hover:bg-slate-200">Cancel</a>
        <button type="submit" class="btn-cta rounded-xl px-6 py-2.5 font-bold text-white shadow-md">Save Article</button>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
