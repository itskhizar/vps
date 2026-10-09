<?php
/**
 * VPS Digital Services - Official Homepage
 * Preserves the high-converting aesthetic of vps-home-final.html connected dynamically to MySQL
 */
require_once __DIR__ . '/include/functions.php';

$page_title = get_setting('meta_title', 'VPS | V Provide Services: Web, App, Design, Data & Architecture Solutions Worldwide');
$page_desc  = get_setting('meta_description', 'VPS helps ambitious businesses worldwide launch, brand and grow with web and app development, graphic design, ecommerce management, data science, and architectural 3D solutions.');
$is_solid_header = false;

// Query dynamic content from MySQL
try {
    $db = get_db();
    
    // Active Services
    $stmt_services = $db->query("SELECT * FROM services WHERE is_active = 1 ORDER BY display_order ASC LIMIT 8");
    $services = $stmt_services->fetchAll();
    
    // Published Portfolio Projects
    $stmt_portfolio = $db->query("SELECT * FROM portfolio WHERE is_published = 1 ORDER BY is_featured DESC, id DESC LIMIT 6");
    $portfolio_items = $stmt_portfolio->fetchAll();
    
    // Active Team Members
    $stmt_team = $db->query("SELECT * FROM team WHERE is_active = 1 ORDER BY display_order ASC LIMIT 4");
    $team_members = $stmt_team->fetchAll();
    
    // Published Blog Posts
    $stmt_blog = $db->query("SELECT * FROM blog_posts WHERE is_published = 1 ORDER BY published_at DESC LIMIT 3");
    $blog_posts = $stmt_blog->fetchAll();

} catch (Exception $e) {
    error_log("Homepage query error: " . $e->getMessage());
    $services = [];
    $portfolio_items = [];
    $team_members = [];
    $blog_posts = [];
}

include __DIR__ . '/include/header.php';
?>

