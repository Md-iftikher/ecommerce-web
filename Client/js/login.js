document.getElementById('login-form').addEventListener('submit', async function(event) {

    event.preventDefault();  
    const email = document.getElementById('email').value;


    if (!(await email_exists(email))) {
        alert('Email not registered. Please sign in first!');
    }
    else {
        document.getElementById('login-form').submit();
    }
});


async function email_exists(email) {
    const data = {email: email};
    const response = await fetch('../php/login/verify_email.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    });
    const response_data = await response.json();
    return response_data.email_exists; 
}
