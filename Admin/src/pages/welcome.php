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
    <style>
        .quick-link-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        .quick-link-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-left-color: currentColor;
        }
        .quick-link-card.dashboard:hover { border-left-color: #3b82f6; }
        .quick-link-card.products:hover { border-left-color: #10b981; }
        .quick-link-card.orders:hover { border-left-color: #8b5cf6; }
        .quick-link-card.customers:hover { border-left-color: #6366f1; }
    </style>
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


        <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Dashboard Card -->
            <a href="dashboard.php" class="quick-link-card dashboard bg-white p-6 rounded-lg shadow-md transition flex items-start relative overflow-hidden">
                <div class="bg-blue-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-tachometer-alt text-blue-600 text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-gray-800 text-lg">Dashboard</h3>
                    <p class="text-sm text-gray-600 mt-1">System overview and analytics</p>
                </div>
                <div class="absolute bottom-0 right-0 p-2 text-blue-500 opacity-10">
                    <i class="fas fa-tachometer-alt text-4xl"></i>
                </div>
            </a>

            <!-- Products Card -->
            <a href="products.php" class="quick-link-card products bg-white p-6 rounded-lg shadow-md transition flex items-start relative overflow-hidden">
                <div class="bg-green-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-box-open text-green-600 text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-gray-800 text-lg">Products</h3>
                    <p class="text-sm text-gray-600 mt-1">Manage product catalog</p>
        
                </div>
                <div class="absolute bottom-0 right-0 p-2 text-green-500 opacity-10">
                    <i class="fas fa-box-open text-4xl"></i>
                </div>
            </a>

            <!-- Orders Card -->
            <a href="orders.php" class="quick-link-card orders bg-white p-6 rounded-lg shadow-md transition flex items-start relative overflow-hidden">
                <div class="bg-purple-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-shopping-cart text-purple-600 text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-gray-800 text-lg">Orders</h3>
                    <p class="text-sm text-gray-600 mt-1">Process customer orders</p>
                   
                </div>
                <div class="absolute bottom-0 right-0 p-2 text-purple-500 opacity-10">
                    <i class="fas fa-shopping-cart text-4xl"></i>
                </div>
            </a>

            <!-- Customers Card -->
            <a href="customers.php" class="quick-link-card customers bg-white p-6 rounded-lg shadow-md transition flex items-start relative overflow-hidden">
                <div class="bg-indigo-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-users text-indigo-600 text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-gray-800 text-lg">Customers</h3>
                    <p class="text-sm text-gray-600 mt-1">Manage customer accounts</p>
                </div>
                <div class="absolute bottom-0 right-0 p-2 text-indigo-500 opacity-10">
                    <i class="fas fa-users text-4xl"></i>
                </div>
            </a>
        </div>
    </div>
</body>
</html>