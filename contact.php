<?php
/**
 * VPS Digital Services - Contact Us Page
 */
require_once __DIR__ . '/include/functions.php';

$page_title = "Contact VPS | Get in Touch with Our Solutions Team";
$page_desc  = "Connect with VPS (V Provide Services) for project inquiries, custom quotes, or technical consulting. Call 03328912706 or WhatsApp +92 332 8912706.";
$is_solid_header = false;

$errors = [];
$success_msg = false;
$contact_phone = get_setting('contact_phone', '03328912706');
$contact_whatsapp = get_setting('contact_whatsapp', '+92 332 8912706');
$clean_whatsapp = preg_replace('/[^0-9]/', '', $contact_whatsapp);
$contact_email = get_setting('contact_email', 'info@vprovideservices.com');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $errors['csrf'] = "Security session expired. Please refresh and try again.";
    }

    $name    = trim($_POST['name'] ?? '');
    $email   = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $phone   = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $privacy = isset($_POST['privacy_accepted']);

    if (empty($name)) {
        $errors['name'] = "Your name is required.";
    }
    if (!$email) {
        $errors['email'] = "A valid email address is required.";
    }
    if (empty($subject)) {
        $errors['subject'] = "Please provide an inquiry subject.";
    }
    if (empty($message)) {
        $errors['message'] = "Please write your message.";
    }
    if (!$privacy) {
        $errors['privacy'] = "Please agree to the privacy policy before submitting.";
    }

    if (empty($errors)) {
        try {
            $db = get_db();
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
            $stmt = $db->prepare("INSERT INTO contact_messages (name, email, phone, subject, message, status, ip_address) VALUES (?, ?, ?, ?, ?, 'New', ?)");
            $stmt->execute([$name, $email, $phone, $subject, $message, $ip]);
            
            $success_msg = "Thank you! Your message has been received. Our team will contact you within one business day.";
            $_POST = []; // Clear form fields
        } catch (Exception $e) {
            error_log("Contact message save failed: " . $e->getMessage());
            $errors['db'] = "Unable to save your message right now. Please message us directly on WhatsApp or email.";
        }
    }
}

include __DIR__ . '/include/header.php';
?>

<!-- Header Banner -->
<section class="hero relative overflow-hidden pb-20 pt-32 text-white lg:pb-28 lg:pt-36">
  <div class="grid-bg absolute inset-0"></div>
  <div class="relative mx-auto max-w-[1280px] px-5 text-center lg:px-8">
    <span class="glass inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold uppercase tracking-[.16em] text-cyan">
      Get in Touch
    </span>
    <h1 class="mt-6 font-display text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
      Let's Discuss Your Project
    </h1>
    <p class="mx-auto mt-4 max-w-2xl text-lg text-slate-300">
      Have a question or looking to build a new platform? Contact our team directly through the form below, by phone, or on WhatsApp.
    </p>
  </div>
</section>

