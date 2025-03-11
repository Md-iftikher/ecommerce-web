

function getEnabled() {
  inputs = document.getElementsByTagName("input");
  for(let i = 0; i < inputs.length; i++) {
    inputs[i].disabled = false;
  }

  document.getElementById("saveBtn").disabled = false;
  document.getElementById("saveBtn").style.visibility = 'visible';
}


function getDisabled() {
  inputs = document.getElementsByTagName("input");
  for(let i = 0; i < inputs.length; i++) {
    inputs[i].disabled = true;
  }

  document.getElementById("saveBtn").disabled = true;
  document.getElementById("saveBtn").style.visibility = 'hidden';
  document.getElementById("profile-form").submit();
}

function toggleEditMode(address) {

  const editIcons = document.getElementsByClassName("edit-address");

  for(let i = 0; i < editIcons.length; i++){
    if(editIcons[i].id != `edit-icon-${address}`)
    {
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