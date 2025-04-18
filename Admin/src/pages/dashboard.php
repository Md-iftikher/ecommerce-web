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
$recentOrders = []; // Initialize as empty array

// Fetch data from database
try {
    // Total Orders
    $stmt = $conn->query("SELECT COUNT(*) AS totalOrders FROM orders");
    $totalOrders = $stmt->fetch_assoc()['totalOrders'];

    // Pending Orders
    $stmt = $conn->query("SELECT COUNT(*) AS pendingOrders FROM orders WHERE status = 'pending'");
    $pendingOrders = $stmt->fetch_assoc()['pendingOrders'];

    // Total Revenue from delivered orders
    $stmt = $conn->query("SELECT COALESCE(SUM(total_price), 0) AS totalRevenue FROM orders WHERE status = 'delivered'");
    $result = $stmt->fetch_assoc();
    $totalRevenue = $result['totalRevenue'] ?? 0;

    // New Customers (last 7 days)
    $stmt = $conn->query("SELECT COUNT(*) AS newCustomers FROM customers WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
    $result = $stmt->fetch_assoc();
    $newCustomers = $result['newCustomers'] ?? 0;
} catch (Exception $e) {
    $error = "Database error: " . $e->getMessage();
    error_log($error);
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
    <style>
        :root {
            --primary: #3b82f6;
            --secondary: #10b981;
            --dark: #1e293b;
            --light: #f8fafc;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        .sidebar {
            transition: all 0.3s;
        }

        .card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-processing {
            background-color: #e0e7ff;
            color: #3730a3;
        }

        .status-delivered {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-out_for_delivery {
            background-color: #ffedd5;
            color: #9a3412;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-gray-200 sidebar">
            <div class="p-4 border-b border-gray-200">
                <h1 class="text-xl font-semibold text-gray-800">
                    <span class="text-blue-500">Admin</span>Panel
                </h1>
            </div>

            <div class="p-4">
                <!-- Admin Profile -->
                <div class="flex items-center space-x-3 p-3 mb-6 bg-gray-50 rounded-lg">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['username'] ?? 'Admin') ?>&background=3b82f6&color=fff"
                        alt="Admin" class="w-10 h-10 rounded-full">
                    <div>
                        <h3 class="font-medium text-gray-800"><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></h3>
                        <p class="text-xs text-gray-500">Administrator</p>
                    </div>
                </div>

                <!-- Navigation -->
                <nav>
                    <ul class="space-y-1">
                        <li>
                            <a href="dashboard.php" class="flex items-center p-3 rounded-lg bg-blue-50 text-blue-600">
                                <i class="fas fa-tachometer-alt mr-3 text-blue-500"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="products.php" class="flex items-center p-3 rounded-lg hover:bg-gray-100 text-gray-600">
                                <i class="fas fa-box-open mr-3 text-gray-500"></i>
                                <span>Products</span>
                            </a>
                        </li>
                        <li>
                            <a href="orders.php" class="flex items-center p-3 rounded-lg hover:bg-gray-100 text-gray-600">
                                <i class="fas fa-shopping-cart mr-3 text-gray-500"></i>
                                <span>Orders</span>
                                <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full"><?= $pendingOrders ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="customers.php" class="flex items-center p-3 rounded-lg hover:bg-gray-100 text-gray-600">
                                <i class="fas fa-users mr-3 text-gray-500"></i>
                                <span>Customers</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Logout Section -->
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200">
                <a href="../PHP/logout.php" class="flex items-center p-3 rounded-lg hover:bg-gray-100 text-gray-600">
                    <i class="fas fa-sign-out-alt mr-3 text-gray-500"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <!-- Top Navigation -->
            <header class="bg-white border-b border-gray-200">
                <div class="flex items-center justify-between p-4">
                    <h2 class="text-lg font-medium text-gray-800">Dashboard Overview</h2>
                    <div class="flex items-center space-x-4">
                        <button class="p-2 rounded-full hover:bg-gray-100 text-gray-500">
                            <i class="fas fa-bell"></i>
                        </button>
                        <div class="relative">
                            <button id="userMenuButton" class="flex items-center space-x-2 focus:outline-none">
                                <span class="text-sm text-gray-600"><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></span>
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['username'] ?? 'Admin') ?>&background=3b82f6&color=fff"
                                    alt="User" class="w-8 h-8 rounded-full">
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <main class="p-6">
                <!-- Welcome Banner -->
                <div class="bg-blue-50 border border-blue-100 rounded-lg p-6 mb-6">
                    <div class="flex flex-col md:flex-row items-center justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800 mb-1">Welcome back, <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?>!</h2>
                            <p class="text-gray-600">Here's your store summary for today.</p>
                        </div>
                        <button id="refresh-btn" class="mt-3 md:mt-0 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-sync-alt mr-2 text-gray-500"></i> Refresh
                        </button>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white border border-gray-200 rounded-lg p-4 card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Total Revenue</p>
                                <p class="text-xl font-semibold mt-1 text-gray-800">
                                    $<?= isset($totalRevenue) && $totalRevenue !== null ? number_format($totalRevenue, 2) : '0.00' ?>
                                </p>
                            </div>
                            <div class="p-2 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4 card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Total Orders</p>
                                <p class="text-xl font-semibold mt-1 text-gray-800"><?= $totalOrders ?></p>
                            </div>
                            <div class="p-2 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4 card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Pending Orders</p>
                                <p class="text-xl font-semibold mt-1 text-gray-800"><?= $pendingOrders ?></p>
                            </div>
                            <div class="p-2 rounded-full bg-yellow-100 text-yellow-600">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4 card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">New Customers</p>
                                <p class="text-xl font-semibold mt-1 text-gray-800">
                                    <?= isset($newCustomers) && $newCustomers !== null ? $newCustomers : '0' ?>
                                </p>
                            </div>
                            <div class="p-2 rounded-full bg-purple-100 text-purple-600">
                                <i class="fas fa-user-plus"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-10">
                    <!-- Recent Orders -->
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden mb-6">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="font-medium text-gray-800">Recent Orders</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="recent-orders-table">
                                    <tr>
                                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">Loading recent orders...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                    </div>
                    <div class="p-3 border-t border-gray-200 flex justify-end">
                        <a href="orders.php" class="text-sm font-medium text-blue-600 hover:text-blue-800">View All Orders →</a>
                    </div>
                </div>



            </main>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Mobile sidebar toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    document.querySelector('aside').classList.toggle('hidden');
                });
            }

            // Load recent orders via AJAX
            function loadRecentOrders() {
                fetch('../PHP/get_recent_orders.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.orders.length > 0) {
                            const ordersTable = document.getElementById('recent-orders-table');
                            ordersTable.innerHTML = '';

                            data.orders.forEach(order => {
                                const row = document.createElement('tr');
                                row.className = 'hover:bg-gray-50';
                                row.innerHTML = `
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-800">${order.order_id}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">${order.customer_name}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">${order.order_date}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">$${parseFloat(order.amount).toFixed(2)}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="status-badge ${getStatusClass(order.status)}">
                                            ${formatStatus(order.status)}
                                        </span>
                                    </td>
                                `;
                                ordersTable.appendChild(row);
                            });
                        } else {
                            document.getElementById('recent-orders-table').innerHTML = `
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-center text-gray-500">No recent orders found</td>
                                </tr>
                            `;
                        }
                    })
                    .catch(error => {
                        console.error('Error loading recent orders:', error);
                        document.getElementById('recent-orders-table').innerHTML = `
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-gray-500">Error loading orders</td>
                            </tr>
                        `;
                    });
            }

            // Helper functions for status display
            function getStatusClass(status) {
                switch (status) {
                    case 'pending':
                        return 'status-pending';
                    case 'processing':
                        return 'status-processing';
                    case 'delivered':
                        return 'status-delivered';
                    case 'out_for_delivery':
                        return 'status-out_for_delivery';
                    default:
                        return 'status-pending';
                }
            }

            function formatStatus(status) {
                return status.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            }

            // Refresh button functionality
            document.getElementById('refresh-btn').addEventListener('click', function() {
                loadRecentOrders();
            });

            // Initial load
            loadRecentOrders();
        });
    </script>
</body>

</html>