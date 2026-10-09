<?php
/**
 * VPS Digital Services - Disclaimer
 */
require_once __DIR__ . '/include/functions.php';

$page_title = "Disclaimer | VPS Digital Services";
$page_desc  = "General legal disclaimer regarding estimates, project results, external links, and technical content published by VPS.";
$is_solid_header = false;

include __DIR__ . '/include/header.php';
?>

<section class="hero relative overflow-hidden pb-16 pt-32 text-white lg:pb-20 lg:pt-36">
  <div class="grid-bg absolute inset-0"></div>
  <div class="relative mx-auto max-w-[900px] px-5 text-center lg:px-8">
    <span class="glass inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold uppercase tracking-[.16em] text-cyan">
      Legal Notices
    </span>
    <h1 class="mt-6 font-display text-4xl font-extrabold tracking-tight sm:text-5xl">Legal Disclaimer</h1>
    <p class="mx-auto mt-4 max-w-xl text-sm text-slate-300">Effective Date: October 2026</p>
  </div>
</section>

<section class="bg-white py-16 lg:py-24">
  <div class="mx-auto max-w-[900px] px-5 lg:px-8">
    <div class="prose max-w-none text-slate-700 leading-relaxed text-sm space-y-6">

      <h2 class="font-display text-xl font-bold text-ink">1. General Information Only</h2>
      <p>The information contained on the VPS website, including blog articles and project guides, is provided for general informational and educational purposes only and does not constitute formal legal, financial, or architectural engineering certification.</p>

      <h2 class="font-display text-xl font-bold text-ink">2. Demonstration &amp; Showcase Material</h2>
      <p>Selected mockups, case studies, and demonstration records displayed in our marketing portfolio represent capabilities and sample client workflows. Case study metrics are illustrative of historical outcomes and do not guarantee identical results for future projects.</p>

      <h2 class="font-display text-xl font-bold text-ink">3. Third-Party Links &amp; Tools</h2>
      <p>Our website may contain links to external third-party tools, libraries, or service providers. VPS has no control over and assumes no liability for the practices, uptime, or privacy standards of third-party platforms.</p>
    </div>
  </div>
</section>

<?php include __DIR__ . '/include/footer.php'; ?>
