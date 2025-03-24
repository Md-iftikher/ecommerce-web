// Function to Add Product to Cart
async function addToCart(productId, price, quantity = 1) {
    try {
        console.log(`Adding to cart: Product ID: ${productId}, Quantity: ${quantity}, Price: ${price}`); 

        const response = await fetch('/ecommerce-frontend/Client/php/cart/add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ product_id: productId, quantity: quantity, price: price }),
        });

        // Log the response status for debugging
        console.log('Response Status:', response.status);

        updateCartCount(); // Update cart count in the navbar
        updateCartDropdown(); // Update cart dropdown content
    } catch (error) {
        console.error('Error:', error);
        alert(error.message);
    }
}

// Function to Remove Product from Cart
async function removeFromCart(productId) {
    try {
        const response = await fetch('../php/cart/remove_from_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}`,
        });

        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Invalid response from server');
        }

        const data = await response.json();
        if (data.error) {
            throw new Error(data.error);
        }
        alert(data.success);
        window.location.reload(); // Refresh the page to reflect changes
    } catch (error) {
        console.error('Error:', error);
        alert(error.message);
    }
}

// Function to Checkout
async function checkout() {
    try {
        const response = await fetch('../php/cart/checkout.php', {
            method: 'POST',
        });

        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Invalid response from server');
        }

        const data = await response.json();
        if (data.error) {
            throw new Error(data.error);
        }
        alert(data.success);
        window.location.href = '../index.php'; // Redirect to home page after checkout
    } catch (error) {
        console.error('Error:', error);
        alert(error.message);
    }
}

// Function to Update Cart Count in Navbar
async function updateCartCount() {
    const cartCountElement = document.querySelector('.indicator .badge');
    if (cartCountElement) {
        try {
            const response = await fetch('/ecommerce-frontend/Client/php/cart/get_cart_items.php');
            const data = await response.json();
            if (data.error) {
                throw new Error(data.error);
            }
            cartCountElement.textContent = data.length; 
        } catch (error) {
            console.error('Error:', error);
        }
    }
}

// Function to Update Cart Dropdown
async function updateCartDropdown() {
    const cartDropdown = document.querySelector('.dropdown-content .card-body');
    if (cartDropdown) {
        try {
            const response = await fetch('/ecommerce-frontend/Client/php/cart/get_cart_items.php');
            const data = await response.json();
            if (data.error) {
                throw new Error(data.error);
            }
            const totalPrice = data.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            cartDropdown.innerHTML = `
                <span class="text-lg font-bold">${data.length} Items</span>
                <span class="text-info">Subtotal: $${totalPrice.toFixed(2)}</span>
                <div class="card-actions">
                    <a href="../Pages/view_cart.php" class="btn btn-primary btn-block">View cart</a>
                </div>
            `;
        } catch (error) {
            console.error('Error:', error);
        }
    }
}

// Initialize Cart UI on Page Load
document.addEventListener('DOMContentLoaded', () => {
    updateCartCount(); // Update cart count in the navbar
    updateCartDropdown(); // Update cart dropdown content
});
