let products = [];

async function fetchProducts() {
    try {
        const response = await fetch('../php/products/retrieve_products.php');
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await response.json();
        return data;
    } catch (error) {
        console.error('There was a problem with the fetch operation:', error);
        return [];
    }
}

window.addEventListener('load', async function() {
    products = await fetchProducts();
    loadCategories();
    filterProducts("all"); 

});

async function loadComponent(url, targetId) {
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const html = await response.text();
        
        document.getElementById(targetId).innerHTML = html;
    } catch (error) {
        console.error('Error loading component:', error);
        document.getElementById(targetId).innerHTML = '<p>Failed to load component.</p>'; 
    }
}



// Loading footer
loadComponent("../Component/footer.html", 'footer-container')


// all categories funallity section 

const categoryNav = document.getElementById("category-nav");
const productGrid = document.getElementById("product-grid");

// Function to Load Categories Dynamically
function loadCategories() {
    const categories = [...new Set(products.map(product => product.category_name))];
    categories.unshift("all");
    categoryNav.innerHTML = categories.map(category => `
        <button class="category-btn px-4 py-2 text-[#6c728d] hover:text-[#343750] transition-all" onclick="filterProducts('${category}')">
            ${category.charAt(0).toUpperCase() + category.slice(1)}
        </button>
    `).join("");
}

function displayProducts(productsToShow) {
    productGrid.innerHTML = productsToShow.map(product => `
    <div class="product-card bg-white shadow-lg h-[400px] rounded-xl overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1 group">
        <!-- Product Image with Hover Overlay -->
        <div class="relative overflow-hidden h-48">
            <img src="${product.image_url || '../assets/default-product.png'}" 
                 alt="${product.product_name}" 
                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
            
            <!-- Stock Badge -->
            <div class="absolute top-2 right-2 px-2 py-1 rounded-full text-xs font-bold 
                       ${product.quantity > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                ${product.quantity > 0 ? `${product.quantity} in stock` : 'Out of stock'}
            </div>
            
            <!-- Quick View Overlay -->
            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                <a href="/ecommerce-frontend/Client/Pages/product_details.php?id=${product.product_id}" 
                   class="bg-white text-gray-800 px-4 py-2 rounded-full font-medium shadow-md hover:bg-gray-100 transition-all">
                    Quick View
                </a>
            </div>
        </div>
        
        <!-- Product Info -->
        <div class="p-4">
            <!-- Product Name and Price -->
            <div class="flex justify-between items-start mb-2">
                <h2 class="text-lg font-semibold text-gray-800 line-clamp-1">${product.product_name}</h2>
                <p class="text-blue-600 font-bold">$${parseFloat(product.price).toFixed(2)}</p>
            </div>
            
            <!-- Description -->
            <p class="text-gray-600 text-sm line-clamp-2 mb-3">${product.description || 'No description available'}</p>
            
            <!-- Rating Stars (Placeholder) -->
            <div class="flex items-center mb-4">
                <div class="flex text-yellow-400 text-sm">
                    ${'<i class="fas fa-star"></i>'.repeat(5)}
                </div>
                <span class="text-gray-500 text-xs ml-1">(24)</span>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex gap-2">
               
                <button onclick="addToCart(${product.product_id}, ${product.price})" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md transition-colors flex items-center justify-center"
                        ${product.quantity <= 0 ? 'disabled class="bg-gray-400 cursor-not-allowed"' : ''}>
                    <i class="fas fa-shopping-cart mr-2"></i> Add
                </button>

                 <a href="/ecommerce-frontend/Client/Pages/product_details.php?id=${product.product_id}" 
                   class="flex-1 text-center bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-md transition-colors flex items-center justify-center">
                    <i class="fas fa-eye mr-2"></i> Details
                </a>
            </div>
        </div>
    </div>
    `).join("");
}

// to load category dunamically
function filterProducts(category) {
    if (category === "all") {
        displayProducts(products);
    } else {
        displayProducts(products.filter(product => product.category_name === category));
    }
    document.querySelectorAll(".category-btn").forEach(btn => btn.classList.remove("active"));
    document.querySelector(`.category-btn[onclick="filterProducts('${category}')"]`).classList.add("active");
}




