function validateForm() {
    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirm_password').value;

    var email_message = '';
    var password_message = '';
    var confirm_password_message = '';

    if (email.trim() === '') {
        email_message = 'Please enter your email';
    }

    if (password.trim() === '') {
        password_message = 'Please enter your password';
    } else if (password.length < 4) {
        password_message = 'Password should be at least 4 characters long';
    }

    if (confirmPassword.trim() === '') {
        confirm_password_message = 'Please confirm your password';
    } else if (confirmPassword.length < 4) {
        confirm_password_message = 'Confirm password should be at least 4 characters long';
    }

    if (password !== confirmPassword) {
        confirm_password_message = 'Passwords do not match';
    }

    document.getElementById('email_message').innerHTML = email_message;
    document.getElementById('password_message').innerHTML = password_message;
    document.getElementById('confirm_password_message').innerHTML = confirm_password_message;

    if (email_message === '' && password_message === '' && confirm_password_message === '') {
        return true;
    } else {
        return false;
    }
}