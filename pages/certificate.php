<?php
/**
 * Cyberpunk Certificate Display Page
 */

require_once __DIR__ . '/../includes/header.php';

requireLogin();

$certificateId = get('id');
if (!$certificateId) {
    redirect('/pages/dashboard');
}

$conn = getDBConnection();
$userId = $currentUser['id'];

// Get certificate details
$sql = "SELECT c.*, u.name as user_name, u.email, co.title as course_title, co.language, co.category
        FROM certificates c
        JOIN users u ON c.user_id = u.id
        JOIN courses co ON c.course_id = co.id
        WHERE c.id = ? AND c.user_id = ?";
$result = executeQuery($conn, $sql, [$certificateId, $userId], "ss");
$certificate = fetchOne($result);

if (!$certificate) {
    setFlashMessage('error', 'Certificate not found');
    redirect('/pages/dashboard');
}

$pageTitle = 'Certificate - ' . $certificate['course_title'];
$issuedDate = date('F d, Y', strtotime($certificate['issued_at']));
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&display=swap');

.cert-container {
    background: linear-gradient(135deg, #0A0A0A 0%, #1A1A1A 100%);
    min-height: 100vh;
    padding: 3rem 1rem;
}

#certificate {
    background: #0A0A0A;
    border: 3px solid #FFD300;
    box-shadow: 
        0 0 30px rgba(255, 211, 0, 0.4),
        0 0 60px rgba(255, 211, 0, 0.2),
        inset 0 0 50px rgba(255, 211, 0, 0.05);
    position: relative;
    overflow: hidden;
}

.cert-corner {
    position: absolute;
    width: 100px;
    height: 100px;
    border: 2px solid #FFD300;
}

.cert-corner-tl { top: 20px; left: 20px; border-right: none; border-bottom: none; }
.cert-corner-tr { top: 20px; right: 20px; border-left: none; border-bottom: none; }
.cert-corner-bl { bottom: 20px; left: 20px; border-right: none; border-top: none; }
.cert-corner-br { bottom: 20px; right: 20px; border-left: none; border-top: none; }

