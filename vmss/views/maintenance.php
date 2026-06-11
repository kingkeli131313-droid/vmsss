<?php 
require_once __DIR__ . '/layouts/header.php'; 
$role = $_SESSION['role'];
?>

<div class="sm:flex sm:items-center sm:justify-between">
    <div>
        <h2 class="text-2xl font-bold tracking-tight text-slate-900">Service Triage & Repair Logs</h2>
        <p class="mt-1 text-sm text-slate-500">Managing issues using Andrew A. Rezin's 3Cs Core Maintenance Methodology.</p>
    </div>
</div>

<?php if (in_array($role, ['Admin', 'FleetManager', 'Driver'])): ?>
<div class="mt-8 bg-white p-6 rounded-lg shadow-sm border border-slate-100">
    <h3 class="text-lg font-medium text-slate-900 mb-4">Log Structural Operator Fault (Complaint)</h3>
    <form action="/vmss/index.php?action=add_complaint" method="POST" class="space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="sm:col-span-1">
                <label class="block text-sm font-medium text-slate-700">Target Vehicle ID</label>
                <input type="number" name="vehicle_id" required placeholder="Asset ID" class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-slate-700">The Complaint (Operator Symptoms)</label>
                <input type="text" name="complaint" required placeholder="e.g., Spongy pedal response under braking, check engine illumination" class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
            </div>
        </div>
        <button type="submit" class="bg-slate-900 text-white font-medium px-4 py-2 rounded hover:bg-slate-800 transition shadow-sm text-sm">File Work Order</button>
    </form>
</div>
<?php endif; ?>

<div class="mt-8 space-y-6">
    <?php foreach ($records as $item): ?>
    <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
        <div class="bg-slate-900 p-4 flex justify-between items-center text-white">
            <div>
                <span class="font-bold text-emerald-400"><?= htmlspecialchars($item['license_plate']); ?></span> - 
                <span class="text-sm font-normal text-slate-300"><?= htmlspecialchars($item['make'] . ' ' . $item['model']); ?></span>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 rounded bg-slate-800 border border-slate-700"><?= htmlspecialchars($item['status']); ?></span>
        </div>
        
        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
            <div class="bg-slate-50 p-4 rounded border border-slate-100">
                <h4 class="font-bold text-slate-500 uppercase tracking-wider text-xs mb-1">1. Complaint</h4>
                <p class="text-slate-900 italic">"<?= htmlspecialchars($item['complaint']); ?>"</p>
                <div class="text-xs text-slate-400 mt-2">Logged by: <?= htmlspecialchars($item['reporter']); ?></div>
            </div>
            <div class="bg-slate-50 p-4 rounded border border-slate-100">
                <h4 class="font-bold text-slate-500 uppercase tracking-wider text-xs mb-1">2. Cause</h4>
                <p class="text-slate-900 font-medium"><?= $item['cause'] ? htmlspecialchars($item['cause']) : '<span class="text-slate-400 font-normal">Awaiting diagnostics</span>'; ?></p>
            </div>
            <div class="bg-slate-50 p-4 rounded border border-slate-100">
                <h4 class="font-bold text-slate-500 uppercase tracking-wider text-xs mb-1">3. Correction</h4>
                <p class="text-slate-900 font-medium"><?= $item['correction'] ? htmlspecialchars($item['correction']) : '<span class="text-slate-400 font-normal">Remediation pending</span>'; ?></p>
                <?php if($item['status'] === 'Completed'): ?>
                    <div class="mt-2 text-xs font-bold text-emerald-600">Cost: GHS <?= number_format($item['total_cost'], 2); ?></div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (in_array($role, ['Admin', 'Technician']) && $item['status'] !== 'Completed'): ?>
        <div class="border-t border-slate-100 bg-slate-50 p-4">
            <form action="/vmss/index.php?action=update_work_order" method="POST" class="grid grid-cols-1 gap-4 sm:grid-cols-5">
                <input type="hidden" name="record_id" value="<?= $item['id']; ?>">
                <div class="sm:col-span-1">
                    <label class="text-xs font-medium text-slate-500">Identify Cause</label>
                    <input type="text" name="cause" required class="mt-1 w-full rounded border-slate-300 px-2 py-1 text-sm shadow-sm">
                </div>
                <div class="sm:col-span-1">
                    <label class="text-xs font-medium text-slate-500">Apply Correction</label>
                    <input type="text" name="correction" required class="mt-1 w-full rounded border-slate-300 px-2 py-1 text-sm shadow-sm">
                </div>
                <div class="sm:col-span-1">
                    <label class="text-xs font-medium text-slate-500">Parts Cost (GHS)</label>
                    <input type="number" step="0.01" name="parts_cost" required class="mt-1 w-full rounded border-slate-300 px-2 py-1 text-sm shadow-sm">
                </div>
                <div class="sm:col-span-1">
                    <label class="text-xs font-medium text-slate-500">Labor Cost (GHS)</label>
                    <input type="number" step="0.01" name="labor_cost" required class="mt-1 w-full rounded border-slate-300 px-2 py-1 text-sm shadow-sm">
                </div>
                <div class="sm:col-span-1">
                    <label class="text-xs font-medium text-slate-500">Workflow State</label>
                    <select name="status" class="mt-1 w-full rounded border-slate-300 px-2 py-1 text-sm shadow-sm bg-white">
                        <option value="In_Progress">In Progress</option>
                        <option value="Awaiting_Parts">Awaiting Parts</option>
                        <option value="Completed">Completed / Sign Off</option>
                    </select>
                </div>
                <div class="sm:col-span-5 flex justify-end">
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white text-xs px-3 py-1.5 rounded transition font-medium">Update Work Order</button>
                </div>
            </form>
        </div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>