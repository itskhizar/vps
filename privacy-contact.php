<?php
/**
 * VPS Digital Services - Data Request & Privacy Contact
 */
require_once __DIR__ . '/include/functions.php';

$page_title = "Data Request & Privacy Contact | VPS Digital Services";
$page_desc  = "Submit formal requests regarding your personal information, data export, or deletion under global data protection principles.";
$is_solid_header = false;

$success_msg = false;
$error_msg = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $error_msg = "Session expired. Please try again.";
    } else {
        $name = trim($_POST['name'] ?? '');
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
        $req_type = trim($_POST['request_type'] ?? 'Export My Data');
        $details = trim($_POST['details'] ?? '');

        if (!$email || empty($name)) {
            $error_msg = "Please provide your full name and a valid email address.";
        } else {
            try {
                $db = get_db();
                $subject = "Privacy Data Request: " . $req_type;
                $msg_body = "Request Type: {$req_type}\n\nClient Name: {$name}\nEmail: {$email}\n\nDetails:\n{$details}";
                $stmt = $db->prepare("INSERT INTO contact_messages (name, email, phone, subject, message, status, ip_address) VALUES (?, ?, '', ?, ?, 'New', ?)");
                $stmt->execute([$name, $email, $subject, $msg_body, $_SERVER['REMOTE_ADDR'] ?? '']);
                $success_msg = "Your data request has been officially logged. Our privacy officer will verify identity and fulfill your request within 30 days.";
            } catch (Exception $e) {
                $error_msg = "Database error logging request. Please contact info@vprovideservices.com directly.";
            }
        }
    }
}

include __DIR__ . '/include/header.php';
?>

<section class="hero relative overflow-hidden pb-16 pt-32 text-white lg:pb-20 lg:pt-36">
  <div class="grid-bg absolute inset-0"></div>
  <div class="relative mx-auto max-w-[900px] px-5 text-center lg:px-8">
    <span class="glass inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold uppercase tracking-[.16em] text-cyan">
      Data Rights Portal
    </span>
    <h1 class="mt-6 font-display text-4xl font-extrabold tracking-tight sm:text-5xl">Data Rights &amp; Privacy Request</h1>
    <p class="mx-auto mt-4 max-w-xl text-sm text-slate-300">Submit requests to access, correct, export, or permanently delete personal data stored by VPS.</p>
  </div>
</section>

<section class="bg-white py-16 lg:py-24">
  <div class="mx-auto max-w-[700px] px-5 lg:px-8">
    
    <?php if ($success_msg): ?>
      <div class="rounded-2xl border border-green-200 bg-green-50 p-6 text-green-800 text-center mb-8">
        <span class="material-symbols-outlined text-4xl text-green-600 mb-2">task_alt</span>
        <h3 class="font-bold text-lg">Request Acknowledged</h3>
        <p class="text-sm mt-2"><?= e($success_msg) ?></p>
      </div>
    <?php endif; ?>

    <?php if ($error_msg): ?>
      <div class="rounded-2xl border border-red-200 bg-red-50 p-5 text-red-700 text-sm mb-8">
        <?= e($error_msg) ?>
      </div>
    <?php endif; ?>

    <div class="rounded-3xl border border-slate-200 bg-soft p-8 shadow-sm">
      <form action="privacy-contact.php" method="post" class="space-y-5">
        <?= csrf_field() ?>

        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-muted mb-2">Full Legal Name <span class="text-cta">*</span></label>
          <input type="text" name="name" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-ink bg-white focus:outline-none focus:border-brand">
        </div>

        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-muted mb-2">Registered Email Address <span class="text-cta">*</span></label>
          <input type="email" name="email" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-ink bg-white focus:outline-none focus:border-brand">
        </div>

        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-muted mb-2">Request Type <span class="text-cta">*</span></label>
          <select name="request_type" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-ink bg-white focus:outline-none focus:border-brand">
            <option value="Export My Data">Export My Data (Portability Copy)</option>
            <option value="Delete My Data">Delete All Personal Information &amp; Files</option>
            <option value="Rectify My Data">Update / Rectify Inaccurate Information</option>
            <option value="General Privacy Inquiry">General Privacy Inquiry</option>
          </select>
        </div>

        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-muted mb-2">Specification / Details</label>
          <textarea name="details" rows="4" placeholder="Provide any additional identifiers, project reference numbers, or details..." class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-ink bg-white focus:outline-none focus:border-brand"></textarea>
        </div>

        <button type="submit" class="btn-cta w-full rounded-xl py-3.5 text-center font-bold text-sm shadow-xl">
          Submit Data Request
        </button>
      </form>
    </div>
  </div>
</section>

<?php include __DIR__ . '/include/footer.php'; ?>
