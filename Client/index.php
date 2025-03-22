<?php
session_start();
$login_flag = false;
if(isset($_SESSION['customer_id'])){
    $login_flag = true;
}

if(isset($_GET['signed_up'])) {
  echo '<script>window.onload = function() { alert("Account created successfully"); }</script>';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="./Styles/style.css" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5.0.0-beta.8/daisyui.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>

    <!-- Navbar Container -->
    <div id="nav-container"></div>
    
    <main>
        <!-- Slider Section -->
        <section>
            <div class="relative">
                <div id="banner-slider" class="h-[300px] sm:h-[400px] md:h-[500px] lg:h-[600px] overflow-hidden">
                    <!-- Slider content goes here -->
                </div>
                <div class="absolute top-1/2 transform -translate-y-1/2 w-full flex justify-between px-4">
                    <button id="prev-slide" class="bg-gray-800 text-white rounded-full p-2">&lt;</button>
                    <button id="next-slide" class="bg-gray-800 text-white rounded-full p-2">&gt;</button>
                </div>
            </div>
        </section>

        <!-- Best Selling Products Section -->
        <section class="container mx-auto px-4 py-8">
            <h2 class="text-3xl font-bold text-center mb-8">Best Selling Products</h2>
            <div id="best-selling-products" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <!-- Product cards will be dynamically inserted here -->
            </div>
        </section>
    </main>

    <!-- Footer Container -->
    <div id="footer-container"></div>

    <!-- JavaScript File -->
    <script src="./js/home.js"></script>
</body>

</html>