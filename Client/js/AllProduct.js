
const products = [
    {
        id: 1,
        name: "Wireless Headphones",
        description: "Noise-cancelling over-ear headphones with Bluetooth 5.0.",
        price: 199.99,
        stock: 50,
        image: "../assets/Images/headphone.png",
        category: "Audio"
    },
    {
        id: 2,
        name: "Smartwatch",
        description: "Fitness tracker with heart rate monitor and GPS.",
        price: 149.99,
        stock: 30,
        image: "../assets/Images/SmartWatch.png",
        category: "Wearables"
    },
    {
        id: 3,
        name: "Gaming Keyboard",
        description: "Mechanical RGB gaming keyboard with customizable keys.",
        price: 89.99,
        stock: 20,
        image: "../assets/Images/headphone.png",
        category: "Accessories"
    },
    {
        id: 4,
        name: "Bluetooth Speaker",
        description: "Portable waterproof speaker with 20-hour battery life.",
        price: 59.99,
        stock: 40,
        image: "../assets/Images/headphone.png",
        category: "Audio"
    },
    {
        id: 5,
        name: "4K Monitor",
        description: "27-inch 4K UHD monitor with HDR support.",
        price: 399.99,
        stock: 15,
        image: "../assets/Images/headphone.png",
        category: "Displays"
    }
];

// function to -load-component 
async function loadComponent(url, targetId) {
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const html = await response.text();
        console.log(html);
        document.getElementById(targetId).innerHTML = html;
    } catch (error) {
        console.error('Error loading component:', error);
        document.getElementById(targetId).innerHTML = '<p>Failed to load component.</p>'; 
    }
}

// Loading navbar
loadComponent("../Component/navbar.html", 'nav-container');

// Loading footer
loadComponent("../Component/footer.html", 'footer-container')


// all categories funallity section 

const categoryNav = document.getElementById("category-nav");
const productGrid = document.getElementById("product-grid");

// Function to Load Categories Dynamically
function loadCategories() {
    const categories = [...new Set(products.map(product => product.category))];
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
    <img src="${product.image}" alt="${product.name}" class="w-full h-48 object-cover">
    <div class="p-4">
        <h2 class="text-lg font-semibold">${product.name}</h2>
        <p class="text-blue-600 font-bold">$${product.price.toFixed(2)}</p>
        <p class="text-gray-600 text-sm mt-2">${product.description}</p>
        <p class="text-sm text-gray-500 mt-2">Remaining Stock: ${product.stock}</p>
        <div class="mt-4 flex space-x-2">
            <a href="#" class="flex-1 text-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-800 transition-all">View Details</a>
            <button onclick="addToCart(${product.id})" class="flex-1 text-center bg-green-600 text-white px-4 py-2 rounded hover:bg-green-800 transition-all">Add to Cart</button>
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
        displayProducts(products.filter(product => product.category === category));
    }
    document.querySelectorAll(".category-btn").forEach(btn => btn.classList.remove("active"));
    document.querySelector(`.category-btn[onclick="filterProducts('${category}')"]`).classList.add("active");
}

// function to add product to Cart
function addToCart(id) {
    const product = products.find(product => product.id === id);
    alert(`Added to Cart: ${product.name} - $${product.price.toFixed(2)}`);
}


loadCategories();
filterProducts("all"); 

