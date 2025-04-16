<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Database connection
require_once __DIR__ . '/../PHP/config.php';

$totalRevenue = 0; 
$newCustomers = 0;


// Fetch data from database
try {
    // Total Orders
    $stmt = $conn->query("SELECT COUNT(*) AS totalOrders FROM orders");
    $totalOrders = $stmt->fetch_assoc()['totalOrders'];

    // Pending Orders
    $stmt = $conn->query("SELECT COUNT(*) AS pendingOrders FROM orders WHERE status = 'Pending'");
    $pendingOrders = $stmt->fetch_assoc()['pendingOrders'];

    $stmt = $conn->query("SELECT COALESCE(SUM(total_amount), 0) AS totalRevenue FROM orders WHERE status = 'Completed'");
    $result = $stmt->fetch_assoc();
    $totalRevenue = $result['totalRevenue'] ?? 0;

    // New Customers (last 7 days)
    $stmt = $conn->query("SELECT COUNT(*) AS newCustomers FROM customers WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
    $result = $stmt->fetch_assoc();
    $newCustomers = $result['newCustomers'] ?? 0; 

    // Recent 5 Orders
    $stmt = $conn->query("
        SELECT o.order_id, CONCAT(c.first_name, ' ', c.last_name) AS customer, 
               DATE_FORMAT(o.order_date, '%Y-%m-%d') AS date, 
               o.total_amount AS amount, o.status
        FROM orders o
        JOIN customers c ON o.customer_id = c.customer_id
        ORDER BY o.order_date DESC
        LIMIT 5
    ");
    $recentOrders = $stmt->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    $error = "Database error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4f46e5',
                        secondary: '#10b981',
                        dark: '#1e293b',
                        light: '#f8fafc'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-dark to-gray-900 text-white shadow-xl transform transition-all duration-300 ease-in-out">
            <div class="p-6 flex items-center justify-between border-b border-gray-700">
                <h1 class="text-2xl font-bold">
                    <span class="text-primary">Ecom</span>Admin
                </h1>
                <button id="sidebarToggle" class="text-gray-400 hover:text-white lg:hidden">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <div class="p-4">
                <!-- Admin Profile -->
                <div class="flex items-center space-x-4 p-4 mb-6 bg-gray-800 rounded-lg">
                    <div class="relative">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['username'] ?? 'Admin') ?>&background=4f46e5&color=fff"
                            alt="Admin" class="w-12 h-12 rounded-full">
                        <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-gray-800"></span>
                    </div>
                    <div>
                        <h3 class="font-semibold"><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></h3>
                        <p class="text-xs text-gray-400">Administrator</p>
                    </div>
                </div>

                <!-- Navigation -->
                <nav>
                    <ul class="space-y-2">
                        <li>
                            <a href="dashboard.php" class="flex items-center p-3 rounded-lg bg-primary text-white group">
                                <i class="fas fa-tachometer-alt mr-3"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="products.php" class="flex items-center p-3 rounded-lg hover:bg-gray-800 text-gray-300 hover:text-white group">
                                <i class="fas fa-box-open mr-3"></i>
                                <span>Products</span>
                            </a>
                        </li>
                        <li>
                            <a href="orders.php" class="flex items-center p-3 rounded-lg hover:bg-gray-800 text-gray-300 hover:text-white group">
                                <i class="fas fa-shopping-cart mr-3"></i>
                                <span>Orders</span>
                                <span class="ml-auto bg-red-500 text-xs px-2 py-1 rounded-full"><?= $pendingOrders ?> pending</span>
                            </a>
                        </li>
                        <li>
                            <a href="customers.php" class="flex items-center p-3 rounded-lg hover:bg-gray-800 text-gray-300 hover:text-white group">
                                <i class="fas fa-users mr-3"></i>
                                <span>Customers</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Logout Section -->
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-700">
                <a href="../PHP/logout.php" class="flex items-center p-3 rounded-lg hover:bg-gray-800 text-gray-300 hover:text-white group">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between p-4">
                    <div class="flex items-center space-x-4">
                        <h2 class="text-xl font-semibold text-gray-800">Dashboard Overview</h2>
                    </div>

                    <div class="flex items-center space-x-4">
                        <button class="p-2 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600">
                            <i class="fas fa-bell"></i>
                            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>
                        <div class="relative">
                            <button id="userMenuButton" class="flex items-center space-x-2 focus:outline-none">
                                <span class="text-sm font-medium"><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></span>
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['username'] ?? 'Admin') ?>&background=4f46e5&color=fff"
                                    alt="User" class="w-8 h-8 rounded-full">
                            </button>
                            <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                                <a href="../PHP/logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign out</a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <main class="p-6">
                <!-- Welcome Banner -->
                <div class="bg-gradient-to-r from-primary to-indigo-600 rounded-xl p-6 text-white mb-8 shadow-lg">
                    <div class="flex flex-col md:flex-row items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold mb-2">Welcome back, <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?>!</h2>
                            <p class="opacity-90">Here's what's happening with your store today.</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <a href="dashboard.php" class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg transition">
                                <i class="fas fa-sync-alt mr-2"></i> Refresh
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-primary">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                                <p class="text-2xl font-semibold mt-1">
                                    $<?= isset($totalRevenue) && $totalRevenue !== null ? number_format($totalRevenue, 2) : '0.00' ?>
                                </p>
                            </div>
                            <div class="p-3 rounded-full bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-secondary">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Orders</p>
                                <p class="text-2xl font-semibold mt-1"><?= $totalOrders ?></p>
                            </div>
                            <div class="p-3 rounded-full bg-secondary bg-opacity-10 text-secondary">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Pending Orders</p>
                                <p class="text-2xl font-semibold mt-1"><?= $pendingOrders ?></p>
                            </div>
                            <div class="p-3 rounded-full bg-yellow-500 bg-opacity-10 text-yellow-500">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">New Customers</p>
                                <p class="text-2xl font-semibold mt-1">
                                    <?= isset($newCustomers) && $newCustomers !== null ? $newCustomers : '0' ?>
                                </p>
                            </div>
                            <div class="p-3 rounded-full bg-purple-500 bg-opacity-10 text-purple-500">
                                <i class="fas fa-user-plus"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="font-semibold text-lg">Recent Orders</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if (!empty($recentOrders)): ?>
                                    <?php foreach ($recentOrders as $order): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($order['order_id']) ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($order['customer']) ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $order['date'] ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">$<?= number_format($order['amount'], 2) ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $order['status'] === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' ?>">
                                                    <?= htmlspecialchars($order['status']) ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="orders.php?id=<?= $order['order_id'] ?>" class="text-primary hover:text-indigo-900">View</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No recent orders found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-gray-100 flex justify-end">
                        <a href="orders.php" class="text-sm font-medium text-primary hover:text-indigo-700">View All Orders →</a>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Only keep the UI interaction JavaScript
        document.addEventListener("DOMContentLoaded", function() {
            // User menu toggle
            const userMenuButton = document.getElementById('userMenuButton');
            const userMenu = document.getElementById('userMenu');

            if (userMenuButton && userMenu) {
                userMenuButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userMenu.classList.toggle('hidden');
                });

                document.addEventListener('click', function() {
                    userMenu.classList.add('hidden');
                });
            }

            // Mobile sidebar toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    document.querySelector('aside').classList.toggle('hidden');
                });
            }
        });
    </script>
</body>

</html>