<?php
/**
 * Common Header
 * Included on all pages
 */

// Ensure config is loaded first (it starts the session)
if (!defined('DB_HOST')) {
    require_once __DIR__ . '/../config/config.php';
}

// Load auth functions
if (!function_exists('getCurrentUser')) {
    require_once __DIR__ . '/auth.php';
}

// Load utility functions
if (!function_exists('sanitizeInput')) {
    require_once __DIR__ . '/functions.php';
}

$currentUser = getCurrentUser();
$isLoggedIn = isLoggedIn();
$isAdminUser = isAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' . SITE_NAME : SITE_NAME; ?></title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- CodeMirror CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/monokai.min.css">
    
    <!-- Pyodide for Python in Browser -->
    <script src="https://cdn.jsdelivr.net/pyodide/v0.24.1/full/pyodide.js"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css?v=<?php echo time(); ?>">
    
    <style>
        /* Cyberpunk Theme Overrides - Must be after Tailwind */
        .nav-link:hover {
            color: var(--primary-yellow, #FFD300) !important;
        }
        .btn-primary {
            background-color: var(--primary-yellow, #FFD300) !important;
            color: #0A0A0A !important;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s;
        }
        .btn-primary:hover {
            background-color: #FFEA00 !important;
            color: #0A0A0A !important;
        }
        
        /* Force ALL blue backgrounds to yellow (except view-all-courses-btn) */
        .bg-blue-50, .bg-blue-100, .bg-blue-200, .bg-blue-300, 
        .bg-blue-400, .bg-blue-500, .bg-blue-600:not(.view-all-courses-btn), 
        .bg-blue-700:not(.view-all-courses-btn), 
        .bg-blue-800, .bg-blue-900 {
            background-color: #FFD300 !important;
            color: #0A0A0A !important;
        }
        
        .hover\:bg-blue-700:hover, .hover\:bg-blue-600:hover, 
        .hover\:bg-blue-500:hover {
            background-color: #FFEA00 !important;
            color: #0A0A0A !important;
        }
        
        /* Force text-white on blue backgrounds to be black */
        .bg-blue-600.text-white:not(.view-all-courses-btn), 
        .bg-blue-700.text-white:not(.view-all-courses-btn),
        .bg-blue-500.text-white {
            color: #0A0A0A !important;
        }
        
        /* Special rule for view-all-courses-btn */
        .view-all-courses-btn {
            background-color: #FFFFFF !important;
            color: #0A0A0A !important;
        }
        
        .view-all-courses-btn:hover {
            background-color: #FFD300 !important;
            color: #0A0A0A !important;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="flex items-center">
                        <span class="text-2xl font-bold neon-text" style="font-family: 'Rajdhani', sans-serif;">⚡ CodeNepal</span>
                    </a>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <?php if ($isLoggedIn): ?>
                        <a href="/pages/dashboard" class="nav-link text-gray-700 hover:text-blue-600">Dashboard</a>
                        <a href="/pages/courses" class="nav-link text-gray-700 hover:text-blue-600">Courses</a>
                        
                        <?php if ($isAdminUser): ?>
                            <a href="/admin" class="nav-link text-gray-700 hover:text-blue-600">Admin</a>
                        <?php endif; ?>
                        
                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-blue-600">
                                <span><?php echo htmlspecialchars($currentUser['name']); ?></span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10" style="display: none;">
                                <a href="/pages/accomplishments" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Accomplishments</a>
                                <a href="/pages/settings" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                                <hr class="my-1">
                                <a href="/pages/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Logout</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="/pages/login" class="nav-link text-gray-700 hover:text-blue-600">Login</a>
                        <a href="/pages/register" class="btn-primary">Get Started</a>
                    <?php endif; ?>
                </div>
                
                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-gray-700 hover:text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <?php if ($isLoggedIn): ?>
                    <a href="/pages/dashboard" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">Dashboard</a>
                    <a href="/pages/courses" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">Courses</a>
                    <a href="/pages/accomplishments" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">Accomplishments</a>
                    <a href="/pages/settings" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">Settings</a>
                    <?php if ($isAdminUser): ?>
                        <a href="/admin" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">Admin</a>
                    <?php endif; ?>
                    <a href="/pages/logout" class="block px-3 py-2 text-red-600 hover:bg-gray-100 rounded">Logout</a>
                <?php else: ?>
                    <a href="/pages/login" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">Login</a>
                    <a href="/pages/register" class="block px-3 py-2 text-blue-600 hover:bg-gray-100 rounded">Get Started</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    
    <!-- Flash Messages -->
    <?php
    $flashMessage = getFlashMessage();
    if ($flashMessage):
        $bgColor = [
            'success' => 'bg-green-100 border-green-500 text-green-700',
            'error' => 'bg-red-100 border-red-500 text-red-700',
            'warning' => 'bg-yellow-100 border-yellow-500 text-yellow-700',
            'info' => 'bg-blue-100 border-blue-500 text-blue-700'
        ][$flashMessage['type']] ?? 'bg-gray-100 border-gray-500 text-gray-700';
    ?>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="<?php echo $bgColor; ?> border-l-4 p-4 rounded" role="alert">
            <p><?php echo htmlspecialchars($flashMessage['message']); ?></p>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Alpine.js for dropdowns -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
