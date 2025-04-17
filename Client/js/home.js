

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
        document.getElementById(targetId).innerHTML = '<p>Failed to load component.</p>'; // Optional: Display error message
    }
}

// Loading navbar
loadComponent("Component/navbar.html", 'nav-container');

// Loading footer
loadComponent('Component/footer.html', 'footer-container');


// loading slider for home page
const slides = [
    {
      image: 'assets/Images/welcomr.jpg',
      title: 'Welcome to Our Store',
      description: 'Discover amazing products and deals.',
      link: '#',
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



