<?php
/**
 * VPS Digital Services - Global Helper Functions & Security Library
 */

if (!defined('DB_SERVER')) {
    require_once __DIR__ . '/classes/constants.php';
}

/**
 * Get PDO Database Connection (Singleton)
 *
 * @return PDO
 */
function get_db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            error_log("Database connection error: " . $e->getMessage());
            die("Database connection failed. Please ensure MySQL is running.");
        }
    }
    return $pdo;
}

/**
 * Escape output for HTML context (prevent XSS)
 *
 * @param mixed $value
 * @return string
 */
function e($value): string
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

/**
 * Generate or get CSRF token
 *
 * @return string
 */
function csrf_token(): string
{
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        @session_start();
    }
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Output hidden CSRF input field
 *
 * @return string
 */
function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

/**
 * Verify CSRF token
 *
 * @param string|null $token
 * @return bool
 */
function verify_csrf(?string $token = null): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if ($token === null) {
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    }
    return !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string)$token);
}

/**
 * Get Setting by Key with optional fallback
 *
 * @param string $key
 * @param string $default
 * @return string
 */
function get_setting(string $key, string $default = ''): string
{
    static $cached_settings = null;
    if ($cached_settings === null) {
        $cached_settings = [];
        try {
            $db = get_db();
            $stmt = $db->query("SELECT setting_key, setting_value FROM settings");
            while ($row = $stmt->fetch()) {
                $cached_settings[$row['setting_key']] = $row['setting_value'];
            }
        } catch (Exception $e) {
            error_log("Failed to load settings: " . $e->getMessage());
        }
    }
    return $cached_settings[$key] ?? $default;
}

/**
 * Update or insert a setting
 *
 * @param string $key
 * @param string $value
 * @return bool
 */
function set_setting(string $key, string $value): bool
{
    try {
        $db = get_db();
        $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        return $stmt->execute([$key, $value, $value]);
    } catch (Exception $e) {
        error_log("Failed to save setting: " . $e->getMessage());
        return false;
    }
}

/**
 * Set flash alert message
 *
 * @param string $type success|error|warning|info
 * @param string $message
 */
function set_flash(string $type, string $message): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['flash_messages'][$type][] = $message;
}

/**
 * Get and clear flash alert messages
 *
 * @param string|null $type
 * @return array
 */
function get_flash(?string $type = null): array
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['flash_messages'])) {
        return [];
    }
    if ($type !== null) {
        $messages = $_SESSION['flash_messages'][$type] ?? [];
        unset($_SESSION['flash_messages'][$type]);
        return $messages;
    }
    $all = $_SESSION['flash_messages'];
    unset($_SESSION['flash_messages']);
    return $all;
}

/**
 * Generate URL-friendly slug
 *
 * @param string $text
 * @return string
 */
function slugify(string $text): string
{
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return empty($text) ? 'item-' . time() : $text;
}

/**
 * Generate unique Project Reference Number (e.g. VPS-2026-AB12)
 *
 * @return string
 */
function generate_project_ref(): string
{
    $year = date('Y');
    $random = strtoupper(bin2hex(random_bytes(2)));
    return "VPS-{$year}-{$random}";
}

/**
 * Safe file upload handler
 *
 * @param array $file $_FILES['input_name']
 * @param string $subDirectory (e.g. 'attachments', 'services', 'team')
 * @param array $allowedExts
 * @param int $maxSizeBytes
 * @return array ['success' => bool, 'path' => string, 'filename' => string, 'error' => string]
 */
