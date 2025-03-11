

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

  inputs = document.getElementsByTagName("input");
  for(let i = 0; i < inputs.length; i++) {
    inputs[i].disabled = false;
  }
 
  const editIcon = document.getElementById(`edit-icon-${address}`);
  const saveBtn = document.getElementById(`save-address-${address}`);

  
  editIcon.style.display = 'none';  
  saveBtn.style.display = 'inline-block';  


}