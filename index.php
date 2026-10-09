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

    <!-- Animated Hero Illustration / High-Tech Interactive Dashboard -->
    <div class="relative lg:col-span-6">
      <!-- Ambient Glow Orb Behind Main Card -->
      <div class="absolute -inset-4 rounded-3xl bg-gradient-to-r from-brand/30 via-cyan/20 to-purple-600/30 blur-2xl animate-pulse-glow -z-10"></div>

      <!-- Main High-Fidelity Glassmorphic Dashboard Window -->
      <div class="glass fl rounded-2xl p-3 shadow-2xl border border-white/15 backdrop-blur-xl bg-[#0A122C]/85">
        <div class="overflow-hidden rounded-xl bg-[#090F26] border border-white/10">
          <!-- Window Header / Browser Chrome -->
          <div class="flex items-center justify-between border-b border-white/10 px-4 py-3 bg-[#070D22]/90">
            <div class="flex items-center gap-2">
              <span class="h-3 w-3 rounded-full bg-rose-500/90 shadow-sm"></span>
              <span class="h-3 w-3 rounded-full bg-amber-400/90 shadow-sm"></span>
              <span class="h-3 w-3 rounded-full bg-emerald-400/90 shadow-sm"></span>
            </div>
            <div class="flex items-center gap-2 rounded-lg bg-white/5 px-3 py-1 text-xs text-slate-300 font-mono border border-white/5">
              <span class="material-symbols-outlined text-cyan text-sm">lock</span>
              <span>vprovideservices.com/pipeline</span>
            </div>
            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/10 px-2.5 py-0.5 text-[10px] font-semibold text-emerald-400 border border-emerald-500/20">
              <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
              Live Systems
            </span>
          </div>

          <!-- Dashboard Content -->
          <div class="p-5 space-y-4">
            <!-- Active Sprint Banner -->
            <div class="rounded-xl bg-gradient-to-r from-brand/90 via-blue-600/90 to-cyan/80 p-4 shadow-lg text-white relative overflow-hidden">
              <div class="absolute -right-6 -bottom-6 h-28 w-28 rounded-full bg-white/10 blur-xl"></div>
              <div class="flex items-center justify-between">
                <div>
                  <span class="text-[10px] font-bold uppercase tracking-widest text-cyan-200">Global Operations</span>
                  <h4 class="text-base font-bold font-display mt-0.5">Active Project Delivery Pipeline</h4>
                </div>
                <span class="rounded-lg bg-white/20 backdrop-blur-md px-2.5 py-1 text-xs font-bold font-mono">99.4% On-Time</span>
              </div>
              <!-- Progress bar -->
              <div class="mt-3">
                <div class="flex justify-between text-[11px] text-white/80 font-medium mb-1">
                  <span>Sprint Velocity &amp; Milestones</span>
                  <span>100% Quality Audited</span>
                </div>
                <div class="h-2 w-full rounded-full bg-black/25 overflow-hidden">
                  <div class="h-full rounded-full bg-gradient-to-r from-cyan via-white to-amber-300" style="width: 94%"></div>
                </div>
              </div>
            </div>

            <!-- 3 Capability Columns -->
            <div class="grid grid-cols-3 gap-3">
              <div class="rounded-xl bg-white/[0.04] p-3 border border-white/5 hover:border-cyan/30 transition-colors">
                <div class="grid h-8 w-8 place-items-center rounded-lg bg-brand/20 text-cyan mb-2">
                  <span class="material-symbols-outlined text-base">code</span>
                </div>
                <div class="text-xs font-bold text-white">Full-Stack</div>
                <div class="text-[10px] text-slate-400 font-mono mt-0.5">PHP • Flutter</div>
              </div>

              <div class="rounded-xl bg-white/[0.04] p-3 border border-white/5 hover:border-cyan/30 transition-colors">
                <div class="grid h-8 w-8 place-items-center rounded-lg bg-cyan/20 text-cyan mb-2">
                  <span class="material-symbols-outlined text-base">palette</span>
                </div>
                <div class="text-xs font-bold text-white">UI/UX Craft</div>
                <div class="text-[10px] text-slate-400 font-mono mt-0.5">Figma • 3D</div>
              </div>

              <div class="rounded-xl bg-white/[0.04] p-3 border border-white/5 hover:border-cyan/30 transition-colors">
                <div class="grid h-8 w-8 place-items-center rounded-lg bg-emerald-500/20 text-emerald-400 mb-2">
                  <span class="material-symbols-outlined text-base">analytics</span>
                </div>
                <div class="text-xs font-bold text-white">Analytics</div>
                <div class="text-[10px] text-slate-400 font-mono mt-0.5">Python • BI</div>
              </div>
            </div>

            <!-- Dynamic Activity Waveform / Chart -->
            <div class="rounded-xl bg-white/[0.03] p-3.5 border border-white/5">
              <div class="flex items-center justify-between text-xs text-slate-400 mb-2.5">
                <span class="font-medium">Continuous Deployment Activity</span>
                <span class="font-mono text-[11px] text-cyan">24/7 Monitored</span>
              </div>
              <div class="flex h-16 items-end gap-2 px-1">
                <div class="flex-1 rounded-t bg-gradient-to-t from-brand/60 to-cyan h-[45%] transition-all hover:brightness-125"></div>
                <div class="flex-1 rounded-t bg-gradient-to-t from-brand/60 to-cyan h-[65%] transition-all hover:brightness-125"></div>
                <div class="flex-1 rounded-t bg-gradient-to-t from-brand/60 to-cyan h-[50%] transition-all hover:brightness-125"></div>
                <div class="flex-1 rounded-t bg-gradient-to-t from-brand/60 to-cyan h-[85%] transition-all hover:brightness-125"></div>
                <div class="flex-1 rounded-t bg-gradient-to-t from-brand/60 to-cyan h-[70%] transition-all hover:brightness-125"></div>
                <div class="flex-1 rounded-t bg-gradient-to-t from-brand/60 to-cyan h-[95%] transition-all hover:brightness-125"></div>
                <div class="flex-1 rounded-t bg-gradient-to-t from-brand/60 to-cyan h-[80%] transition-all hover:brightness-125"></div>
                <div class="flex-1 rounded-t bg-gradient-to-t from-brand/60 to-cyan h-[100%] transition-all hover:brightness-125"></div>
                <div class="flex-1 rounded-t bg-gradient-to-t from-brand/60 to-cyan h-[90%] transition-all hover:brightness-125"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Floating Glass Badge 1 (Top-Right): Global Client Satisfaction -->
      <div class="animate-float absolute -top-6 -right-3 sm:-right-6 z-20 rounded-2xl border border-white/15 bg-navy/90 p-3.5 shadow-2xl backdrop-blur-xl">
        <div class="flex items-center gap-3">
          <div class="grid h-10 w-10 place-items-center rounded-xl bg-amber-400/20 text-amber-300">
            <span class="material-symbols-outlined text-xl">star</span>
          </div>
          <div>
            <div class="flex items-center gap-1 text-xs font-bold text-amber-300">
              <span>★ 4.9 / 5.0</span>
              <span class="text-[10px] text-slate-400 font-normal">(80+ Reviews)</span>
            </div>
            <div class="text-[11px] font-semibold text-white">Client Satisfaction Rating</div>
          </div>
        </div>
      </div>

      <!-- Floating Glass Badge 2 (Bottom-Left): Turnaround Guarantee -->
      <div class="animate-float-reverse absolute -bottom-6 -left-3 sm:-left-6 z-20 rounded-2xl border border-white/15 bg-navy/90 p-3.5 shadow-2xl backdrop-blur-xl">
        <div class="flex items-center gap-3">
          <div class="grid h-10 w-10 place-items-center rounded-xl bg-cyan/20 text-cyan">
            <span class="material-symbols-outlined text-xl">bolt</span>
          </div>
          <div>
            <div class="text-xs font-bold text-white flex items-center gap-1.5">
              <span>24h Turnaround</span>
              <span class="h-2 w-2 rounded-full bg-cyan animate-pulse"></span>
            </div>
            <div class="text-[11px] text-slate-400">Detailed Scope &amp; Quote</div>
          </div>
        </div>
      </div>

      <!-- Floating Glass Badge 3 (Right Middle): Full IP Ownership -->
      <div class="animate-float absolute -right-2 bottom-16 sm:-right-8 z-20 hidden sm:flex items-center gap-2.5 rounded-xl border border-white/15 bg-navy/90 px-4 py-2.5 text-xs font-semibold text-white shadow-xl backdrop-blur-xl">
        <span class="material-symbols-outlined text-emerald-400 text-lg">verified_user</span>
        <span>100% IP &amp; Code Ownership</span>
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

