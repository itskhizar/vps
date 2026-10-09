<?php
/**
 * VPS Digital Services - Single Blog Article Page
 */
require_once __DIR__ . '/include/functions.php';

$slug = trim($_GET['slug'] ?? '');
$id   = (int)($_GET['id'] ?? 0);

if (empty($slug) && $id <= 0) {
    header("Location: blog.php");
    exit;
}

try {
    $db = get_db();
    if (!empty($slug)) {
        $stmt = $db->prepare("SELECT * FROM blog_posts WHERE slug = ? AND is_published = 1 LIMIT 1");
        $stmt->execute([$slug]);
    } else {
        $stmt = $db->prepare("SELECT * FROM blog_posts WHERE id = ? AND is_published = 1 LIMIT 1");
        $stmt->execute([$id]);
    }
    $post = $stmt->fetch();

    if (!$post) {
        http_response_code(404);
        include __DIR__ . '/404.php';
        exit;
    }

    // Related posts
    $stmt_related = $db->prepare("SELECT * FROM blog_posts WHERE id != ? AND is_published = 1 ORDER BY (category_name = ?) DESC, id DESC LIMIT 3");
    $stmt_related->execute([$post['id'], $post['category_name']]);
    $related_posts = $stmt_related->fetchAll();

} catch (Exception $e) {
    error_log("Blog post error: " . $e->getMessage());
    header("Location: blog.php");
    exit;
}

$page_title = (!empty($post['meta_title'])) ? $post['meta_title'] : $post['title'] . " | VPS Insights";
$page_desc  = (!empty($post['meta_desc'])) ? $post['meta_desc'] : $post['excerpt'];
$is_solid_header = false;

// Schema.org BlogPosting Structured Data
$schema_json = json_encode([
    "@context" => "https://schema.org",
    "@type" => "BlogPosting",
    "headline" => $post['title'],
    "description" => $post['excerpt'],
    "author" => [
        "@type" => "Person",
        "name" => $post['author'] ?: "VPS Engineering Team"
    ],
    "publisher" => [
        "@type" => "Organization",
        "name" => "VPS — V Provide Services",
        "url" => "https://vprovideservices.com"
    ],
    "datePublished" => $post['published_at'] ?? date('c'),
    "mainEntityOfPage" => [
        "@type" => "WebPage",
        "@id" => $canonical_url ?? "https://vprovideservices.com/blog-post.php"
    ]
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

include __DIR__ . '/include/header.php';
?>

<!-- Header Banner -->
<section class="hero relative overflow-hidden pb-20 pt-32 text-white lg:pb-24 lg:pt-36">
  <div class="grid-bg absolute inset-0"></div>
  <div class="relative mx-auto max-w-[1000px] px-5 lg:px-8">
    <nav class="mb-6 flex items-center gap-2 text-xs font-semibold text-slate-400">
      <a href="index.php" class="hover:text-cyan transition-colors">Home</a>
      <span class="material-symbols-outlined text-sm">chevron_right</span>
      <a href="blog.php" class="hover:text-cyan transition-colors">Blog</a>
      <span class="material-symbols-outlined text-sm">chevron_right</span>
      <span class="text-white"><?= e($post['category_name']) ?></span>
    </nav>

    <div class="space-y-4">
      <div class="flex flex-wrap items-center gap-3 text-xs">
        <span class="rounded-full bg-brand/30 px-3 py-1 font-semibold text-cyan border border-brand/50"><?= e($post['category_name']) ?></span>
        <span class="text-slate-400"><?= date('F j, Y', strtotime($post['published_at'])) ?></span>
        <span class="text-slate-400">•</span>
        <span class="text-slate-400"><?= e($post['reading_time']) ?></span>
      </div>

      <h1 class="font-display text-3xl font-extrabold tracking-tight sm:text-4xl lg:text-5xl leading-tight">
        <?= e($post['title']) ?>
      </h1>

      <div class="flex items-center gap-3 pt-2">
        <div class="grid h-10 w-10 place-items-center rounded-full bg-brand text-xs font-bold text-white">
          <?= substr($post['author'], 0, 2) ?>
        </div>
        <div class="text-xs">
          <p class="font-bold text-white"><?= e($post['author']) ?></p>
          <p class="text-slate-400">VPS Specialist Author</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Article Content Body -->
<section class="bg-white py-16 lg:py-24">
  <div class="mx-auto max-w-[900px] px-5 lg:px-8">
    
    <!-- Content Typography Container -->
    <div class="prose prose-lg max-w-none text-slate-700 leading-relaxed">
      <?php if (!empty($post['featured_image']) && file_exists(__DIR__ . '/' . $post['featured_image'])): ?>
        <div class="mb-10 overflow-hidden rounded-3xl shadow-xl">
          <img src="<?= e($post['featured_image']) ?>" alt="<?= e($post['title']) ?>" class="w-full object-cover">
        </div>
      <?php endif; ?>

      <?= $post['content'] ?>
    </div>

    <!-- Author Callout Box -->
    <div class="mt-16 rounded-3xl border border-slate-200 bg-soft p-8 flex flex-col sm:flex-row items-center gap-6">
      <div class="grid h-20 w-20 shrink-0 place-items-center rounded-2xl bg-navy font-display text-2xl font-bold text-cyan shadow-lg">
        <?= substr($post['author'], 0, 2) ?>
      </div>
      <div>
        <span class="text-xs font-bold uppercase tracking-wider text-brand">Written by</span>
        <h3 class="font-display text-xl font-bold text-ink"><?= e($post['author']) ?></h3>
        <p class="mt-2 text-sm text-muted">A senior contributor at VPS providing technical leadership across full-stack engineering, interface architecture, and client solutions.</p>
      </div>
    </div>

    <!-- Related Articles -->
    <?php if (!empty($related_posts)): ?>
      <div class="mt-20 border-t border-slate-100 pt-12">
        <h3 class="font-display text-2xl font-bold text-ink">Related Articles</h3>
        <div class="mt-8 grid gap-6 sm:grid-cols-3">
          <?php foreach ($related_posts as $rp): ?>
            <a href="blog-post.php?slug=<?= urlencode($rp['slug']) ?>" class="group block rounded-2xl border border-slate-200 p-5 transition hover:border-brand/40 hover:shadow-lg">
              <span class="text-xs font-semibold text-brand"><?= e($rp['category_name']) ?></span>
              <h4 class="mt-2 font-display text-base font-bold text-ink group-hover:text-brand transition-colors line-clamp-2"><?= e($rp['title']) ?></h4>
              <p class="mt-2 text-xs text-muted line-clamp-2"><?= e($rp['excerpt']) ?></p>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

  </div>
</section>

<?php include __DIR__ . '/include/footer.php'; ?>
