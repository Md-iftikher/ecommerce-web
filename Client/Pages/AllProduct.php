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
</head>

<body class="bg-gray-100">
    <div id="nav-container">
        <nav class="bg-base-100 shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex-shrink-0">
                        <span class="text-xl font-bold text-gray-800">ECOM</span>
                    </div>
                    <div class="flex space-x-8 items-center">
                        <a href="/ecommerce-frontend/Client/index.php"
                            class="text-gray-800 hover:text-gray-600">Home</a>
                        <a href="/ecommerce-frontend/Client/Pages/AllProduct.php" class="text-gray-800 hover:text-gray-600">AllProducts</a>
                        <a href="#" class="text-gray-800 hover:text-gray-600">Blog</a>
                        <a href="#" class="text-red-400 hover:text-gray-600">Discounts & Offers</a>
                        <a href="#" class="text-gray-800 hover:text-gray-600">About Us</a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="dropdown dropdown-end">
                            <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                                <div class="indicator">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span class="badge badge-sm indicator-item">0</span> <!-- Cart Count -->
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
                                    <!-- removed avatar class (wasn't letting display be flex) -->
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full ">
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
                            <div class="button-container">
                                <a href="/ecommerce-frontend/Client/Pages/signup.php" class="button signup-btn">Sign Up</a>
                                <a href="/ecommerce-frontend/Client/Pages/login.php" class="button login-btn">Log in</a>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </nav>
    </div>

    <!-- Header -->
    <header class="bg-[#4a4e69] text-white text-center py-6 all-products">
        <h1 class="text-4xl font-bold">All Products</h1>
        <div class="search-bar">
        <label for="search-products">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input id="search-products" name="search-products" type="text" placeholder="Search products..">
        </label>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="bg-white shadow-md py-4 sticky top-0 z-10">
    <div id="category-nav" class="container mx-auto flex justify-between items-center flex-wrap gap-4 py-4">
    <!-- Left: Categories -->
    <div id="category-buttons" class="flex flex-wrap gap-2">
    </div>

    <!-- Right: Sort Dropdown -->
    <div class="sort-dropdown flex items-center">
        <label for="sort" class="mr-2 font-medium text-[#4a4e69]">Sort by:</label>
        <select id="sort" class="border border-[#4a4e69] text-[#4a4e69] rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-[#4a4e69]">
            <option value="default">Default</option>
            <option value="low-high">Price (Low &gt; High)</option>
            <option value="high-low">Price (High &gt; Low)</option>
        </select>
    </div>
</div>

    </nav>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <div id="product-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <!-- Product Cards will be dynamically inserted here -->
        </div>
    </main>

    <!-- footer  -->
    <div id="footer-container"></div>

    <!-- Link to External JavaScript File -->
     <script src="../js/toast.js"></script>
    <script src="../js/cart.js"></script>
    <script src="../js/AllProduct.js"></script>
</body>

</html>