<!-- Dynamic Services Overview Section (Pixel-Perfect Alignment, No Numbers) -->
<section id="services" class="bg-white py-20 lg:py-28">
  <div class="mx-auto max-w-[1280px] px-5 lg:px-8">
    <div class="reveal mb-14 max-w-2xl mx-auto text-center">
      <span class="inline-flex items-center gap-2 rounded-full bg-brand/5 px-4 py-1.5 text-xs font-bold uppercase tracking-[.2em] text-brand border border-brand/10">
        Specialist Capabilities
      </span>
      <h2 class="mt-4 font-display text-3xl font-extrabold tracking-tight text-ink md:text-[42px] md:leading-tight">
        Everything You Need to Launch, Brand &amp; Scale
      </h2>
      <p class="mt-4 text-base md:text-lg leading-relaxed text-muted">
        Tailored digital, engineering, and creative services loaded dynamically from MySQL, built for long-term reliability and high conversion.
      </p>
    </div>

    <!-- Pixel-Perfect Equal-Height Grid -->
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4 items-stretch">
      <?php if (!empty($services)): ?>
        <?php foreach ($services as $idx => $s): ?>
          <div class="group reveal relative flex flex-col justify-between h-full overflow-hidden rounded-2xl border border-slate-200/90 bg-white p-7 transition-all duration-300 hover:-translate-y-2 hover:border-brand/40 hover:shadow-2xl hover:shadow-brand/10">
            <!-- Top Section: Icon & Category Badge -->
            <div>
              <div class="flex items-center justify-between">
                <div class="grid h-14 w-14 place-items-center rounded-2xl bg-gradient-to-br from-brand to-cyan text-white shadow-lg shadow-brand/25 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                  <span class="material-symbols-outlined !text-2xl"><?= e($s['icon'] ?: 'code') ?></span>
                </div>
                <span class="rounded-full bg-soft px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-brand border border-brand/10">
                  <?= e($s['category'] ?: 'Service') ?>
                </span>
              </div>

              <!-- Title & Description (Consistent Line-Clamp & Heights) -->
              <h3 class="mt-5 font-display text-xl font-bold text-ink group-hover:text-brand transition-colors line-clamp-1 min-h-[28px]">
                <a href="service-details.php?slug=<?= urlencode($s['slug']) ?>" class="focus:outline-none">
                  <?= e($s['title']) ?>
                </a>
              </h3>

              <p class="mt-2.5 text-sm leading-relaxed text-muted line-clamp-2 min-h-[44px]">
                <?= e($s['short_desc']) ?>
              </p>

              <!-- Feature Tags (Neat, Uniform Pills) -->
              <?php if (!empty($s['features'])): 
                $feats = array_slice(explode(',', $s['features']), 0, 3);
              ?>
                <div class="mt-4 flex flex-wrap gap-1.5 min-h-[52px]">
                  <?php foreach ($feats as $f): ?>
                    <span class="inline-flex items-center gap-1 rounded-lg bg-slate-50 px-2.5 py-1 text-[11px] font-medium text-slate-600 border border-slate-100">
                      <span class="material-symbols-outlined text-xs text-brand">check</span>
                      <?= e(trim($f)) ?>
                    </span>
                  <?php endforeach; ?>
                </div>
              <?php else: ?>
                <div class="mt-4 min-h-[52px]"></div>
              <?php endif; ?>
            </div>

            <!-- Bottom Action Bar (Uniformly Pushed to Base) -->
            <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-between">
              <a href="service-details.php?slug=<?= urlencode($s['slug']) ?>" class="inline-flex items-center gap-1.5 text-xs font-bold text-brand transition-all group-hover:gap-2">
                <span>Explore Scope</span>
                <span class="material-symbols-outlined text-base">arrow_forward</span>
              </a>
              <a href="start-project.php?service=<?= urlencode($s['slug']) ?>" class="rounded-lg bg-soft px-3 py-1 text-xs font-semibold text-slate-700 transition hover:bg-cta hover:text-white">
                Get Quote
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <div class="mt-12 text-center">
      <a href="services.php" class="inline-flex items-center gap-2 rounded-xl bg-soft px-8 py-4 font-semibold text-brand ring-1 ring-slate-200 transition-all duration-300 hover:bg-brand hover:text-white hover:shadow-lg hover:shadow-brand/20">
        Explore All 10 Specialist Services <span class="material-symbols-outlined text-lg">arrow_forward</span>
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