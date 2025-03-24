<?php
session_start();
include_once "../php/config.php";

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

// Fetch delivery addresses for customer
$sql_address = "SELECT address_id, address FROM delivery_addresses WHERE customer_id = ?";
$stmt_address = $conn->prepare($sql_address);
$stmt_address->bind_param("i", $customer_id);
$stmt_address->execute();
$result_address = $stmt_address->get_result();

$addresses = [];
while ($row = $result_address->fetch_assoc()) {
    $addresses[] = $row;
}
$stmt_address->close();

// Fetch cart items
$sql_cart = "SELECT c.cart_id, ci.product_id, p.product_name, p.price, ci.quantity, (ci.price * ci.quantity) AS subtotal
             FROM carts c
             JOIN cart_items ci ON c.cart_id = ci.cart_id
             JOIN products p ON ci.product_id = p.product_id
             WHERE c.customer_id = ? AND c.status = 'active'";

$stmt_cart = $conn->prepare($sql_cart);
$stmt_cart->bind_param("i", $customer_id);
$stmt_cart->execute();
$result_cart = $stmt_cart->get_result();

$cart_items = [];
$total_price = 0;
$cart_id = 0;

while ($row = $result_cart->fetch_assoc()) {
    $cart_items[] = $row;
    $total_price += $row['subtotal'];
    $cart_id = $row['cart_id']; 
}

$stmt_cart->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Page Header -->
            <div class="bg-blue-600 text-white px-6 py-4">
                <h1 class="text-2xl font-bold">Checkout</h1>
            </div>

            <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Summary Section -->
                <div class="lg:col-span-2">
                    <h2 class="text-xl font-semibold mb-4 pb-2 border-b border-gray-200">Order Summary</h2>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($cart_items as $item) { ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($item['product_name']) ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        $<?= number_format($item['price'], 2) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= $item['quantity'] ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        $<?= number_format($item['subtotal'], 2) ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <div class="bg-gray-50 p-4 rounded-lg w-full max-w-md">
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="font-medium">$<?= number_format($total_price, 2) ?></span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">Shipping:</span>
                                <span class="font-medium">$0.00</span>
                            </div>
                            <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-200">
                                <span>Total:</span>
                                <span>$<?= number_format($total_price, 2) ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delivery Address Section -->
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <h2 class="text-xl font-semibold mb-4 pb-2 border-b border-gray-200">Delivery Address</h2>
                        
                        <form action="../php/cart/place_order.php" method="POST">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="address_id">
                                    Select Address
                                </label>
                                <select name="address_id" required
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <?php foreach ($addresses as $address) { ?>
                                        <option value="<?= $address['address_id'] ?>"><?= htmlspecialchars($address['address']) ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="mt-2">
                                <a href="../Pages/my_profile.php?address=true" class="text-blue-600 hover:text-blue-800 text-sm flex items-center">
                                    <i class="fas fa-plus-circle mr-1"></i> Add new address
                                </a>
                            </div>

                            <input type="hidden" name="cart_id" value="<?= $cart_id ?>">
                            <input type="hidden" name="total_price" value="<?= $total_price ?>">

                            <div class="mt-6">
                                <button type="submit" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-md transition duration-300 flex items-center justify-center">
                                    <i class="fas fa-shopping-bag mr-2"></i> Place Order
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Payment Methods -->
                    <div class="mt-6 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <h2 class="text-xl font-semibold mb-4 pb-2 border-b border-gray-200">Payment Method</h2>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input id="credit-card" name="payment-method" type="radio" checked
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <label for="credit-card" class="ml-3 block text-sm font-medium text-gray-700">
                                    Credit/Debit Card
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input id="paypal" name="payment-method" type="radio"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <label for="paypal" class="ml-3 block text-sm font-medium text-gray-700">
                                    PayPal
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input id="cash-on-delivery" name="payment-method" type="radio"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <label for="cash-on-delivery" class="ml-3 block text-sm font-medium text-gray-700">
                                    Cash on Delivery
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>