<!-- Hero Section -->
<section class="hero relative overflow-hidden pb-24 pt-32 text-white lg:pb-32 lg:pt-40">
  <div class="grid-bg absolute inset-0"></div>
  <div class="relative mx-auto grid max-w-[1280px] items-center gap-16 px-5 lg:grid-cols-12 lg:px-8">
    <div class="lg:col-span-6">
      <span class="glass inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold uppercase tracking-[.16em] text-cyan">
        <span class="h-2 w-2 animate-pulse rounded-full bg-cyan"></span>
        Global IT &amp; Creative Services
      </span>
      <h1 class="mt-7 font-display text-[40px] font-extrabold leading-[1.08] tracking-tight sm:text-5xl lg:text-[62px]">
        Everything Digital.<br>
        <span class="bg-gradient-to-r from-cyan via-sky-300 to-brand bg-clip-text text-transparent">One Trusted Provider.</span>
      </h1>
      <p class="mt-6 max-w-xl text-lg leading-8 text-slate-300">
        VPS helps ambitious businesses worldwide launch, brand and grow with web and app development, graphic design, ecommerce management, data science, architecture and interior design from a single dedicated team.
      </p>
      <div class="mt-9 flex flex-wrap gap-4">
        <a href="start-project.php" class="btn-cta inline-flex items-center gap-2 rounded-xl px-8 py-4 font-semibold">
          Start Your Project <span class="material-symbols-outlined text-lg">arrow_forward</span>
        </a>
        <a href="portfolio.php" class="glass inline-flex items-center gap-2 rounded-xl px-8 py-4 font-semibold transition hover:bg-white/15">
          Explore Our Work <span class="material-symbols-outlined text-lg">north_east</span>
        </a>
      </div>
      <ul class="mt-10 flex flex-wrap gap-x-8 gap-y-3 text-sm text-slate-300">
        <li class="flex items-center gap-2">
          <span class="material-symbols-outlined text-cyan text-lg">check_circle</span>Free initial consultation
        </li>
        <li class="flex items-center gap-2">
          <span class="material-symbols-outlined text-cyan text-lg">check_circle</span>Detailed quote within 24 hours
        </li>
        <li class="flex items-center gap-2">
          <span class="material-symbols-outlined text-cyan text-lg">check_circle</span>Full IP &amp; source code ownership
        </li>
      </ul>
    </div>

    <!-- Animated Hero Illustration / Interactive Mockup -->
    <div class="relative lg:col-span-6">
      <div class="glass fl rounded-2xl p-3 shadow-2xl">
        <div class="overflow-hidden rounded-xl bg-[#0F1A3D]">
          <div class="flex items-center gap-1.5 border-b border-white/10 px-4 py-3">
            <i class="h-2.5 w-2.5 rounded-full bg-red-400"></i>
            <i class="h-2.5 w-2.5 rounded-full bg-yellow-400"></i>
            <i class="h-2.5 w-2.5 rounded-full bg-green-400"></i>
            <span class="ml-3 flex-1 rounded bg-white/10 px-3 py-1 text-[11px] text-slate-400">vprovideservices.com</span>
          </div>
          <div class="grid grid-cols-12 gap-3 p-4">
            <div class="col-span-3 space-y-2">
              <i class="block h-2.5 rounded bg-white/20"></i>
              <i class="block h-2.5 rounded bg-white/20"></i>
              <i class="block h-2.5 rounded bg-white/20"></i>
              <i class="block h-2.5 rounded bg-white/20"></i>
              <i class="block h-2.5 rounded bg-white/20"></i>
            </div>
            <div class="col-span-9 space-y-3">
              <div class="rounded-lg bg-gradient-to-r from-brand to-cyan p-4">
                <i class="block h-3 w-2/3 rounded bg-white/90"></i>
                <i class="mt-2 block h-2 w-1/2 rounded bg-white/60"></i>
                <i class="mt-4 block h-6 w-20 rounded bg-cta"></i>
              </div>
              <div class="grid grid-cols-3 gap-3">
                <div class="grid h-16 place-items-center rounded-lg bg-white/5 text-cyan">
                  <span class="material-symbols-outlined !text-2xl">code</span>
                </div>
                <div class="grid h-16 place-items-center rounded-lg bg-white/5 text-cyan">
                  <span class="material-symbols-outlined !text-2xl">palette</span>
                </div>
                <div class="grid h-16 place-items-center rounded-lg bg-white/5 text-cyan">
                  <span class="material-symbols-outlined !text-2xl">hub</span>
                </div>
              </div>
              <div class="flex h-20 items-end gap-1.5 rounded-lg bg-white/5 p-3">
                <i class="block flex-1 rounded-t bg-gradient-to-t from-brand to-cyan" style="height:35%"></i>
                <i class="block flex-1 rounded-t bg-gradient-to-t from-brand to-cyan" style="height:55%"></i>
                <i class="block flex-1 rounded-t bg-gradient-to-t from-brand to-cyan" style="height:45%"></i>
                <i class="block flex-1 rounded-t bg-gradient-to-t from-brand to-cyan" style="height:75%"></i>
                <i class="block flex-1 rounded-t bg-gradient-to-t from-brand to-cyan" style="height:60%"></i>
                <i class="block flex-1 rounded-t bg-gradient-to-t from-brand to-cyan" style="height:90%"></i>
                <i class="block flex-1 rounded-t bg-gradient-to-t from-brand to-cyan" style="height:70%"></i>
                <i class="block flex-1 rounded-t bg-gradient-to-t from-brand to-cyan" style="height:100%"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Floating Badges -->
      <div class="glass absolute -bottom-8 -left-2 w-28 rounded-[26px] p-2 shadow-2xl sm:-left-8">
        <div class="rounded-[20px] bg-white p-2.5">
          <i class="block h-14 rounded-lg bg-gradient-to-br from-brand to-cyan"></i>
          <i class="mt-2 block h-2 w-3/4 rounded bg-slate-200"></i>
          <i class="mt-1.5 block h-2 w-1/2 rounded bg-slate-200"></i>
          <i class="mt-3 block h-5 rounded bg-cta"></i>
        </div>
      </div>
      <div class="glass absolute -right-2 top-8 flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold shadow-xl sm:-right-6">
        <span class="material-symbols-outlined text-cyan">draw</span> Brand Identity
      </div>
      <div class="glass absolute -right-2 bottom-12 flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold shadow-xl sm:-right-6">
        <span class="material-symbols-outlined text-cyan">phone_iphone</span> Mobile Apps
      </div>
    </div>
  </div>
</section>

