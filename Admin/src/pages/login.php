<?php
session_start();
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: welcome.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../css/styles.css"> <!-- Tailwind or your custom CSS -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">

    <div class="bg-white shadow-md rounded-lg p-8 w-full max-w-sm">
        <h2 class="text-2xl font-semibold mb-6 text-center">Admin Login</h2>

        <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4 text-sm">
                Invalid username or password. Please try again.
            </div>
        <?php endif; ?>

        <form action="../PHP/authenticate.php" method="POST" class="space-y-4">
            <div>
                <label for="username" class="block text-sm font-medium">Username</label>
                <input
                    type="text"
                    name="username"
                    id="username"
                    class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="adminuser"
                    required>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium">Password</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="••••••••"
                    required>
            </div>

            <button
                type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition duration-200">
                Login
            </button>
        </form>
    </div>

</body>
</html>
