// Function to check if user is logged in
async function checkLoginStatus() {
    try {
        const response = await fetch('/ecommerce-frontend/Client/php/cart/check_login.php');
        const data = await response.json();
        return data.loggedIn || false;
    } catch (error) {
        console.error('Error checking login status:', error);
        return false;
    }
}

// Function to Add Product to Cart
async function addToCart(productId, price, quantity = 1) {
    try {
        // First check if user is logged in
        const isLoggedIn = await checkLoginStatus();
        if (!isLoggedIn) {
            showToast('Please login to add items to cart', 'error', {
                url: '/ecommerce-frontend/Client/Pages/login.php?redirect=cart',
                text: 'Login Now'
            });
            return;
        }

        //console.log(`Adding to cart: Product ID: ${productId}, Quantity: ${quantity}, Price: ${price}`); 

        const response = await fetch('/ecommerce-frontend/Client/php/cart/add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ product_id: productId, quantity: quantity, price: price }),
        });

       
        // console.log('Response Status:', response.status);

        updateCartCount(); // Update cart count in the navbar
        updateCartDropdown(); // Update cart dropdown content
        showToast('Item added to your cart.', 'success');
    } catch (error) {
        console.error('Error:', error);
        showToast('Failed to add item to cart', 'error');
    }
}

// Function to Remove Product from Cart
async function removeFromCart(productId) {
    if (!productId || productId === 'null') {
        console.error('Error: Product ID is missing');
        showToast('Error: Product ID is missing', 'error');
        return;
    }
    try {
        const response = await fetch('/ecommerce-frontend/Client/php/cart/remove_from_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ product_id: productId.trim() }),
        });

        const data = await response.json();
        if (data.error) {
            throw new Error(data.error);
        }
        showToast(data.success || 'Item removed from cart', 'success');
        updateCartCount();
        updateCartDropdown();
    } catch (error) {
        console.error('Error:', error);
        showToast(error.message || 'Failed to remove item', 'error');
    }
}

// Function to Checkout
async function checkout() {
    try {
        // First check if user is logged in
        const isLoggedIn = await checkLoginStatus();
        if (!isLoggedIn) {
            showToast('Please login to checkout', 'error');
            window.location.href = '/ecommerce-frontend/Client/Pages/login.php?redirect=checkout';
            return;
        }

        const checkoutBtn = document.getElementById('checkout-btn');
        if (checkoutBtn) {
            checkoutBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
            checkoutBtn.disabled = true;
        }

        const response = await fetch('/ecommerce-frontend/Client/php/cart/checkout.php', {
            method: 'POST',
        });

        const data = await response.json();
        if (data.error) {
            throw new Error(data.error);
        }
        
        showToast('Order placed successfully!', 'success');
        
        if (data.order_id) {
            window.location.href = `/ecommerce-frontend/Client/Pages/order_confirmation.php?order_id=${data.order_id}`;
        } else {
            window.location.href = '../index.php';
        }
    } catch (error) {
        console.error('Error:', error);
        showToast(error.message || 'Checkout failed', 'error');
        const checkoutBtn = document.getElementById('checkout-btn');
        if (checkoutBtn) {
            checkoutBtn.innerHTML = 'Proceed to Checkout';
            checkoutBtn.disabled = false;
        }
    }
}


// Function to Update Cart Count in Navbar
async function updateCartCount() {
    const cartCountElement = document.querySelector(".indicator .badge");
    if (cartCountElement) {
      try {
        const response = await fetch(
          "/ecommerce-frontend/Client/php/cart/get_cart_items.php"
        );
        const data = await response.json();
        if (data.error) {
          throw new Error(data.error);
        }
        const totalQuantity = data.reduce((sum, item) => sum + item.quantity, 0);
        cartCountElement.textContent = totalQuantity;
      } catch (error) {
        console.error("Error:", error);
      }
    }
  }
  
  // Function to Update Cart Dropdown
  async function updateCartDropdown() {
    const cartDropdown = document.querySelector(".dropdown-content .card-body");
    if (cartDropdown) {
      try {
        const response = await fetch(
          "/ecommerce-frontend/Client/php/cart/get_cart_items.php"
        );
        const data = await response.json();
  
        if (data.error) {
          throw new Error(data.error);
        }
  
        // Calculating total quantity and price
        const totalQuantity = data.reduce((sum, item) => sum + item.quantity, 0);
        const totalPrice = data.reduce(
          (sum, item) => sum + item.price * item.quantity,
          0
        );
  
        // Updating dropdown content
        cartDropdown.innerHTML = `
                  <span class="text-lg font-bold">${totalQuantity} ${
          totalQuantity === 1 ? "Item" : "Items"
        }</span>
                  <span class="text-info">Subtotal: $${totalPrice.toFixed(
                    2
                  )}</span>
                  <div class="card-actions">
                      <a href="/ecommerce-frontend/Client/Pages/view_cart.php" class="btn btn-primary btn-block">
                          View Cart
                      </a>
                  </div>
              `;
      } catch (error) {
        console.error("Error updating cart dropdown:", error);
        // Fallback content if there's an error
        cartDropdown.innerHTML = `
                  <span class="text-lg font-bold">0 Items</span>
                  <span class="text-info">Subtotal: $0.00</span>
                  <div class="card-actions">
                      <a href="/ecommerce-frontend/Client/Pages/view_cart.php" class="btn btn-primary btn-block">
                          View Cart
                      </a>
                  </div>
              `;
      }
    }
  }
  
  // Function to show toast notifications
// function showToast(message, type = 'info', action = null) {
//     const toast = document.createElement('div');
//     toast.className = `fixed top-4 right-4 px-6 py-3 rounded-md shadow-lg text-white flex items-center ${
//         type === 'error' ? 'bg-red-500' : 
//         type === 'success' ? 'bg-green-500' : 
//         'bg-blue-500'
//     }`;
    
//     toast.innerHTML = `
//         <span>${message}</span>
//         ${action ? `<a href="${action.url}" class="ml-3 font-bold underline">${action.text}</a>` : ''}
//     `;
    
//     document.body.appendChild(toast);
    
//     setTimeout(() => {
//         toast.classList.add('opacity-0', 'transition-opacity', 'duration-300');
//         setTimeout(() => toast.remove(), 300);
//     }, 3000);
// }
// Initialize Cart UI on Page Load
document.addEventListener('DOMContentLoaded', () => {
    updateCartCount(); // Update cart count in the navbar
    updateCartDropdown(); // Update cart dropdown content
});