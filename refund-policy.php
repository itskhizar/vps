<?php
/**
 * VPS Digital Services - Refund and Cancellation Policy
 */
require_once __DIR__ . '/include/functions.php';

$page_title = "Refund & Cancellation Policy | VPS Digital Services";
$page_desc  = "Clear and fair terms regarding deposits, milestone sign-offs, cancellations, and refund eligibility for digital services.";
$is_solid_header = false;

include __DIR__ . '/include/header.php';
?>

<section class="hero relative overflow-hidden pb-16 pt-32 text-white lg:pb-20 lg:pt-36">
  <div class="grid-bg absolute inset-0"></div>
  <div class="relative mx-auto max-w-[900px] px-5 text-center lg:px-8">
    <span class="glass inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold uppercase tracking-[.16em] text-cyan">
      Fair Business Practices
    </span>
    <h1 class="mt-6 font-display text-4xl font-extrabold tracking-tight sm:text-5xl">Refund &amp; Cancellation Policy</h1>
    <p class="mx-auto mt-4 max-w-xl text-sm text-slate-300">Effective Date: October 2026</p>
  </div>
</section>

<section class="bg-white py-16 lg:py-24">
  <div class="mx-auto max-w-[900px] px-5 lg:px-8">
    <div class="prose max-w-none text-slate-700 leading-relaxed text-sm space-y-6">

      <h2 class="font-display text-xl font-bold text-ink">1. Milestone-Based Billing Model</h2>
      <p>Because VPS provides customized technical engineering, creative design, and architectural modeling tailored specifically to each client's specifications, services are billed across predefined milestone stages (e.g. Discovery, Prototype, Development, QA, Deployment).</p>

      <h2 class="font-display text-xl font-bold text-ink">2. Deposit &amp; Discovery Phase</h2>
      <p>Initial project kickoff deposits cover scoping, system architecture planning, wireframing, and initial resource reservation. Kickoff deposits become non-refundable once the discovery phase has commenced and preliminary work has been presented.</p>

      <h2 class="font-display text-xl font-bold text-ink">3. Approved Milestones</h2>
      <p>Once a project milestone has been reviewed, demonstrated, and formally signed off or approved by the client, payments allocated to that milestone are fully earned and non-refundable.</p>

      <h2 class="font-display text-xl font-bold text-ink">4. Project Cancellation</h2>
      <p>Either party may terminate a project agreement upon 14 days written notice. In the event of early termination:</p>
      <ul class="list-disc pl-5 space-y-1">
        <li>The client is billed only for work completed up to the date of cancellation notice.</li>
        <li>Any unspent milestone funds in escrow or on account will be promptly refunded within 14 business days.</li>
        <li>All completed files and code up to the paid milestone are transferred to the client.</li>
      </ul>

      <h2 class="font-display text-xl font-bold text-ink">5. Monthly Retainers</h2>
      <p>Monthly support, ecommerce management, or dedicated team retainers may be cancelled at any time prior to the next billing cycle by giving 15 days written notice.</p>
    </div>
  </div>
</section>

<?php include __DIR__ . '/include/footer.php'; ?>
