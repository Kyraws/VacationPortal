<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 flex items-center justify-center min-h-screen text-white">
<div class="bg-gray-800 text-white p-8 rounded-lg shadow-lg w-96">
    <h2 class="text-3xl font-bold text-center mb-6">Edit Employee</h2>

    <?php if (!empty($error)): ?>
        <p class="bg-red-500 text-white p-3 rounded-md text-center mb-4">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="/manager/edit-employee?id=<?php echo urlencode($userId); ?>" class="space-y-4">
        <div>
            <label for="username" class="block text-sm font-medium">Username</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($userData['username']); ?>"
                   required
                   class="w-full px-4 py-2 mt-1 rounded-md bg-gray-700 text-white border border-gray-600 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="name" class="block text-sm font-medium">Full Name</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($userData['name']); ?>" required
                   class="w-full px-4 py-2 mt-1 rounded-md bg-gray-700 text-white border border-gray-600 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($userData['email']); ?>" required
                   class="w-full px-4 py-2 mt-1 rounded-md bg-gray-700 text-white border border-gray-600 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium">New Password (leave blank to keep current)</label>
            <input type="password" id="password" name="password"
                   class="w-full px-4 py-2 mt-1 rounded-md bg-gray-700 text-white border border-gray-600 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 transition px-4 py-2 rounded-md text-white font-semibold">
            Update Employee
        </button>
    </form>

    <a href="/manager/dashboard"
       class="block text-center mt-4 bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded-md text-white font-semibold">
        Back to Manager Page
    </a>
</div>
</body>
</html>