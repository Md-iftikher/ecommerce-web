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
        <button class="category-btn px-4 py-2 text-gray-600 hover:text-blue-600 transition-all" onclick="filterProducts('${category}')">
            ${category.charAt(0).toUpperCase() + category.slice(1)}
        </button>
    `).join("");
}

// Function to Display Products in cards
function displayProducts(productsToShow) {
    productGrid.innerHTML = productsToShow.map(product => `
<div class="product-card bg-white shadow-md rounded-lg overflow-hidden">
    <img src="${product.image_url}" alt="${product.product_name}" class="w-full h-48 object-cover">
    <div class="p-4">
        <h2 class="text-lg font-semibold">${product.product_name}</h2>
        <p class="text-blue-600 font-bold">$${parseFloat(product.price).toFixed(2)}</p>
        <p class="text-gray-600 text-sm mt-2">${product.description}</p>
        <p class="text-sm text-gray-500 mt-2">Remaining Stock: ${product.quantity}</p>
        <div class="mt-4 flex space-x-2">
            <a href="#" class="flex-1 text-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-800 transition-all">View Details</a>
            <button onclick="addToCart(${product.product_id}, ${product.price})" class="flex-1 text-center bg-green-600 text-white px-4 py-2 rounded hover:bg-green-800 transition-all">Add to Cart</button>
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




