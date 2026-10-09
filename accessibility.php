<?php
/**
 * VPS Digital Services - Accessibility Statement
 */
require_once __DIR__ . '/include/functions.php';

$page_title = "Accessibility Statement | VPS Digital Services";
$page_desc  = "VPS's commitment to web accessibility (WCAG 2.1 AA) and inclusive digital experiences.";
$is_solid_header = false;

include __DIR__ . '/include/header.php';
?>

<section class="hero relative overflow-hidden pb-16 pt-32 text-white lg:pb-20 lg:pt-36">
  <div class="grid-bg absolute inset-0"></div>
  <div class="relative mx-auto max-w-[900px] px-5 text-center lg:px-8">
    <span class="glass inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold uppercase tracking-[.16em] text-cyan">
      Inclusive Standards
    </span>
    <h1 class="mt-6 font-display text-4xl font-extrabold tracking-tight sm:text-5xl">Accessibility Statement</h1>
    <p class="mx-auto mt-4 max-w-xl text-sm text-slate-300">Last Reviewed: October 2026</p>
  </div>
</section>

<section class="bg-white py-16 lg:py-24">
  <div class="mx-auto max-w-[900px] px-5 lg:px-8">
    <div class="prose max-w-none text-slate-700 leading-relaxed text-sm space-y-6">

      <h2 class="font-display text-xl font-bold text-ink">1. Our Commitment to Digital Accessibility</h2>
      <p>VPS is dedicated to ensuring that its website and interactive web applications are accessible to all visitors, including individuals utilizing assistive screen readers, keyboard-only navigation, and high-contrast color settings.</p>

      <h2 class="font-display text-xl font-bold text-ink">2. Standards &amp; Conformance</h2>
      <p>We actively design and code in alignment with the World Wide Web Consortium's (W3C) Web Content Accessibility Guidelines (WCAG) 2.1 at Level AA conformance. Our implementations prioritize:</p>
      <ul class="list-disc pl-5 space-y-1">
        <li>Semantic HTML5 landmark structures and skip-to-content bypass links.</li>
        <li>High-contrast color palettes exceeding WCAG AA minimum ratios.</li>
        <li>Accessible forms with explicit label associations, aria-attributes, and clear error messaging.</li>
        <li>Respect for operating system <code>prefers-reduced-motion</code> directives by disabling non-essential transitions.</li>
      </ul>

      <h2 class="font-display text-xl font-bold text-ink">3. Feedback &amp; Assistance</h2>
      <p>If you encounter an accessibility hurdle on any VPS page, please notify our team at <a href="mailto:info@vprovideservices.com" class="text-brand font-semibold underline">info@vprovideservices.com</a> or via WhatsApp at <code>+92 332 8912706</code> so we can provide immediate assistance.</p>
    </div>
  </div>
</section>

<?php include __DIR__ . '/include/footer.php'; ?>
