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
  
  // Load and Footer
  loadComponent('../Component/footer.html', 'footer-container');