<!-- Tech Ticker Bar -->
<section class="border-b border-slate-100 bg-white py-8">
  <p class="mb-5 text-center text-xs font-semibold uppercase tracking-[.2em] text-muted">Technologies &amp; Frameworks We Build With</p>
  <div class="overflow-hidden" style="-webkit-mask-image:linear-gradient(90deg,transparent,#000 10%,#000 90%,transparent);mask-image:linear-gradient(90deg,transparent,#000 10%,#000 90%,transparent)">
    <div class="mq flex w-max gap-4">
      <span class="rounded-full border border-slate-200 bg-soft px-5 py-2 text-sm font-semibold text-muted">PHP &amp; MySQL</span>
      <span class="rounded-full border border-slate-200 bg-soft px-5 py-2 text-sm font-semibold text-muted">Laravel</span>
      <span class="rounded-full border border-slate-200 bg-soft px-5 py-2 text-sm font-semibold text-muted">JavaScript / jQuery</span>
      <span class="rounded-full border border-slate-200 bg-soft px-5 py-2 text-sm font-semibold text-muted">Tailwind CSS</span>
      <span class="rounded-full border border-slate-200 bg-soft px-5 py-2 text-sm font-semibold text-muted">Flutter &amp; Dart</span>
      <span class="rounded-full border border-slate-200 bg-soft px-5 py-2 text-sm font-semibold text-muted">Python &amp; Pandas</span>
      <span class="rounded-full border border-slate-200 bg-soft px-5 py-2 text-sm font-semibold text-muted">Power BI</span>
      <span class="rounded-full border border-slate-200 bg-soft px-5 py-2 text-sm font-semibold text-muted">Figma &amp; UI/UX</span>
      <span class="rounded-full border border-slate-200 bg-soft px-5 py-2 text-sm font-semibold text-muted">Shopify &amp; WooCommerce</span>
      <span class="rounded-full border border-slate-200 bg-soft px-5 py-2 text-sm font-semibold text-muted">AutoCAD &amp; Revit</span>
      <span class="rounded-full border border-slate-200 bg-soft px-5 py-2 text-sm font-semibold text-muted">3ds Max &amp; V-Ray</span>
      <!-- Duplicate for infinite marquee loop -->
      <span class="rounded-full border border-slate-200 bg-soft px-5 py-2 text-sm font-semibold text-muted">PHP &amp; MySQL</span>
      <span class="rounded-full border border-slate-200 bg-soft px-5 py-2 text-sm font-semibold text-muted">Laravel</span>
      <span class="rounded-full border border-slate-200 bg-soft px-5 py-2 text-sm font-semibold text-muted">JavaScript / jQuery</span>
      <span class="rounded-full border border-slate-200 bg-soft px-5 py-2 text-sm font-semibold text-muted">Tailwind CSS</span>
      <span class="rounded-full border border-slate-200 bg-soft px-5 py-2 text-sm font-semibold text-muted">Flutter &amp; Dart</span>
      <span class="rounded-full border border-slate-200 bg-soft px-5 py-2 text-sm font-semibold text-muted">Python &amp; Pandas</span>
      <span class="rounded-full border border-slate-200 bg-soft px-5 py-2 text-sm font-semibold text-muted">Power BI</span>
      <span class="rounded-full border border-slate-200 bg-soft px-5 py-2 text-sm font-semibold text-muted">Figma &amp; UI/UX</span>
      <span class="rounded-full border border-slate-200 bg-soft px-5 py-2 text-sm font-semibold text-muted">Shopify &amp; WooCommerce</span>
      <span class="rounded-full border border-slate-200 bg-soft px-5 py-2 text-sm font-semibold text-muted">AutoCAD &amp; Revit</span>
      <span class="rounded-full border border-slate-200 bg-soft px-5 py-2 text-sm font-semibold text-muted">3ds Max &amp; V-Ray</span>
    </div>
  </div>
</section>

<!-- Dynamic Services Overview Section -->
<section id="services" class="bg-white py-20 lg:py-28">
  <div class="mx-auto max-w-[1280px] px-5 lg:px-8">
    <div class="reveal mb-12 max-w-2xl mx-auto text-center">
      <span class="text-xs font-bold uppercase tracking-[.2em] text-brand">What We Do</span>
      <h2 class="mt-3 font-display text-3xl font-bold tracking-tight text-ink md:text-[40px] md:leading-tight">
        Everything You Need to Launch, Brand &amp; Grow
      </h2>
      <p class="mt-4 text-lg leading-8 text-muted">
        Specialist capabilities loaded directly from MySQL, delivering end-to-end digital excellence.
      </p>
    </div>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
      <?php if (!empty($services)): ?>
        <?php foreach ($services as $idx => $s): 
          $s_num = str_pad((string)($idx + 1), 2, '0', STR_PAD_LEFT);
        ?>
          <a href="service-details.php?slug=<?= urlencode($s['slug']) ?>" class="group reveal relative flex flex-col justify-between overflow-hidden rounded-2xl border border-slate-200 bg-white p-7 transition duration-300 hover:-translate-y-1.5 hover:border-brand/40 hover:shadow-2xl hover:shadow-brand/10">
            <span class="absolute right-5 top-4 font-display text-5xl font-extrabold text-slate-100 select-none"><?= $s_num ?></span>
            <div class="relative">
              <div class="grid h-14 w-14 place-items-center rounded-2xl bg-gradient-to-br from-brand to-cyan text-white shadow-lg shadow-brand/30">
                <span class="material-symbols-outlined !text-2xl"><?= e($s['icon'] ?: 'code') ?></span>
              </div>
              <h3 class="mt-6 font-display text-xl font-semibold"><?= e($s['title']) ?></h3>
              <p class="mt-3 text-sm leading-6 text-muted"><?= e($s['short_desc']) ?></p>
              <?php if (!empty($s['features'])): 
                $feats = array_slice(explode(',', $s['features']), 0, 3);
              ?>
                <p class="mt-4 text-xs font-semibold text-brand/80"><?= e(implode(' • ', array_map('trim', $feats))) ?></p>
              <?php endif; ?>
            </div>
            <span class="relative mt-6 inline-flex items-center gap-1 text-sm font-semibold text-brand transition-all group-hover:gap-2">
              Learn more <span class="material-symbols-outlined text-base">arrow_forward</span>
            </span>
          </a>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <div class="mt-12 text-center">
      <a href="services.php" class="inline-flex items-center gap-2 rounded-xl bg-soft px-8 py-4 font-semibold text-brand ring-1 ring-slate-200 transition hover:bg-brand hover:text-white">
        Explore All Services <span class="material-symbols-outlined text-lg">arrow_forward</span>
      </a>
    </div>
  </div>
</section>

