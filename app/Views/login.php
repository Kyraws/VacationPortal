<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 flex items-center justify-center min-h-screen">
<div class="bg-gray-800 text-white p-8 rounded-lg shadow-lg w-96">
    <h2 class="text-3xl font-bold text-center mb-6">Login</h2>
    <?php if (!empty($error)): ?>
        <p class="bg-red-500 text-white p-3 rounded-md text-center mb-4">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php endif; ?>
    <form method="POST" action="/login" class="space-y-4">
        <div>
            <label for="username" class="block text-sm font-medium">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter Username"
                   class="w-full px-4 py-2 mt-1 rounded-md bg-gray-700 text-white border border-gray-600 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                   required>
        </div>
        <div>
            <label for="password" class="block text-sm font-medium">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter Password"
                   class="w-full px-4 py-2 mt-1 rounded-md bg-gray-700 text-white border border-gray-600 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                   required>
        </div>
        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 transition px-4 py-2 rounded-md text-white font-semibold">
            Login
        </button>
    </form>
</div>
</body>
</html>
