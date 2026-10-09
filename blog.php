<?php
/**
 * VPS Digital Services - Blog & Insights
 */
require_once __DIR__ . '/include/functions.php';

$page_title = "Insights & Technical Blog | VPS Digital Services";
$page_desc  = "Articles, industry insights, and engineering guides on modern web development, UI/UX branding, machine learning, and architectural 3D technology.";
$is_solid_header = false;

$db = get_db();
$search = trim($_GET['q'] ?? '');
$cat_filter = trim($_GET['category'] ?? '');
$page = max(1, (int)($_GET['p'] ?? 1));
$per_page = 6;
$offset = ($page - 1) * $per_page;

try {
    // Categories
    $stmt_cats = $db->query("SELECT * FROM blog_categories ORDER BY name ASC");
    $categories = $stmt_cats->fetchAll();

    // Query builder for posts
    $where = ["is_published = 1"];
    $params = [];

    if (!empty($search)) {
        $where[] = "(title LIKE ? OR excerpt LIKE ? OR content LIKE ?)";
        $params[] = "%{$search}%";
        $params[] = "%{$search}%";
        $params[] = "%{$search}%";
    }

    if (!empty($cat_filter)) {
        $where[] = "category_name = ?";
        $params[] = $cat_filter;
    }

    $where_sql = implode(' AND ', $where);

    // Count
    $stmt_count = $db->prepare("SELECT COUNT(*) FROM blog_posts WHERE {$where_sql}");
    $stmt_count->execute($params);
    $total_posts = (int)$stmt_count->fetchColumn();
    $total_pages = ceil($total_posts / $per_page);

    // Fetch page items
    $sql_posts = "SELECT * FROM blog_posts WHERE {$where_sql} ORDER BY published_at DESC LIMIT {$per_page} OFFSET {$offset}";
    $stmt_posts = $db->prepare($sql_posts);
    $stmt_posts->execute($params);
    $posts = $stmt_posts->fetchAll();

} catch (Exception $e) {
    error_log("Blog query error: " . $e->getMessage());
    $categories = [];
    $posts = [];
    $total_pages = 1;
}

include __DIR__ . '/include/header.php';
?>

<!-- Header Banner -->
<section class="hero relative overflow-hidden pb-20 pt-32 text-white lg:pb-28 lg:pt-36">
  <div class="grid-bg absolute inset-0"></div>
  <div class="relative mx-auto max-w-[1280px] px-5 text-center lg:px-8">
    <span class="glass inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold uppercase tracking-[.16em] text-cyan">
      VPS Engineering &amp; Design Insights
    </span>
    <h1 class="mt-6 font-display text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
      Practical Knowledge for Growing Businesses
    </h1>
    <p class="mx-auto mt-4 max-w-2xl text-lg text-slate-300">
      Field notes, case retrospectives, and architecture guides from the specialists building digital solutions at VPS.
    </p>

    <!-- Search Box -->
    <form action="blog.php" method="get" class="mx-auto mt-8 flex max-w-md gap-2 rounded-2xl bg-white/10 p-2 backdrop-blur-md border border-white/20">
      <input type="text" name="q" value="<?= e($search) ?>" placeholder="Search articles, AI, branding, tech..." class="min-w-0 flex-1 bg-transparent px-4 py-2 text-sm text-white placeholder-slate-400 focus:outline-none">
      <button type="submit" class="btn-cta rounded-xl px-5 py-2 text-sm font-semibold">Search</button>
    </form>
  </div>
</section>

<!-- Main Blog Section -->
<section class="bg-white py-16 lg:py-24">
  <div class="mx-auto max-w-[1280px] px-5 lg:px-8">
    
    <!-- Category Filter Bar -->
    <?php if (!empty($categories)): ?>
      <div class="mb-12 flex flex-wrap justify-center gap-2">
        <a href="blog.php" class="rounded-full px-5 py-2 text-sm font-semibold transition <?= empty($cat_filter) ? 'bg-brand text-white shadow-md shadow-brand/30' : 'bg-white text-muted ring-1 ring-slate-200 hover:text-ink' ?>">
          All Articles
        </a>
        <?php foreach ($categories as $cat): 
          $isActive = ($cat_filter === $cat['name']);
        ?>
          <a href="blog.php?category=<?= urlencode($cat['name']) ?>" class="rounded-full px-5 py-2 text-sm font-semibold transition <?= $isActive ? 'bg-brand text-white shadow-md shadow-brand/30' : 'bg-white text-muted ring-1 ring-slate-200 hover:text-ink' ?>">
            <?= e($cat['name']) ?>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <!-- Articles Grid -->
    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
      <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $b): ?>
          <article class="group reveal overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 transition duration-300 hover:-translate-y-1 hover:shadow-2xl flex flex-col justify-between">
            <div>
              <div class="grid aspect-[16/9] place-items-center bg-gradient-to-br from-navy to-cyan text-white/90">
                <span class="material-symbols-outlined !text-6xl"><?= e($b['icon'] ?: 'article') ?></span>
              </div>
              <div class="p-6">
                <div class="flex items-center justify-between text-xs">
                  <span class="rounded-full bg-soft px-3 py-1 font-semibold text-brand"><?= e($b['category_name']) ?></span>
                  <span class="text-muted"><?= e($b['reading_time']) ?></span>
                </div>
                <h3 class="mt-3 font-display text-xl font-bold text-ink transition-colors group-hover:text-brand">
                  <a href="blog-post.php?slug=<?= urlencode($b['slug']) ?>"><?= e($b['title']) ?></a>
                </h3>
                <p class="mt-2 text-sm leading-6 text-muted line-clamp-3"><?= e($b['excerpt']) ?></p>
              </div>
            </div>

            <div class="border-t border-slate-100 px-6 py-4 flex items-center justify-between text-xs text-muted">
              <span>By <?= e($b['author'] ?: 'VPS Team') ?></span>
              <a href="blog-post.php?slug=<?= urlencode($b['slug']) ?>" class="font-bold text-brand inline-flex items-center gap-1 group-hover:gap-2 transition-all">
                Read Article <span class="material-symbols-outlined text-sm">arrow_forward</span>
              </a>
            </div>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-span-3 text-center py-12">
          <p class="text-base text-muted">No published articles found matching your criteria.</p>
          <a href="blog.php" class="mt-4 inline-block text-sm font-bold text-brand">Reset filters</a>
        </div>
      <?php endif; ?>
    </div>

    <!-- Pagination Controls -->
    <?php if ($total_pages > 1): ?>
      <div class="mt-14 flex justify-center gap-2">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <a href="blog.php?p=<?= $i ?><?= !empty($cat_filter) ? '&category=' . urlencode($cat_filter) : '' ?><?= !empty($search) ? '&q=' . urlencode($search) : '' ?>" class="grid h-10 w-10 place-items-center rounded-xl text-sm font-bold transition <?= $i === $page ? 'bg-brand text-white shadow-md' : 'bg-soft text-ink hover:bg-slate-200' ?>">
            <?= $i ?>
          </a>
        <?php endfor; ?>
      </div>
    <?php endif; ?>

  </div>
</section>

<?php include __DIR__ . '/include/footer.php'; ?>
