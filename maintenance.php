<?php
/**
 * VPS Digital Services - Maintenance Mode Page
 */
require_once __DIR__ . '/include/functions.php';

$page_title = "Scheduled Maintenance | VPS Digital Services";
$page_desc  = "VPS digital systems are currently undergoing scheduled maintenance.";
$is_solid_header = false;

include __DIR__ . '/include/header.php';
?>

<section class="hero relative overflow-hidden pb-28 pt-36 text-white min-h-[70vh] flex items-center">
  <div class="grid-bg absolute inset-0"></div>
  <div class="relative mx-auto max-w-[800px] px-5 text-center">
    <div class="mx-auto mb-6 grid h-20 w-20 place-items-center rounded-3xl bg-brand/20 text-cyan">
      <span class="material-symbols-outlined !text-5xl animate-spin">sync</span>
    </div>
    <h1 class="font-display text-3xl font-extrabold tracking-tight sm:text-5xl">Scheduled System Maintenance</h1>
    <p class="mx-auto mt-4 max-w-lg text-base text-slate-300">
      We are currently deploying performance and security updates. All systems will resume momentarily. For urgent inquiries, reach out on WhatsApp.
    </p>
    <div class="mt-8 flex justify-center gap-4">
      <a href="https://wa.me/923328912706" target="_blank" rel="noopener" class="btn-cta inline-flex items-center gap-2 rounded-xl px-7 py-3.5 font-semibold">
        WhatsApp Emergency Support
      </a>
    </div>
  </div>
</section>

<?php include __DIR__ . '/include/footer.php'; ?>
