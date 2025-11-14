<?php
/**
 * Settings Page
 * User account settings
 */

$pageTitle = 'Settings';
require_once __DIR__ . '/../includes/header.php';

requireLogin();

$conn = getDBConnection();
$userId = $currentUser['id'];
$error = '';
$success = '';

// Handle profile update
if (isPost() && post('action') === 'update_profile') {
    $csrfToken = post('csrf_token');
    
    if (!validateCSRFToken($csrfToken)) {
        $error = 'Invalid request';
    } else {
        $name = post('name');
        
        if (empty($name)) {
            $error = 'Name is required';
        } else {
            $result = updateUserProfile($userId, ['name' => $name]);
            
            if ($result) {
                $success = 'Profile updated successfully';
                $currentUser['name'] = $name;
            } else {
                $error = 'Failed to update profile';
            }
        }
    }
}

// Handle password change
if (isPost() && post('action') === 'change_password') {
    $csrfToken = post('csrf_token');
    
    if (!validateCSRFToken($csrfToken)) {
        $error = 'Invalid request';
    } else {
        $currentPassword = post('current_password');
        $newPassword = post('new_password');
        $confirmPassword = post('confirm_password');
        
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $error = 'All password fields are required';
        } elseif ($newPassword !== $confirmPassword) {
            $error = 'New passwords do not match';
        } else {
            $result = changePassword($userId, $currentPassword, $newPassword);
            
            if ($result['success']) {
                $success = $result['message'];
            } else {
                $error = $result['message'];
            }
        }
    }
}
?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Account Settings</h1>
    
    <?php if ($error): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
        <?php echo htmlspecialchars($error); ?>
    </div>
    <?php endif; ?>
    
    <?php if ($success): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
        <?php echo htmlspecialchars($success); ?>
    </div>
    <?php endif; ?>
    
    <!-- Profile Settings -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Profile Information</h2>
        </div>
        <div class="p-6">
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <input type="hidden" name="action" value="update_profile">
                
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" value="<?php echo htmlspecialchars($currentUser['email']); ?>" 
                           disabled
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600">
                    <p class="text-xs text-gray-500 mt-1">Email cannot be changed</p>
                </div>
                
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" id="name" name="name" required
                           value="<?php echo htmlspecialchars($currentUser['name']); ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                        <?php echo ucfirst($currentUser['role']); ?>
                    </span>
                </div>
                
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition font-medium">
                    Update Profile
                </button>
            </form>
        </div>
    </div>
    
    <!-- Password Settings -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Change Password</h2>
        </div>
        <div class="p-6">
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <input type="hidden" name="action" value="change_password">
                
                <div class="mb-4">
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input type="password" id="new_password" name="new_password" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Must be at least 8 characters</p>
                </div>
                
                <div class="mb-4">
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition font-medium">
                    Change Password
                </button>
            </form>
        </div>
    </div>
    
    <!-- Danger Zone -->
    <div class="bg-white rounded-lg shadow border-2 border-red-200">
        <div class="px-6 py-4 border-b border-red-200 bg-red-50">
            <h2 class="text-xl font-semibold text-red-900">Danger Zone</h2>
        </div>
        <div class="p-6">
            <p class="text-gray-700 mb-4">Once you delete your account, there is no going back. Please be certain.</p>
            <button type="button" 
                    onclick="alert('Account deletion is not implemented in this demo')"
                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition font-medium">
                Delete Account
            </button>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
