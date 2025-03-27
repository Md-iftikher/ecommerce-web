<?php
session_start();
include_once __DIR__ . "/../php/config.php";

$login_flag = isset($_SESSION['customer_id']);

// Get product ID from URL
$product_id = $_GET['id'] ?? null;

if (!$product_id) {
    header('Location: AllProduct.php');
    exit;
}
// Fetch product details with category name
$sql = "SELECT p.*, c.category_name 
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.category_id
        WHERE p.product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: AllProduct.php');
    exit;
}

$product = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['product_name']) ?> | ECOM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5.0.0-beta.8/daisyui.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="../Styles/product_details.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <!-- Navbar Container -->
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
                                    <span class="badge badge-sm indicator-item" id="cart-count">0</span> <!-- Dynamic Cart Count -->
                                </div>
                            </div>
                            <div tabindex="0"
                                class="card card-compact dropdown-content bg-base-100 z-1 mt-3 w-52 shadow">
                                <div class="card-body" id="cart-dropdown-content">
                                    <!-- Content will be dynamically updated by cart.js -->
                                    <span class="text-lg font-bold">0 Items</span>
                                    <span class="text-info">Subtotal: $0.00</span>
                                    <div class="card-actions">
                                        <a href="/ecommerce-frontend/Client/Pages/view_cart.php" class="btn btn-primary btn-block">View</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if ($login_flag): ?>
                        <div class="dropdown dropdown-end">
                            <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full">
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
                        <?php else: ?>
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


    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Product Details -->
            <div class="md:flex">
                <!-- Product Image -->
                <div class="md:w-1/2 p-6 flex items-center justify-center bg-white">
                    <img src="<?= htmlspecialchars($product['image_url'] ?? '../assets/default-product.png') ?>"
                        alt="<?= htmlspecialchars($product['product_name']) ?>"
                        class="product-image w-full rounded-lg shadow-md">
                </div>

                <!-- Product Info -->
                <div class="md:w-1/2 p-6">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2"><?= htmlspecialchars($product['product_name']) ?></h1>

                    <?php if (!empty($product['category_name'])): ?>
                        <div class="mb-2">
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                <?= htmlspecialchars($product['category_name']) ?>
                            </span>
                        </div>
                    <?php endif; ?>

                    <div class="text-2xl font-bold text-blue-600 mb-4">
                        $<?= number_format($product['price'], 2) ?>
                    </div>

                    <!-- Stock Status -->
                    <div class="mb-4">
                        <span class="<?= $product['quantity'] > 0 ? 'text-green-600' : 'text-red-600' ?> font-medium">
                            <i class="fas <?= $product['quantity'] > 0 ? 'fa-check-circle' : 'fa-times-circle' ?> mr-1"></i>
                            <?= $product['quantity'] > 0 ? 'In Stock' : 'Out of Stock' ?>
                        </span>
                        <?php if ($product['quantity'] > 0): ?>
                            <span class="text-gray-600 text-sm ml-2">(<?= $product['quantity'] ?> available)</span>
                        <?php endif; ?>
                    </div>

                    <!-- Description -->
                    <div class="mb-6 border-b pb-4">
                        <h3 class="text-lg font-semibold mb-2">Description</h3>
                        <p class="text-gray-700 whitespace-pre-line"><?= htmlspecialchars($product['description'] ?? 'No description available') ?></p>
                    </div>

                    <!-- Add to Cart Section -->
                    <div class="pt-4">
                        <?php if ($product['quantity'] > 0): ?>
                            <div class="flex items-center mb-4">
                                <label for="quantity" class="mr-3 font-medium">Quantity:</label>
                                <div class="flex items-center border rounded-md">
                                    <button type="button" class="quantity-btn decrease px-3 py-2 bg-gray-100 hover:bg-gray-200">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" id="quantity" value="1" min="1" max="<?= $product['quantity'] ?>"
                                        class="w-16 text-center border-0 focus:ring-0">
                                    <button type="button" class="quantity-btn increase px-3 py-2 bg-gray-100 hover:bg-gray-200">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <button id="add-to-cart-btn"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-md font-medium transition duration-300"
                                data-product-id="<?= $product['product_id'] ?>"
                                data-price="<?= $product['price'] ?>">
                                <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
                            </button>
                        <?php else: ?>
                            <button class="w-full bg-gray-400 text-white py-3 px-4 rounded-md font-medium cursor-not-allowed">
                                <i class="fas fa-times-circle mr-2"></i> Out of Stock
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer will be loaded here -->
    <div id="footer-container"></div>

    <!-- JavaScript -->
    <script src="../js/toast.js"></script>
    <script src="../js/ProductDetals.js"></script>
    <script src="../js/cart.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Quantity controls
            const quantityInput = document.getElementById('quantity');
            if (quantityInput) {
                document.querySelectorAll('.quantity-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        let qty = parseInt(quantityInput.value);
                        if (this.classList.contains('decrease')) {
                            qty = Math.max(1, qty - 1);
                        } else {
                            qty = Math.min(<?= $product['quantity'] ?>, qty + 1);
                        }
                        quantityInput.value = qty;
                    });
                });

                quantityInput.addEventListener('change', function() {
                    let qty = parseInt(this.value);
                    if (isNaN(qty) || qty < 1) {
                        this.value = 1;
                    } else if (qty > <?= $product['quantity'] ?>) {
                        this.value = <?= $product['quantity'] ?>;
                    }
                });
            }

            // Add to cart button
            const addToCartBtn = document.getElementById('add-to-cart-btn');
            if (addToCartBtn) {
                addToCartBtn.addEventListener('click', async function() {
                    const productId = this.dataset.productId;
                    const price = this.dataset.price;
                    const quantity = parseInt(document.getElementById('quantity').value);

                    // Show loading state
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Adding...';
                    this.disabled = true;

                    try {
                        // Use the addToCart function from cart.js
                        await addToCart(productId, price, quantity);

                        // Update cart count in navbar
                        if (typeof updateCartCount === 'function') {
                            updateCartCount();
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showToast(error.message || 'Failed to add to cart', 'error');
                    } finally {
                        // Restore button state
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }
                });
            }
        });
        document.addEventListener('DOMContentLoaded', () => {
            updateCartCount(); 
            updateCartDropdown();
        });
    </script>
</body>

</html>