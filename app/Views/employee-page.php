<?php

use App\Session\Session;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 flex items-center justify-center min-h-screen text-white">
<div class="max-w-6xl w-full bg-gray-800 p-6 rounded-lg shadow-lg">
    <h2 class="text-3xl font-bold text-center mb-6">
        Welcome, <?php echo htmlspecialchars(Session::get('role')); ?>!</h2>

    <div class="flex justify-between mb-6">
        <a href="/employee/create-request"
           class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">Make
            a New Request</a>
        <a href="/logout" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition">Logout</a>
    </div>

    <h2 class="text-xl font-semibold mb-4">Your Requests</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-700 rounded-lg">
            <thead class="bg-gray-700">
            <tr>
                <th class="px-4 py-2 text-left">Date Submitted</th>
                <th class="px-4 py-2 text-left">Date Requested</th>
                <th class="px-4 py-2 text-left">Reason</th>
                <th class="px-4 py-2 text-left">Total Days</th>
                <th class="px-4 py-2 text-left">Status</th>
                <th class="px-4 py-2 text-left">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-600">
            <?php if (!empty($requests)): ?>
                <?php foreach ($requests as $request): ?>
                    <tr class="hover:bg-gray-700">
                        <td class="px-4 py-2 text-left"><?php echo htmlspecialchars($request['date_submitted']); ?></td>
                        <td class="px-4 py-2 text-left"><?php echo htmlspecialchars($request['start_date']) . " → " . htmlspecialchars($request['end_date']); ?></td>
                        <td class="px-4 py-2 text-left"><?php echo htmlspecialchars($request['reason']); ?></td>
                        <td class="px-4 py-2 text-left"><?php echo htmlspecialchars($request['total_days']); ?></td>
                        <td class="px-4 py-2 text-left">
                            <span class="px-2 py-1 rounded-md text-white <?php echo $request['status'] === 'pending' ? 'bg-yellow-500' : ($request['status'] === 'approved' ? 'bg-green-500' : 'bg-red-500'); ?>">
                                <?php echo ucfirst(htmlspecialchars($request['status'])); ?>
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            <?php if ($request['status'] === 'pending'): ?>
                                <form method="POST" action="/employee/delete-request" class="inline">
                                    <input type="hidden" name="id"
                                           value="<?php echo htmlspecialchars($request['id']); ?>">
                                    <button type="submit"
                                            class="bg-red-600 text-white px-3 py-1 rounded-md hover:bg-red-700 transition"
                                            onclick="return confirm('Are you sure you want to delete this request?')">
                                        Delete
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="text-gray-400 italic">No Actions Available</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="px-4 py-2 text-center text-gray-400">No requests found.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
