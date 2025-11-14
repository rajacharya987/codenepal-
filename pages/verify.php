<?php
/**
 * Certificate Verification Page
 */

$pageTitle = 'Verify Certificate';
require_once __DIR__ . '/../includes/header.php';

$verificationCode = get('code', '');
$certificate = null;
$error = '';

if ($verificationCode) {
    $conn = getDBConnection();
    
    // Clean the verification code
    $verificationCode = strtoupper(trim($verificationCode));
    
    // Get certificate details
    $sql = "SELECT c.*, u.name as user_name, u.email, co.title as course_title, co.language, co.category
            FROM certificates c
            JOIN users u ON c.user_id = u.id
            JOIN courses co ON c.course_id = co.id
            WHERE c.verification_code = ?";
    $result = executeQuery($conn, $sql, [$verificationCode], "s");
    $certificate = fetchOne($result);
    
    if (!$certificate) {
        $error = 'Invalid verification code. Please check and try again.';
    }
}
?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-purple-50 py-12">
    <div class="max-w-2xl mx-auto px-4">
        <!-- Verification Form -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <div class="text-center mb-8">
                <div class="text-6xl mb-4">🔍</div>
                <h1 class="text-3xl font-bold text-gray-900">Verify Certificate</h1>
                <p class="text-gray-600 mt-2">Enter the verification code to validate a CodeNepal certificate</p>
            </div>
            
            <form method="GET" action="" class="max-w-md mx-auto">
                <div class="mb-4">
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Verification Code</label>
                    <input type="text" 
                           id="code" 
                           name="code" 
                           value="<?php echo htmlspecialchars($verificationCode); ?>"
                           placeholder="e.g., A1B2C3D4E5F6"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-center text-lg font-mono uppercase"
                           required>
                </div>
                
                <button type="submit" 
                        class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                    Verify Certificate
                </button>
            </form>
        </div>
        
        <!-- Verification Result -->
        <?php if ($verificationCode): ?>
            <?php if ($certificate): ?>
                <!-- Valid Certificate -->
                <div class="bg-green-50 border-2 border-green-500 rounded-lg p-8">
                    <div class="text-center mb-6">
                        <div class="text-6xl mb-4">✅</div>
                        <h2 class="text-2xl font-bold text-green-800">Valid Certificate</h2>
                        <p class="text-green-600 mt-2">This certificate is authentic and verified</p>
                    </div>
                    
                    <div class="bg-white rounded-lg p-6 space-y-4">
                        <div class="flex justify-between border-b pb-3">
                            <span class="font-semibold text-gray-700">Recipient:</span>
                            <span class="text-gray-900"><?php echo htmlspecialchars($certificate['user_name']); ?></span>
                        </div>
                        
                        <div class="flex justify-between border-b pb-3">
                            <span class="font-semibold text-gray-700">Course:</span>
                            <span class="text-gray-900"><?php echo htmlspecialchars($certificate['course_title']); ?></span>
                        </div>
                        
                        <div class="flex justify-between border-b pb-3">
                            <span class="font-semibold text-gray-700">Language:</span>
                            <span class="text-gray-900"><?php echo ucfirst($certificate['language']); ?></span>
                        </div>
                        
                        <div class="flex justify-between border-b pb-3">
                            <span class="font-semibold text-gray-700">Level:</span>
                            <span class="text-gray-900"><?php echo ucfirst($certificate['category']); ?></span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-700">Issued Date:</span>
                            <span class="text-gray-900"><?php echo date('F d, Y', strtotime($certificate['issued_at'])); ?></span>
                        </div>
                    </div>
                    
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">
                            This certificate was issued by CodeNepal and confirms that the recipient has successfully completed the course.
                        </p>
                    </div>
                </div>
            <?php else: ?>
                <!-- Invalid Certificate -->
                <div class="bg-red-50 border-2 border-red-500 rounded-lg p-8 text-center">
                    <div class="text-6xl mb-4">❌</div>
                    <h2 class="text-2xl font-bold text-red-800 mb-2">Invalid Certificate</h2>
                    <p class="text-red-600"><?php echo htmlspecialchars($error); ?></p>
                    <p class="text-sm text-gray-600 mt-4">
                        Please verify the code is correct and try again. Certificate codes are case-insensitive.
                    </p>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <!-- Info Section -->
        <div class="mt-8 bg-blue-50 rounded-lg p-6">
            <h3 class="font-semibold text-gray-900 mb-3">About Certificate Verification</h3>
            <ul class="space-y-2 text-sm text-gray-700">
                <li class="flex items-start">
                    <span class="mr-2">•</span>
                    <span>Each certificate has a unique verification code</span>
                </li>
                <li class="flex items-start">
                    <span class="mr-2">•</span>
                    <span>Verification codes are 12 characters long</span>
                </li>
                <li class="flex items-start">
                    <span class="mr-2">•</span>
                    <span>Codes are case-insensitive</span>
                </li>
                <li class="flex items-start">
                    <span class="mr-2">•</span>
                    <span>All certificates are permanently stored in our database</span>
                </li>
            </ul>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
