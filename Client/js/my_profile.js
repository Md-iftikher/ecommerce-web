

function getEnabled() {
  inputs = document.getElementsByTagName("input");
  for (let i = 0; i < inputs.length; i++) {
    inputs[i].disabled = false;
  }

  document.getElementById("saveBtn").disabled = false;
  document.getElementById("saveBtn").style.visibility = 'visible';
}


function getDisabled() {
  inputs = document.getElementsByTagName("input");
  for (let i = 0; i < inputs.length; i++) {
    inputs[i].disabled = true;
  }

  document.getElementById("saveBtn").disabled = true;
  document.getElementById("saveBtn").style.visibility = 'hidden';
  document.getElementById("profile-form").submit();
}

function toggleEditMode(address) {

  const editIcons = document.getElementsByClassName("edit-address");

  for (let i = 0; i < editIcons.length; i++) {
    if (editIcons[i].id != `edit-icon-${address}`) {
      editIcons[i].disabled = true;
    }
  }

  input = document.getElementById(`address-input-${address}`);
  input.disabled = false;

  // Get the edit icon and save button elements by their IDs
  const editIcon = document.getElementById(`edit-icon-${address}`);
  const saveBtn = document.getElementById(`save-address-${address}`);

  // Toggle visibility: hide the edit icon and show the save button
  editIcon.style.display = 'none';  // Hide edit icon
  saveBtn.style.display = 'inline-block';  // Show the save button


}

function toggleAddMode(count) {
  if(count >= 3) {
    alert("You have reached the maximum limit of 3 addresses");
    return;
  }

  const addressInsertForm = document.getElementById("address-insert-form");
  const addressForms = document.getElementsByClassName("address-form");
  const insertIcon = document.getElementById("insert-address-icon");
  const insertInput = document.getElementById("insert-address-input");

  for (let i = 0; i < addressForms.length; i++) {
    const inputs = addressForms[i].querySelectorAll('input, select, textarea, button');

    inputs.forEach(element => {
      element.disabled = true; // or false to enable
    });
  }

  addressInsertForm.style.display = "flex";
  insertIcon.style.display = "inline-block";
  insertInput.focus();


}

function confirmDeletion(event) {
  if(!confirm("Are you sure you want to proceed with the deletion")) {
    event.preventDefault();
  }
}


document.querySelectorAll(".get-details").forEach(button => {
  button.addEventListener("click", async function() {
    let orderId = this.getAttribute("order-id");

    // Fetch order details from PHP
    let response = await fetch("../php/my_profile/product_details.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({ order_id: orderId })
    });

    let data = await response.json();
    
    // Access order details and total price
    console.log("Order Details:", data.order_details); // Array of product details
    console.log("Total Price:", data.total_price); // Total price

    // Hide the order list and show the product details section
    document.getElementById("order-list").style.display = "none";
    document.getElementById("product-details").style.display = "block"; // Show the product details div

    // Populate the product details section
    document.getElementById("details-content").innerHTML = `
      <h2>Order Details</h2>
      <table>
        <thead>
          <tr>
            <th>Product Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody id="order-items">
          <!-- Dynamically fill rows here -->
          ${data.order_details.map(item => `
            <tr>
              <td>${item.product_name}</td>
              <td>$${item.price}</td>
              <td>${item.quantity}</td>
              <td>$${item.subtotal}</td>
            </tr>
          `).join('')}
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3" class="total-label">Total Price:</td>
            <td id="total-price">$${data.total_price}</td>
          </tr>
        </tfoot>
      </table>
    `;
  });
});

// Close functionality
document.getElementById("close-details").addEventListener("click", function() {
  // Hide product details and show the order list again
  document.getElementById("product-details").style.display = "none";
  document.getElementById("order-list").style.display = "block";
});
