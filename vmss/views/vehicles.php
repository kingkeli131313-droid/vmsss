<?php 
require_once __DIR__ . '/layouts/header.php'; 
$role = $_SESSION['role'];
?>

<div class="sm:flex sm:items-center sm:justify-between">
    <div>
        <h2 class="text-2xl font-bold tracking-tight text-slate-900">Enterprise Fleet Board</h2>
        <p class="mt-1 text-sm text-slate-500">Asset logs, structural odometer tracking, and active regulatory DVLA metrics.</p>
    </div>
</div>

<?php if (in_array($role, ['Admin', 'FleetManager'])): ?>
<div class="mt-8 bg-white p-6 rounded-lg shadow-sm border border-slate-100">
    <h3 class="text-lg font-medium text-slate-900 mb-4">Onboard High-Value Vehicle Asset</h3>
    <form action="/vmss/index.php?action=add_vehicle" method="POST" class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-4">
        <div>
            <label class="block text-sm font-medium text-slate-700">License Plate</label>
            <input type="text" name="license_plate" required placeholder="e.g., GW-4012-24" class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">VIN</label>
            <input type="text" name="vin" required class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Make</label>
            <input type="text" name="make" required placeholder="e.g., Kantanka / Mercedes" class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Model</label>
            <input type="text" name="model" required class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Year</label>
            <input type="number" name="manufacture_year" required class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Current Odometer (KM)</label>
            <input type="number" name="current_odometer" required class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">DVLA Roadworthy Expiry</label>
            <input type="date" name="dvla_roadworthy_expiry" required class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Operational Status</label>
            <select name="status" class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                <option value="Available">Available</option>
                <option value="In_Service">In Service</option>
                <option value="Breakdown">Breakdown</option>
            </select>
        </div>
        <div class="sm:col-span-4 flex justify-end">
            <button type="submit" class="bg-emerald-600 text-white font-medium px-4 py-2 rounded hover:bg-emerald-500 transition shadow-sm text-sm">Register Asset</button>
        </div>
    </form>
</div>
<?php endif; ?>

<div class="mt-8 flex flex-col">
    <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg bg-white">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-3 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Vehicle Asset</th>
                            <th class="px-3 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">VIN</th>
                            <th class="px-3 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Odometer</th>
                            <th class="px-3 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">DVLA Compliance</th>
                            <th class="px-3 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        <?php foreach ($vehicles as $row): ?>
                        <tr>
                            <td class="whitespace-nowrap px-3 py-4 text-sm">
                                <div class="font-semibold text-slate-900"><?= htmlspecialchars($row['license_plate']); ?></div>
                                <div class="text-slate-500 text-xs"><?= htmlspecialchars($row['make'] . ' ' . $row['model']); ?></div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500 font-mono text-xs"><?= htmlspecialchars($row['vin']); ?></td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500"><?= number_format($row['current_odometer']); ?> KM</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm">
                                <span class="text-xs px-2 py-0.5 rounded font-medium <?= $row['compliance_days_remaining'] < 30 ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-slate-50 text-slate-700' ?>">
                                    <?= htmlspecialchars($row['dvla_roadworthy_expiry']); ?> (<?= $row['compliance_days_remaining']; ?> days)
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm">
                                <span class="inline-flex rounded-full px-2.5 text-xs font-semibold leading-5 <?= $row['status'] === 'Available' ? 'bg-emerald-50 text-emerald-800' : ($row['status'] === 'In_Service' ? 'bg-amber-50 text-amber-800' : 'bg-rose-50 text-rose-800') ?>">
                                    <?= htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>