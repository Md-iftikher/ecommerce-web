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
    <title>About Us | YourStore</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5.0.0-beta.8/daisyui.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hero-pattern {
            background-image: radial-gradient(rgba(0, 0, 0, 0.1) 1px, transparent 1px);
            background-size: 20px 20px;
        }

        .team-member:hover .member-social {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Mobile menu styles */
        @media (max-width: 768px) {
            .mobile-menu-button {
                display: block;
            }
            .desktop-nav {
                display: none;
            }
            .mobile-menu {
                display: none;
            }
            .mobile-menu.active {
                display: block;
            }
            .button-container {
                flex-direction: column;
                gap: 0.5rem;
            }
            .button-container a {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Navbar Container -->
    <div id="nav-container">
        <nav class="bg-base-100 shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <!-- Mobile menu button -->
                    <div class="flex items-center md:hidden">
                        <button type="button" id="mobile-menu-button" class="text-gray-800 hover:text-gray-600 mobile-menu-button">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="flex-shrink-0 ml-4 md:ml-0">
                        <span class="text-xl font-bold text-gray-800">ECOM</span>
                    </div>
                    
                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex space-x-4 lg:space-x-8 items-center desktop-nav">
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
            <div id="mobile-menu" class="hidden md:hidden bg-base-100 pb-4 px-4 mobile-menu">
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

    <!-- Hero Section -->
    <section class="hero-pattern bg-gradient-to-r from-blue-50 to-indigo-50 py-12 md:py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-800 mb-4">Our Story</h1>
            <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto">
                Dedicated to bringing you quality products with exceptional service since 2015.
            </p>
        </div>
    </section>

    <!-- About Content -->
    <section class="py-12 md:py-16">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row items-center gap-8 md:gap-12">
                <div class="lg:w-1/2">
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80"
                        alt="Our Team" class="rounded-xl shadow-lg w-full h-auto">
                </div>
                <div class="lg:w-1/2">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 md:mb-6">Who We Are</h2>
                    <p class="text-gray-600 mb-4">
                        Founded in 2015, YourStore began as a small family business with a passion for delivering high-quality products to our community. What started as a modest operation has grown into a trusted e-commerce platform serving customers nationwide.
                    </p>
                    <p class="text-gray-600 mb-6">
                        We carefully curate our product selection, working directly with manufacturers to ensure quality and affordability. Our team of dedicated professionals is committed to providing an exceptional shopping experience.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-white p-4 rounded-lg shadow-sm">
                            <div class="text-blue-600 text-2xl mb-2">
                                <i class="fas fa-award"></i>
                            </div>
                            <h3 class="font-bold text-gray-800">Quality Products</h3>
                            <p class="text-sm text-gray-600">Rigorously tested items</p>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-sm">
                            <div class="text-blue-600 text-2xl mb-2">
                                <i class="fas fa-headset"></i>
                            </div>
                            <h3 class="font-bold text-gray-800">24/7 Support</h3>
                            <p class="text-sm text-gray-600">Always here to help</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="py-12 md:py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-8 md:mb-12">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">Our Core Values</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Principles that guide everything we do
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                <div class="bg-white p-6 md:p-8 rounded-xl shadow-sm text-center">
                    <div class="bg-blue-100 w-14 h-14 md:w-16 md:h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-hand-holding-heart text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 text-lg md:text-xl mb-3">Customer First</h3>
                    <p class="text-gray-600 text-sm md:text-base">
                        We prioritize your satisfaction above all else, with responsive support and easy returns.
                    </p>
                </div>

                <div class="bg-white p-6 md:p-8 rounded-xl shadow-sm text-center">
                    <div class="bg-green-100 w-14 h-14 md:w-16 md:h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-leaf text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 text-lg md:text-xl mb-3">Sustainability</h3>
                    <p class="text-gray-600 text-sm md:text-base">
                        Committed to eco-friendly packaging and ethical sourcing practices.
                    </p>
                </div>

                <div class="bg-white p-6 md:p-8 rounded-xl shadow-sm text-center">
                    <div class="bg-purple-100 w-14 h-14 md:w-16 md:h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-lightbulb text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 text-lg md:text-xl mb-3">Innovation</h3>
                    <p class="text-gray-600 text-sm md:text-base">
                        Constantly improving our platform to enhance your shopping experience.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-blue-600 text-white py-12 md:py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-2xl md:text-3xl font-bold mb-4 md:mb-6">Ready to Shop With Us?</h2>
            <p class="text-lg md:text-xl text-blue-100 max-w-2xl mx-auto mb-6 md:mb-8">
                Join thousands of satisfied customers enjoying quality products and exceptional service.
            </p>
            <a href="/ecommerce-frontend/Client/Pages/AllProduct.php" class="inline-block bg-white text-blue-600 px-6 py-2 md:px-8 md:py-3 rounded-lg font-bold hover:bg-gray-100 transition duration-300">
                Browse Products
            </a>
        </div>
    </section>

    <footer class="footer footer-center lg:footer-horizontal bg-base-300 text-base-content p-10">
        <nav class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div>
                <h6 class="footer-title">Services</h6> 
                <a class="link link-hover">Branding</a>
                <a class="link link-hover">Design</a>
                <a class="link link-hover">Marketing</a>
                <a class="link link-hover">Advertisement</a>
            </div> 
            <div>
                <h6 class="footer-title">Company</h6> 
                <a class="link link-hover">About us</a>
                <a class="link link-hover">Contact</a>
                <a class="link link-hover">Jobs</a>
                <a class="link link-hover">Press kit</a>
            </div> 
            <div>
                <h6 class="footer-title">Legal</h6> 
                <a class="link link-hover">Terms of use</a>
                <a class="link link-hover">Privacy policy</a>
                <a class="link link-hover">Cookie policy</a>
            </div> 
            <div>
                <h6 class="footer-title">Newsletter</h6> 
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Enter your email address</span>
                    </label> 
                    <div class="join">
                        <input type="text" placeholder="username@site.com" class="input input-bordered join-item w-full" /> 
                        <button class="btn btn-primary join-item">Subscribe</button>
                    </div>
                </div>
            </div>
        </nav>
    </footer>
    
    <footer class="footer footer-center bg-base-300 text-base-content p-4 font-semibold">
        <aside>
            <p>Copyright © 2025 - All right reserved</p>
        </aside>
    </footer>
    
    <!-- Link to External JavaScript File -->
    <script src="../js/toast.js"></script>
    <script src="../js/cart.js"></script>
    <script src="../js/AllProduct.js"></script>
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
</body>

</html>