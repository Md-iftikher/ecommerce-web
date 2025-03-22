document.getElementById('signup-form').addEventListener('submit', async function(event) {
    const first_name = document.getElementById('first-name').value;
    const last_name = document.getElementById('last-name').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm-password').value;
    const email = document.getElementById('email').value;

    if (inValidName(first_name)) {
        event.preventDefault();  
        alert('Enter a valid first name!');
    }
    else if (last_name && inValidName(last_name)) {
        event.preventDefault();  
        alert('Enter a valid last name!');
    }
    
    else if(password != confirmPassword) {
        event.preventDefault();
        alert("Passwords do not match!");
    }
    else if (inValidPassword(password)) {
        event.preventDefault(); // Prevent form submission
        alert("Password must be at least 8 characters long, contain at least one number, and one special character.");
    }
});

function inValidName(name) {
    const namePattern = /^[A-Za-z\s]+$/;
    return !namePattern.test(name);
}

function inValidPassword(password) {
    const passwordPattern = /^(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    return !passwordPattern.test(password);
}

// async function email_exists(email) {
//     var data = { email: email };

//     try {
//         const response = await fetch('../php/functions.php', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json',
//             },
//             body: JSON.stringify(data),
//         });

//         const responseData = await response.json();
//         return responseData.status;  // Returns true or false based on email existence
//     } catch (error) {
//         console.error('Error:', error);
//         alert('An error occurred.');
//         return false;
//     }
// }
