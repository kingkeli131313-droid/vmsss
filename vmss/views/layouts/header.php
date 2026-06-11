<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VMSS Portal Enterprise</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .scroll-header {
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }
    </style>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-900">
    <header id="mainHeader" class="scroll-header sticky top-0 z-50 bg-slate-900 text-white p-4 shadow-md">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold tracking-tight">VMSS <span class="text-emerald-400 font-normal">Ghana</span></h1>
            <?php if (isset($_SESSION['user_id'])): ?>
                <nav class="hidden md:flex space-x-6">
                    <a href="/vmss/index.php?action=vehicles" class="hover:text-emerald-400 transition">Fleet Board</a>
                    <a href="/vmss/index.php?action=maintenance" class="hover:text-emerald-400 transition">Service Triage</a>
                </nav>
                <div class="flex items-center space-x-4">
                    <span class="text-xs bg-slate-800 px-2.5 py-1 rounded text-emerald-300 border border-emerald-500/20"><?= htmlspecialchars($_SESSION['role']); ?></span>
                    <a href="/vmss/index.php?action=logout" class="text-sm bg-rose-600 hover:bg-rose-500 px-3 py-1.5 rounded transition font-medium">Logout</a>
                </div>
            <?php endif; ?>
        </div>
    </header>
    <script>
        window.addEventListener('scroll', () => {
            const header = document.getElementById('mainHeader');
            if (window.scrollY > 20) {
                header.classList.remove('bg-slate-900');
                header.classList.add('bg-slate-950', 'backdrop-blur-md', 'shadow-lg');
            } else {
                header.classList.remove('bg-slate-950', 'backdrop-blur-md', 'shadow-lg');
                header.classList.add('bg-slate-900');
            }
        });
    </script>
    <main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">