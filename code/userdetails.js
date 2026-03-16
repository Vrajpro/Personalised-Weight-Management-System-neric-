// Function to handle button click events and add/remove the 'active' class
function handleButtonClick(buttons, button) {
  buttons.forEach(function (btn) {
    btn.classList.remove('active');
  });
  button.classList.add('active');
}

// Create an array of button sets and add event listeners
var buttonSets = [document.querySelectorAll('#btn'), document.querySelectorAll('#btn2'), document.querySelectorAll('#btn3')];

buttonSets.forEach(function (buttons, index) {
  buttons.forEach(function (button) {
    button.addEventListener('click', function () {
      handleButtonClick(buttons, this);
    });
  });
});

document.querySelector(".submit-button").addEventListener("click", function (event) {
  var birthdate = document.getElementById("birthdate").value;
  var today = new Date();
  var birthDate = new Date(birthdate);
  var age = today.getFullYear() - birthDate.getFullYear();

  if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) {
    age--;
  }

  if (age < 15) {
    alert("Sorry, you must be at least 15 years old to use this website.");
  } else if (isNaN(age)) { // Check for NaN
    alert("Age cannot be null or invalid");
  } else if (age >= 16) {
    var zipCode = document.getElementById("zipCode").value;

    if (zipCode.length !== 6 || isNaN(zipCode)) {
      alert("Please enter a valid 6-digit zip code.");
    } else {
      var redirectTo = "./home-page.html"; // Replace with your desired URL
      window.location.href = redirectTo;
    }
  }
});


