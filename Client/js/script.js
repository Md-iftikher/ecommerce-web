document.addEventListener("DOMContentLoaded", function () {
    function updateClock() {
        const now = new Date();
        const hours = now.getHours().toString().padStart(2, "0");
        const minutes = now.getMinutes().toString().padStart(2, "0");
        const seconds = now.getSeconds().toString().padStart(2, "0");
        const timeString = `${hours}:${minutes}:${seconds}`;

        document.getElementById("clock").textContent = timeString;
    }

    setInterval(updateClock, 1000); // Update every 1000 milliseconds (1 second)
    updateClock(); // Initialize the clock immediately

    const displayBox = document.getElementById("display-box");
    const profileLink = document.getElementById("profile-link");
    const addressLink = document.getElementById("address-link");
    const ordersLink = document.getElementById("orders-link");

    // Helper function to clear active states
    function clearActive() {
        profileLink.classList.remove("active");
        addressLink.classList.remove("active");
        ordersLink.classList.remove("active");
    }

    // Load My Profile content
    function loadProfile() {
        clearActive();
        profileLink.classList.add("active");

        // Update greeting text if desired
        const greeting = document.getElementById("greeting-text");
        if (greeting) greeting.textContent = "Good Morning! R";

        displayBox.innerHTML = `
      <div class="profile-form-container">
        <div class="profile-form-header">
          <h2><i class="fa-solid fa-user"></i>My Profile</h2>
          <i class="fa-solid fa-pen-to-square" id="edit-icon"></i>
        </div>

        <!-- Row 1: First Name / Last Name -->
        <div class="profile-form-row">
          <div class="form-group">
            <label for="firstName">First Name</label>
            <input type="text" id="firstName" placeholder="R" disabled />
          </div>
          <div class="form-group">
            <label for="lastName">Last Name</label>
            <input type="text" id="lastName" placeholder="S" disabled />
          </div>
        </div>

        <!-- Row 2: Email / Contact Number -->
        <div class="profile-form-row">
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" placeholder="Email" disabled />
          </div>
          <div class="form-group">
            <label for="contactNumber">Contact Number</label>
            <input type="text" id="contactNumber" placeholder="Contact Number" disabled />
          </div>
        </div>

        <!-- Row 3: Birthdate / Gender -->
        <div class="profile-form-row">
          <div class="form-group birthdate-group">
            <label>Birthdate</label>
            <div class="birthdate-fields">
              <input type="text" placeholder="DD" disabled />
              <input type="text" placeholder="MM" disabled />
              <input type="text" placeholder="YYYY" disabled />
            </div>
          </div>
          <div class="form-group gender-group">
            <label>Gender</label>
            <div class="gender-options">
              <label><input type="radio" name="gender" value="male" disabled /> Male</label>
              <label><input type="radio" name="gender" value="female" disabled /> Female</label>
              <label><input type="radio" name="gender" value="other" disabled /> Other</label>
            </div>
          </div>
        </div>
        <div class="form-group">
            <span id="saveBtn" class="button" disabled>Save</span>
        </div>
        
      </div>
    `;


    }

    // Load Delivery Address content
    function loadAddress() {
        clearActive();
        addressLink.classList.add("active");
        displayBox.innerHTML = `
      <div class="profile-form-container">
        <div class="profile-form-header">
            <h2><i class="fa-solid fa-location-dot"></i>Delivery Address</h2>
            <i class="fa-solid fa-pen-to-square" id="edit-icon-address"></i>
        </div>
        <div class="profile-form-row">
            <div class="form-group">
                <label for="delivery-address">Delivery Address</label>
                <input type="text" id="delivery-address" placeholder="Enter your address" disabled />
            </div>
        </div>

         <div class="form-group">
            <span id="saveBtn-address" class="button" disabled>Save</span>
        </div>
        </div>
    `;
    const editIconAddress = document.getElementById("edit-icon-address");
    const saveBtnAddress = document.getElementById("saveBtn-address");
    const deliveryAddressInput = document.getElementById("delivery-address");


    editIconAddress.addEventListener('click', function () {
        deliveryAddressInput.disabled = false;
        saveBtnAddress.disabled = false;
        saveBtnAddress.style.visibility = 'visible';
    });


    saveBtnAddress.addEventListener('click', function () {
        const updatedAddress = deliveryAddressInput.value;

        deliveryAddressInput.disabled = true;
        saveBtnAddress.disabled = true;
        saveBtnAddress.style.visibility = "hidden";

    });
    }


    function loadOrders() {
        clearActive();
        ordersLink.classList.add("active");
        displayBox.innerHTML = `
        <div class="profile-form-container">
            <div class="profile-form-header">
                <h2><i class="fa-solid fa-basket-shopping"></i>My Orders</h2>
            </div>

             <div class="profile-form-row">
                 <ul class="orders-list">
                    <li>
                        <div class="order-item">
                            <span class="order-id">Order #1</span>
                            <span class="order-status status-delivered">Status: Delivered</span>
                        </div>
                    </li>
                    <li>
                        <div class="order-item">
                            <span class="order-id">Order #2</span>
                            <span class="order-status status-processing">Status: Processing</span>
                        </div>
                    </li>
                    <li>
                        <div class="order-item">
                            <span class="order-id">Order #3</span>
                            <span class="order-status status-shipped">Status: Shipped</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

    `;
    }


    profileLink.addEventListener("click", function (e) {
        e.preventDefault();
        loadProfile();
    });

    addressLink.addEventListener("click", function (e) {
        e.preventDefault();
        loadAddress();
    });

    ordersLink.addEventListener("click", function (e) {
        e.preventDefault();
        loadOrders();
    });

    loadProfile();

    const editIcon = document.getElementById("edit-icon");
    const saveBtn = document.getElementById("saveBtn");
    const inputs = document.querySelectorAll("input");
    const firstNameInput = document.getElementById("firstName");
    const lastNameInput = document.getElementById("lastName");
    const emailInput = document.getElementById("email");
    const contactNumberInput = document.getElementById("contactNumber");

    editIcon.addEventListener('click', function () {

        inputs.forEach(input => input.disabled = false);
        saveBtn.disabled = false;
        saveBtn.style.visibility = 'visible';
    });

    saveBtn.addEventListener('click', function () {

        const updatedFirstName = firstNameInput.value;
        const updatedLastName = lastNameInput.value;
        const updatedEmail = emailInput.value;
        const updatedContactNumber = contactNumberInput.value;

        inputs.forEach(input => input.disabled = true);
        saveBtn.disabled = true;
        saveBtn.style.visibility = "hidden";
    });
});
