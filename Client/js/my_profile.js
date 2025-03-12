

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