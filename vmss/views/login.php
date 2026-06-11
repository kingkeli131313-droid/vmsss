<?php require_once __DIR__ . '/layouts/header.php'; ?>
<div class="flex min-h-[75vh] flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto $sm:w-full sm:max-w-md">
        <h2 class="text-center text-3xl font-extrabold tracking-tight text-slate-900">Sign in to VMSS</h2>
        <?php if(isset($_GET['error'])): ?>
            <div class="mt-4 p-3 bg-rose-50 text-rose-700 text-sm rounded border border-rose-200 text-center">
                <?= htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 border border-slate-100">
            <form action="/vmss/index.php?action=login_submit" method="POST" class="space-y-6">
                <div>
                    <label for="username" class="block text-sm font-medium text-slate-700">Username</label>
                    <input id="username" name="username" type="text" required class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 sm:text-sm">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                    <input id="password" name="password" type="password" required class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 sm:text-sm">
                </div>
                <div>
                    <button type="submit" class="flex w-full justify-center rounded-md bg-slate-900 py-2 px-4 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition">Verify Identity</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/layouts/footer.php'; ?>