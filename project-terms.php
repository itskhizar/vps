<?php
/**
 * VPS Digital Services - Project Terms and Conditions
 */
require_once __DIR__ . '/include/functions.php';

$page_title = "Project Terms & Conditions | VPS Digital Services";
$page_desc  = "Detailed contractual terms covering project intake, requirements freeze, milestone sign-offs, and warranty support windows.";
$is_solid_header = false;

include __DIR__ . '/include/header.php';
?>

<section class="hero relative overflow-hidden pb-16 pt-32 text-white lg:pb-20 lg:pt-36">
  <div class="grid-bg absolute inset-0"></div>
  <div class="relative mx-auto max-w-[900px] px-5 text-center lg:px-8">
    <span class="glass inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold uppercase tracking-[.16em] text-cyan">
      Project Intake Contract
    </span>
    <h1 class="mt-6 font-display text-4xl font-extrabold tracking-tight sm:text-5xl">Project Terms &amp; Conditions</h1>
    <p class="mx-auto mt-4 max-w-xl text-sm text-slate-300">Effective Date: October 2026</p>
  </div>
</section>

<section class="bg-white py-16 lg:py-24">
  <div class="mx-auto max-w-[900px] px-5 lg:px-8">
    <div class="prose max-w-none text-slate-700 leading-relaxed text-sm space-y-6">

      <h2 class="font-display text-xl font-bold text-ink">1. Scope of Work (SOW) &amp; Requirements Baseline</h2>
      <p>All project development begins with a finalized Scope of Work (SOW) derived from your initial project intake brief. Any functional additions requested after scope finalization will be treated as change orders and quoted separately.</p>

      <h2 class="font-display text-xl font-bold text-ink">2. Milestone Review &amp; Acceptance Window</h2>
      <p>Upon notification of milestone completion, clients have five (5) business days to review deliverables and provide consolidated feedback. In the absence of feedback within this period, the milestone will be deemed accepted to prevent schedule stagnation.</p>

      <h2 class="font-display text-xl font-bold text-ink">3. 30-Day Post-Launch Warranty Support</h2>
      <p>Every custom website, application, or system developed by VPS includes thirty (30) consecutive calendar days of complimentary post-launch bug fixing. This covers any defects or discrepancies between the approved SOW and production behavior.</p>

      <h2 class="font-display text-xl font-bold text-ink">4. Hosting, Domain &amp; Third-Party Services</h2>
      <p>Unless explicitly contracted under an ongoing VPS infrastructure management retainer, hosting accounts, third-party API subscriptions (e.g. Stripe, AWS, Twilio), and domain names remain the direct financial responsibility of the client.</p>
    </div>
  </div>
</section>

<?php include __DIR__ . '/include/footer.php'; ?>
