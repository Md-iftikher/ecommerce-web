<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-16">
        <div class="flex justify-between items-center mb-12">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Admin Panel</h1>
                <p class="text-gray-600">Management Console</p>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-gray-700">
                    <i class="fas fa-user-circle mr-2"></i>
                    <?php echo htmlspecialchars($_SESSION['username']); ?>
                </span>
                <a href="../PHP/logout.php" class="text-red-500 hover:text-red-700 transition">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        <!-- Welcome Card -->
        <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md overflow-hidden">
            <div class="md:flex">
                <div class="p-8">
                    <div class="uppercase tracking-wide text-sm text-blue-500 font-semibold">Welcome back</div>
                    <h2 class="mt-2 text-2xl font-bold text-gray-800">
                        Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>!
                    </h2>
                    <p class="mt-2 text-gray-500">
                        You are logged in to the administration panel. Access the dashboard to manage your e-commerce system.
                    </p>
                    <div class="mt-6">
                        <a href="dashboard.php" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            Go to Dashboard
                        </a>
                    </div>
                </div>
                <div class="hidden md:block md:flex-shrink-0 bg-blue-50 flex items-center justify-center p-8">
                    <i class="fas fa-shield-alt text-blue-400 text-8xl"></i>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="dashboard.php" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition flex items-start">
                <div class="bg-blue-100 p-3 rounded-full mr-4">
                    <i class="fas fa-tachometer-alt text-blue-600"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Dashboard</h3>
                    <p class="text-sm text-gray-600 mt-1">View system overview and analytics</p>
                </div>
            </a>
            <a href="products.php" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition flex items-start">
                <div class="bg-green-100 p-3 rounded-full mr-4">
                    <i class="fas fa-box-open text-green-600"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Products</h3>
                    <p class="text-sm text-gray-600 mt-1">Manage your product catalog</p>
                </div>
            </a>
            <a href="orders.php" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition flex items-start">
                <div class="bg-purple-100 p-3 rounded-full mr-4">
                    <i class="fas fa-shopping-cart text-purple-600"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Orders</h3>
                    <p class="text-sm text-gray-600 mt-1">View and process customer orders</p>
                </div>
            </a>
        </div>
    </div>
</body>
</html>