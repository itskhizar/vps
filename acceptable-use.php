<?php
/**
 * VPS Digital Services - Acceptable Use Policy
 */
require_once __DIR__ . '/include/functions.php';

$page_title = "Acceptable Use Policy | VPS Digital Services";
$page_desc  = "Standards of acceptable conduct when accessing VPS digital infrastructure, forms, and client portals.";
$is_solid_header = false;

include __DIR__ . '/include/header.php';
?>

<section class="hero relative overflow-hidden pb-16 pt-32 text-white lg:pb-20 lg:pt-36">
  <div class="grid-bg absolute inset-0"></div>
  <div class="relative mx-auto max-w-[900px] px-5 text-center lg:px-8">
    <span class="glass inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold uppercase tracking-[.16em] text-cyan">
      Infrastructure Security
    </span>
    <h1 class="mt-6 font-display text-4xl font-extrabold tracking-tight sm:text-5xl">Acceptable Use Policy</h1>
    <p class="mx-auto mt-4 max-w-xl text-sm text-slate-300">Effective Date: October 2026</p>
  </div>
</section>

<section class="bg-white py-16 lg:py-24">
  <div class="mx-auto max-w-[900px] px-5 lg:px-8">
    <div class="prose max-w-none text-slate-700 leading-relaxed text-sm space-y-6">

      <h2 class="font-display text-xl font-bold text-ink">1. Permitted Usage</h2>
      <p>Users may access the VPS website and interactive project intake forms solely for legitimate inquiries, contracting professional digital services, and reviewing company insights.</p>

      <h2 class="font-display text-xl font-bold text-ink">2. Prohibited Conduct</h2>
      <p>Users are strictly prohibited from:</p>
      <ul class="list-disc pl-5 space-y-1">
        <li>Attempting SQL injections, cross-site scripting (XSS), or automated vulnerability scanning.</li>
        <li>Uploading executable scripts (.php, .exe, .sh, .bat) disguised as requirement attachments.</li>
        <li>Executing automated credential stuffing or brute-forcing administrative login portals.</li>
        <li>Scraping content or media for illicit commercial reproduction.</li>
      </ul>

      <h2 class="font-display text-xl font-bold text-ink">3. Enforcement &amp; Legal Referral</h2>
      <p>Violations of this Acceptable Use Policy will result in immediate IP banning and, where criminal intent is identified, referral to cybercrime authorities under the Prevention of Electronic Crimes Act (PECA) of Pakistan and relevant international agencies.</p>
    </div>
  </div>
</section>

<?php include __DIR__ . '/include/footer.php'; ?>
