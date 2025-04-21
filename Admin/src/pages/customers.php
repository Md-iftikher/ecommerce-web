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
    <title>Customer Management</title>
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
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <!-- Mobile Sidebar Toggle -->
    <button id="sidebar-toggle" class="md:hidden fixed z-50 top-4 left-4 bg-white p-2 rounded-full shadow-md">
        <i class="fas fa-bars text-gray-600"></i>
    </button>

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="md:translate-x-0 transform -translate-x-full fixed md:relative z-40 w-64 bg-white border-r border-gray-200 transition-transform duration-300 ease-in-out flex flex-col">
            <div class="flex-1 overflow-y-auto">
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
                                <a href="dashboard.php" class="flex items-center p-3 rounded-lg hover:bg-gray-100 text-gray-600">
                                    <i class="fas fa-tachometer-alt mr-3 text-gray-500"></i>
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
                                <a href="customers.php" class="flex items-center p-3 rounded-lg bg-blue-50 text-blue-600">
                                    <i class="fas fa-users mr-3 text-blue-500"></i>
                                    <span>Customers</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <!-- Logout Section - Fixed at bottom -->
            <div class="p-4 border-t border-gray-200 bg-white">
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
                        <h2 class="text-lg md:text-xl font-semibold text-gray-800">Customer Management</h2>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button id="userMenuButton" class="flex items-center space-x-2 focus:outline-none">
                                <span class="text-sm text-gray-600 hidden sm:inline"><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></span>
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['username'] ?? 'Admin') ?>&background=3b82f6&color=fff"
                                    alt="User" class="w-8 h-8 rounded-full">
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <main class="p-4 md:p-6">
                <!-- Search and Filter -->
                <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" id="customer-search" class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Search customers...">
                        </div>
                    </div>
                </div>

                <!-- Customers Table -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Email</th>
                                    <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Gender</th>
                                    <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                                    <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="customers-table-body">
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Loading customers...</td>
                                </tr>
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

    <!-- Customer Details Modal -->
    <div id="customer-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-customer-title">
                                Customer Details
                            </h3>
                            <div class="mt-4">
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <h4 class="font-medium text-gray-900">Customer Information</h4>
                                        <div class="mt-2 space-y-2 text-sm text-gray-500">
                                            <p><strong>Name:</strong> <span id="modal-customer-name"></span></p>
                                            <p><strong>Email:</strong> <span id="modal-customer-email"></span></p>
                                            <p><strong>Gender:</strong> <span id="modal-customer-gender"></span></p>
                                            <p><strong>Date of Birth:</strong> <span id="modal-customer-dob"></span></p>
                                            <p><strong>Contact No: </strong> <span id="modal-customer-contact"></span></p>
                                            <p><strong>Total Orders:</strong> <span id="modal-customer-total-orders">0</span></p>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Address Information</h4>
                                        <div class="mt-2 space-y-2 text-sm text-gray-500" id="modal-customer-addresses">
                                            <!-- Addresses will be loaded here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="close-modal" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Mobile sidebar toggle
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
                sidebar.classList.toggle('translate-x-0');
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', (e) => {
                if (window.innerWidth < 768 && 
                    !sidebar.contains(e.target) && 
                    e.target !== sidebarToggle && 
                    !sidebarToggle.contains(e.target)) {
                    sidebar.classList.add('-translate-x-full');
                    sidebar.classList.remove('translate-x-0');
                }
            });

            // Global variables
            let currentPage = 1;
            let totalPages = 1;
            let currentSearch = '';
            let searchTimeout = null;

            // DOM elements
            const customersTableBody = document.getElementById('customers-table-body');
            const searchInput = document.getElementById('customer-search');
            const prevPageBtn = document.getElementById('prev-page');
            const nextPageBtn = document.getElementById('next-page');
            const prevPageMobileBtn = document.getElementById('prev-page-mobile');
            const nextPageMobileBtn = document.getElementById('next-page-mobile');
            const pageNumbersContainer = document.getElementById('page-numbers');
            const paginationInfo = document.getElementById('pagination-info');

            // Modal elements
            const customerModal = document.getElementById('customer-modal');
            const closeModalBtn = document.getElementById('close-modal');

            // Initialize the page
            loadCustomers();
            loadStatusCounts();

            // Event listeners
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    currentSearch = this.value.trim();
                    currentPage = 1;
                    loadCustomers();
                }, 500);
            });

            closeModalBtn.addEventListener('click', () => customerModal.classList.add('hidden'));

            // Pagination event listeners
            prevPageBtn.addEventListener('click', goToPrevPage);
            nextPageBtn.addEventListener('click', goToNextPage);
            prevPageMobileBtn.addEventListener('click', goToPrevPage);
            nextPageMobileBtn.addEventListener('click', goToNextPage);

            // Functions
            function loadStatusCounts() {
                fetch('../PHP/get_order_counts.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('pending-stat-sidebar').textContent = data.counts.pending;
                        }
                    })
                    .catch(error => console.error('Error loading status counts:', error));
            }

            function loadCustomers() {
                const params = new URLSearchParams();
                params.append('page', currentPage);
                if (currentSearch) params.append('search', currentSearch);

                fetch(`../PHP/get_customers.php?${params.toString()}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            renderCustomers(data.customers);
                            updatePagination(data.total, data.limit, data.page);
                        }
                    })
                    .catch(error => console.error('Error loading customers:', error));
            }

            function renderCustomers(customers) {
                customersTableBody.innerHTML = '';

                if (customers.length === 0) {
                    customersTableBody.innerHTML = `
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                ${currentSearch ? 'No customers found matching your search' : 'No customers found'}
                            </td>
                        </tr>
                    `;
                    return;
                }

                customers.forEach(customer => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-gray-50';
                    
                    // Format gender for display
                    let genderDisplay = 'Other';
                    if (customer.gender === 'M') genderDisplay = 'Male';
                    if (customer.gender === 'F') genderDisplay = 'Female';
                    
                    row.innerHTML = `
                        <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" 
                                         src="https://ui-avatars.com/api/?name=${encodeURIComponent(customer.first_name + ' ' + (customer.last_name || ''))}&background=3b82f6&color=fff" 
                                         alt="${customer.first_name}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">${customer.first_name} ${customer.last_name || ''}</div>
                                    <div class="text-sm text-gray-500 sm:hidden">${customer.email}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden sm:table-cell">
                            ${customer.email}
                        </td>
                        <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">
                            ${genderDisplay}
                        </td>
                        <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            ${customer.total_orders || 0}
                        </td>
                        <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="view-customer-btn text-blue-600 hover:text-blue-800" data-id="${customer.customer_id}" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    `;
                    customersTableBody.appendChild(row);
                });

                // Add event listeners to view buttons
                document.querySelectorAll('.view-customer-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const customerId = this.dataset.id;
                        showCustomerDetails(customerId);
                    });
                });
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
                    pageBtn.className = `relative inline-flex items-center px-4 py-2 border text-sm font-medium ${
                        i === page ? 'z-10 bg-blue-600 border-blue-600 text-white' 
                                  : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                    }`;
                    pageBtn.textContent = i;
                    pageBtn.addEventListener('click', () => {
                        currentPage = i;
                        loadCustomers();
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
                    loadCustomers();
                }
            }

            function goToNextPage() {
                if (currentPage < totalPages) {
                    currentPage++;
                    loadCustomers();
                }
            }

            function showCustomerDetails(customerId) {
                // Get customer details
                fetch(`../PHP/get_customer_details.php?id=${customerId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const customer = data.customer;
                            
                            // Format gender for display
                            let genderDisplay = 'Other';
                            if (customer.gender === 'M') genderDisplay = 'Male';
                            if (customer.gender === 'F') genderDisplay = 'Female';
                            
                            // Format date of birth
                            const dob = customer.dob ? new Date(customer.dob).toLocaleDateString() : 'N/A';
                            
                            // Populate modal with customer details
                            document.getElementById('modal-customer-title').textContent = `${customer.first_name} ${customer.last_name || ''}'s Details`;
                            document.getElementById('modal-customer-name').textContent = `${customer.first_name} ${customer.last_name || ''}`;
                            document.getElementById('modal-customer-email').textContent = customer.email;
                            document.getElementById('modal-customer-gender').textContent = genderDisplay;
                            document.getElementById('modal-customer-dob').textContent = dob;
                            document.getElementById('modal-customer-contact').textContent = customer.contact || 'N/A';
                            document.getElementById('modal-customer-total-orders').textContent = data.total_orders || 0;

                            // Load addresses
                            const addressesContainer = document.getElementById('modal-customer-addresses');
                            addressesContainer.innerHTML = '';
                            
                            if (data.addresses && data.addresses.length > 0) {
                                data.addresses.forEach(address => {
                                    const addressDiv = document.createElement('div');
                                    addressDiv.className = 'mb-2 p-2 bg-gray-50 rounded';
                                    addressDiv.innerHTML = `<strong>Address:</strong> ${address || 'N/A'}`;
                                    addressesContainer.appendChild(addressDiv);
                                });
                            } else {
                                addressesContainer.innerHTML = '<p>No addresses found</p>';
                            }

                            // Show the modal
                            customerModal.classList.remove('hidden');
                        }
                    })
                    .catch(error => console.error('Error loading customer details:', error));
            }
        });
    </script>
</body>
</html>