<!-- Main Contact Section -->
<section class="bg-white py-16 lg:py-24">
  <div class="mx-auto max-w-[1280px] px-5 lg:px-8">
    <div class="grid gap-12 lg:grid-cols-12">
      
      <!-- Left: Contact Details & Channels -->
      <div class="space-y-8 lg:col-span-5">
        <div>
          <span class="text-xs font-bold uppercase tracking-[.2em] text-brand">Contact Information</span>
          <h2 class="mt-2 font-display text-3xl font-bold text-ink">We Are Ready to Help</h2>
          <p class="mt-3 text-sm leading-6 text-muted">
            Reach out through your preferred channel. We respond promptly during business hours and monitor critical alerts 24/7.
          </p>
        </div>

        <!-- Info Cards -->
        <div class="space-y-4">
          <div class="flex items-start gap-4 rounded-2xl border border-slate-200 bg-soft p-6">
            <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-brand/10 text-brand">
              <span class="material-symbols-outlined !text-2xl">call</span>
            </span>
            <div>
              <p class="text-xs font-bold uppercase tracking-wider text-muted">Phone (Pakistan)</p>
              <a href="tel:<?= e($contact_phone) ?>" class="mt-1 block font-display text-lg font-bold text-ink hover:text-brand transition-colors">
                <?= e($contact_phone) ?>
              </a>
              <p class="text-xs text-muted">Available Mon to Sat, 9am - 6pm PKT</p>
            </div>
          </div>

          <div class="flex items-start gap-4 rounded-2xl border border-green-200 bg-green-50/50 p-6">
            <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-green-100 text-green-600">
              <span class="material-symbols-outlined !text-2xl">chat</span>
            </span>
            <div>
              <p class="text-xs font-bold uppercase tracking-wider text-green-700">WhatsApp Worldwide</p>
              <a href="https://wa.me/<?= e($clean_whatsapp) ?>" target="_blank" rel="noopener" class="mt-1 block font-display text-lg font-bold text-green-800 hover:text-green-600 transition-colors">
                <?= e($contact_whatsapp) ?>
              </a>
              <p class="text-xs text-green-600">Quick inquiries and voice consultations</p>
            </div>
          </div>

          <div class="flex items-start gap-4 rounded-2xl border border-slate-200 bg-soft p-6">
            <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-brand/10 text-brand">
              <span class="material-symbols-outlined !text-2xl">mail</span>
            </span>
            <div>
              <p class="text-xs font-bold uppercase tracking-wider text-muted">Email Support</p>
              <a href="mailto:<?= e($contact_email) ?>" class="mt-1 block font-display text-lg font-bold text-ink hover:text-brand transition-colors">
                <?= e($contact_email) ?>
              </a>
              <p class="text-xs text-muted">Inquiries reviewed within 24 hours</p>
            </div>
          </div>
        </div>

        <div class="rounded-2xl bg-navy p-6 text-white">
          <h4 class="font-display text-base font-bold text-cyan">Starting a specific project?</h4>
          <p class="mt-2 text-xs text-slate-300">If you have detailed requirements or reference documents to upload, our structured intake form is the fastest route to an accurate estimate.</p>
          <a href="start-project.php" class="btn-cta mt-4 inline-flex items-center gap-1.5 rounded-lg px-4 py-2 text-xs font-semibold">
            Use Project Intake Form <span class="material-symbols-outlined text-sm">arrow_forward</span>
          </a>
        </div>
      </div>

      <!-- Right: Contact Form -->
      <div class="lg:col-span-7">
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl md:p-10">
          <h3 class="font-display text-2xl font-bold text-ink">Send Us a Direct Message</h3>
          <p class="mt-1 text-sm text-muted">Fill out this quick form and our client services lead will respond shortly.</p>

          <?php if ($success_msg): ?>
            <div class="my-6 rounded-2xl border border-green-200 bg-green-50 p-6 text-green-800">
              <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-2xl text-green-600">check_circle</span>
                <p class="font-semibold text-sm"><?= e($success_msg) ?></p>
              </div>
            </div>
          <?php endif; ?>

          <?php if (!empty($errors)): ?>
            <div class="my-6 rounded-2xl border border-red-200 bg-red-50 p-5 text-sm text-red-700">
              <ul class="list-disc list-inside space-y-1">
                <?php foreach ($errors as $err): ?>
                  <li><?= e($err) ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <form action="contact.php" method="post" class="mt-6 space-y-5">
            <?= csrf_field() ?>

            <div class="grid gap-5 sm:grid-cols-2">
              <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-muted mb-2">Your Name <span class="text-cta">*</span></label>
                <input type="text" name="name" required value="<?= e($_POST['name'] ?? '') ?>" placeholder="e.g. Sarah Khan" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-ink focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
              </div>

              <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-muted mb-2">Email Address <span class="text-cta">*</span></label>
                <input type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>" placeholder="sarah@example.com" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-ink focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
              </div>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
              <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-muted mb-2">Phone Number</label>
                <input type="text" name="phone" value="<?= e($_POST['phone'] ?? '') ?>" placeholder="+92 332 8912706" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-ink focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
              </div>

              <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-muted mb-2">Subject <span class="text-cta">*</span></label>
                <input type="text" name="subject" required value="<?= e($_POST['subject'] ?? '') ?>" placeholder="General Inquiry / Project Question" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-ink focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold uppercase tracking-wider text-muted mb-2">Your Message <span class="text-cta">*</span></label>
              <textarea name="message" rows="5" required placeholder="How can VPS help your business? Provide any details or questions..." class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-ink focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand"><?= e($_POST['message'] ?? '') ?></textarea>
            </div>

            <div class="rounded-xl bg-soft p-4 border border-slate-200">
              <label class="flex items-start gap-2.5 text-xs text-slate-700 cursor-pointer">
                <input type="checkbox" name="privacy_accepted" value="1" required class="mt-0.5 rounded text-brand focus:ring-brand">
                <span>I consent to VPS processing my contact details to reply to my inquiry according to the <a href="privacy-policy.php" target="_blank" class="text-brand font-semibold underline">Privacy Policy</a>.</span>
              </label>
            </div>

            <button type="submit" class="btn-cta w-full rounded-xl py-4 text-center font-bold text-sm shadow-xl flex items-center justify-center gap-2">
              Send Message <span class="material-symbols-outlined">send</span>
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>
</section>

<?php include __DIR__ . '/include/footer.php'; ?>