<!-- Why Choose VPS Section -->
<section id="why" class="bg-soft py-20 lg:py-28">
  <div class="mx-auto max-w-[1280px] px-5 lg:px-8">
    <div class="grid items-start gap-14 lg:grid-cols-12">
      <div class="lg:col-span-5 lg:sticky lg:top-28">
        <div class="reveal mb-12 max-w-2xl">
          <span class="text-xs font-bold uppercase tracking-[.2em] text-brand">Why VPS</span>
          <h2 class="mt-3 font-display text-3xl font-bold tracking-tight text-ink md:text-[40px] md:leading-tight">
            A Partner Who Treats Your Business Like Their Own
          </h2>
          <p class="mt-4 text-lg leading-8 text-muted">
            We combine technical depth with creative craft and honest communication, so projects finish on time and work seamlessly after launch.
          </p>
        </div>
        <div class="-mt-4 flex flex-wrap gap-4">
          <a href="about.php" class="inline-flex items-center gap-2 rounded-xl bg-brand px-7 py-3.5 font-semibold text-white shadow-lg shadow-brand/30 transition hover:bg-navy">
            About VPS <span class="material-symbols-outlined text-lg">arrow_forward</span>
          </a>
          <a href="contact.php" class="inline-flex items-center gap-2 rounded-xl bg-white px-7 py-3.5 font-semibold text-ink ring-1 ring-slate-200 transition hover:ring-brand">
            Talk to Our Team
          </a>
        </div>
      </div>

      <div class="grid gap-6 sm:grid-cols-2 lg:col-span-7">
        <div class="reveal rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-100 transition hover:shadow-xl">
          <div class="grid h-12 w-12 place-items-center rounded-xl bg-brand/10 text-brand">
            <span class="material-symbols-outlined">diversity_3</span>
          </div>
          <h3 class="mt-5 font-display text-lg font-semibold">One Team, Every Discipline</h3>
          <p class="mt-2 text-sm leading-6 text-muted">Developers, designers, analysts and architects under one roof, so your project never gets lost between multiple vendors.</p>
        </div>

        <div class="reveal rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-100 transition hover:shadow-xl">
          <div class="grid h-12 w-12 place-items-center rounded-xl bg-brand/10 text-brand">
            <span class="material-symbols-outlined">request_quote</span>
          </div>
          <h3 class="mt-5 font-display text-lg font-semibold">Transparent Pricing</h3>
          <p class="mt-2 text-sm leading-6 text-muted">Clear packages, fixed milestones and no surprise costs. You always know exactly what you are paying for.</p>
        </div>

        <div class="reveal rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-100 transition hover:shadow-xl">
          <div class="grid h-12 w-12 place-items-center rounded-xl bg-brand/10 text-brand">
            <span class="material-symbols-outlined">shield_lock</span>
          </div>
          <h3 class="mt-5 font-display text-lg font-semibold">Secure &amp; Scalable</h3>
          <p class="mt-2 text-sm leading-6 text-muted">Secure PHP/MySQL coding, tested releases and clean architecture engineered to scale alongside your organization.</p>
        </div>

        <div class="reveal rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-100 transition hover:shadow-xl">
          <div class="grid h-12 w-12 place-items-center rounded-xl bg-brand/10 text-brand">
            <span class="material-symbols-outlined">workspace_premium</span>
          </div>
          <h3 class="mt-5 font-display text-lg font-semibold">You Own Everything</h3>
          <p class="mt-2 text-sm leading-6 text-muted">Full source code, Figma design files, credentials and assets transferred to you upon completion.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Project Workflow / Process Section -->
<section id="process" class="bg-white py-20 lg:py-28">
  <div class="mx-auto max-w-[1280px] px-5 lg:px-8">
    <div class="reveal mb-12 max-w-2xl mx-auto text-center">
      <span class="text-xs font-bold uppercase tracking-[.2em] text-brand">How We Work</span>
      <h2 class="mt-3 font-display text-3xl font-bold tracking-tight text-ink md:text-[40px] md:leading-tight">
        A Simple, Transparent Path from Brief to Launch
      </h2>
    </div>
    <div class="grid gap-10 md:grid-cols-4">
      <div class="reveal">
        <div class="flex items-center gap-4">
          <span class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-navy font-display text-lg font-bold text-cyan shadow-lg">01</span>
          <span class="hidden h-px flex-1 border-t-2 border-dashed border-brand/30 md:block"></span>
        </div>
        <h3 class="mt-5 font-display text-xl font-semibold">Discover</h3>
        <p class="mt-2 text-sm leading-6 text-muted">Goals, target audience and scope defined in an intensive initial kickoff session.</p>
      </div>

      <div class="reveal">
        <div class="flex items-center gap-4">
          <span class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-navy font-display text-lg font-bold text-cyan shadow-lg">02</span>
          <span class="hidden h-px flex-1 border-t-2 border-dashed border-brand/30 md:block"></span>
        </div>
        <h3 class="mt-5 font-display text-xl font-semibold">Design</h3>
        <p class="mt-2 text-sm leading-6 text-muted">Wireframes, UI prototypes and architecture blueprints reviewed and approved before building begins.</p>
      </div>

      <div class="reveal">
        <div class="flex items-center gap-4">
          <span class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-navy font-display text-lg font-bold text-cyan shadow-lg">03</span>
          <span class="hidden h-px flex-1 border-t-2 border-dashed border-brand/30 md:block"></span>
        </div>
        <h3 class="mt-5 font-display text-xl font-semibold">Build</h3>
        <p class="mt-2 text-sm leading-6 text-muted">Iterative development with regular progress demonstrations and QA checks at every milestone.</p>
      </div>

      <div class="reveal">
        <div class="flex items-center gap-4">
          <span class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-navy font-display text-lg font-bold text-cyan shadow-lg">04</span>
        </div>
        <h3 class="mt-5 font-display text-xl font-semibold">Launch &amp; Support</h3>
        <p class="mt-2 text-sm leading-6 text-muted">Smooth production deployment, thorough handover documentation and dedicated post-launch support.</p>
      </div>
    </div>
  </div>
