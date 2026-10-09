<?php
/**
 * VPS Digital Services - 403 Forbidden
 */
http_response_code(403);
require_once __DIR__ . '/include/functions.php';

$page_title = "403 - Forbidden | VPS Digital Services";
$page_desc  = "Access to this area of VPS is restricted.";
$is_solid_header = false;

include __DIR__ . '/include/header.php';
?>

<section class="hero relative overflow-hidden pb-28 pt-36 text-white min-h-[70vh] flex items-center">
  <div class="grid-bg absolute inset-0"></div>
  <div class="relative mx-auto max-w-[800px] px-5 text-center">
    <p class="font-display text-8xl font-extrabold text-cta">403</p>
    <h1 class="mt-4 font-display text-3xl font-extrabold tracking-tight sm:text-5xl">Access Denied</h1>
    <p class="mx-auto mt-4 max-w-lg text-base text-slate-300">
      You do not have administrative clearance to access this directory or protected resource.
    </p>
    <div class="mt-8 flex justify-center gap-4">
      <a href="index.php" class="btn-cta inline-flex items-center gap-2 rounded-xl px-7 py-3.5 font-semibold">
        Return to Safety
      </a>
      <a href="admin/login.php" class="glass inline-flex items-center gap-2 rounded-xl px-7 py-3.5 font-semibold hover:bg-white/15">
        Staff Sign In
      </a>
    </div>
  </div>
</section>

<?php include __DIR__ . '/include/footer.php'; ?>
