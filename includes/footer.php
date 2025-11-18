    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- About -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">CodeNepal</h3>
                    <p class="text-gray-400 text-sm">
                        Interactive programming learning platform for aspiring developers in Nepal and beyond.
                    </p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/pages/courses" class="text-gray-400 hover:text-white">Courses</a></li>
                        <li><a href="/pages/dashboard" class="text-gray-400 hover:text-white">Dashboard</a></li>
                        <li><a href="/pages/accomplishments" class="text-gray-400 hover:text-white">Accomplishments</a></li>
                    </ul>
                </div>
                
                <!-- Support -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Support</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/pages/verify" class="text-gray-400 hover:text-white">Verify Certificate</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Help Center</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Contact Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">FAQ</a></li>
                    </ul>
                </div>
                
                <!-- Legal -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Legal</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-400 hover:text-white">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-400">
                <p>&copy; <?php echo date('Y'); ?> CodeNepal. Built with ❤️ for aspiring programmers.</p>
            </div>
        </div>
    </footer>
    
    <!-- Main JavaScript -->
    <script src="/assets/js/main.js?v=<?php echo time(); ?>"></script>
</body>
</html>
<?php
// Flush output buffer at the end
if (ob_get_level()) {
    ob_end_flush();
}
?>