</section>

<!-- Dynamic Portfolio / Case Studies Section with Categories Filter -->
<section id="portfolio" class="bg-soft py-20 lg:py-28">
  <div class="mx-auto max-w-[1280px] px-5 lg:px-8">
    <div class="reveal mb-12 max-w-2xl mx-auto text-center">
      <span class="text-xs font-bold uppercase tracking-[.2em] text-brand">Selected Work</span>
      <h2 class="mt-3 font-display text-3xl font-bold tracking-tight text-ink md:text-[40px] md:leading-tight">
        Projects That Deliver Real Business Impact
      </h2>
      <p class="mt-4 text-lg leading-8 text-muted">
        A curated selection of our work across web development, mobile apps, brand identity, ecommerce, and 3D architectural renders.
      </p>
    </div>

    <!-- Filter Buttons -->
    <div class="mb-10 flex flex-wrap justify-center gap-2" role="group" aria-label="Filter projects">
      <button data-f="all" aria-pressed="true" class="pf rounded-full px-5 py-2 text-sm font-medium transition bg-brand text-white shadow-md shadow-brand/30">All</button>
      <button data-f="web" aria-pressed="false" class="pf rounded-full px-5 py-2 text-sm font-medium transition bg-white text-muted ring-1 ring-slate-200 hover:text-ink">Web</button>
      <button data-f="app" aria-pressed="false" class="pf rounded-full px-5 py-2 text-sm font-medium transition bg-white text-muted ring-1 ring-slate-200 hover:text-ink">Apps</button>
      <button data-f="brand" aria-pressed="false" class="pf rounded-full px-5 py-2 text-sm font-medium transition bg-white text-muted ring-1 ring-slate-200 hover:text-ink">Branding</button>
      <button data-f="shop" aria-pressed="false" class="pf rounded-full px-5 py-2 text-sm font-medium transition bg-white text-muted ring-1 ring-slate-200 hover:text-ink">Ecommerce</button>
      <button data-f="data" aria-pressed="false" class="pf rounded-full px-5 py-2 text-sm font-medium transition bg-white text-muted ring-1 ring-slate-200 hover:text-ink">Data Science</button>
      <button data-f="arch" aria-pressed="false" class="pf rounded-full px-5 py-2 text-sm font-medium transition bg-white text-muted ring-1 ring-slate-200 hover:text-ink">Architecture</button>
    </div>

    <!-- Portfolio Grid Loaded from Database -->
    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
      <?php if (!empty($portfolio_items)): ?>
        <?php foreach ($portfolio_items as $p): 
          $cat = strtolower($p['category']);
          // Category badge labels
          $cat_labels = [
            'web' => 'Web Development',
            'app' => 'App Development',
            'brand' => 'Logo & Branding',
            'shop' => 'Ecommerce',
            'data' => 'Data Science',
            'arch' => 'Architecture & 3D'
          ];
          $cat_label = $cat_labels[$cat] ?? ucfirst($cat);
        ?>
          <article data-c="<?= e($cat) ?>" class="pc group overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 transition duration-300 hover:-translate-y-1 hover:shadow-2xl">
            <div class="relative aspect-[16/10] overflow-hidden bg-gradient-to-br from-brand to-cyan">
              <?php if (!empty($p['featured_image']) && file_exists(__DIR__ . '/' . $p['featured_image'])): ?>
                <img src="<?= e($p['featured_image']) ?>" alt="<?= e($p['title']) ?>" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
              <?php else: ?>
                <span class="material-symbols-outlined absolute inset-0 flex items-center justify-center text-[75px] text-white/90 transition duration-500 group-hover:scale-110">
                  <?= $cat === 'web' ? 'language' : ($cat === 'app' ? 'phone_iphone' : ($cat === 'brand' ? 'draw' : ($cat === 'shop' ? 'shopping_bag' : ($cat === 'data' ? 'hub' : 'architecture')))) ?>
                </span>
              <?php endif; ?>
              
              <div class="absolute inset-0 flex flex-col items-center justify-center gap-2 bg-navy/90 p-6 text-center text-white opacity-0 transition duration-300 group-hover:opacity-100">
                <span class="text-xs font-semibold uppercase tracking-wider text-cyan"><?= e($p['technologies']) ?></span>
                <p class="text-sm text-slate-300 line-clamp-2"><?= e($p['short_desc']) ?></p>
                <a href="case-study.php?slug=<?= urlencode($p['slug']) ?>" class="mt-2 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-brand transition hover:bg-cyan hover:text-navy">
                  View Case Study
                </a>
              </div>
            </div>
            <div class="p-6">
              <span class="text-xs font-semibold uppercase tracking-wide text-brand"><?= e($cat_label) ?></span>
              <h3 class="mt-1 font-display text-xl font-semibold text-ink group-hover:text-brand transition-colors">
                <a href="case-study.php?slug=<?= urlencode($p['slug']) ?>"><?= e($p['title']) ?></a>
              </h3>
            </div>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <p class="mt-12 text-center">
      <a href="portfolio.php" class="inline-flex items-center gap-2 rounded-xl bg-white px-7 py-3.5 font-semibold text-brand ring-1 ring-brand/30 transition hover:bg-brand hover:text-white">
        View All Case Studies <span class="material-symbols-outlined text-lg">arrow_forward</span>
      </a>
    </p>
  </div>
