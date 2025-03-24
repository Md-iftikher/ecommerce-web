<?php
session_start();
include_once __DIR__ . "/../php/config.php";

if (!isset($_SESSION['customer_id'])) {
    header('Location: ../Pages/login.php');
    exit;
}

$customer_id = $_SESSION['customer_id'];

// Get active cart for the customer
$sql_cart = "SELECT c.cart_id, ci.product_id, p.product_name, p.price, ci.quantity, (ci.price * ci.quantity) AS subtotal, p.image_url
             FROM carts c
             JOIN cart_items ci ON c.cart_id = ci.cart_id
             JOIN products p ON ci.product_id = p.product_id
             WHERE c.customer_id = ? AND c.status = 'active'";

$stmt = $conn->prepare($sql_cart);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
$total_price = 0;

while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $total_price += $row['subtotal'];
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-800 mb-8">Your Shopping Cart</h2>
            
            <?php if (empty($cart_items)): ?>
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <p class="text-gray-600 text-lg">Your cart is empty</p>
                    <a href="../Pages/products.php" class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md transition duration-300">Continue Shopping</a>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="hidden md:grid grid-cols-6 bg-gray-100 p-4 border-b border-gray-200">
                        <div class="col-span-2 font-medium text-gray-700">Product</div>
                        <div class="font-medium text-gray-700 text-center">Price</div>
                        <div class="font-medium text-gray-700 text-center">Quantity</div>
                        <div class="font-medium text-gray-700 text-center">Subtotal</div>
                        <div class="font-medium text-gray-700 text-center">Action</div>
                    </div>
                    
                    <?php foreach ($cart_items as $item) { ?>
                    <div class="cart-item grid grid-cols-1 md:grid-cols-6 p-4 border-b border-gray-200 items-center" data-product-id="<?= $item['product_id'] ?>">
                        <div class="col-span-2 flex items-center mb-4 md:mb-0">
                            <?php if (!empty($item['image_url'])): ?>
                                <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>" class="w-16 h-16 object-cover rounded mr-4">
                            <?php else: ?>
                                <div class="w-16 h-16 bg-gray-200 rounded mr-4 flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            <?php endif; ?>
                            <div>
                                <h3 class="font-medium text-gray-800"><?= htmlspecialchars($item['product_name']) ?></h3>
                            </div>
                        </div>
                        <div class="text-center text-gray-700 mb-2 md:mb-0">$<?= number_format($item['price'], 2) ?></div>
                        <div class="text-center text-gray-700 mb-2 md:mb-0">
                            <div class="inline-flex items-center border border-gray-300 rounded-md">
                                <button class="quantity-btn decrease px-2 py-1 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-l-md">
                                    <i class="fas fa-minus text-xs"></i>
                                </button>
                                <span class="quantity-input px-3 py-1"><?= $item['quantity'] ?></span>
                                <button class="quantity-btn increase px-2 py-1 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-r-md">
                                    <i class="fas fa-plus text-xs"></i>
                                </button>
                            </div>
                        </div>
                        <div class="text-center text-gray-700 font-medium mb-2 md:mb-0">$<?= number_format($item['subtotal'], 2) ?></div>
                        <div class="text-center">
                            <button class="remove-item-btn text-red-500 hover:text-red-700 transition duration-200" data-product-id="<?= $item['product_id'] ?>">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                    <?php } ?>
                    
                    <div class="p-6 bg-gray-50 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <div class="text-xl font-bold text-gray-800">Total: $<span id="cart-total"><?= number_format($total_price, 2) ?></span></div>
                            <div class="space-x-4">
                                <a href="../Pages/products.php" class="inline-block px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 transition duration-300">Continue Shopping</a>
                                <a href="checkout.php" id="checkout-btn" class="inline-block px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-300">Proceed to Checkout</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
    // Function to show toast notifications
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 px-6 py-3 rounded-md shadow-lg text-white ${
            type === 'error' ? 'bg-red-500' : 
            type === 'success' ? 'bg-green-500' : 
            'bg-blue-500'
        }`;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.add('opacity-0', 'transition-opacity', 'duration-300');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Function to remove item from cart
    async function removeFromCart(productId, cartItemElement = null) {
        try {
            const response = await fetch('/ecommerce-frontend/Client/php/cart/remove_from_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ product_id: productId }),
            });

            const data = await response.json();
            
            if (data.error) {
                showToast(data.error, 'error');
                return;
            }

            showToast('Product removed from cart', 'success');
            
            // If we have a reference to the DOM element, animate removal
            if (cartItemElement) {
                cartItemElement.classList.add('opacity-0', 'transition-opacity', 'duration-300');
                setTimeout(() => {
                    cartItemElement.remove();
                    updateCartTotals();
                    // If no items left, show empty cart message
                    if (document.querySelectorAll('.cart-item').length === 0) {
                        location.reload();
                    }
                }, 300);
            } else {
                // Otherwise just reload
                location.reload();
            }
            
        } catch (error) {
            console.error('Error:', error);
            showToast('Failed to remove item', 'error');
        }
    }

    // Function to update cart totals
    function updateCartTotals() {
        let newTotal = 0;
        document.querySelectorAll('.cart-item').forEach(item => {
            const price = parseFloat(item.querySelector('.text-center.text-gray-700.font-medium').textContent.replace('$', ''));
            newTotal += price;
        });
        document.getElementById('cart-total').textContent = newTotal.toFixed(2);
    }

    // Attach event listeners
    document.addEventListener('DOMContentLoaded', () => {
        // Remove item buttons
        document.querySelectorAll('.remove-item-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const productId = this.dataset.productId;
                const cartItem = this.closest('.cart-item');
                removeFromCart(productId, cartItem);
            });
        });

        // Quantity buttons (if you want to implement these later)
        document.querySelectorAll('.quantity-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // You can implement quantity update functionality here
                // Similar to the remove functionality
            });
        });
    });
    </script>
</body>
</html>