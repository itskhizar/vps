<?php
/**
 * VPS Digital Services - 404 Page Not Found
 */
http_response_code(404);
require_once __DIR__ . '/include/functions.php';

$page_title = "404 - Page Not Found | VPS Digital Services";
$page_desc  = "The requested page could not be located on the VPS server.";
$is_solid_header = false;

include __DIR__ . '/include/header.php';
?>

<section class="hero relative overflow-hidden pb-28 pt-36 text-white min-h-[70vh] flex items-center">
  <div class="grid-bg absolute inset-0"></div>
  <div class="relative mx-auto max-w-[800px] px-5 text-center">
    <p class="font-display text-8xl font-extrabold text-cyan">404</p>
    <h1 class="mt-4 font-display text-3xl font-extrabold tracking-tight sm:text-5xl">Page Not Found</h1>
    <p class="mx-auto mt-4 max-w-lg text-base text-slate-300">
      The page or resource you are looking for may have moved, been renamed, or does not exist.
    </p>
    <div class="mt-8 flex flex-wrap justify-center gap-4">
      <a href="index.php" class="btn-cta inline-flex items-center gap-2 rounded-xl px-7 py-3.5 font-semibold">
        <span class="material-symbols-outlined">home</span> Return to Home
      </a>
      <a href="services.php" class="glass inline-flex items-center gap-2 rounded-xl px-7 py-3.5 font-semibold hover:bg-white/15">
        Explore Services
      </a>
    </div>
  </div>
</section>

<?php include __DIR__ . '/include/footer.php'; ?>
