<?php 
require_once __DIR__ . '/layouts/header.php'; 

// Safely pull session roles
$role = isset($_SESSION['role']) ? strtolower(trim($_SESSION['role'])) : 'guest';
?>

<div class="sm:flex sm:items-center sm:justify-between mb-8">
    <div>
        <h2 class="text-2xl font-bold tracking-tight text-slate-900">Service Triage Log</h2>
        <p class="mt-1 text-sm text-slate-500">Track workshop diagnostics, repair execution, and historical asset maintenance costs.</p>
    </div>
</div>

<div class="bg-white p-6 rounded-lg shadow-sm border border-slate-100 mb-8">
    <h3 class="text-lg font-medium text-slate-900 mb-4">File New Vehicle Complaint</h3>
    
    <form action="index.php?action=save_maintenance" method="POST" class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-3">
        <div>
            <label class="block text-sm font-medium text-slate-700">Select Vehicle Asset</label>
            <select name="vehicle_id" required class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                <option value="">-- Choose Plate --</option>
                <?php if (isset($vehicles) && is_array($vehicles)): ?>
                    <?php foreach ($vehicles as $v): ?>
                        <option value="<?= $v['id']; ?>"><?= htmlspecialchars($v['license_plate'] . ' - ' . $v['make'] . ' ' . $v['model']); ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        
        <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-slate-700">Operator Complaint Description</label>
            <input type="text" name="service_details" required placeholder="e.g., Engine overheating on Spintex Road / Brake pads worn out" class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
        </div>

        <div class="sm:col-span-3 flex justify-end gap-x-3">
            <input type="hidden" name="service_date" value="<?= date('Y-m-d'); ?>">
            <input type="hidden" name="cost" value="0.00">
            <button type="submit" class="bg-slate-900 text-white font-medium px-4 py-2 rounded hover:bg-slate-800 transition shadow-sm text-sm">Log Workshop Complaint</button>
        </div>
    </form>
</div>

<div class="flex flex-col">
    <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg bg-white">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-3 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Vehicle</th>
                            <th class="px-3 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Service Details / Complaint</th>
                            <th class="px-3 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Date Logged</th>
                            <th class="px-3 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Maintenance Cost</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        <?php if (isset($records) && is_array($records) && count($records) > 0): ?>
                            <?php foreach ($records as $row): ?>
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-sm font-semibold text-slate-900">
                                    <?= htmlspecialchars(isset($row['license_plate']) ? $row['license_plate'] : 'Asset-ID: '.$row['vehicle_id']); ?>
                                </td>
                                <td class="px-3 py-4 text-sm text-slate-600">
                                    <?= htmlspecialchars($row['service_details']); ?>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                                    <?= htmlspecialchars($row['service_date']); ?>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-900 font-medium">
                                    GHS <?= number_format($row['cost'], 2); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-3 py-8 text-center text-sm text-slate-500 bg-slate-25">
                                    No ongoing service triage modifications recorded yet.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
