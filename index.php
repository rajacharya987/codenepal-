<?php
/**
 * Landing Page
 * Homepage for CodeNepal
 */

$pageTitle = 'Learn Programming Interactively';
require_once __DIR__ . '/includes/header.php';

// Get featured courses
$conn = getDBConnection();
$sql = "SELECT * FROM courses WHERE is_published = 1 ORDER BY created_at DESC LIMIT 3";
$result = executeQuery($conn, $sql);
$featuredCourses = fetchAll($result);
?>

<!-- Hero Section -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                Learn Programming<br>The Interactive Way
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-blue-100">
                Master Python, JavaScript, and C++ through hands-on coding exercises
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <?php if ($isLoggedIn): ?>
                    <a href="/pages/courses" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                        Browse Courses
                    </a>
                    <a href="/pages/dashboard" class="bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-800 transition border-2 border-white">
                        Go to Dashboard
                    </a>
                <?php else: ?>
                    <a href="/pages/register" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                        Get Started Free
                    </a>
                    <a href="/pages/courses" class="bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-800 transition border-2 border-white">
                        Browse Courses
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h2 class="text-3xl font-bold text-center mb-12">Why Choose CodeNepal?</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Feature 1 -->
        <div class="text-center p-6">
            <div class="text-5xl mb-4">💻</div>
            <h3 class="text-xl font-semibold mb-2">Interactive Code Editor</h3>
            <p class="text-gray-600">Write and execute code directly in your browser with instant feedback</p>
        </div>
        
        <!-- Feature 2 -->
        <div class="text-center p-6">
            <div class="text-5xl mb-4">🎯</div>
            <h3 class="text-xl font-semibold mb-2">Progressive Learning</h3>
            <p class="text-gray-600">Unlock lessons as you progress, ensuring solid understanding at each step</p>
        </div>
        
        <!-- Feature 3 -->
        <div class="text-center p-6">
            <div class="text-5xl mb-4">🏆</div>
            <h3 class="text-xl font-semibold mb-2">Earn Certificates</h3>
            <p class="text-gray-600">Complete courses and earn certificates to showcase your skills</p>
        </div>
        
        <!-- Feature 4 -->
        <div class="text-center p-6">
            <div class="text-5xl mb-4">🌐</div>
            <h3 class="text-xl font-semibold mb-2">Multiple Languages</h3>
            <p class="text-gray-600">Learn Python, JavaScript, and C++ all in one platform</p>
        </div>
        
        <!-- Feature 5 -->
        <div class="text-center p-6">
            <div class="text-5xl mb-4">📊</div>
            <h3 class="text-xl font-semibold mb-2">Track Progress</h3>
            <p class="text-gray-600">Monitor your learning journey with detailed progress tracking</p>
        </div>
        
        <!-- Feature 6 -->
        <div class="text-center p-6">
            <div class="text-5xl mb-4">🎓</div>
            <h3 class="text-xl font-semibold mb-2">Beginner Friendly</h3>
            <p class="text-gray-600">Start from zero with courses designed for complete beginners</p>
        </div>
    </div>
</div>

<!-- Featured Courses Section -->
<?php if (!empty($featuredCourses)): ?>
<div class="bg-gray-100 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center mb-12">Featured Courses</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach ($featuredCourses as $course): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <?php if ($course['thumbnail_url']): ?>
                    <img src="<?php echo htmlspecialchars($course['thumbnail_url']); ?>" alt="<?php echo htmlspecialchars($course['title']); ?>" class="w-full h-48 object-cover">
                <?php else: ?>
                    <div class="w-full h-48 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                        <span class="text-white text-6xl">
                            <?php 
                            echo $course['language'] === 'python' ? '🐍' : 
                                 ($course['language'] === 'javascript' ? '⚡' : '⚙️'); 
                            ?>
                        </span>
                    </div>
                <?php endif; ?>
                
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2 py-1 bg-blue-100 text-blue-600 text-xs rounded">
                            <?php echo ucfirst($course['language']); ?>
                        </span>
                        <span class="px-2 py-1 bg-green-100 text-green-600 text-xs rounded">
                            <?php echo ucfirst($course['category']); ?>
                        </span>
                    </div>
                    
                    <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($course['title']); ?></h3>
                    <p class="text-gray-600 mb-4"><?php echo truncateText($course['description'], 100); ?></p>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">⏱️ <?php echo htmlspecialchars($course['duration']); ?></span>
                        <a href="/pages/course?id=<?php echo urlencode($course['id']); ?>" class="text-blue-600 hover:text-blue-800 font-semibold">
                            View Course →
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-8">
            <a href="/pages/courses" class="view-all-courses-btn inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                View All Courses
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- CTA Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-12 text-center text-white">
        <h2 class="text-3xl font-bold mb-4">Ready to Start Your Coding Journey?</h2>
        <p class="text-xl mb-8">Join thousands of learners mastering programming skills</p>
        <?php if (!$isLoggedIn): ?>
            <a href="/pages/register" class="inline-block bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                Sign Up Now - It's Free!
            </a>
        <?php else: ?>
            <a href="/pages/courses" class="inline-block bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                Start Learning Now
            </a>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
