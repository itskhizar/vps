<?php
/**
 * VPS Digital Services - Intellectual Property Policy
 */
require_once __DIR__ . '/include/functions.php';

$page_title = "Intellectual Property Policy | VPS Digital Services";
$page_desc  = "Learn about client ownership of code, design files, trademarks, and third-party open-source components.";
$is_solid_header = false;

include __DIR__ . '/include/header.php';
?>

<section class="hero relative overflow-hidden pb-16 pt-32 text-white lg:pb-20 lg:pt-36">
  <div class="grid-bg absolute inset-0"></div>
  <div class="relative mx-auto max-w-[900px] px-5 text-center lg:px-8">
    <span class="glass inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold uppercase tracking-[.16em] text-cyan">
      Ownership &amp; Rights
    </span>
    <h1 class="mt-6 font-display text-4xl font-extrabold tracking-tight sm:text-5xl">Intellectual Property Policy</h1>
    <p class="mx-auto mt-4 max-w-xl text-sm text-slate-300">Effective Date: October 2026</p>
  </div>
</section>

<section class="bg-white py-16 lg:py-24">
  <div class="mx-auto max-w-[900px] px-5 lg:px-8">
    <div class="prose max-w-none text-slate-700 leading-relaxed text-sm space-y-6">

      <h2 class="font-display text-xl font-bold text-ink">1. Complete Client Ownership of Custom Deliverables</h2>
      <p>VPS operates on a strict work-for-hire model for custom client deliverables. Upon full settlement of project invoices, all custom written source code, tailored database schemas, graphic designs, vectors, Figma master files, and architectural 3D models become the exclusive intellectual property of the client.</p>

      <h2 class="font-display text-xl font-bold text-ink">2. Open-Source &amp; Third-Party Libraries</h2>
      <p>Deliverables may incorporate open-source libraries, PHP extensions, or JavaScript frameworks (e.g., jQuery, Tailwind CSS, Bootstrap). These components remain licensed under their respective MIT, Apache, BSD, or GPL licenses and do not restrict client ownership of custom application logic.</p>

      <h2 class="font-display text-xl font-bold text-ink">3. Pre-Existing Tools &amp; Boilerplates</h2>
      <p>VPS retains ownership of its internal utility libraries, developer scripts, and generic boilerplate configurations. The client is granted a perpetual, irrevocable, worldwide, royalty-free license to use, modify, and host such tools within the delivered application.</p>

      <h2 class="font-display text-xl font-bold text-ink">4. Portfolio &amp; Case Study Display Rights</h2>
      <p>Unless a formal Non-Disclosure Agreement (NDA) specifies otherwise, VPS reserves the right to showcase completed client work, screenshots, and high-level case study summaries in our marketing portfolio.</p>
    </div>
  </div>
</section>

<?php include __DIR__ . '/include/footer.php'; ?>
