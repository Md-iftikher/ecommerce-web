<?php
session_start();
include_once __DIR__ . "/../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_SESSION['customer_id'];
    $cart_id = $_POST['cart_id'];
    $address_id = $_POST['address_id'];
    $total_price = $_POST['total_price'];

    // Insert new order
    $sql_order = "INSERT INTO orders (customer_id, address_id, total_price, status) VALUES (?, ?, ?, 'pending')";
    $stmt_order = $conn->prepare($sql_order);
    $stmt_order->bind_param("iid", $customer_id, $address_id, $total_price);
    $stmt_order->execute();
    $order_id = $stmt_order->insert_id;
    $stmt_order->close();

    // Move cart items to order_items
    $sql_items = "INSERT INTO order_items (order_id, product_id, quantity, price)
                  SELECT ?, product_id, quantity, price FROM cart_items WHERE cart_id = ?";
    $stmt_items = $conn->prepare($sql_items);
    $stmt_items->bind_param("ii", $order_id, $cart_id);
    $stmt_items->execute();
    $stmt_items->close();

    // 🔹 Update product stock
    $sql_update_stock = "UPDATE products p
                     JOIN cart_items ci ON p.product_id = ci.product_id
                     SET p.quantity = GREATEST(0, p.quantity - ci.quantity)
                     WHERE ci.cart_id = ?";
    $stmt_update_stock = $conn->prepare($sql_update_stock);
    $stmt_update_stock->bind_param("i", $cart_id);
    $stmt_update_stock->execute();
    $stmt_update_stock->close();


    // Mark cart as completed
    $sql_update_cart = "UPDATE carts SET status = 'completed' WHERE cart_id = ?";
    $stmt_update_cart = $conn->prepare($sql_update_cart);
    $stmt_update_cart->bind_param("i", $cart_id);
    $stmt_update_cart->execute();
    $stmt_update_cart->close();

    echo "<script>alert('Order Placed Successfully!'); window.location.href = '../../Pages/view_cart.php';</script>";
}
