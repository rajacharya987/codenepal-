<?php
/**
 * Logout Handler
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

logout();
setFlashMessage('success', 'You have been logged out successfully');
redirect('/');
