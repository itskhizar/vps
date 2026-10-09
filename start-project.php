<?php
/**
 * VPS Digital Services - Start a Project (Project Inquiry & Intake System)
 */
require_once __DIR__ . '/include/functions.php';

$page_title = "Start Your Project | VPS Digital Services";
$page_desc  = "Submit your project requirements, scope, timeline, and files to receive a detailed technical proposal and transparent quote within 24 hours.";
$is_solid_header = false;

$db = get_db();
$errors = [];
$preselected_service = trim($_GET['service'] ?? '');
$success_ref = trim($_GET['success'] ?? '');

// Load active services for dropdown
try {
    $stmt_services = $db->query("SELECT id, title, slug FROM services WHERE is_active = 1 ORDER BY display_order ASC");
    $available_services = $stmt_services->fetchAll();
} catch (Exception $e) {
    $available_services = [];
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $errors['csrf'] = "Security validation failed. Please refresh the page and try again.";
    }

    $client_name       = trim($_POST['client_name'] ?? '');
    $client_email      = filter_var(trim($_POST['client_email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $client_phone      = trim($_POST['client_phone'] ?? '');
    $client_whatsapp   = trim($_POST['client_whatsapp'] ?? '');
    $company_name      = trim($_POST['company_name'] ?? '');
    $country           = trim($_POST['country'] ?? '');
    $preferred_contact = trim($_POST['preferred_contact'] ?? 'email');
    $project_title     = trim($_POST['title'] ?? '');
    $service_slug      = trim($_POST['service'] ?? '');
    $description       = trim($_POST['description'] ?? '');
    $objectives        = trim($_POST['objectives'] ?? '');
    $scope_features    = trim($_POST['scope_features'] ?? '');
    $timeline          = trim($_POST['timeline'] ?? '');
    $budget_type       = trim($_POST['budget_type'] ?? 'fixed');
    $budget_amount     = trim($_POST['budget_amount'] ?? '');
    $currency          = trim($_POST['currency'] ?? 'USD');
    $budget_flexible   = isset($_POST['budget_flexible']) ? 1 : 0;
    $additional_notes  = trim($_POST['additional_notes'] ?? '');
    $privacy_accepted  = isset($_POST['privacy_accepted']);

    // Validations
    if (empty($client_name)) {
        $errors['client_name'] = "Full name is required.";
    }
    if (!$client_email) {
        $errors['client_email'] = "A valid email address is required.";
    }
    if (empty($project_title)) {
        $errors['title'] = "Project title or brief summary is required.";
    }
    if (empty($description)) {
        $errors['description'] = "Please provide details about your project requirements.";
    }
    if (!$privacy_accepted) {
        $errors['privacy'] = "You must agree to the privacy policy to submit your project.";
    }

    // Validate selected service
    $selected_service_id = null;
    $selected_service_name = null;
    if (!empty($service_slug)) {
        foreach ($available_services as $s) {
            if ($s['slug'] === $service_slug || (string)$s['id'] === $service_slug) {
                $selected_service_id = $s['id'];
                $selected_service_name = $s['title'];
                break;
            }
        }
    }
    if (empty($selected_service_name)) {
        $errors['service'] = "Please select a valid service from the list.";
    }

    // Process file upload if provided
    $uploaded_attachment = null;
    if (empty($errors) && isset($_FILES['attachment']) && $_FILES['attachment']['error'] !== UPLOAD_ERR_NO_FILE) {
        $upload_res = safe_upload($_FILES['attachment'], 'attachments', ['pdf', 'doc', 'docx', 'txt', 'zip', 'rar', 'png', 'jpg', 'jpeg'], 15728640); // 15MB limit
        if ($upload_res['success']) {
            $uploaded_attachment = $upload_res;
        } else {
            $errors['attachment'] = $upload_res['error'];
        }
    }

    // Insert project into MySQL if no errors
    if (empty($errors)) {
        try {
            $db->beginTransaction();

            $ref_no = generate_project_ref();

            $sql = "INSERT INTO projects (
                reference_no, client_name, client_email, client_phone, client_whatsapp,
                company_name, country, preferred_contact, title, service_id, service_name,
                description, objectives, scope_features, timeline, budget_type, budget_amount,
                currency, budget_flexible, additional_notes, status
            ) VALUES (
                ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?,
                ?, ?, ?, 'Pending Review'
            )";

            $stmt_proj = $db->prepare($sql);
            $stmt_proj->execute([
                $ref_no, $client_name, $client_email, $client_phone, $client_whatsapp,
                $company_name, $country, $preferred_contact, $project_title, $selected_service_id, $selected_service_name,
                $description, $objectives, $scope_features, $timeline, $budget_type, $budget_amount,
                $currency, $budget_flexible, $additional_notes
            ]);

            $new_project_id = (int)$db->lastInsertId();

            // Insert attachment metadata if file was uploaded
            if ($uploaded_attachment) {
                $stmt_att = $db->prepare("INSERT INTO project_attachments (project_id, original_name, file_path, file_size, mime_type) VALUES (?, ?, ?, ?, ?)");
                $stmt_att->execute([
                    $new_project_id,
                    $uploaded_attachment['original_name'],
                    $uploaded_attachment['relative_path'],
                    $uploaded_attachment['size'],
                    $uploaded_attachment['mime']
                ]);
            }

            // Insert initial status history record
            $stmt_hist = $db->prepare("INSERT INTO project_status_history (project_id, previous_status, new_status, changed_by, comment) VALUES (?, NULL, 'Pending Review', 'Client', 'Project brief submitted online')");
            $stmt_hist->execute([$new_project_id]);

            $db->commit();

            // Post-Redirect-Get to prevent duplicate submissions
            header("Location: start-project.php?success=" . urlencode($ref_no));
            exit;

        } catch (Exception $e) {
            $db->rollBack();
            error_log("Project submission failed: " . $e->getMessage());
            $errors['db'] = "An error occurred while saving your project inquiry. Please try again or contact us directly.";
        }
    }
}

include __DIR__ . '/include/header.php';
?>

<!-- Header Banner -->
<section class="hero relative overflow-hidden pb-16 pt-32 text-white lg:pb-20 lg:pt-36">
  <div class="grid-bg absolute inset-0"></div>
  <div class="relative mx-auto max-w-[1280px] px-5 text-center lg:px-8">
    <span class="glass inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold uppercase tracking-[.16em] text-cyan">
      Project Intake &amp; Estimate
    </span>
    <h1 class="mt-6 font-display text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
      Let's Build Something Exceptional
    </h1>
    <p class="mx-auto mt-4 max-w-2xl text-lg text-slate-300">
      Tell us about your goals, features, and timeline. You will receive a technical review and transparent proposal within 24 hours.
    </p>
  </div>
</section>

<section class="bg-soft py-16 lg:py-24">
  <div class="mx-auto max-w-[960px] px-5 lg:px-8">

    <?php if (!empty($success_ref)): ?>
      <!-- Success Confirmation Box -->
      <div class="rounded-3xl border border-green-200 bg-white p-8 shadow-xl text-center md:p-14">
        <div class="mx-auto grid h-20 w-20 place-items-center rounded-full bg-green-100 text-green-600 mb-6">
          <span class="material-symbols-outlined !text-4xl">check_circle</span>
        </div>
        <span class="text-xs font-bold uppercase tracking-[.2em] text-green-600">Inquiry Confirmed</span>
        <h2 class="mt-3 font-display text-3xl font-bold text-ink">Project Successfully Submitted!</h2>
        <p class="mt-4 text-base text-muted max-w-xl mx-auto">
          Thank you for reaching out to VPS. Your project inquiry has been logged into our management system under reference number:
        </p>

        <div class="my-6 inline-block rounded-2xl bg-soft px-8 py-4 border border-slate-200">
          <span class="block text-xs uppercase tracking-wider text-muted font-bold">Reference Number</span>
          <span class="font-mono text-2xl font-extrabold text-brand"><?= e($success_ref) ?></span>
        </div>

        <div class="mt-4 max-w-lg mx-auto rounded-xl bg-slate-50 p-6 text-left border border-slate-200 text-sm text-slate-700 space-y-3">
          <h4 class="font-bold text-ink">What happens next:</h4>
          <p>1. Our technical lead will review your specifications and uploaded files.</p>
          <p>2. We will prepare a transparent quote outlining milestones, timeline, and exact scope.</p>
          <p>3. You will receive an email response within 24 hours. For expedited queries, you can quote this reference number on WhatsApp.</p>
        </div>

        <div class="mt-8 flex flex-wrap justify-center gap-4">
          <a href="https://wa.me/<?= e($clean_whatsapp) ?>?text=<?= urlencode("Hello VPS, I just submitted project reference: " . $success_ref) ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-xl bg-green-500 px-6 py-3 font-semibold text-white transition hover:bg-green-600">
            <span class="material-symbols-outlined">chat</span> Follow up on WhatsApp
          </a>
          <a href="index.php" class="inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3 font-semibold text-ink ring-1 ring-slate-200 transition hover:ring-brand">
            Back to Homepage
          </a>
        </div>
      </div>

    <?php else: ?>
      <!-- Intake Form Container -->
      <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl md:p-12">
        
        <?php if (!empty($errors)): ?>
          <div class="mb-8 rounded-2xl border border-red-200 bg-red-50 p-5 text-sm text-red-700">
            <p class="font-bold">Please correct the following before submitting:</p>
            <ul class="mt-2 list-disc list-inside space-y-1">
              <?php foreach ($errors as $err): ?>
                <li><?= e($err) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <form action="start-project.php" method="post" enctype="multipart/form-data" class="space-y-8">
          <?= csrf_field() ?>

          <!-- Section 1: Client Information -->
          <div>
            <h3 class="font-display text-xl font-bold text-ink flex items-center gap-2">
              <span class="grid h-7 w-7 place-items-center rounded-lg bg-brand/10 text-brand text-xs">1</span>
              Client &amp; Contact Details
            </h3>
            <div class="mt-5 grid gap-5 sm:grid-cols-2">
              <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-muted mb-2">Full Name <span class="text-cta">*</span></label>
                <input type="text" name="client_name" required value="<?= e($_POST['client_name'] ?? '') ?>" placeholder="e.g. Alex Morgan" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-ink focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
              </div>

              <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-muted mb-2">Email Address <span class="text-cta">*</span></label>
                <input type="email" name="client_email" required value="<?= e($_POST['client_email'] ?? '') ?>" placeholder="alex@company.com" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-ink focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
              </div>

              <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-muted mb-2">Phone Number</label>
                <input type="text" name="client_phone" value="<?= e($_POST['client_phone'] ?? '') ?>" placeholder="+1 (555) 000-0000" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-ink focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
              </div>

              <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-muted mb-2">WhatsApp Number (Optional)</label>
                <input type="text" name="client_whatsapp" value="<?= e($_POST['client_whatsapp'] ?? '') ?>" placeholder="+92 332 8912706" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-ink focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
              </div>

              <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-muted mb-2">Company / Organization</label>
                <input type="text" name="company_name" value="<?= e($_POST['company_name'] ?? '') ?>" placeholder="Company Ltd." class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-ink focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
              </div>

              <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-muted mb-2">Country</label>
                <input type="text" name="country" value="<?= e($_POST['country'] ?? '') ?>" placeholder="e.g. United States, United Kingdom, Pakistan" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-ink focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
              </div>

              <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-muted mb-2">Preferred Communication Channel</label>
                <div class="flex flex-wrap gap-4 text-sm text-ink">
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="preferred_contact" value="email" checked class="text-brand focus:ring-brand"> Email
                  </label>
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="preferred_contact" value="whatsapp" <?= (isset($_POST['preferred_contact']) && $_POST['preferred_contact'] === 'whatsapp') ? 'checked' : '' ?> class="text-brand focus:ring-brand"> WhatsApp
                  </label>
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="preferred_contact" value="call" <?= (isset($_POST['preferred_contact']) && $_POST['preferred_contact'] === 'call') ? 'checked' : '' ?> class="text-brand focus:ring-brand"> Phone Call / Google Meet
                  </label>
                </div>
              </div>
            </div>
          </div>

          <hr class="border-slate-100">

          <!-- Section 2: Project Scope & Service -->
          <div>
            <h3 class="font-display text-xl font-bold text-ink flex items-center gap-2">
              <span class="grid h-7 w-7 place-items-center rounded-lg bg-brand/10 text-brand text-xs">2</span>
              Project Scope &amp; Specifications
            </h3>

            <div class="mt-5 space-y-5">
              <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-muted mb-2">Primary Service Needed <span class="text-cta">*</span></label>
                <select name="service" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-ink focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand bg-white">
                  <option value="">-- Select a Service --</option>
                  <?php foreach ($available_services as $s): 
                    $isSelected = ($preselected_service === $s['slug'] || (isset($_POST['service']) && $_POST['service'] === $s['slug']));
                  ?>
                    <option value="<?= e($s['slug']) ?>" <?= $isSelected ? 'selected' : '' ?>>
                      <?= e($s['title']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-muted mb-2">Project Title / Name <span class="text-cta">*</span></label>
                <input type="text" name="title" required value="<?= e($_POST['title'] ?? '') ?>" placeholder="e.g. Modern E-Commerce Web Store &amp; Brand Redesign" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-ink focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
              </div>

              <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-muted mb-2">Project Description &amp; Requirements <span class="text-cta">*</span></label>
                <textarea name="description" rows="5" required placeholder="Describe what you want to achieve, your target audience, required pages or workflows..." class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-ink focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand"><?= e($_POST['description'] ?? '') ?></textarea>
              </div>

              <div class="grid gap-5 sm:grid-cols-2">
                <div>
                  <label class="block text-xs font-bold uppercase tracking-wider text-muted mb-2">Key Objectives</label>
                  <textarea name="objectives" rows="3" placeholder="e.g. Increase conversion rate, modernize brand perception..." class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-ink focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand"><?= e($_POST['objectives'] ?? '') ?></textarea>
                </div>
                <div>
                  <label class="block text-xs font-bold uppercase tracking-wider text-muted mb-2">Required Features or Scope</label>
                  <textarea name="scope_features" rows="3" placeholder="e.g. Payment gateway, user dashboard, 3D renderings..." class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-ink focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand"><?= e($_POST['scope_features'] ?? '') ?></textarea>
                </div>
              </div>

              <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-muted mb-2">Expected Timeline / Deadline</label>
                <input type="text" name="timeline" value="<?= e($_POST['timeline'] ?? '') ?>" placeholder="e.g. 3 to 4 weeks, or within 2 months" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-ink focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
              </div>
            </div>
          </div>

          <hr class="border-slate-100">

          <!-- Section 3: Budget & Terms -->
          <div>
            <h3 class="font-display text-xl font-bold text-ink flex items-center gap-2">
              <span class="grid h-7 w-7 place-items-center rounded-lg bg-brand/10 text-brand text-xs">3</span>
              Budget &amp; Supporting Documents
            </h3>

            <div class="mt-5 space-y-5">
              <div class="grid gap-5 sm:grid-cols-3">
                <div>
                  <label class="block text-xs font-bold uppercase tracking-wider text-muted mb-2">Budget Model</label>
                  <select name="budget_type" id="budgetType" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-ink focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand bg-white">
                    <option value="fixed">Fixed Budget</option>
                    <option value="range">Budget Range</option>
                    <option value="flexible">Flexible / Open to discussion</option>
                    <option value="not_sure">Not sure yet</option>
                  </select>
                </div>

                <div id="budgetAmountWrap">
                  <label class="block text-xs font-bold uppercase tracking-wider text-muted mb-2">Estimated Amount / Range</label>
                  <input type="text" name="budget_amount" value="<?= e($_POST['budget_amount'] ?? '') ?>" placeholder="e.g. 1500 or 1500 - 3000" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-ink focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
                </div>

                <div>
                  <label class="block text-xs font-bold uppercase tracking-wider text-muted mb-2">Currency</label>
                  <select name="currency" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-ink focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand bg-white">
                    <option value="USD">USD ($)</option>
                    <option value="GBP">GBP (£)</option>
                    <option value="EUR">EUR (€)</option>
                    <option value="PKR">PKR (Rs)</option>
                    <option value="AED">AED (د.إ)</option>
                  </select>
                </div>
              </div>

              <div>
                <label class="flex items-center gap-2.5 text-sm text-ink cursor-pointer">
                  <input type="checkbox" name="budget_flexible" value="1" <?= isset($_POST['budget_flexible']) ? 'checked' : '' ?> class="rounded text-brand focus:ring-brand">
                  <span>Our budget is flexible based on technical recommendations and scope.</span>
                </label>
              </div>

              <!-- File Upload Attachment -->
              <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-muted mb-2">
                  Attach Requirements Document or Assets (Optional)
                </label>
                <div class="rounded-2xl border-2 border-dashed border-slate-200 p-6 text-center hover:border-brand/40 transition">
                  <span class="material-symbols-outlined text-4xl text-muted">upload_file</span>
                  <p class="mt-2 text-sm text-ink font-semibold">Upload PDF, DOCX, TXT, ZIP, or Image files</p>
                  <p class="mt-1 text-xs text-muted">Maximum file size: 15MB. Server validates file types securely.</p>
                  <input type="file" name="attachment" class="mt-4 block w-full text-xs text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-soft file:px-4 file:py-2 file:text-xs file:font-semibold file:text-brand hover:file:bg-brand/10">
                </div>
              </div>

              <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-muted mb-2">Additional Notes / Special Instructions</label>
                <textarea name="additional_notes" rows="2" placeholder="Any specific requirements regarding NDA, milestones, or external integrations..." class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-ink focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand"><?= e($_POST['additional_notes'] ?? '') ?></textarea>
              </div>

              <!-- Privacy Policy Acceptance -->
              <div class="rounded-xl bg-soft p-4 border border-slate-200">
                <label class="flex items-start gap-2.5 text-xs text-slate-700 cursor-pointer">
                  <input type="checkbox" name="privacy_accepted" value="1" required class="mt-0.5 rounded text-brand focus:ring-brand">
                  <span>I agree to the <a href="privacy-policy.php" target="_blank" class="text-brand font-semibold underline">Privacy Policy</a> and authorize VPS to contact me regarding this project proposal.</span>
                </label>
              </div>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="pt-4">
            <button type="submit" class="btn-cta w-full rounded-xl py-4 text-center font-bold text-base shadow-xl flex items-center justify-center gap-2">
              Submit Project Inquiry <span class="material-symbols-outlined">send</span>
            </button>
            <p class="mt-3 text-center text-xs text-muted">No obligation. Your intellectual property and brief remain strictly confidential.</p>
          </div>
        </form>
      </div>
    <?php endif; ?>

  </div>
</section>

<script>
  // Dynamic budget input toggle
  const budgetType = document.getElementById('budgetType');
  const budgetAmountWrap = document.getElementById('budgetAmountWrap');
  if (budgetType && budgetAmountWrap) {
    budgetType.addEventListener('change', () => {
      if (budgetType.value === 'flexible' || budgetType.value === 'not_sure') {
        budgetAmountWrap.style.opacity = '0.5';
      } else {
        budgetAmountWrap.style.opacity = '1';
      }
    });
  }
</script>

<?php include __DIR__ . '/include/footer.php'; ?>
