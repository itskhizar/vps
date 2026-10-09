<?php
/**
 * VPS Digital Services - Website Settings Management
 */
$admin_title = "Website Settings";
require_once __DIR__ . '/header.php';

$db = get_db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        set_flash('error', 'CSRF verification failed.');
        header("Location: settings.php");
        exit;
    }

    $keys = [
        'company_name', 'company_tagline', 'contact_email',
        'contact_phone', 'contact_whatsapp', 'address',
        'social_facebook', 'social_linkedin', 'social_instagram',
        'meta_title', 'meta_description', 'maintenance_mode'
    ];

    try {
        foreach ($keys as $k) {
            $val = trim($_POST[$k] ?? '');
            set_setting($k, $val);
        }
        set_flash('success', 'Website settings successfully updated.');
    } catch (Exception $e) {
        set_flash('error', 'Failed to save settings: ' . $e->getMessage());
    }

    header("Location: settings.php");
    exit;
}

// Fetch current settings
$settings = [];
try {
    $rows = $db->query("SELECT setting_key, setting_value FROM settings")->fetchAll();
    foreach ($rows as $r) {
        $settings[$r['setting_key']] = $r['setting_value'];
    }
} catch (Exception $e) {
    $settings = [];
}
?>

<div class="space-y-8 max-w-4xl">
  <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    <h2 class="font-display text-lg font-bold text-ink">Global Website Configuration</h2>
    <p class="text-xs text-muted">Update company contact numbers, social media profiles, and SEO defaults without editing source code.</p>
  </div>

  <form action="settings.php" method="post" class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm space-y-8 text-xs">
    <?= csrf_field() ?>

    <!-- Section 1: Company Profile -->
    <div>
      <h3 class="font-display text-base font-bold text-ink pb-3 border-b border-slate-100 flex items-center gap-2">
        <span class="material-symbols-outlined text-brand">business</span> Company Identity
      </h3>
      <div class="mt-4 grid gap-4 sm:grid-cols-2">
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Company Legal Name</label>
          <input type="text" name="company_name" value="<?= e($settings['company_name'] ?? 'V Provide Services (VPS)') ?>" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Tagline</label>
          <input type="text" name="company_tagline" value="<?= e($settings['company_tagline'] ?? 'Everything Digital. One Trusted Provider.') ?>" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
      </div>
    </div>

    <!-- Section 2: Contact Channels -->
    <div>
      <h3 class="font-display text-base font-bold text-ink pb-3 border-b border-slate-100 flex items-center gap-2">
        <span class="material-symbols-outlined text-brand">call</span> Official Contact Details
      </h3>
      <div class="mt-4 grid gap-4 sm:grid-cols-3">
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Contact Phone</label>
          <input type="text" name="contact_phone" value="<?= e($settings['contact_phone'] ?? '03328912706') ?>" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
        <div>
          <label class="block font-bold text-muted uppercase mb-1">WhatsApp Worldwide</label>
          <input type="text" name="contact_whatsapp" value="<?= e($settings['contact_whatsapp'] ?? '+92 332 8912706') ?>" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Support Email</label>
          <input type="email" name="contact_email" value="<?= e($settings['contact_email'] ?? 'info@vprovideservices.com') ?>" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
      </div>

      <div class="mt-4">
        <label class="block font-bold text-muted uppercase mb-1">Physical Address / Headquarters</label>
        <input type="text" name="address" value="<?= e($settings['address'] ?? 'Islamabad / Rawalpindi, Pakistan • Serving Clients Worldwide') ?>" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
      </div>
    </div>

    <!-- Section 3: Social Links -->
    <div>
      <h3 class="font-display text-base font-bold text-ink pb-3 border-b border-slate-100 flex items-center gap-2">
        <span class="material-symbols-outlined text-brand">share</span> Social Media Presence
      </h3>
      <div class="mt-4 grid gap-4 sm:grid-cols-3">
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Facebook URL</label>
          <input type="text" name="social_facebook" value="<?= e($settings['social_facebook'] ?? '') ?>" placeholder="https://facebook.com/..." class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
        <div>
          <label class="block font-bold text-muted uppercase mb-1">LinkedIn URL</label>
          <input type="text" name="social_linkedin" value="<?= e($settings['social_linkedin'] ?? '') ?>" placeholder="https://linkedin.com/company/..." class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Instagram URL</label>
          <input type="text" name="social_instagram" value="<?= e($settings['social_instagram'] ?? '') ?>" placeholder="https://instagram.com/..." class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
      </div>
    </div>

    <!-- Section 4: SEO Metadata Defaults -->
    <div>
      <h3 class="font-display text-base font-bold text-ink pb-3 border-b border-slate-100 flex items-center gap-2">
        <span class="material-symbols-outlined text-brand">search</span> Default SEO Metadata
      </h3>
      <div class="mt-4 space-y-4">
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Default Meta Title</label>
          <input type="text" name="meta_title" value="<?= e($settings['meta_title'] ?? '') ?>" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink">
        </div>
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Default Meta Description</label>
          <textarea name="meta_description" rows="3" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-ink"><?= e($settings['meta_description'] ?? '') ?></textarea>
        </div>
      </div>
    </div>

    <div class="pt-4 border-t border-slate-100 flex justify-end">
      <button type="submit" class="btn-cta rounded-xl px-8 py-3 font-bold text-white shadow-lg">
        Save All Settings
      </button>
    </div>
  </form>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
