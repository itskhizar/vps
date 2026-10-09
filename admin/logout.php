<?php
/**
 * VPS Digital Services - Admin Logout
 */
require_once __DIR__ . '/../include/classes/session.php';

$session->logout();
header("Location: login.php?msg=logged_out");
exit;