</section>

<!-- Company Statistics Section with Animated Counters -->
<section id="stats" class="hero relative overflow-hidden py-16 text-white">
  <div class="relative mx-auto grid max-w-[1280px] grid-cols-2 gap-8 px-5 text-center lg:grid-cols-4 lg:px-8">
    <div>
      <p class="font-display text-5xl font-extrabold text-cyan"><span data-count="10">10</span>+</p>
      <p class="mt-2 font-medium text-slate-300">Specialist Services</p>
    </div>
    <div>
      <p class="font-display text-5xl font-extrabold text-cyan"><span data-count="24">24</span>h</p>
      <p class="mt-2 font-medium text-slate-300">Fast Quote Turnaround</p>
    </div>
    <div>
      <p class="font-display text-5xl font-extrabold text-cyan"><span data-count="100">100</span>%</p>
      <p class="mt-2 font-medium text-slate-300">Client IP Ownership</p>
    </div>
    <div>
      <p class="font-display text-5xl font-extrabold text-cyan"><span data-count="24">24</span>/7</p>
      <p class="mt-2 font-medium text-slate-300">Dedicated Support</p>
    </div>
  </div>
</section>

<!-- Dynamic Team Preview Section -->
<section id="team" class="bg-white py-20 lg:py-28">
  <div class="mx-auto max-w-[1280px] px-5 lg:px-8">
    <div class="mb-12 flex flex-col justify-between gap-4 md:flex-row md:items-end">
      <div>
        <span class="text-xs font-bold uppercase tracking-[.2em] text-brand">Our People</span>
        <h2 class="mt-3 font-display text-3xl font-bold tracking-tight md:text-[40px]">The Experts Behind Your Project</h2>
      </div>
      <a href="team.php" class="inline-flex items-center gap-1 font-semibold text-brand">Meet the full team <span class="material-symbols-outlined text-base">arrow_forward</span></a>
    </div>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
      <?php if (!empty($team_members)): ?>
        <?php foreach ($team_members as $m): ?>
          <a href="team-member.php?slug=<?= urlencode($m['slug']) ?>" class="group overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-1 hover:shadow-xl">
            <?php if (!empty($m['image']) && file_exists(__DIR__ . '/' . $m['image'])): ?>
              <div class="aspect-square overflow-hidden bg-slate-100">
                <img src="<?= e($m['image']) ?>" alt="<?= e($m['name']) ?>" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
              </div>
            <?php else: ?>
              <div class="grid aspect-square place-items-center bg-gradient-to-br from-navy to-brand font-display text-6xl font-extrabold text-white/90">
                <?= e($m['initials'] ?: substr($m['name'], 0, 2)) ?>
              </div>
            <?php endif; ?>
            <div class="p-6">
              <h3 class="font-display text-lg font-semibold text-ink"><?= e($m['name']) ?></h3>
              <p class="text-sm font-medium text-brand"><?= e($m['title']) ?></p>
            </div>
          </a>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Testimonials Section -->
