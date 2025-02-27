document.addEventListener('DOMContentLoaded', function() {
    const sidebarButtons = document.querySelectorAll('.sidebar-button');
    const contentArea = document.getElementById('content-area');
  
    sidebarButtons.forEach(button => {
      button.addEventListener('click', function() {
        const contentType = this.getAttribute('data-content');
        loadContent(contentType);
      });
    });
  
    function loadContent(contentType) {
      switch (contentType) {
        case 'profile':
          contentArea.innerHTML = `
            <div class="profile-form">
              <h2>Good Morning! R</h2>
              <div class="form-row">
                <label>First Name</label>
                <input type="text" value="R" readonly>
              </div>
              <div class="form-row">
                <label>Last Name</label>
                <input type="text" value="S" readonly>
              </div>
              <div class="form-row">
                <label>Email</label>
                <input type="email" value="rs@example.com" readonly>
              </div>
              <div class="form-row">
                <label>Contact Number</label>
                <input type="tel" value="123-456-7890" readonly>
              </div>
              <div class="form-row">
                <label>Birthdate</label>
                <select id="birth-day" readonly></select>
                <select id="birth-month" readonly></select>
                <select id="birth-year" readonly></select>
              </div>
              <div class="form-row">
                <label>Gender</label>
                <input type="radio" id="male" name="gender" value="male" checked readonly><label for="male">Male</label>
                <input type="radio" id="female" name="gender" value="female" readonly><label for="female">Female</label>
                <input type="radio" id="other" name="gender" value="other" readonly><label for="other">Other</label>
              </div>
              <button class="edit-button" id="edit-profile">Edit</button>
            </div>
          `;
          populateBirthdayOptions();
          addEditProfileListener();
          break;
        case 'address':
          contentArea.innerHTML = `
            <div class="profile-form">
              <h2>Delivery Address</h2>
              <div class="form-row">
                <label>Address</label>
                <textarea id="address-text" readonly>123 Main St, Dhaka, Bangladesh</textarea>
              </div>
              <button class="edit-button" id="edit-address">Edit</button>
            </div>
          `;
          addEditAddressListener();
          break;
        case 'orders':
          contentArea.innerHTML = `
            <h2>My Orders</h2>
            <div class="order-card">Order #123 - Shipped</div>
            <div class="order-card">Order #456 - Processing</div>
          `;
          break;
        default:
          contentArea.innerHTML = '<p>Select an option from the sidebar.</p>';
      }
    }
  
    function populateBirthdayOptions() {
      // ... (populateBirthdayOptions function from previous response) ...
    }
  
    function addEditProfileListener() {
      // ... (addEditProfileListener function from previous response) ...
    }
  
    function addEditAddressListener() {
      const editButton = document.getElementById('edit-address');
      const addressText = document.getElementById('address-text');
      const originalValue = addressText.value;
  
      editButton.addEventListener('click', function() {
        addressText.removeAttribute('readonly');
        editButton.textContent = 'Save';
        editButton.removeEventListener('click', arguments.callee);
        editButton.addEventListener('click', function() {
          addressText.setAttribute('readonly', true);
          editButton.textContent = 'Edit';
          editButton.removeEventListener('click', arguments.callee);
          addEditAddressListener();
        });
      });
    }
  
    // Initial content
    loadContent('profile');
  });