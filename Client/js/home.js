// Slider for home page
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
loadComponent('Component/footer.html', 'footer-container');

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
      <div class="product-card bg-white shadow-md rounded-lg overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
          <!-- Product Image with hover effect -->
          <div class="relative overflow-hidden h-48">
              <img src="${product.image_url}" alt="${product.product_name}" 
                   class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">

          </div>
          
          <!-- Product Info -->
          <div class="p-4">
              <h2 class="text-lg font-semibold text-gray-800 line-clamp-1">${product.product_name}</h2>
              
              <!-- Price and Rating -->
              <div class="flex items-center justify-between mt-2">
                  <p class="text-blue-600 font-bold text-lg">$${parseFloat(product.price).toFixed(2)}</p>
                  <div class="flex items-center">
                      <i class="fas fa-star text-yellow-400 mr-1"></i>
                      <span class="text-gray-600 text-sm">4.5</span>
                  </div>
              </div>
              
              <!-- Short Description -->
              <p class="text-gray-600 text-sm mt-2 line-clamp-2">${product.description}</p>
              
              <!-- Action Buttons -->
              <div class="flex gap-2 mt-4">
                  <!-- Add to Cart Button -->
                  <button onclick="addToCart(${product.product_id}, ${product.price})" 
                          class="flex-1 bg-blue-600 hover:bg-blue-900 text-white text-[15px] px-3 py-2 rounded-md transition-colors flex items-center justify-center">
                      <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
                  </button>
                  
                  <!-- View Details Button -->
                  <a href="/ecommerce-frontend/Client/Pages/product_details.php?id=${product.product_id}"
                     class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-2 rounded-md text-center transition-colors flex items-center justify-center">
                      <i class="fas fa-info-circle mr-2"></i> Details
                  </a>
              </div>
          </div>
      </div>
  `).join("");

  // Add event listener for quick view buttons
  document.querySelectorAll('.quick-view-btn').forEach(button => {
      button.addEventListener('click', (e) => {
          e.stopPropagation();
          // Implement quick view functionality here
          console.log("Quick view clicked");
      });
  });
}