<section id="testimonials" class="bg-soft py-20 lg:py-28">
  <div class="mx-auto max-w-[1280px] px-5 lg:px-8">
    <div class="mb-10 flex items-end justify-between gap-4">
      <div>
        <span class="text-xs font-bold uppercase tracking-[.2em] text-brand">Client Feedback</span>
        <h2 class="mt-3 font-display text-3xl font-bold tracking-tight md:text-[40px]">Trusted by International Clients</h2>
      </div>
      <div class="flex gap-2">
        <button id="tPrev" aria-label="Previous testimonial" class="grid h-11 w-11 place-items-center rounded-lg bg-white ring-1 ring-slate-200 transition hover:bg-brand hover:text-white">
          <span class="material-symbols-outlined">chevron_left</span>
        </button>
        <button id="tNext" aria-label="Next testimonial" class="grid h-11 w-11 place-items-center rounded-lg bg-white ring-1 ring-slate-200 transition hover:bg-brand hover:text-white">
          <span class="material-symbols-outlined">chevron_right</span>
        </button>
      </div>
    </div>

    <div id="tTrack" class="hide-sb flex snap-x snap-mandatory gap-6 overflow-x-auto scroll-smooth pb-2">
      <figure class="w-[85%] shrink-0 snap-start rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-100 md:w-[calc(33.333%-16px)]">
        <div class="flex text-cta">
          <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">star</span>
          <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">star</span>
          <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">star</span>
          <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">star</span>
          <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">star</span>
        </div>
        <blockquote class="mt-4 leading-7 text-ink">"VPS engineered our corporate platform and admin dashboard ahead of schedule. The code was exceptionally well-structured and fast."</blockquote>
        <figcaption class="mt-6 flex items-center gap-3">
          <span class="grid h-11 w-11 place-items-center rounded-full bg-brand/10 font-bold text-brand">DM</span>
          <span><b class="block text-sm">David Miller</b><span class="text-sm text-muted">Operations Director, Apex Global</span></span>
        </figcaption>
      </figure>

      <figure class="w-[85%] shrink-0 snap-start rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-100 md:w-[calc(33.333%-16px)]">
        <div class="flex text-cta">
          <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">star</span>
          <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">star</span>
          <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">star</span>
          <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">star</span>
          <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">star</span>
        </div>
        <blockquote class="mt-4 leading-7 text-ink">"The 3D architectural renders and space plans were breathtaking. Their team communicated clearly across WhatsApp and video calls."</blockquote>
        <figcaption class="mt-6 flex items-center gap-3">
          <span class="grid h-11 w-11 place-items-center rounded-full bg-brand/10 font-bold text-brand">TA</span>
          <span><b class="block text-sm">Tariq Al-Mansoor</b><span class="text-sm text-muted">Managing Director, Horizon Developments</span></span>
        </figcaption>
      </figure>

      <figure class="w-[85%] shrink-0 snap-start rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-100 md:w-[calc(33.333%-16px)]">
        <div class="flex text-cta">
          <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">star</span>
          <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">star</span>
          <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">star</span>
          <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">star</span>
          <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">star</span>
        </div>
        <blockquote class="mt-4 leading-7 text-ink">"Our multi-channel marketplace accounts grew over 200% under their management. Highly reliable, professional partners."</blockquote>
        <figcaption class="mt-6 flex items-center gap-3">
          <span class="grid h-11 w-11 place-items-center rounded-full bg-brand/10 font-bold text-brand">SC</span>
          <span><b class="block text-sm">Sarah Chen</b><span class="text-sm text-muted">Founder, Aura Living</span></span>
        </figcaption>
      </figure>
    </div>
  </div>
</section>

<!-- FAQ Accordion Section -->
<section id="faq" class="bg-white py-20 lg:py-28">
  <div class="mx-auto max-w-[1280px] px-5 lg:px-8">
    <div class="grid gap-12 lg:grid-cols-12">
      <div class="lg:col-span-4">
        <div class="reveal mb-12 max-w-2xl">
          <span class="text-xs font-bold uppercase tracking-[.2em] text-brand">FAQ</span>
          <h2 class="mt-3 font-display text-3xl font-bold tracking-tight text-ink md:text-[40px] md:leading-tight">
            Frequently Asked Questions
          </h2>
          <p class="mt-4 text-lg leading-8 text-muted">Have a question not listed here? Our client onboarding team replies within one business day.</p>
        </div>
        <a href="contact.php" class="-mt-4 inline-flex items-center gap-2 font-semibold text-brand">Contact our team <span class="material-symbols-outlined text-lg">arrow_forward</span></a>
      </div>

      <div class="space-y-4 lg:col-span-8">
        <details class="rounded-2xl border border-slate-200 bg-white p-6 transition">
          <summary class="flex items-center justify-between gap-4 font-display text-lg font-semibold text-ink">
            How long does a typical project take?
            <span class="chev material-symbols-outlined shrink-0 text-brand transition">expand_more</span>
          </summary>
          <p class="mt-4 leading-7 text-muted">A standard business website takes 2 to 3 weeks, a custom web app or mobile application 6 to 10 weeks, and a complete brand identity 1 to 2 weeks. Every quote includes an unambiguous delivery roadmap.</p>
        </details>

        <details class="rounded-2xl border border-slate-200 bg-white p-6 transition">
          <summary class="flex items-center justify-between gap-4 font-display text-lg font-semibold text-ink">
            How does pricing work?
            <span class="chev material-symbols-outlined shrink-0 text-brand transition">expand_more</span>
          </summary>
          <p class="mt-4 leading-7 text-muted">We provide both fixed-price project quotes and dedicated monthly retainers. Every proposal outlines exact milestones and deliverables with zero hidden charges.</p>
        </details>

        <details class="rounded-2xl border border-slate-200 bg-white p-6 transition">
          <summary class="flex items-center justify-between gap-4 font-display text-lg font-semibold text-ink">
            Do I own the intellectual property and code?
            <span class="chev material-symbols-outlined shrink-0 text-brand transition">expand_more</span>
          </summary>
          <p class="mt-4 leading-7 text-muted">Yes, 100%. Upon completion and settlement, all source code, design master files, database schemas and credentials are transferred directly to you.</p>
        </details>

        <details class="rounded-2xl border border-slate-200 bg-white p-6 transition">
          <summary class="flex items-center justify-between gap-4 font-display text-lg font-semibold text-ink">
            How do we collaborate across timezones?
            <span class="chev material-symbols-outlined shrink-0 text-brand transition">expand_more</span>
          </summary>
          <p class="mt-4 leading-7 text-muted">We routinely work with international clients across North America, the UK, Europe, Australia and the Middle East via email, WhatsApp, and Zoom/Google Meet at times that suit you.</p>
        </details>
      </div>
    </div>
  </div>
