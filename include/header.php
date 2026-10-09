<?php
/**
 * VPS Header Component
 */
require_once __DIR__ . '/functions.php';

$page_title = $page_title ?? get_setting('meta_title', 'VPS | V Provide Services: Web, App, Design, Data & Architecture Solutions Worldwide');
$page_desc  = $page_desc ?? get_setting('meta_description', 'VPS is a premier global digital agency providing high-performance website development, mobile apps, graphic design, branding, ecommerce management, data analytics, and 3D architectural visualization.');
$page_keywords = $page_keywords ?? 'VPS, V Provide Services, web development company Pakistan, mobile app development, UI UX design, ecommerce management, data science, architectural 3D rendering, Flutter apps, PHP developers';
$current_page = basename($_SERVER['PHP_SELF'], '.php');
if ($current_page === 'index') $current_page = 'home';
$contact_phone = get_setting('contact_phone', '03328912706');
$contact_whatsapp = get_setting('contact_whatsapp', '+92 332 8912706');
$clean_whatsapp = preg_replace('/[^0-9]/', '', $contact_whatsapp);
$is_solid_header = $is_solid_header ?? false;

// Canonical URL Resolution
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$req_uri = strtok($_SERVER["REQUEST_URI"] ?? '/', '?');
$canonical_url = $canonical_url ?? ($protocol . "://" . $host . $req_uri);
$og_image = $og_image ?? ($protocol . "://" . $host . "/vprovideservices/images/logo.png");
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= e($page_title) ?></title>
  
  <!-- Comprehensive Technical & On-Page SEO -->
  <meta name="description" content="<?= e($page_desc) ?>">
  <meta name="keywords" content="<?= e($page_keywords) ?>">
  <meta name="author" content="V Provide Services (VPS)">
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
  <link rel="canonical" href="<?= e($canonical_url) ?>">
  
  <!-- Geo Meta Tags for Regional & Global Search -->
  <meta name="geo.region" content="PK-IS">
  <meta name="geo.placename" content="Islamabad, Pakistan">
  <meta name="geo.position" content="33.6844;73.0479">
  <meta name="ICBM" content="33.6844, 73.0479">
  
  <!-- Open Graph / Facebook Protocol -->
  <meta property="og:locale" content="en_US">
  <meta property="og:type" content="<?= $og_type ?? 'website' ?>">
  <meta property="og:site_name" content="VPS — V Provide Services">
  <meta property="og:title" content="<?= e($page_title) ?>">
  <meta property="og:description" content="<?= e($page_desc) ?>">
  <meta property="og:url" content="<?= e($canonical_url) ?>">
  <meta property="og:image" content="<?= e($og_image) ?>">
  <meta property="og:image:alt" content="VPS Digital Services Worldwide">
  
  <!-- Twitter Cards -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:site" content="@vprovideservices">
  <meta name="twitter:title" content="<?= e($page_title) ?>">
  <meta name="twitter:description" content="<?= e($page_desc) ?>">
  <meta name="twitter:image" content="<?= e($og_image) ?>">
  
  <!-- Schema.org JSON-LD Structured Data for Top Search Engine Ranking -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "ProfessionalService",
    "name": "VPS — V Provide Services",
    "url": "<?= e($canonical_url) ?>",
    "logo": "<?= e($og_image) ?>",
    "description": "<?= e($page_desc) ?>",
    "telephone": "+923328912706",
    "email": "devworkspace3300@gmail.com",
    "priceRange": "$$",
    "address": {
      "@type": "PostalAddress",
      "addressLocality": "Islamabad",
      "addressCountry": "PK"
    },
    "geo": {
      "@type": "GeoCoordinates",
      "latitude": 33.6844,
      "longitude": 73.0479
    },
    "openingHoursSpecification": {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
      "opens": "09:00",
      "closes": "18:00"
    },
    "sameAs": [
      "https://wa.me/923328912706"
    ]
  }
  </script>
  <?php if (!empty($schema_json)): ?>
  <script type="application/ld+json">
  <?= $schema_json ?>
  </script>
  <?php endif; ?>

  <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 40 40'%3E%3Cpolygon points='4,4 14,4 20,13.75 20,30' fill='%230A1128'/%3E%3Cpolygon points='36,4 26,4 20,13.75 20,30' fill='%232F54EB'/%3E%3Ccircle cx='20' cy='35' r='3.2' fill='%23FF7A1A'/%3E%3C/svg%3E">
  
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: '#2F54EB',
            navy: '#0A1128',
            cyan: '#14C8E8',
            cta: '#FF7A1A',
            soft: '#F5F7FB',
            ink: '#1B2236',
            muted: '#6B7690'
          },
          fontFamily: {
            display: ['Plus Jakarta Sans', 'sans-serif'],
            sans: ['Inter', 'sans-serif']
          }
        }
      }
    }
  </script>
  
  <style>
    html, body { overflow-x: hidden; }
    html { scroll-padding-top: 90px; }
    body {
      font-family: Inter, sans-serif;
      color: #1B2236;
      background: #fff;
      -webkit-font-smoothing: antialiased;
    }
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400;
      line-height: 1;
      vertical-align: middle;
    }
    .btn-cta {
      background: #FF7A1A;
      color: #fff;
      box-shadow: 0 10px 24px rgba(255, 122, 26, .35);
      transition: all .25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .btn-cta:hover {
      background: #E6680C;
      transform: translateY(-2px);
      box-shadow: 0 14px 28px rgba(255, 122, 26, .45);
    }
    
    /* Smooth Scroll Reveals */
    .reveal {
      opacity: 0;
      transform: translateY(28px);
      transition: opacity .8s cubic-bezier(0.16, 1, 0.3, 1), transform .8s cubic-bezier(0.16, 1, 0.3, 1);
      will-change: opacity, transform;
    }
    .reveal.in {
      opacity: 1;
      transform: translateY(0);
    }
    .delay-100 { transition-delay: 100ms; }
    .delay-150 { transition-delay: 150ms; }
    .delay-200 { transition-delay: 200ms; }
    .delay-300 { transition-delay: 300ms; }
    .delay-400 { transition-delay: 400ms; }

    /* Custom Animation Keyframes */
    @keyframes float-slow {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-8px); }
    }
    @keyframes float-reverse {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(8px); }
    }
    @keyframes pulse-glow {
      0%, 100% { opacity: 0.35; transform: scale(1); }
      50% { opacity: 0.7; transform: scale(1.06); }
    }
    @keyframes radar-ping {
      0% { transform: scale(0.95); opacity: 0.8; }
      100% { transform: scale(1.8); opacity: 0; }
    }
    .animate-float { animation: float-slow 5s ease-in-out infinite; }
    .animate-float-reverse { animation: float-reverse 6s ease-in-out infinite; }
    .animate-pulse-glow { animation: pulse-glow 4s ease-in-out infinite; }
    .animate-radar { animation: radar-ping 2s cubic-bezier(0, 0, 0.2, 1) infinite; }

    :focus-visible {
      outline: 3px solid #14C8E8;
      outline-offset: 2px;
    }
    .hide-sb { scrollbar-width: none; }
    .hide-sb::-webkit-scrollbar { display: none; }
    
    #hdr { transition: .3s ease; }
    #hdr.solid, #hdr.always-solid {
      background: rgba(255, 255, 255, .96);
      backdrop-filter: blur(16px);
      box-shadow: 0 1px 0 rgba(15, 23, 42, .06), 0 8px 24px rgba(15, 23, 42, .05);
    }
    #hdr .mk { --logo-left: #fff; }
    #hdr.solid .mk, #hdr.always-solid .mk { --logo-left: #0A1128; }
    .lg-t { color: #fff; }
    .lg-s { color: #14C8E8; }
    #hdr.solid .lg-t, #hdr.always-solid .lg-t { color: #0A1128; }
    #hdr.solid .lg-s, #hdr.always-solid .lg-s { color: #2F54EB; }
    .nl { color: #cbd5e1; }
    .nl:hover, .nl.act { color: #fff; }
    #hdr.solid .nl, #hdr.always-solid .nl { color: #6B7690; }
    #hdr.solid .nl:hover, #hdr.always-solid .nl:hover { color: #1B2236; }
    #hdr.solid .nl.act, #hdr.always-solid .nl.act { color: #2F54EB; font-weight: 700; }
    #menuBtn { color: #fff; }
    #hdr.solid #menuBtn, #hdr.always-solid #menuBtn { color: #1B2236; }
    
    .hero {
      background: radial-gradient(60rem 40rem at 85% -10%, rgba(47, 84, 235, .45), transparent 60%),
                  radial-gradient(40rem 30rem at -10% 110%, rgba(20, 200, 232, .22), transparent 60%),
                  #0A1128;
    }
    .grid-bg {
      background-image: linear-gradient(rgba(255, 255, 255, .04) 1px, transparent 1px),
                        linear-gradient(90deg, rgba(255, 255, 255, .04) 1px, transparent 1px);
      background-size: 48px 48px;
      -webkit-mask-image: radial-gradient(ellipse at center, #000 30%, transparent 75%);
      mask-image: radial-gradient(ellipse at center, #000 30%, transparent 75%);
    }
    .glass {
      background: rgba(255, 255, 255, .08);
      border: 1px solid rgba(255, 255, 255, .14);
      backdrop-filter: blur(14px);
    }
    .mq { animation: mq 38s linear infinite; }
    @keyframes mq { to { transform: translateX(-50%); } }
    @keyframes fl { 50% { transform: translateY(-10px); } }
    .fl { animation: fl 6s ease-in-out infinite; }
    details summary { list-style: none; cursor: pointer; }
    details summary::-webkit-details-marker { display: none; }
    details[open] .chev { transform: rotate(180deg); }
    details[open] { box-shadow: 0 12px 30px rgba(47, 84, 235, .1); }

    /* Portfolio tall image scroll effect */
    .tall-img-wrap {
      overflow: hidden;
      position: relative;
    }
    .tall-img-wrap img {
      transition: transform 3.5s cubic-bezier(0.25, 1, 0.5, 1);
      transform: translateY(0);
    }
    .tall-img-wrap:hover img {
      transform: translateY(calc(-100% + 280px));
    }
    @media (prefers-reduced-motion: reduce) {
      * { animation: none !important; transition: none !important; }
      .reveal { opacity: 1; transform: none; }
    }
  </style>
</head>
<body>
  <!-- Vector SVG Defs -->
  <svg width="0" height="0" class="absolute" aria-hidden="true">
    <defs>
      <linearGradient id="vpsGrad" x1="0" y1="0" x2="1" y2="1">
        <stop offset="0" stop-color="#2F54EB"/>
        <stop offset="1" stop-color="#14C8E8"/>
      </linearGradient>
      <symbol id="vps-mark" viewBox="0 0 40 40">
        <polygon points="4,4 14,4 20,13.75 20,30" fill="var(--logo-left,#0A1128)"/>
        <polygon points="36,4 26,4 20,13.75 20,30" fill="url(#vpsGrad)"/>
        <circle cx="20" cy="35" r="3.2" fill="#FF7A1A"/>
      </symbol>
    </defs>
  </svg>

  <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-3 focus:top-3 focus:z-[100] focus:rounded focus:bg-white focus:p-3">Skip to content</a>

  <!-- Navigation Header -->
  <header id="hdr" class="fixed inset-x-0 top-0 z-50 <?= $is_solid_header ? 'always-solid' : '' ?>">
    <div class="mx-auto flex h-20 max-w-[1280px] items-center justify-between px-5 lg:px-8">
      <a href="index.php" class="flex items-center gap-2.5" aria-label="VPS - V Provide Services">
        <svg class="mk h-9 w-9" viewBox="0 0 40 40" aria-hidden="true"><use href="#vps-mark"/></svg>
        <span class="leading-none">
          <span class="block font-display text-2xl font-extrabold tracking-tight lg-t">VPS</span>
          <span class="mt-1 block text-[9px] font-semibold tracking-[.22em] lg-s">V PROVIDE SERVICES</span>
        </span>
      </a>

      <nav class="hidden items-center gap-8 lg:flex" aria-label="Main Navigation">
        <a href="index.php" class="nl text-sm font-medium transition-colors <?= ($current_page === 'home' || $current_page === 'index') ? 'act' : '' ?>">Home</a>
        <a href="about.php" class="nl text-sm font-medium transition-colors <?= $current_page === 'about' ? 'act' : '' ?>">About</a>
        <a href="services.php" class="nl text-sm font-medium transition-colors <?= (strpos($current_page, 'service') !== false) ? 'act' : '' ?>">Services</a>
        <a href="portfolio.php" class="nl text-sm font-medium transition-colors <?= (strpos($current_page, 'portfolio') !== false || strpos($current_page, 'case-study') !== false) ? 'act' : '' ?>">Portfolio</a>
        <a href="team.php" class="nl text-sm font-medium transition-colors <?= (strpos($current_page, 'team') !== false) ? 'act' : '' ?>">Team</a>
        <a href="blog.php" class="nl text-sm font-medium transition-colors <?= (strpos($current_page, 'blog') !== false) ? 'act' : '' ?>">Blog</a>
        <a href="contact.php" class="nl text-sm font-medium transition-colors <?= $current_page === 'contact' ? 'act' : '' ?>">Contact</a>
      </nav>

      <div class="flex items-center gap-3">
        <a href="start-project.php" class="btn-cta hidden rounded-lg px-5 py-2.5 text-sm font-semibold sm:inline-flex">Start a Project</a>
        <button id="menuBtn" aria-label="Toggle menu" aria-expanded="false" class="rounded-lg p-2 lg:hidden">
          <span class="material-symbols-outlined">menu</span>
        </button>
      </div>
    </div>

    <!-- Mobile Drawer Menu -->
    <div id="mobileMenu" class="hidden border-t border-slate-100 bg-white px-5 pb-5 lg:hidden">
      <a href="index.php" class="block border-b border-slate-100 py-3 text-ink font-medium">Home</a>
      <a href="about.php" class="block border-b border-slate-100 py-3 text-ink font-medium">About</a>
      <a href="services.php" class="block border-b border-slate-100 py-3 text-ink font-medium">Services</a>
      <a href="portfolio.php" class="block border-b border-slate-100 py-3 text-ink font-medium">Portfolio</a>
      <a href="team.php" class="block border-b border-slate-100 py-3 text-ink font-medium">Team</a>
      <a href="blog.php" class="block border-b border-slate-100 py-3 text-ink font-medium">Blog</a>
      <a href="contact.php" class="block border-b border-slate-100 py-3 text-ink font-medium">Contact</a>
      <a href="start-project.php" class="btn-cta mt-4 block rounded-lg px-5 py-3 text-center font-semibold">Start a Project</a>
    </div>
  </header>
  <main id="main">
