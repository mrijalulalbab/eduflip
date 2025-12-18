<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';
require_once '../../includes/certificates.php';

// Auth Check
if (!isLoggedIn()) { header('Location: ../login.php'); exit; }

$cert_code = $_GET['code'] ?? '';
$student_id = $_SESSION['user_id'];

// Verify certificate belongs to student
$stmt = $pdo->prepare("
    SELECT cert.*, c.course_name, u.full_name as student_name, i.full_name as instructor_name
    FROM certificates cert
    JOIN courses c ON cert.course_id = c.id
    JOIN users u ON cert.student_id = u.id
    JOIN users i ON c.created_by = i.id
    WHERE cert.certificate_code = ? AND cert.student_id = ?
");
$stmt->execute([$cert_code, $student_id]);
$cert = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cert) {
    die("Certificate not found or access denied.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate - <?php echo htmlspecialchars($cert['course_name']); ?></title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@400;600&family=Great+Vibes&display=swap" rel="stylesheet">
    
    <style>
        @media print {
            @page { size: landscape; margin: 0; }
            body { margin: 0; padding: 0; -webkit-print-color-adjust: exact; }
            .no-print { display: none; }
        }
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-script { font-family: 'Great Vibes', cursive; }
        .certificate-border {
            border: 2px solid #b45309;
            outline: 2px solid #b45309;
            outline-offset: 4px;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center p-4">

    <!-- Print Controls -->
    <div class="no-print fixed top-4 right-4 flex gap-2">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-bold shadow-lg transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            Print / Save as PDF
        </button>
        <a href="index.php" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-bold shadow-lg transition">Close</a>
    </div>

    <!-- Certificate Container (Landscape A4ish) -->
    <div class="bg-white text-gray-900 w-[1100px] h-[750px] shadow-2xl relative p-12 flex flex-col items-center text-center certificate-bg overflow-hidden relative">
        
        <!-- Border Pattern -->
        <div class="absolute inset-4 border-[12px] border-double border-orange-900/20 pointer-events-none"></div>
        <div class="absolute inset-6 border border-orange-900/10 pointer-events-none"></div>

        <!-- Watermark -->
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 opacity-[0.03] pointer-events-none">
            <svg width="600" height="600" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L1 21H23L12 2ZM12 6L19.53 19H4.47L12 6Z"/></svg>
        </div>

        <!-- Header -->
        <div class="mt-8 mb-4">
            <h1 class="text-6xl font-serif font-bold text-orange-700 tracking-wider mb-2 uppercase">Certificate</h1>
            <span class="text-xl text-orange-600/60 font-serif tracking-[0.5em] uppercase">of Completion</span>
        </div>

        <div class="w-24 h-1 bg-gradient-to-r from-transparent via-orange-400 to-transparent mb-12"></div>

        <!-- Content -->
        <p class="text-xl text-gray-500 font-serif italic mb-4">This checks and validates that</p>
        
        <h2 class="text-5xl font-script text-gray-900 mb-8 px-8 py-2 border-b-2 border-gray-100 min-w-[500px]">
            <?php echo htmlspecialchars($cert['student_name']); ?>
        </h2>

        <p class="text-xl text-gray-500 font-serif italic mb-4">Has successfully completed the course requirements for</p>

        <h3 class="text-3xl font-serif font-bold text-gray-800 mb-12 max-w-3xl leading-tight">
            <?php echo htmlspecialchars($cert['course_name']); ?>
        </h3>

        <!-- Signatures & Date -->
        <div class="w-full flex justify-around items-end mt-auto px-16 pb-8">
            <div class="text-center">
                <div class="w-48 border-b border-gray-400 mb-2 pb-2 font-script text-3xl text-gray-700">
                    <?php echo htmlspecialchars($cert['instructor_name']); ?>
                </div>
                <p class="text-sm font-bold uppercase tracking-widest text-gray-400">Instructor</p>
            </div>

            <div class="text-center">
                <!-- Seal -->
                <div class="w-24 h-24 rounded-full border-4 border-orange-700/30 flex items-center justify-center mb-4 mx-auto text-orange-700/30">
                     <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L1 21H23L12 2ZM12 6L19.53 19H4.47L12 6Z"/></svg>
                </div>
                <div class="text-[10px] text-gray-400 font-mono">
                    ID: <?php echo htmlspecialchars($cert['certificate_code']); ?>
                </div>
            </div>

            <div class="text-center">
                <div class="w-48 border-b border-gray-400 mb-2 pb-4 font-serif text-xl text-gray-700">
                    <?php echo date('F j, Y', strtotime($cert['issued_at'])); ?>
                </div>
                <p class="text-sm font-bold uppercase tracking-widest text-gray-400">Date Issued</p>
            </div>
        </div>

    </div>

</body>
</html>