</section>

<!-- Dynamic Blog / Insights Preview Section -->
<section id="blog" class="bg-soft py-20 lg:py-28">
  <div class="mx-auto max-w-[1280px] px-5 lg:px-8">
    <div class="mb-12 flex flex-col justify-between gap-4 md:flex-row md:items-end">
      <div>
        <span class="text-xs font-bold uppercase tracking-[.2em] text-brand">Insights &amp; Articles</span>
        <h2 class="mt-3 font-display text-3xl font-bold tracking-tight md:text-[40px]">Latest From VPS Insights</h2>
      </div>
      <a href="blog.php" class="inline-flex items-center gap-1 font-semibold text-brand">Read all articles <span class="material-symbols-outlined text-base">arrow_forward</span></a>
    </div>

    <div class="grid gap-8 md:grid-cols-3">
      <?php if (!empty($blog_posts)): ?>
        <?php foreach ($blog_posts as $b): ?>
          <article class="group overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-1 hover:shadow-xl">
            <div class="grid aspect-[16/9] place-items-center bg-gradient-to-br from-navy to-cyan text-white/90">
              <span class="material-symbols-outlined !text-6xl"><?= e($b['icon'] ?: 'article') ?></span>
            </div>
            <div class="p-6">
              <div class="flex items-center justify-between text-xs">
                <span class="rounded-full bg-soft px-3 py-1 font-semibold text-brand"><?= e($b['category_name']) ?></span>
                <span class="text-muted"><?= e($b['reading_time']) ?></span>
              </div>
              <h3 class="mt-3 font-display text-lg font-semibold transition-colors group-hover:text-brand">
                <a href="blog-post.php?slug=<?= urlencode($b['slug']) ?>"><?= e($b['title']) ?></a>
              </h3>
              <p class="mt-2 text-sm leading-6 text-muted line-clamp-2"><?= e($b['excerpt']) ?></p>
            </div>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Final CTA Banner -->
<section class="hero relative overflow-hidden py-24 text-center text-white">
  <div class="grid-bg absolute inset-0"></div>
  <div class="relative mx-auto max-w-3xl px-5">
    <h2 class="font-display text-3xl font-extrabold tracking-tight md:text-5xl">Ready to Build Something Remarkable?</h2>
    <p class="mx-auto mt-5 max-w-2xl text-lg text-slate-300">Tell us about your project or business idea. We will review your brief and send a clear, tailored proposal within 24 hours.</p>
    <div class="mt-9 flex flex-wrap justify-center gap-4">
      <a href="start-project.php" class="btn-cta inline-flex items-center gap-2 rounded-xl px-9 py-4 font-semibold">
        Start Your Project <span class="material-symbols-outlined text-lg">arrow_forward</span>
      </a>
      <a href="contact.php" class="glass inline-flex items-center gap-2 rounded-xl px-9 py-4 font-semibold transition hover:bg-white/15">
        <span class="material-symbols-outlined text-lg">chat</span> Talk to Our Team
      </a>
    </div>
  </div>
</section>

<script>
  // Filter Portfolio cards
  const pBtns = document.querySelectorAll('.pf');
  const pCards = document.querySelectorAll('.pc');
  pBtns.forEach(btn => {
    btn.onclick = () => {
      pBtns.forEach(b => {
        const active = (b === btn);
        b.setAttribute('aria-pressed', active);
        b.className = 'pf rounded-full px-5 py-2 text-sm font-medium transition ' + (active ? 'bg-brand text-white shadow-md shadow-brand/30' : 'bg-white text-muted ring-1 ring-slate-200 hover:text-ink');
      });
      const f = btn.dataset.f;
      pCards.forEach(c => {
        c.style.display = (f === 'all' || c.dataset.c === f) ? '' : 'none';
      });
    };
  });

  // Testimonials track scroll
  const tTrack = document.getElementById('tTrack');
  const getStep = () => tTrack.firstElementChild.offsetWidth + 24;
  const tNext = document.getElementById('tNext');
  const tPrev = document.getElementById('tPrev');
  if (tNext && tTrack) tNext.onclick = () => tTrack.scrollBy({ left: getStep(), behavior: 'smooth' });
  if (tPrev && tTrack) tPrev.onclick = () => tTrack.scrollBy({ left: -getStep(), behavior: 'smooth' });
</script>

<?php include __DIR__ . '/include/footer.php'; ?>