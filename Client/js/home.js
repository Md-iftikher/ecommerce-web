


// loading slider for home page 
const slides = [
    {
      image: 'assets/Images/welcomr.jpg',
      title: 'Welcome to Our Store',
      description: 'Discover amazing products and deals.',
      link: '/Client/Pages/AllProduct.html',
      buttonText: 'Shop Now',
    },
    {
      image: 'assets/Images/newarival.png',
      title: 'New Arrivals',
      description: 'Check out our latest collection.',
      link: '#',
      buttonText: 'View More',
    },
    // Add more slides as needed
  ];
  
  const slider = document.getElementById('banner-slider');
  const prevButton = document.getElementById('prev-slide');
  const nextButton = document.getElementById('next-slide');
  let currentIndex = 0;
  
  function createSlide(slide) {
    const slideDiv = document.createElement('div');
    slideDiv.className = 'relative h-full w-full flex-shrink-0';
    slideDiv.innerHTML = `
      <div class="absolute inset-0 bg-black/50 z-10"></div>
      <img src="${slide.image}" alt="${slide.title}" class="w-full h-full object-cover">
      <div class="absolute inset-0 z-20 flex items-center justify-center">
        <div class="text-center text-white max-w-3xl px-4">
          <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-4">${slide.title}</h1>
          <p class="text-base sm:text-lg md:text-xl mb-6 sm:mb-8">${slide.description}</p>
          <a href="${slide.link}">
            <button class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg flex items-center gap-2 mx-auto transition-all border-none transform hover:scale-105 px-4 py-2 sm:px-6 sm:py-3 text-sm sm:text-lg font-semibold shadow-lg hover:shadow-xl hover:bg-gradient-to-r hover:from-blue-700 hover:to-blue-900 active:scale-95">${slide.buttonText}</button>
          </a>
        </div>
      </div>
    `;
    return slideDiv;
  }
  
  function updateSlider() {
    slider.innerHTML = '';
    const currentSlide = createSlide(slides[currentIndex]);
    slider.appendChild(currentSlide);
  }
  
  function nextSlide() {
    currentIndex = (currentIndex + 1) % slides.length;
    updateSlider();
  }
  
  function prevSlide() {
    currentIndex = (currentIndex - 1 + slides.length) % slides.length;
    updateSlider();
  }
  updateSlider();
 
  nextButton.addEventListener('click', nextSlide);
  prevButton.addEventListener('click', prevSlide);
  setInterval(nextSlide, 3000);


  

// Global Cart State
let cart = JSON.parse(localStorage.getItem('cart')) || [];

// Function to Load Components (Navbar and Footer)
async function loadComponent(url, targetId) {
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const html = await response.text();
        document.getElementById(targetId).innerHTML = html;
        if (targetId === 'nav-container') {
            updateCartCount(); // Update cart count after loading navbar
            updateCartDropdown(); // Update cart dropdown after loading navbar
        }
    } catch (error) {
        console.error('Error loading component:', error);
        document.getElementById(targetId).innerHTML = '<p>Failed to load component.</p>';
    }
}

// Load Navbar and Footer
// loadComponent("Component/navbar.html", 'nav-container');
loadComponent('Component/footer.html', 'footer-container');

// Function to Update Cart Count in Navbar
function updateCartCount() {
    const cartCountElement = document.querySelector('.indicator .badge');
    if (cartCountElement) {
        cartCountElement.textContent = cart.length;
    }
}

// Function to Update Cart Dropdown
function updateCartDropdown() {
    const cartDropdown = document.querySelector('.dropdown-content .card-body');
    if (cartDropdown) {
        cartDropdown.innerHTML = `
            <span class="text-lg font-bold">${cart.length} Items</span>
            <span class="text-info">Subtotal: $${cart.reduce((sum, item) => sum + item.price, 0).toFixed(2)}</span>
            <div class="card-actions">
                <button class="btn btn-primary btn-block">View cart</button>
            </div>
        `;
    }
}

// Function to Add Product to Cart
function addToCart(id) {
    const product = bestSellingProducts.find(product => product.id === id);
    if (!cart.some(item => item.id === id)) {
        cart.push(product);
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCount(); // Update cart count in navbar
        updateCartDropdown(); // Update cart dropdown content
        alert(`Added to Cart: ${product.name} - $${product.price.toFixed(2)}`);
    } else {
        alert(`${product.name} is already in your cart.`);
    }
}

// Sample Best Selling Products Data
const bestSellingProducts = [
    {
        id: 1,
        name: "Wireless Headphones",
        price: 199.99,
        image: "https://via.placeholder.com/300",
        description: "Noise-cancelling over-ear headphones with Bluetooth 5.0."
    },
    {
        id: 2,
        name: "Smartwatch",
        price: 149.99,
        image: "https://via.placeholder.com/300",
        description: "Fitness tracker with heart rate monitor and GPS."
    },
    {
        id: 3,
        name: "Gaming Keyboard",
        price: 89.99,
        image: "https://via.placeholder.com/300",
        description: "Mechanical RGB gaming keyboard with customizable keys."
    },
    {
        id: 4,
        name: "Bluetooth Speaker",
        price: 59.99,
        image: "https://via.placeholder.com/300",
        description: "Portable waterproof speaker with 20-hour battery life."
    },
    {
        id: 5,
        name: "4K Monitor",
        price: 399.99,
        image: "https://via.placeholder.com/300",
        description: "27-inch 4K UHD monitor with HDR support."
    },
    {
        id: 6,
        name: "Wireless Earbuds",
        price: 129.99,
        image: "https://via.placeholder.com/300",
        description: "True wireless earbuds with 24-hour playtime."
    },
    {
        id: 7,
        name: "Laptop Backpack",
        price: 49.99,
        image: "https://via.placeholder.com/300",
        description: "Durable and stylish backpack for laptops up to 15.6 inches."
    },
    {
        id: 8,
        name: "Desk Lamp",
        price: 29.99,
        image: "https://via.placeholder.com/300",
        description: "Adjustable LED desk lamp with touch control."
    }
];

// Function to Display Best Selling Products
function displayBestSellingProducts() {
    const productGrid = document.getElementById("best-selling-products");
    productGrid.innerHTML = bestSellingProducts.map(product => `
        <div class="product-card bg-white shadow-md rounded-lg overflow-hidden">
            <img src="${product.image}" alt="${product.name}" class="w-full h-48 object-cover">
            <div class="p-4">
                <h2 class="text-lg font-semibold">${product.name}</h2>
                <p class="text-blue-600 font-bold">$${product.price.toFixed(2)}</p>
                <p class="text-gray-600 text-sm mt-2">${product.description}</p>
                <button onclick="addToCart(${product.id})" class="mt-4 w-full bg-green-600 text-white px-4 py-2 rounded hover:bg-green-800 transition-all">Buy Now</button>
            </div>
        </div>
    `).join("");
}

// Load Best Selling Products on Page Load
displayBestSellingProducts();