.cert-glow-line {
    position: absolute;
    height: 2px;
    background: linear-gradient(90deg, transparent, #FFD300, transparent);
    animation: scan 3s linear infinite;
}

@keyframes scan {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.cert-title {
    font-family: 'Orbitron', sans-serif;
    font-weight: 900;
    font-size: 3rem;
    color: #FFD300;
    text-transform: uppercase;
    letter-spacing: 0.2em;
    text-shadow: 
        0 0 10px rgba(255, 211, 0, 0.8),
        0 0 20px rgba(255, 211, 0, 0.6),
        0 0 30px rgba(255, 211, 0, 0.4);
}

.cert-name {
    font-family: 'Orbitron', sans-serif;
    font-weight: 700;
    font-size: 2.5rem;
    color: #00FFFF;
    text-shadow: 
        0 0 10px rgba(0, 255, 255, 0.8),
        0 0 20px rgba(0, 255, 255, 0.6);
}

.cert-course {
    font-family: 'Orbitron', sans-serif;
    font-weight: 700;
    font-size: 2rem;
    color: #FF007F;
    text-shadow: 
        0 0 10px rgba(255, 0, 127, 0.8),
        0 0 20px rgba(255, 0, 127, 0.6);
}

.cert-code {
    font-family: 'Share Tech Mono', monospace;
    font-size: 2rem;
    color: #FFD300;
    letter-spacing: 0.3em;
    text-shadow: 
        0 0 10px rgba(255, 211, 0, 0.8),
        0 0 20px rgba(255, 211, 0, 0.6);
}

.cyber-grid {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
        linear-gradient(rgba(255, 211, 0, 0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 211, 0, 0.03) 1px, transparent 1px);
    background-size: 50px 50px;
    pointer-events: none;
}

.cert-badge {
    width: 80px;
    height: 80px;
    border: 3px solid #FFD300;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    background: radial-gradient(circle, rgba(255, 211, 0, 0.2), transparent);
    box-shadow: 0 0 20px rgba(255, 211, 0, 0.5);
}
</style>

<div class="cert-container">
    <div class="max-w-5xl mx-auto">
        <!-- Certificate Card -->
        <div id="certificate" class="rounded-2xl p-16 relative">
            <!-- Cyber Grid Background -->
            <div class="cyber-grid"></div>
            
            <!-- Scanning Line -->
            <div class="cert-glow-line" style="top: 50%; width: 100%;"></div>
            
            <!-- Corner Decorations -->
            <div class="cert-corner cert-corner-tl"></div>
            <div class="cert-corner cert-corner-tr"></div>
            <div class="cert-corner cert-corner-bl"></div>
            <div class="cert-corner cert-corner-br"></div>
            
            <!-- Content -->
            <div class="text-center relative z-10">
                <!-- Logo & Title -->
                <div class="mb-8">
                    <div class="flex justify-center items-center gap-4 mb-4">
                        <div class="cert-badge">⚡</div>
                        <div class="cert-badge">🇳🇵</div>
                        <div class="cert-badge">💻</div>
                    </div>
                    <h1 class="cert-title mb-2">CODENEPAL</h1>
                    <div class="text-electric-cyan text-xl tracking-widest" style="color: #00FFFF; font-family: 'Share Tech Mono', monospace;">
                        [ CERTIFICATE OF COMPLETION ]
                    </div>
                </div>
                
                <!-- Divider -->
                <div class="my-8 flex items-center justify-center">
                    <div class="h-px bg-gradient-to-r from-transparent via-yellow-400 to-transparent w-full max-w-2xl"></div>
                </div>
                
                <!-- Recipient -->
                <div class="mb-8">
                    <p class="text-gray-400 text-sm uppercase tracking-wider mb-3" style="font-family: 'Rajdhani', sans-serif;">
                        THIS CERTIFIES THAT
                    </p>
                    <h2 class="cert-name mb-2"><?php echo strtoupper(htmlspecialchars($certificate['user_name'])); ?></h2>
                    <p class="text-gray-500 text-sm" style="font-family: 'Share Tech Mono', monospace;">
                        ID: <?php echo strtoupper(substr($certificate['user_id'], 0, 8)); ?>
                    </p>
                </div>
                
                <!-- Achievement -->
                <div class="mb-8">
                    <p class="text-gray-400 text-sm uppercase tracking-wider mb-3" style="font-family: 'Rajdhani', sans-serif;">
                        HAS SUCCESSFULLY COMPLETED
                    </p>
                    <h3 class="cert-course mb-4"><?php echo strtoupper(htmlspecialchars($certificate['course_title'])); ?></h3>
                </div>
                
                <!-- Details Grid -->
                <div class="grid grid-cols-3 gap-6 max-w-2xl mx-auto mb-8">
                    <div class="bg-gray-900 border border-yellow-400 rounded-lg p-4">
                        <p class="text-gray-500 text-xs uppercase mb-1" style="font-family: 'Rajdhani', sans-serif;">Language</p>
                        <p class="text-yellow-400 font-bold text-lg" style="font-family: 'Orbitron', sans-serif;">
                            <?php echo strtoupper($certificate['language']); ?>
                        </p>
                    </div>
                    <div class="bg-gray-900 border border-cyan-400 rounded-lg p-4">
                        <p class="text-gray-500 text-xs uppercase mb-1" style="font-family: 'Rajdhani', sans-serif;">Level</p>
                        <p class="text-cyan-400 font-bold text-lg" style="font-family: 'Orbitron', sans-serif;">
                            <?php echo strtoupper($certificate['category']); ?>
                        </p>
                    </div>
                    <div class="bg-gray-900 border border-magenta-400 rounded-lg p-4">
                        <p class="text-gray-500 text-xs uppercase mb-1" style="font-family: 'Rajdhani', sans-serif;">Date</p>
                        <p class="text-magenta-400 font-bold text-sm" style="font-family: 'Orbitron', sans-serif; color: #FF007F;">
                            <?php echo strtoupper(date('d.m.Y', strtotime($certificate['issued_at']))); ?>
                        </p>
                    </div>
                </div>
                
                <!-- Divider -->
                <div class="my-8 flex items-center justify-center">
                    <div class="h-px bg-gradient-to-r from-transparent via-cyan-400 to-transparent w-full max-w-2xl"></div>
                </div>
                
                <!-- Verification Code -->
                <div class="bg-gray-900 border-2 border-yellow-400 rounded-lg p-6 max-w-xl mx-auto">
                    <p class="text-gray-400 text-xs uppercase tracking-wider mb-3" style="font-family: 'Rajdhani', sans-serif;">
                        ⚡ VERIFICATION CODE ⚡
                    </p>
                    <p class="cert-code mb-3"><?php echo $certificate['verification_code']; ?></p>
                    <p class="text-gray-500 text-xs" style="font-family: 'Share Tech Mono', monospace;">
                        VERIFY AT: <?php echo strtoupper(str_replace(['http://', 'https://'], '', SITE_URL)); ?>/PAGES/VERIFY
                    </p>
                </div>
                
                <!-- Footer -->
                <div class="mt-8 text-gray-600 text-xs" style="font-family: 'Share Tech Mono', monospace;">
                    <p>ISSUED BY CODENEPAL LEARNING PLATFORM</p>
                    <p class="mt-1">BLOCKCHAIN VERIFIED • TAMPER PROOF • PERMANENT RECORD</p>
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="mt-8 flex justify-center gap-4">
            <button onclick="downloadCertificate()" 
                    class="px-8 py-4 bg-yellow-400 text-black rounded-lg font-bold hover:bg-yellow-300 transition flex items-center gap-3 cyber-pulse"
                    style="font-family: 'Rajdhani', sans-serif; text-transform: uppercase; letter-spacing: 0.1em;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Download Certificate
            </button>
            <a href="/pages/dashboard" 
               class="px-8 py-4 bg-transparent border-2 border-yellow-400 text-yellow-400 rounded-lg font-bold hover:bg-yellow-400 hover:text-black transition"
               style="font-family: 'Rajdhani', sans-serif; text-transform: uppercase; letter-spacing: 0.1em;">
                Back to Dashboard
            </a>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
function downloadCertificate() {
    const certificate = document.getElementById('certificate');
    const btn = event.target;
    btn.innerHTML = '<span class="animate-spin inline-block mr-2">⚡</span> GENERATING...';
    btn.disabled = true;
    
    html2canvas(certificate, {
        scale: 3,
        backgroundColor: '#0A0A0A',
        logging: false
    }).then(canvas => {
        const link = document.createElement('a');
        link.download = 'CodeNepal_Certificate_<?php echo $certificate['verification_code']; ?>.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
        
        btn.innerHTML = '<svg class="w-6 h-6 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg> DOWNLOAD CERTIFICATE';
        btn.disabled = false;
    });
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
