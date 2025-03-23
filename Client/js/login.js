document.getElementById('login-form').addEventListener('submit', async function(event) {

    event.preventDefault();  
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;


    if (!(await email_exists(email))) {
        alert('Email not registered. Please sign in first!');
    }
    else if(await incorrect_password(email, password)) {
        document.getElementById('password').value = "";
        alert('Incorrect Password! Please try again.');  

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

async function incorrect_password(email, password) {
    const data = {
        password: password,
        email: email
    };
    const response = await fetch('../php/login/verify_password.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    });
    const response_data = await response.json();
    return !(response_data.is_password_correct);
}
