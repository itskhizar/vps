<?php
/**
 * VPS Digital Services - Cookie Policy
 */
require_once __DIR__ . '/include/functions.php';

$page_title = "Cookie Policy | VPS Digital Services";
$page_desc  = "Information on how VPS utilizes cookies and local storage tokens for security, performance, and user preference tracking.";
$is_solid_header = false;

include __DIR__ . '/include/header.php';
?>

<section class="hero relative overflow-hidden pb-16 pt-32 text-white lg:pb-20 lg:pt-36">
  <div class="grid-bg absolute inset-0"></div>
  <div class="relative mx-auto max-w-[900px] px-5 text-center lg:px-8">
    <span class="glass inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold uppercase tracking-[.16em] text-cyan">
      Tracking Transparency
    </span>
    <h1 class="mt-6 font-display text-4xl font-extrabold tracking-tight sm:text-5xl">Cookie Policy</h1>
    <p class="mx-auto mt-4 max-w-xl text-sm text-slate-300">Effective Date: October 2026</p>
  </div>
</section>

<section class="bg-white py-16 lg:py-24">
  <div class="mx-auto max-w-[900px] px-5 lg:px-8">
    <div class="prose max-w-none text-slate-700 leading-relaxed text-sm space-y-6">

      <h2 class="font-display text-xl font-bold text-ink">1. What Are Cookies?</h2>
      <p>Cookies are small text files placed on your browser when visiting websites. They facilitate seamless navigation, preserve session security, and retain user preferences such as banner dismissal and language selections.</p>

      <h2 class="font-display text-xl font-bold text-ink">2. Categories of Cookies We Use</h2>
      <ul class="list-disc pl-5 space-y-2">
        <li><strong>Strictly Essential Cookies:</strong> Critical for CSRF verification, secure user authentication, and maintaining shopping or intake session integrity. Without these, the site cannot function safely.</li>
        <li><strong>Functional &amp; Preference Tokens:</strong> Local storage values (e.g. <code>vps_cookie_consent</code>) that remember your cookie consent preferences and UI state.</li>
        <li><strong>Analytical Cookies:</strong> Aggregated, non-identifying telemetry tracking page response speeds, network errors, and popular service pages to improve navigation.</li>
      </ul>

      <h2 class="font-display text-xl font-bold text-ink">3. Managing and Disabling Cookies</h2>
      <p>You can adjust your cookie settings through your browser preferences. Disabling essential cookies may impair form submissions, CSRF verification, and authenticated dashboard access.</p>

      <h2 class="font-display text-xl font-bold text-ink">4. Policy Updates</h2>
      <p>We may revise this Cookie Policy periodically to reflect technological adjustments or regulatory guidance. Changes become effective immediately upon posting.</p>
    </div>
  </div>
</section>

<?php include __DIR__ . '/include/footer.php'; ?>
