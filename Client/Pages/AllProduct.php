<?php
session_start();

$login_flag = false;
if (isset($_SESSION['customer_id'])) {
    $login_flag = true;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Products - eCommerce</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../Styles/AllProduct.css">
    <link rel="stylesheet" href="../Styles/navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Tailwind CSS CDN -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5.0.0-beta.8/daisyui.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        .search-bar input {
            min-width: 0;

        }

        .category-buttons {
            -webkit-overflow-scrolling: touch;
        }

        @media (max-width: 640px) {
            .product-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                /* Better grid on mobile */
            }
        }
    </style>
</head>

<body class="bg-gray-100">
    <!-- Navbar Container -->
    <div id="nav-container">
        <nav class="bg-base-100 shadow-md">
            <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <!-- Mobile menu button -->
                    <div class="flex items-center md:hidden">
                        <button type="button" id="mobile-menu-button" class="text-gray-800 hover:text-gray-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>

                    <div class="flex-shrink-0 ml-4 md:ml-0">
                        <span class="text-xl font-bold text-gray-800">ECOM</span>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex space-x-4 lg:space-x-8 items-center">
                        <a href="/ecommerce-frontend/Client/index.php" class="text-gray-800 hover:text-gray-600">Home</a>
                        <a href="/ecommerce-frontend/Client/Pages/AllProduct.php" class="text-gray-800 hover:text-gray-600">AllProducts</a>
                        <a href="#" class="text-gray-800 hover:text-gray-600">Blog</a>
                        <a href="aboutus.php" class="text-gray-800 hover:text-gray-600">About Us</a>
                    </div>

                    <div class="flex items-center space-x-2 sm:space-x-4">
                        <div class="dropdown dropdown-end">
                            <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                                <div class="indicator">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span class="badge badge-sm indicator-item">0</span>
                                </div>
                            </div>
                            <div tabindex="0"
                                class="card card-compact dropdown-content bg-base-100 z-1 mt-3 w-52 shadow">
                                <div class="card-body">
                                    <span class="text-lg font-bold">${cart.length} Items</span>
                                    <span class="text-info">Subtotal: $${cart.reduce((sum, item) => sum + item.price,
                                        0).toFixed(2)}</span>
                                    <div class="card-actions">
                                        <button class="btn btn-primary btn-block">View cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if ($login_flag): ?>
                            <div class="dropdown dropdown-end">
                                <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                                    <div class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-full">
                                        <i class="fa-solid fa-user fa-lg"></i>
                                    </div>
                                </div>
                                <ul tabindex="0"
                                    class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
                                    <li>
                                        <a class="justify-between" href="/ecommerce-frontend/Client/Pages/my_profile.php">
                                            Profile
                                        </a>
                                    </li>
                                    <li><a>Settings</a></li>
                                    <li><a href="/ecommerce-frontend/Client/php/login/logout.php">Logout</a></li>
                                </ul>
                            </div>
                        <?php else : ?>
                            <div class="button-container hidden sm:flex">
                                <a href="/ecommerce-frontend/Client/Pages/signup.php" class="button signup-btn">Sign Up</a>
                                <a href="/ecommerce-frontend/Client/Pages/login.php" class="button login-btn">Log in</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div id="mobile-menu" class="hidden md:hidden bg-base-100 pb-4 px-4">
                <div class="flex flex-col space-y-2">
                    <a href="/ecommerce-frontend/Client/index.php" class="text-gray-800 hover:text-gray-600 py-2">Home</a>
                    <a href="/ecommerce-frontend/Client/Pages/AllProduct.php" class="text-gray-800 hover:text-gray-600 py-2">AllProducts</a>
                    <a href="#" class="text-gray-800 hover:text-gray-600 py-2">Blog</a>
                    <a href="aboutus.php" class="text-gray-800 hover:text-gray-600 py-2">About Us</a>
                    <?php if (!$login_flag): ?>
                        <div class="flex flex-col space-y-2 pt-2">
                            <a href="/ecommerce-frontend/Client/Pages/signup.php" class="button signup-btn w-full text-center">Sign Up</a>
                            <a href="/ecommerce-frontend/Client/Pages/login.php" class="button login-btn w-full text-center">Log in</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </div>

    <!-- Header -->
    <header class="bg-[#4a4e69] text-white py-4 sm:py-6 all-products flex-col justify-center ">
        <div class="px-2">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center">All Products</h1>
        </div>
        <div class="w-full max-w-xl mx-auto px-4">
            <div class="flex items-center bg-white border border-gray-300 rounded-lg shadow-sm focus-within:ring-2 focus-within:ring-[#4a4e69]">
                <span class="pl-3 text-gray-500">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input
                    id="search-products"
                    name="search-products"
                    type="text"
                    placeholder="Search products..."
                    class="w-full py-2 px-3 text-sm sm:text-base focus:outline-none text-gray-900 bg-transparent rounded-r-lg">
            </div>
        </div>



    </header>

    <!-- Navigation -->
    <nav class="bg-white shadow-md py-2 sm:py-4 sticky top-0 z-10">
        <div id="category-nav" class="container mx-auto px-2 sm:px-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 py-2 sm:py-4">
            <!-- Left: Categories -->
            <div id="category-buttons" class="category-buttons flex flex-nowrap gap-2 overflow-x-auto w-full sm:w-auto pb-2 sm:pb-0 px-2 sm:px-0">
                <!-- Categories will be dynamically inserted here -->
                <button class="category-btn whitespace-nowrap px-3 py-1 rounded-full bg-gray-200 text-gray-800 text-sm">All</button>
                <button class="category-btn whitespace-nowrap px-3 py-1 rounded-full bg-gray-200 text-gray-800 text-sm">Electronics</button>
                <button class="category-btn whitespace-nowrap px-3 py-1 rounded-full bg-gray-200 text-gray-800 text-sm">Clothing</button>
                <button class="category-btn whitespace-nowrap px-3 py-1 rounded-full bg-gray-200 text-gray-800 text-sm">Home</button>
                <button class="category-btn whitespace-nowrap px-3 py-1 rounded-full bg-gray-200 text-gray-800 text-sm">Beauty</button>
            </div>

            <!-- Right: Sort Dropdown -->
            <div class="sort-dropdown flex items-center w-full sm:w-auto justify-end sm:justify-start px-2 sm:px-0">
                <label for="sort" class="mr-2 font-medium text-[#4a4e69] text-sm sm:text-base">Sort by:</label>
                <select id="sort" class="border border-[#4a4e69] text-[#4a4e69] rounded-md px-2 sm:px-3 py-1 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#4a4e69]">
                    <option value="default">Default</option>
                    <option value="name_asc">Name A–Z</option>
                    <option value="name_desc">Name Z–A</option>
                    <option value="popular">Popular</option>
                    <option value="price_asc">Price: Low to High</option>
                    <option value="price_desc">Price: High to Low</option>
                    <option value="latest">Latest</option>
                </select>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mx-auto px-2 sm:px-4 py-4 sm:py-8">
        <div id="product-grid" class="product-grid grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6 px-2 sm:px-0">

        </div>
    </main>

    <!-- Footer -->
    <div id="footer-container"></div>

    <!-- Link to External JavaScript File -->
    <script src="../js/toast.js"></script>
    <script src="../js/cart.js"></script>
    <script src="../js/AllProduct.js"></script>
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });

        // Make category buttons scrollable on mobile
        const categoryButtons = document.getElementById('category-buttons');
        if (categoryButtons) {
            categoryButtons.addEventListener('wheel', (e) => {
                if (window.innerWidth < 640) { // Only on mobile
                    e.preventDefault();
                    categoryButtons.scrollLeft += e.deltaY;
                }
            });
        }
    </script>
</body>

</html>