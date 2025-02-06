<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Request</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body class="bg-gray-900 flex items-center justify-center min-h-screen text-white">
<div class="bg-gray-800 text-white p-8 rounded-lg shadow-lg w-96">
    <h2 class="text-3xl font-bold text-center mb-6">Create New Request</h2>

    <?php if (!empty($error)): ?>
        <p class="bg-red-500 text-white p-3 rounded-md text-center mb-4">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="/employee/create-request" class="space-y-4">
        <div>
            <label for="reason" class="block text-sm font-medium">Reason</label>
            <textarea id="reason" name="reason" rows="4" required
                      class="w-full px-4 py-2 mt-1 rounded-md bg-gray-700 text-white border border-gray-600 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500"><?= isset($reason) ? htmlspecialchars($reason) : ''; ?></textarea>
        </div>

        <div>
            <label for="start_date" class="block text-sm font-medium">Start Date</label>
            <input type="text" id="start_date" name="start_date" required value="<?= $start_date ?? ''; ?>"
                   class="w-full px-4 py-2 mt-1 rounded-md bg-gray-700 text-white border border-gray-600 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="end_date" class="block text-sm font-medium">End Date</label>
            <input type="text" id="end_date" name="end_date" required value="<?= $end_date ?? ''; ?>"
                   class="w-full px-4 py-2 mt-1 rounded-md bg-gray-700 text-white border border-gray-600 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <script>
            flatpickr("#start_date", {
                dateFormat: "d-m-Y",
                allowInput: true,
                minDate: "today"
            });
            flatpickr("#end_date", {
                dateFormat: "d-m-Y",
                allowInput: true,
                minDate: "today"
            });
        </script>

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 transition px-4 py-2 rounded-md text-white font-semibold">
            Submit Request
        </button>
    </form>

    <a href="/employee/dashboard"
       class="block text-center mt-4 bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded-md text-white font-semibold">
        Back to Employee Dashboard
    </a>
</div>
</body>
</html>
