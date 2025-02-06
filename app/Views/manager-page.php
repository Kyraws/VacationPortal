<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Page - Users List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">
<div class="max-w-6xl mx-auto p-6">

    <div class="flex justify-between mb-6">
        <a href="/manager/create-employee"
           class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
            Create New Employee
        </a>
        <a href="/manager/manage-requests"
           class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-700 transition">
            Manage Requests
        </a>
        <a href="/logout" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-700 transition">
            Logout
        </a>
    </div>

    <h2 class="text-xl font-semibold mb-4">Registered Users</h2>

    <div class="overflow-x-auto bg-gray-800 rounded-lg shadow-md">
        <table class="min-w-full border border-gray-700">
            <thead class="bg-gray-700">
            <tr>
                <th class="px-4 py-2 text-left">Username</th>
                <th class="px-4 py-2 text-left">Name</th>
                <th class="px-4 py-2 text-left">Email</th>
                <th class="px-4 py-2 text-left">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-600">
            <?php if (!empty($result)): ?>
                <?php foreach ($result as $user): ?>
                    <tr class="hover:bg-gray-700">
                        <td class="px-4 py-2 text-left"><?php echo htmlspecialchars($user['username']); ?></td>
                        <td class="px-4 py-2 text-left"><?php echo htmlspecialchars($user['name']); ?></td>
                        <td class="px-4 py-2 text-left"><?php echo htmlspecialchars($user['email']); ?></td>
                        <td class="px-4 py-2 text-left flex gap-2">
                            <a href="/manager/edit-employee?id=<?php echo urlencode($user['id']); ?>"
                               class="bg-yellow-700 text-white px-3 py-1 rounded-md hover:bg-yellow-800 transition">
                                Edit
                            </a>
                            <form method="POST" action="/manager/delete-employee"
                                  onsubmit="return confirm('Are you sure you want to delete this user?');">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
                                <button type="submit"
                                        class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-700 transition">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="px-4 py-2 text-center text-gray-400">No users found.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>