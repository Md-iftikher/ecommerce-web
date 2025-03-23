


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
    const product = recentProducts.find(product => product.product_id === id);
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



let recentProducts = [];

async function fetchProducts() {
    try {
        const response = await fetch('/ecommerce-frontend/Client/php/products/retrieve_recent_products.php');
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
    recentProducts = await fetchProducts();
    displayRecentProducts();
    
});


// Function to Display the Recently Added Products
function displayRecentProducts() {
    const productGrid = document.getElementById("recently-added-products");
    productGrid.innerHTML = recentProducts.map(product => `
        <div class="product-card bg-white shadow-md rounded-lg overflow-hidden">
            <img src="${product.image_url}" alt="${product.product_name}" class="w-full h-48 object-cover">
            <div class="p-4">
                <h2 class="text-lg font-semibold">${product.product_name}</h2>
                <p class="text-blue-600 font-bold">$${parseFloat(product.price).toFixed(2)}</p>
                <p class="text-gray-600 text-sm mt-2">${product.description}</p>
                <button onclick="addToCart(${product.product_id})" class="mt-4 w-full bg-green-600 text-white px-4 py-2 rounded hover:bg-green-800 transition-all">Buy Now</button>
            </div>
        </div>
    `).join("");
}


