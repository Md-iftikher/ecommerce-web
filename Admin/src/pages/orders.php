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
    <title>Order Management</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
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

<body class="bg-gray-50 font-sans">
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
                                <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full" id="pending-stat-sidebar">0</span>
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
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between p-4">
                    <div class="flex items-center space-x-4">
                        <h2 class="text-xl font-semibold text-gray-800">Order Management</h2>
                    </div>
                    <div class="flex items-center space-x-4">
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

            <main class="p-6">
                <!-- Status Cards -->
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                    <a href="#" class="status-card bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500 hover:bg-gray-50 transition" data-status="all">
                        <p class="text-sm text-gray-500">All Orders</p>
                        <p class="text-2xl font-bold" id="all-count">0</p>
                    </a>
                    <a href="#" class="status-card bg-white p-4 rounded-lg shadow-sm border-l-4 border-yellow-500 hover:bg-gray-50 transition" data-status="pending">
                        <p class="text-sm text-gray-500">Pending</p>
                        <p class="text-2xl font-bold" id="pending-count-card">0</p>
                    </a>
                    <a href="#" class="status-card bg-white p-4 rounded-lg shadow-sm border-l-4 border-purple-500 hover:bg-gray-50 transition" data-status="pending">
                        <p class="text-sm text-gray-500">Processing</p>
                        <p class="text-2xl font-bold" id="processing-order">0</p>
                    </a>
                   
                    <a href="#" class="status-card bg-white p-4 rounded-lg shadow-sm border-l-4 border-green-500 hover:bg-gray-50 transition" data-status="delivered">
                        <p class="text-sm text-gray-500">Delivered</p>
                        <p class="text-2xl font-bold" id="delivered-count">0</p>
                    </a>
                    <a href="#" class="status-card bg-white p-4 rounded-lg shadow-sm border-l-4 border-orange-500 hover:bg-gray-50 transition" data-status="out_for_delivery">
                        <p class="text-sm text-gray-500">Out for Delivery</p>
                        <p class="text-2xl font-bold" id="out-for-delivery-count">0</p>
                    </a>
                </div>

                <!-- Orders Table -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="orders-table-body">
                                <!-- Orders will be loaded here via AJAX -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <button id="prev-page-mobile" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Previous
                            </button>
                            <button id="next-page-mobile" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Next
                            </button>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700" id="pagination-info">
                                    Showing <span class="font-medium">0</span> to <span class="font-medium">0</span> of <span class="font-medium">0</span> results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    <button id="prev-page" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        <span class="sr-only">Previous</span>
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <div id="page-numbers" class="flex">
                                        <!-- Page numbers will be inserted here -->
                                    </div>
                                    <button id="next-page" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        <span class="sr-only">Next</span>
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Order Details Modal -->
    <div id="order-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-order-title">
                                Order Details
                            </h3>
                            <div class="mt-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="font-medium text-gray-900">Order Information</h4>
                                        <div class="mt-2 space-y-2 text-sm text-gray-500">
                                            <p><strong>Order ID:</strong> <span id="modal-order-id"></span></p>
                                            <p><strong>Date:</strong> <span id="modal-order-date"></span></p>
                                            <p><strong>Status:</strong>
                                                <select id="modal-order-status" class="ml-2 px-2 py-1 text-xs leading-5 font-semibold rounded-full focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                                    <option value="pending">Pending</option>
                                                    <option value="processing">Processing</option>
                                                    <option value="out_for_delivery">Out for Delivery</option>
                                                    <option value="delivered">Delivered</option>
                                                </select>
                                            </p>
                                            <p><strong>Total Amount:</strong> $<span id="modal-order-total"></span></p>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Customer Information</h4>
                                        <div class="mt-2 space-y-2 text-sm text-gray-500">
                                            <p><strong>Name:</strong> <span id="modal-customer-name"></span></p>
                                            <p><strong>Email:</strong> <span id="modal-customer-email"></span></p>
                                            <p><strong>Delivery Address:</strong> <span id="modal-delivery-address"></span></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <h4 class="font-medium text-gray-900">Order Items</h4>
                                    <div class="mt-2 overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200" id="modal-order-items">
                                                <!-- Order items will be loaded here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="update-status-btn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm">
                        Update Status
                    </button>
                    <button type="button" id="close-modal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Global variables
            let currentPage = 1;
            let totalPages = 1;
            let currentStatus = '';
            let currentOrderId = null;

            // DOM elements
            const ordersTableBody = document.getElementById('orders-table-body');
            const statusCards = document.querySelectorAll('.status-card');
            const prevPageBtn = document.getElementById('prev-page');
            const nextPageBtn = document.getElementById('next-page');
            const prevPageMobileBtn = document.getElementById('prev-page-mobile');
            const nextPageMobileBtn = document.getElementById('next-page-mobile');
            const pageNumbersContainer = document.getElementById('page-numbers');
            const paginationInfo = document.getElementById('pagination-info');

            // Modal elements
            const orderModal = document.getElementById('order-modal');
            const closeModalBtn = document.getElementById('close-modal');
            const updateStatusBtn = document.getElementById('update-status-btn');
            const modalOrderStatus = document.getElementById('modal-order-status');

            // Initialize the page
            loadStatusCounts();
            loadOrders();

            // Event listeners for status cards
            statusCards.forEach(card => {
                card.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentStatus = this.dataset.status === 'all' ? '' : this.dataset.status;
                    currentPage = 1;
                    loadOrders();
                });
            });

            // Event listeners for pagination
            prevPageBtn.addEventListener('click', goToPrevPage);
            nextPageBtn.addEventListener('click', goToNextPage);
            prevPageMobileBtn.addEventListener('click', goToPrevPage);
            nextPageMobileBtn.addEventListener('click', goToNextPage);

            // Modal event listeners
            closeModalBtn.addEventListener('click', () => orderModal.classList.add('hidden'));
            updateStatusBtn.addEventListener('click', updateOrderStatus);

            // Functions
            function loadStatusCounts() {
    fetch('../PHP/get_order_counts.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log(data.counts);
                const {
                    all,
                    pending,
                    processing,
                    delivered,
                    out_for_delivery
                } = data.counts;

                document.getElementById('all-count').textContent = all;
                document.getElementById('pending-count-card').textContent = pending;
                document.getElementById('processing-order').textContent = processing; // Changed from pending to processing
                document.getElementById('delivered-count').textContent = delivered;
                document.getElementById('out-for-delivery-count').textContent = out_for_delivery;
                document.getElementById('pending-stat-sidebar').textContent = pending;

            }
        })
        .catch(error => console.error('Error loading status counts:', error));
}

            function loadOrders() {
                const params = new URLSearchParams();
                if (currentStatus) params.append('status', currentStatus);
                params.append('page', currentPage);

                fetch(`../PHP/get_orders.php?${params.toString()}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            renderOrders(data.orders);
                            updatePagination(data.total, data.limit, data.page);
                        }
                    })
                    .catch(error => console.error('Error loading orders:', error));
            }

            function renderOrders(orders) {
                ordersTableBody.innerHTML = '';

                if (orders.length === 0) {
                    ordersTableBody.innerHTML = `
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                No orders found
                            </td>
                        </tr>
                    `;
                    return;
                }

                orders.forEach(order => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-gray-50';
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #${order.order_id}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">${order.customer_name}</div>
                            <div class="text-sm text-gray-500">${order.customer_email}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            ${new Date(order.order_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            ${order.item_count}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            $${parseFloat(order.total_amount).toFixed(2)}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs leading-5 font-semibold rounded-full 
                                ${getStatusClass(order.status)}">
                                ${formatStatus(order.status)}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="view-order-btn text-primary hover:text-primary-dark" data-id="${order.order_id}" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    `;
                    ordersTableBody.appendChild(row);
                });

                // Add event listeners to view buttons
                document.querySelectorAll('.view-order-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const orderId = this.dataset.id;
                        showOrderDetails(orderId);
                    });
                });
            }

            function getStatusClass(status) {
                switch (status) {
                    case 'pending':
                        return 'bg-yellow-100 text-yellow-800';
                    case 'processing':
                        return 'bg-purple-100 text-purple-800';
                    case 'delivered':
                        return 'bg-green-100 text-green-800';
                    case 'out_for_delivery':
                        return 'bg-orange-100 text-orange-800';
                    default:
                        return 'bg-gray-100 text-gray-800';
                }
            }

            function formatStatus(status) {
                return status.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            }

            function updatePagination(total, limit, page) {
                totalPages = Math.ceil(total / limit);
                currentPage = page;

                // Update pagination info
                const start = (page - 1) * limit + 1;
                const end = Math.min(page * limit, total);
                paginationInfo.innerHTML = `
                    Showing <span class="font-medium">${start}</span> to <span class="font-medium">${end}</span> of <span class="font-medium">${total}</span> results
                `;

                // Update page numbers
                pageNumbersContainer.innerHTML = '';
                const startPage = Math.max(1, page - 2);
                const endPage = Math.min(totalPages, page + 2);

                for (let i = startPage; i <= endPage; i++) {
                    const pageBtn = document.createElement('button');
                    pageBtn.className = `relative inline-flex items-center px-4 py-2 border text-sm font-medium ${i === page ? 'z-10 bg-primary border-primary text-white' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'}`;
                    pageBtn.textContent = i;
                    pageBtn.addEventListener('click', () => {
                        currentPage = i;
                        loadOrders();
                    });
                    pageNumbersContainer.appendChild(pageBtn);
                }

                // Enable/disable navigation buttons
                prevPageBtn.disabled = page === 1;
                nextPageBtn.disabled = page === totalPages;
                prevPageMobileBtn.disabled = page === 1;
                nextPageMobileBtn.disabled = page === totalPages;
            }

            function goToPrevPage() {
                if (currentPage > 1) {
                    currentPage--;
                    loadOrders();
                }
            }

            function goToNextPage() {
                if (currentPage < totalPages) {
                    currentPage++;
                    loadOrders();
                }
            }

            function showOrderDetails(orderId) {
                currentOrderId = orderId;

                fetch(`../PHP/get_order_details.php?id=${orderId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Populate modal with order details
                            document.getElementById('modal-order-id').textContent = data.order.order_id;
                            document.getElementById('modal-order-date').textContent = new Date(data.order.created_at).toLocaleDateString();
                            document.getElementById('modal-order-total').textContent = parseFloat(data.order.total_price).toFixed(2);
                            document.getElementById('modal-customer-name').textContent = data.order.customer_name;
                            document.getElementById('modal-customer-email').textContent = data.order.customer_email;
                            document.getElementById('modal-delivery-address').textContent = data.order.address;

                            // Set the current status in the select
                            modalOrderStatus.value = data.order.status;

                            // Populate order items
                            const itemsContainer = document.getElementById('modal-order-items');
                            itemsContainer.innerHTML = '';

                            data.items.forEach(item => {
                                const row = document.createElement('tr');
                                row.className = 'hover:bg-gray-50';
                                row.innerHTML = `
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        ${item.product_name}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        $${parseFloat(item.price).toFixed(2)}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ${item.quantity}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        $${parseFloat(item.price * item.quantity).toFixed(2)}
                                    </td>
                                `;
                                itemsContainer.appendChild(row);
                            });

                            // Show the modal
                            orderModal.classList.remove('hidden');
                        }
                    })
                    .catch(error => console.error('Error loading order details:', error));
            }

            function updateOrderStatus() {
                const newStatus = modalOrderStatus.value;

                fetch('../PHP/update_order_status.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            order_id: currentOrderId,
                            status: newStatus
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Close the modal and refresh the orders
                            orderModal.classList.add('hidden');
                            loadOrders();
                            loadStatusCounts();

                            // Show success message (you could add a toast notification here)
                            alert('Order status updated successfully!');
                        } else {
                            alert('Error updating order status: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error updating order status:', error);
                        alert('Error updating order status. Please try again.');
                    });
            }
        });
    </script>
</body>

</html>