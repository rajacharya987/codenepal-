<?php
/**
 * Course Catalog Page
 */

$pageTitle = 'Courses';
require_once __DIR__ . '/../includes/header.php';

requireLogin();

$conn = getDBConnection();
$userId = $currentUser['id'];

// Get filter parameters
$filterLanguage = get('language', '');
$filterCategory = get('category', '');
$searchQuery = get('search', '');

// Build query
$sql = "SELECT c.*, 
        (SELECT COUNT(*) FROM lessons WHERE course_id = c.id) as lesson_count,
        (SELECT COUNT(*) FROM user_progress WHERE course_id = c.id AND user_id = ?) as is_enrolled
        FROM courses c
        WHERE c.is_published = 1";

$params = [$userId];
$types = "s";

if ($filterLanguage) {
    $sql .= " AND c.language = ?";
    $params[] = $filterLanguage;
    $types .= "s";
}

if ($filterCategory) {
    $sql .= " AND c.category = ?";
    $params[] = $filterCategory;
    $types .= "s";
}

if ($searchQuery) {
    $sql .= " AND (c.title LIKE ? OR c.description LIKE ?)";
    $searchTerm = "%$searchQuery%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= "ss";
}

$sql .= " ORDER BY c.created_at DESC";

$result = executeQuery($conn, $sql, $params, $types);
$courses = fetchAll($result);
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Explore Courses</h1>
        <p class="text-gray-600 mt-2">Choose from our collection of programming courses</p>
    </div>
    
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <form method="GET" action="" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" id="search" name="search" 
                       value="<?php echo htmlspecialchars($searchQuery); ?>"
                       placeholder="Search courses..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <!-- Language Filter -->
            <div>
                <label for="language" class="block text-sm font-medium text-gray-700 mb-1">Language</label>
                <select id="language" name="language" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Languages</option>
                    <option value="python" <?php echo $filterLanguage === 'python' ? 'selected' : ''; ?>>Python</option>
                    <option value="javascript" <?php echo $filterLanguage === 'javascript' ? 'selected' : ''; ?>>JavaScript</option>
                    <option value="cpp" <?php echo $filterLanguage === 'cpp' ? 'selected' : ''; ?>>C++</option>
                </select>
            </div>
            
            <!-- Category Filter -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Level</label>
                <select id="category" name="category" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Levels</option>
                    <option value="beginner" <?php echo $filterCategory === 'beginner' ? 'selected' : ''; ?>>Beginner</option>
                    <option value="intermediate" <?php echo $filterCategory === 'intermediate' ? 'selected' : ''; ?>>Intermediate</option>
                    <option value="advanced" <?php echo $filterCategory === 'advanced' ? 'selected' : ''; ?>>Advanced</option>
                </select>
            </div>
            
            <!-- Submit -->
            <div class="flex items-end">
                <button type="submit" 
                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Apply Filters
                </button>
            </div>
        </form>
        
        <?php if ($filterLanguage || $filterCategory || $searchQuery): ?>
        <div class="mt-4">
            <a href="/pages/courses" class="text-sm text-blue-600 hover:text-blue-800">
                Clear all filters
            </a>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Course Grid -->
    <?php if (empty($courses)): ?>
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No courses found</h3>
            <p class="mt-1 text-sm text-gray-500">Try adjusting your filters</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($courses as $course): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <?php if ($course['thumbnail_url']): ?>
                    <img src="<?php echo htmlspecialchars($course['thumbnail_url']); ?>" 
                         alt="<?php echo htmlspecialchars($course['title']); ?>" 
                         class="w-full h-48 object-cover">
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
                    <div class="flex items-center gap-2 mb-3">
                        <span class="px-2 py-1 bg-blue-100 text-blue-600 text-xs rounded font-medium">
                            <?php echo ucfirst($course['language']); ?>
                        </span>
                        <span class="px-2 py-1 bg-green-100 text-green-600 text-xs rounded font-medium">
                            <?php echo ucfirst($course['category']); ?>
                        </span>
                        <?php if ($course['is_enrolled']): ?>
                        <span class="px-2 py-1 bg-purple-100 text-purple-600 text-xs rounded font-medium">
                            Enrolled
                        </span>
                        <?php endif; ?>
                    </div>
                    
                    <h3 class="text-xl font-semibold mb-2 text-gray-900"><?php echo htmlspecialchars($course['title']); ?></h3>
                    <p class="text-gray-600 text-sm mb-4"><?php echo truncateText($course['description'], 100); ?></p>
                    
                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                        <span>📚 <?php echo $course['lesson_count']; ?> lessons</span>
                        <span>⏱️ <?php echo htmlspecialchars($course['duration']); ?></span>
                    </div>
                    
                    <a href="/pages/course?id=<?php echo urlencode($course['id']); ?>" 
                       class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition font-medium">
                        <?php echo $course['is_enrolled'] ? 'Continue Learning' : 'View Course'; ?>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