function safe_upload(array $file, string $subDirectory, array $allowedExts = [], int $maxSizeBytes = 10485760): array
{
    if (!isset($file['error']) || is_array($file['error'])) {
        return ['success' => false, 'error' => 'Invalid file parameter'];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors = [
            UPLOAD_ERR_INI_SIZE   => 'File exceeds server size limit',
            UPLOAD_ERR_FORM_SIZE  => 'File exceeds form size limit',
            UPLOAD_ERR_PARTIAL    => 'File was only partially uploaded',
            UPLOAD_ERR_NO_FILE    => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder on server',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the file upload',
        ];
        return ['success' => false, 'error' => $errors[$file['error']] ?? 'Unknown upload error'];
    }

    if ($file['size'] > $maxSizeBytes) {
        $mb = round($maxSizeBytes / (1024 * 1024));
        return ['success' => false, 'error' => "File size cannot exceed {$mb}MB"];
    }

    $originalName = basename($file['name']);
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    // Default safe extensions if none provided
    if (empty($allowedExts)) {
        $allowedExts = ['pdf', 'doc', 'docx', 'txt', 'zip', 'rar', 'jpg', 'jpeg', 'png', 'webp', 'gif'];
    }

    // Disallow dangerous executable extensions always
    $blocked = ['php', 'phtml', 'php3', 'php4', 'php5', 'php7', 'phps', 'phar', 'exe', 'bat', 'cmd', 'sh', 'js', 'vbs', 'cgi', 'pl'];
    if (in_array($extension, $blocked, true) || !in_array($extension, $allowedExts, true)) {
        return ['success' => false, 'error' => 'File type not permitted'];
    }

    // Verify MIME type using finfo
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);

    $allowedMimes = [
        'pdf'  => ['application/pdf'],
        'doc'  => ['application/msword'],
        'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
        'txt'  => ['text/plain'],
        'zip'  => ['application/zip', 'application/x-zip-compressed', 'multipart/x-zip'],
        'rar'  => ['application/x-rar-compressed', 'application/octet-stream'],
        'jpg'  => ['image/jpeg', 'image/pjpeg'],
        'jpeg' => ['image/jpeg', 'image/pjpeg'],
        'png'  => ['image/png'],
        'webp' => ['image/webp'],
        'gif'  => ['image/gif']
    ];

    if (isset($allowedMimes[$extension]) && !in_array($mimeType, $allowedMimes[$extension], true)) {
        // Fallback check for safe image or text types
        if (strpos($mimeType, 'image/') !== 0 && $extension !== 'zip' && $extension !== 'rar') {
            return ['success' => false, 'error' => 'File content does not match its extension'];
        }
    }

    $uploadRoot = dirname(__DIR__) . '/uploads/' . trim($subDirectory, '/');
    if (!is_dir($uploadRoot)) {
        mkdir($uploadRoot, 0755, true);
    }

    $safeFileName = time() . '_' . bin2hex(random_bytes(6)) . '.' . $extension;
    $targetPath = $uploadRoot . '/' . $safeFileName;
    $relativeDbPath = 'uploads/' . trim($subDirectory, '/') . '/' . $safeFileName;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['success' => false, 'error' => 'Failed to save uploaded file'];
    }

    return [
        'success'       => true,
        'original_name' => $originalName,
        'saved_name'    => $safeFileName,
        'path'          => $targetPath,
        'relative_path' => $relativeDbPath,
        'size'          => $file['size'],
        'mime'          => $mimeType,
        'extension'     => $extension
    ];
}

/**
 * Require Admin Authentication or Redirect
 */
function require_admin(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Check session or session class
    $isAdmin = false;
    if (isset($_SESSION['userlevel']) && (int)$_SESSION['userlevel'] === 9) {
        $isAdmin = true;
    } elseif (!empty($_SESSION['username'])) {
        // Verify userlevel directly in database
        try {
            $db = get_db();
            $stmt = $db->prepare("SELECT userlevel FROM users WHERE username = ? LIMIT 1");
            $stmt->execute([$_SESSION['username']]);
            $level = $stmt->fetchColumn();
            if ($level !== false && (int)$level === 9) {
                $_SESSION['userlevel'] = 9;
                $isAdmin = true;
            }
        } catch (Exception $e) {
            $isAdmin = false;
        }
    }

    if (!$isAdmin) {
        header("Location: /admin/login.php?msg=auth_required");
        exit;
    }
}

/**
 * Format relative time (e.g. 2 hours ago)
 *
 * @param string $datetime
 * @return string
 */
function relative_time(string $datetime): string
{
    $timestamp = strtotime($datetime);
    if (!$timestamp) return $datetime;
    
    $diff = time() - $timestamp;
    if ($diff < 60) return "Just now";
    if ($diff < 3600) return floor($diff / 60) . " mins ago";
    if ($diff < 86400) return floor($diff / 3600) . " hours ago";
    if ($diff < 604800) return floor($diff / 86400) . " days ago";
    return date("M j, Y", $timestamp);
}
