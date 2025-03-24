// Function to Add Product to Cart
async function addToCart(productId, price) {
    try {
        const response = await fetch('../php/cart/add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}&quantity=1&price=${price}`,
        });
        const data = await response.json();
        if (data.error) {
            throw new Error(data.error);
        }
        alert(data.success);
        updateCartCount(); // Update cart count in the navbar
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
        const data = await response.json();
        if (data.error) {
            throw new Error(data.error);
        }
        alert(data.success);
        window.location.reload(); // Refresh the page
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
        const data = await response.json();
        if (data.error) {
            throw new Error(data.error);
        }
        alert(data.success);
        window.location.href = '../index.php'; // Redirect to home page
    } catch (error) {
        console.error('Error:', error);
        alert(error.message);
    }
}

// Function to Update Cart Count in Navbar
function updateCartCount() {
    const cartCountElement = document.querySelector('.indicator .badge');
    if (cartCountElement) {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        cartCountElement.textContent = cart.length;
    }
}

// Function to Update Cart Dropdown
function updateCartDropdown() {
    const cartDropdown = document.querySelector('.dropdown-content .card-body');
    if (cartDropdown) {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        cartDropdown.innerHTML = `
            <span class="text-lg font-bold">${cart.length} Items</span>
            <span class="text-info">Subtotal: $${cart.reduce((sum, item) => sum + item.price, 0).toFixed(2)}</span>
            <div class="card-actions">
                <button class="btn btn-primary btn-block">View cart</button>
            </div>
        `;
    }
}