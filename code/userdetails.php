<?php
session_start();
if (!isset($_SESSION['signup_successful']) || $_SESSION['signup_successful'] === false) {
  session_unset();
  session_destroy();
  header("Location: signup.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Medical Form</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/userdetails.css">
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      min-height: 100vh;
      color: #2d3748;
    }

    .container {
      position: absolute;
      top: 0;
      right: 0;
      min-height: 100vh;
      padding: 2rem;
      display: flex;
      justify-content: flex-end;
      align-items: center;
    }

    .page {
      display: none;
      width: 500px;
      max-width: calc(100% - 2rem);
      background: rgba(255, 255, 255, 0.95);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      border: 1px solid rgba(255, 255, 255, 0.18);
      padding: 2rem;
      margin: 1rem;
      transition: all 0.3s ease;
    }

    .page:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    h2 {
      text-align: center;
      color: #1a202c;
      font-size: 1.75rem;
      font-weight: 600;
      margin-bottom: 1.5rem;
    }

    .btn {
      background-color: #38a169 !important;
      color: white !important;
      padding: 0.75rem 1.5rem;
      border-radius: 12px;
      border: none;
      font-weight: 500;
      transition: all 0.2s ease;
      cursor: pointer;
      margin-right: 1rem;
    }

    .btn:hover {
      background-color: #2f855a !important;
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(56, 161, 105, 0.2);
    }

    input[type="text"],
    input[type="number"],
    input[type="tel"],
    input[type="date"],
    textarea,
    select {
      width: 100%;
      padding: 0.75rem 1rem;
      background-color: #f7fafc;
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      color: #2d3748;
      font-size: 1rem;
      transition: all 0.2s ease;
      margin-bottom: 1rem;
    }

    input:focus,
    textarea:focus,
    select:focus {
      outline: none;
      border-color: #38a169;
      box-shadow: 0 0 0 3px rgba(56, 161, 105, 0.2);
      background-color: white;
    }

    #camera-container {
      width: 100%;
      max-width: 480px;
      margin: 1rem auto;
    }

    #camera-feed {
      width: 100%;
      max-width: 370px;
      border-radius: 16px;
      border: 3px solid #e2e8f0;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }

    #captured-photo-container {
      margin-top: 1.5rem;
      text-align: center;
    }

    #captured-photo {
      width: 300px;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .button-container {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      margin-top: 1.5rem;
      gap: 1rem;
    }

    @media (max-width: 768px) {
      .container {
        padding: 1rem;
        justify-content: center;
      }

      .page {
        margin: 0.5rem;
        padding: 1.5rem;
      }

      .button-container {
        flex-direction: column;
        gap: 0.75rem;
      }

      .btn {
        width: 100%;
        margin-right: 0;
      }
    }
  </style>
</head>

<body onload="startCamera()">

  <img src="./images/personal.svg" alt="Example SVG" width="900" height="700">
  </a>


  <div class="container">
    <div id="personal-info" class="page" method="post" enctype="multipart/form-data" action="#">
      <h2>Personal Information</h2>
      <form id="personal-info-form" enctype="multipart/form-data">
        <!-- Personal Information fields -->
        <!-- Name -->

        <div class="form-group">
          <label for="name">Name:</label>
          <input type="text" class="form-control" id="name" name="name" required pattern="[A-Za-z]{1,10}" title="Enter only letters, limit to 10 characters, and disallow spaces.">
          <div class="invalid-feedback">Enter only letters, limit to 10 characters, and disallow spaces.</div>
        </div>

        <div class="form-group">
          <label for="gender">Gender:</label>
          <select class="form-control" id="gender" name="gender" required>
            <option value="">Select Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
          </select>
          <div class="invalid-feedback">Please select your gender.</div>
        </div>

        <!-- Date of Birth -->
        <div class="form-group">
          <label for="dob">Date of Birth:</label>
          <input type="date" class="form-control" id="dob" name="dob" max="2008-01-01" required>
          <div class="invalid-feedback">Please enter your date of birth between 1962 and 2008.</div>
        </div>

        <!-- Live Photo Capture -->
        <div class="form-group">
          <label for="photo" class="h5">Live Photo:</label><br>
          <div id="camera-container">
            <video id="camera-feed" autoplay></video>
          </div><br>

          <button id="capture-button" class="btn bg-primary" onclick="capturePhoto()">Capture Photo</button>
          <!-- Button to retake the photo -->
          <button id="retake-button" class="btn" onclick="retakePhoto()" style="display: none;">Retake Photo</button>
          <!-- Hidden link for automatic download -->
          <a id="download-link" download="captured-photo.jpeg" style="display: none;"></a>
        </div>

        <!-- File upload input -->
        <div class="form-group">
          <label for="upload-photo" class="h5">Upload Photo:</label>
          <input type="file" id="upload-photo" accept="image/*" name="h_user_img">
          <p id="upload-error" style="color: red; display: none;">Error: Uploaded photo does not match the captured photo.</p>

        </div>
        <!-- Container for the captured photo -->
        <div class="form-group">
          <h5>Captured Photo:</h5>
          <img id="captured-photo" class="ml-5" src="" name="h_liveimg" alt="Please capture img">
        </div>
        <div class="form-group">
          <label for="phone">Phone Number:</label>
          <input type="tel" class="form-control" id="phone" name="phone" required>
          <div class="invalid-feedback">Please enter a valid 10-digit phone number.</div>

        </div>


        <div class="form-group">
          <label for="address">Address:</label>
          <textarea class="form-control" id="address" name="address" required></textarea>
          <div class="invalid-feedback">Please enter your address.</div>
        </div>

        <!-- Next Button -->
        <button type="button" class="btn btn-primary" onclick="nextPage('medical-history')">Next</button>
      </form>
    </div>



    <!-- Continue from the last snippet -->

    <div id="medical-history" class="page" style="display:none;">
      <h2>Medical History</h2>
      <form id="medical-history-form">
        <!-- Do you have any diseases? -->
        <div class="form-group">
          <label for="has-diseases">Do you have any diseases?</label>
          <select class="form-control" id="has-diseases" name="has-diseases" onchange="toggleMedicalDetails()">

            <option value="no">No</option>
            <option value="yes">Yes</option>
          </select>
        </div>

        <!-- Medical History fields (hidden initially) -->
        <div id="medical-details" style="display:none;">
          <!-- Pre-existing conditions -->
          <div class="form-group">
            <label for="conditions">Pre-existing conditions:</label>
            <input type="text" class="form-control" id="conditions" name="conditions">
          </div>
          <!-- Previous surgeries -->
          <div class="form-group">
            <label for="surgeries">Previous surgeries or hospitalizations:</label>
            <input type="text" class="form-control" id="surgeries" name="surgeries">
          </div>
          <!-- Family medical history -->
          <div class="form-group">
            <label for="family-history">Family medical history:</label>
            <input type="text" class="form-control" id="family-history" name="family-history">
          </div>
          <!-- Allergies -->
          <div class="form-group">
            <label for="allergies">Allergies:</label>
            <input type="text" class="form-control" id="allergies" name="allergies">
          </div>
          <!-- Medications -->
          <div class="form-group">
            <label for="medications">Medications currently being taken:</label>
            <input type="text" class="form-control" id="medications" name="medications">
          </div>
          <!-- Current symptoms -->
          <div class="form-group">
            <label for="symptoms">Current symptoms:</label>
            <input type="text" class="form-control" id="symptoms" name="symptoms">
            <small class="form-text text-muted">You can skip this page if you don’t have the above-mentioned health problems.</small>
          </div>
        </div>

        <!-- Previous Button -->
        <button type="button" class="btn btn-secondary" onclick="prevPage()">Previous</button>
        <!-- Next Button -->
        <button type="button" class="btn btn-primary" onclick="nextPage()">Next</button>
      </form>
    </div>

    <div id="lifestyle" class="page" style="display:none;">
      <h2>Lifestyle Factors</h2>
      <form id="lifestyle-form">
        <!-- Lifestyle fields -->
        <!-- Diet habits -->
        <div class="form-group">
          <label for="diet">Meal Type:</label>
          <select class="form-control" id="diet" name="diet" required>
            <option value="">Select Diet Habits</option>

            <option value="vegetarian">Vegetarian</option>
            <option value="omnivore">Non-Vegetarian </option>
            <option value="vegan">vegan</option>

            <!-- Add more options as needed -->
          </select>
          <div class="invalid-feedback">Please select a diet habit.</div>
        </div>
        <!-- Physical activity level -->
        <div class="form-group">
          <label for="activity">Physical activity level:</label>
          <select class="form-control" id="activity" name="activity" required>
            <option value="">Select Activity Level</option>

            <option value="lightly_active">Lightly Active (e.g. teacher, salesman)
            </option>
            <option value="moderately_active">Moderately Active (e.g. waitress, mailman)
            </option>
            <option value="very_active">Very Active(e.g. bike messenger, carpenter)</option>


            <!-- Add more options as needed -->
          </select>
          <div class="invalid-feedback">Please select an activity level.</div><br>


        </div>

        <!-- Previous Button -->
        <button type="button" class="btn btn-secondary" onclick="prevPage('medical-history')">Previous</button>
        <!-- Next Button -->
        <button type="button" class="btn btn-primary" onclick="nextPage('vital-signs')">Next</button>
      </form>
    </div>


    <div id="vital-signs" class="page" style="display:none;">
      <h2>Vital Signs and Measurements</h2>
      <form id="vital-signs-form">
        <!-- Vital Signs fields -->
        <!-- Height -->
        <div class="form-group">
          <label for="height">Height (feet):</label>
          <input type="number" class="form-control" id="height" name="height" min="2" max="9" step="0.01" required>
          <div class="invalid-feedback">Height must be between 2 and 9 feet.</div>
        </div>
        <!-- Weight -->
        <div class="form-group">
          <label for="weight">Weight (kg):</label>
          <input type="number" class="form-control" id="weight" name="weight" min="20" step="0.01" max="120" required>
          <div class="invalid-feedback">Weight must be between 20 and 120 kg.</div>
        </div>
        <div id="bmiResult"></div><br>
        <label for="your_goal">Select Your Goal</label>
        <select class="form-control" id="your_goal" name="your_goal" required onchange="handleGoalSelection()">
          <option value="">Select Activity Level</option>
          <option value="loss_weight">Loss weight</option>
          <option value="maintain_weight">Maintain weight</option>
          <option value="gain_weight">Gain weight</option>
        </select><br>
        <div class="invalid-feedback">Please select Your Goal.</div>
        <div id="weightInput" style="display: none;">
          <label id="weightLabel"></label>
          <input type="number" class="form-control" id="user_goal" name="h_user_goal" min="42" max="120">
          <div class="invalid-feedback">Enter your goal weigh it should be in minimum 42 kg to maximum 120.</div>
        </div><br>
        <!-- Buttons container -->
        <div class="button-container">
          <!-- Previous Button -->
          <button type="button" class="btn btn-secondary" onclick="prevPage('lifestyle')">Previous</button>
          <!-- Next or Save Button -->
          <div id="nextSaveButtons">
            <button type="button" id="nextButton" class="btn btn-primary" onclick="validateVitalSigns()">Next</button>
            <button type="button" id="saveButton" class="btn btn-success" onclick="saveForm()">Save</button>
          </div>
        </div>
      </form>
    </div>


    <div id="Daily_calories" class="page" style="display:none;">
      <h3 id="d_heading"></h3>
      <form id="daily_calories-form">
        <div class='text-center'>
          <img id="img" style="width: 60px;" class="center" alt="Logo">
          <div><br>

            <h5 id="r_heading_gain"></h5>
            <select id="gain_rountine" class="form-control" name="gain_weekly_routine">
              <option>Select the routine</option>
              <option value='227'>Gain Weight 227 gram / 0.5 pounds per week (Recommended)</option>
              <option value='453'>453 gram / 1 pound</option>
            </select><br>
            <h5 id="r_heading_loss"></h5>
            <select id="loss_rountine" class="form-control" name="loss_weekly_routine">
              <option>Select the routine</option>
              <option value='227'>Lose Weight 227 gram / 0.5 pounds per week</option>
              <option value='340'>Lose Weight 340 gram / 0.75 pounds per week (Recommended)</option>
              <option value='453'>453 gram / 1 pound</option>
            </select><br>

            <button type="button" class="btn btn-secondary" onclick="prevPage('Daily_calories')">Previous</button>
            <!-- Save Button -->
            <button type="button" class="btn btn-success" onclick="saveForm()">Save</button>
      </form>
    </div>

    <!-- Result Section -->
    <div id="result"></div>

    <!-- Bootstrap JS and your custom script -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
      // Your JavaScript functions here

      var cameraStream;
      var captureButton = document.getElementById('capture-button');
      var retakeButton = document.getElementById('retake-button');
      var downloadLink = document.getElementById('download-link');
      var uploadInput = document.getElementById('upload-photo');
      var uploadError = document.getElementById('upload-error');
      var capturedPhotoDataURL;

      // Function to start the camera and display the live feed
      function startCamera() {
        // Access the user's camera
        navigator.mediaDevices.getUserMedia({
            video: true
          })
          .then(function(stream) {
            // Get the video element
            var videoElement = document.getElementById('camera-feed');
            // Set the video element's source to the camera stream
            videoElement.srcObject = stream;
            // Save the camera stream
            cameraStream = stream;
            // Show the capture button
            captureButton.style.display = 'inline-block';
          })
          .catch(function(error) {
            console.error('Error accessing camera:', error);
          });
      }

      // Function to stop the camera
      function stopCamera() {
        // Stop the camera stream
        if (cameraStream) {
          cameraStream.getTracks().forEach(function(track) {
            track.stop();
          });
          cameraStream = null;
          // Hide the capture button
          captureButton.style.display = 'none';
          // Show the retake button
          retakeButton.style.display = 'inline-block';
        }
      }
      // Function to capture a photo from the live feed
      function capturePhoto() {
        // Disable the capture button to prevent multiple captures
        captureButton.disabled = true;

        // Get the video element
        var videoElement = document.getElementById('camera-feed');
        // Create a canvas element
        var canvas = document.createElement('canvas');
        // Set the canvas dimensions to match the video feed
        canvas.width = videoElement.videoWidth;
        canvas.height = videoElement.videoHeight;
        // Get the canvas context
        var context = canvas.getContext('2d');
        // Draw the current frame of the video onto the canvas
        context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);
        // Convert the canvas content to a data URL representing the captured photo
        capturedPhotoDataURL = canvas.toDataURL('image/jpeg');
        // Set the captured photo as the source for the image element
        var capturedPhotoElement = document.getElementById('captured-photo');
        capturedPhotoElement.src = capturedPhotoDataURL;

        // Set the download link's href to the captured photo data URL and trigger the download
        downloadLink.href = capturedPhotoDataURL;
        downloadLink.click();

        // Show the upload input
        uploadInput.style.display = 'block';

        // Stop the camera after capturing the photo
        stopCamera();
      }

      // Function to retake the photo
      function retakePhoto() {
        // Clear the captured photo
        var capturedPhotoElement = document.getElementById('captured-photo');
        capturedPhotoElement.src = '';
        capturedPhotoDataURL = '';

        // Hide the retake button
        retakeButton.style.display = 'none';
        // Hide the upload input and error message
        uploadInput.style.display = 'none';
        uploadError.style.display = 'none';

        // Start the camera again
        startCamera();
      }

      // Event listener for file upload
      uploadInput.addEventListener('change', function() {
        var uploadedFile = this.files[0];
        var reader = new FileReader();
        reader.onload = function(event) {
          var uploadedPhotoDataURL = event.target.result;
          // Check if the uploaded photo matches the captured photo
          if (uploadedPhotoDataURL !== capturedPhotoDataURL) {
            uploadError.style.display = 'block';
            // Clear the input field
            uploadInput.value = '';
          } else {
            // Photo matches, proceed with upload
            uploadError.style.display = 'none';
            // You can add your upload logic here
            console.log('Uploaded photo matches the captured photo.');
          }
        };
        reader.readAsDataURL(uploadedFile);
      });

      // Start the camera when the page loads
      startCamera();

      function PCDtoggleInputField() {
        const conditionsSelect = document.getElementById('conditionsSelect');
        const conditionsInputField = document.getElementById('conditionsInputField');

        if (conditionsSelect.value === 'yes') {
          conditionsInputField.style.display = 'block';
        } else {
          conditionsInputField.style.display = 'none';
        }
      }

      function toggleFileUpload() {
        const surgeriesSelect = document.getElementById('surgeriesSelect');
        const surgeriesFileUploadGroup = document.getElementById('surgeriesFileUploadGroup');

        if (surgeriesSelect.value === 'yes') {
          surgeriesFileUploadGroup.style.display = 'block';
        } else {
          surgeriesFileUploadGroup.style.display = 'none';
        }
      }
      let currentPage = 0;
      const pages = document.querySelectorAll('.page');

      function showPage(pageIndex) {
        pages.forEach((page, index) => {
          if (index === pageIndex) {
            page.style.display = 'block';
          } else {
            page.style.display = 'none';
          }
        });
      }

      function toggleMedicalDetails() {
        var hasDiseases = document.getElementById("has-diseases").value;
        var medicalDetails = document.getElementById("medical-details");

        // Show the medical history fields if "Yes" is selected, hide them otherwise
        if (hasDiseases === "yes") {
          medicalDetails.style.display = "block";
        } else {
          medicalDetails.style.display = "none";
        }
      }

      function handleGoalSelection() {
        var goal = document.getElementById("your_goal").value;
        if (goal === "maintain_weight") {
          // Show save button directly
          document.getElementById("saveButton").style.display = "block";
          document.getElementById("nextButton").style.display = "none"; // Hide next button
        } else {
          // Show next button for other goals
          document.getElementById("nextButton").style.display = "block";
          document.getElementById("saveButton").style.display = "none"; // Hide save button
        }
      }

      function nextPage() {
        // Check if all required fields are filled before proceeding
        const currentForm = document.querySelector('.page:nth-child(' + (currentPage + 1) + ') form');
        const inputs = currentForm.querySelectorAll('input, select, textarea');
        let isValid = true;

        inputs.forEach(input => {
          if (input.hasAttribute('required') && input.value.trim() === '') {
            isValid = false;
            input.classList.add('is-invalid');
          }
        });

        if (isValid) {
          if (currentPage < pages.length - 1) {
            currentPage++;
            showPage(currentPage);
          }
        } else {
          // Display error message or handle invalid input as needed
          console.log('Please fill in all required fields.');
        }
      }

      function prevPage() {
        if (currentPage > 0) {
          currentPage--;
          showPage(currentPage);
        }
      }

      function saveForm() {
        const forms = document.querySelectorAll('form');
        const formData = new FormData();
        forms.forEach(form => {
          for (const [key, value] of new FormData(form)) {
            formData.append(key, value);
          }
        });
        // Send form data to the server using AJAX
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'userdetails.php');
        xhr.onload = function() {
          if (xhr.status === 200) {
            // Display success message or perform any other action upon successful submission
            document.getElementById('result').innerHTML = "<p>Data saved successfully!</p>";

            // Redirect to the home page
            window.location.href = './login.php';
          } else {
            // Handle errors if any
            console.error('Error:', xhr.statusText);
          }
        };
        xhr.onerror = function() {
          console.error('Request failed');
        };
        xhr.send(formData);
      }

      function nextPage(nextPageId) {
        // Check if all required fields are filled and valid before proceeding
        const currentForm = document.querySelector('.page:nth-child(' + (currentPage + 1) + ') form');
        const inputs = currentForm.querySelectorAll('input, select, textarea');
        let isValid = true;

        inputs.forEach(input => {
          if (input.hasAttribute('required') && (input.value.trim() === '' || !input.checkValidity())) {
            isValid = false;
            input.classList.add('is-invalid');
          } else {
            input.classList.remove('is-invalid');
          }
        });

        if (isValid) {
          if (currentPage < pages.length - 1) {
            currentPage++;
            showPage(currentPage);
          }
        } else {
          // Display error message or handle invalid input as needed
          console.log('Please fill in all required fields.');
        }
      }

      // Get the date of birth input field
      const dobInput = document.getElementById('dob');

      // Add event listener for input event
      dobInput.addEventListener('input', function(event) {
        // Get the entered date value
        const enteredDate = new Date(event.target.value);

        // Define the range of valid dates (1962 to 2008)
        const minDate = new Date('1962-01-01');
        const maxDate = new Date('2008-12-31');

        // Check if the entered date falls within the valid range
        if (enteredDate < minDate || enteredDate > maxDate) {
          // Display error message and mark input as invalid
          dobInput.classList.add('is-invalid');
        } else {
          // Remove error message and mark input as valid
          dobInput.classList.remove('is-invalid');
        }
      });
      const phoneInput = document.getElementById('phone');

      // Function to check if any digit repeats more than 4 times
      function hasRepeatedDigits(input) {
        const digitCount = {};
        for (const digit of input) {
          if (!digitCount[digit]) {
            digitCount[digit] = 0;
          }
          digitCount[digit] += 1;
          if (digitCount[digit] > 4) {
            return true;
          }
        }
        return false;
      }

      // Add event listener for input event
      phoneInput.addEventListener('input', function(event) {
        // Get the entered value
        let inputValue = event.target.value;

        // Remove any non-numeric characters from the input value
        inputValue = inputValue.replace(/\D/g, '');

        // Limit the input value to 10 digits
        inputValue = inputValue.slice(0, 10);

        // Update the input value
        event.target.value = inputValue;

        // Check if the entered phone number is exactly 10 digits and has no repeated digits more than 4 times
        if (inputValue.length !== 10 || hasRepeatedDigits(inputValue)) {
          phoneInput.classList.add('is-invalid');
          if (inputValue.length !== 10) {
            phoneInput.setCustomValidity('Phone number must be exactly 10 digits.');
          } else if (hasRepeatedDigits(inputValue)) {
            phoneInput.setCustomValidity('No digit should repeat more than 4 times.');
          }
        } else {
          phoneInput.classList.remove('is-invalid');
          phoneInput.setCustomValidity('');
        }
      });



      document.getElementById('height').addEventListener('input', calculateBMI);
      document.getElementById('weight').addEventListener('input', calculateBMI);

      // Function to calculate BMI
      function calculateBMI() {
        const height = parseFloat(document.getElementById('height').value);
        const weight = parseFloat(document.getElementById('weight').value);
        const bmiResult = document.getElementById('bmiResult');

        if (!isNaN(height) && !isNaN(weight) && height >= 2 && height <= 9 && weight >= 20 && weight <= 120) {
          // Calculate BMI
          const heightMeters = height * 0.3048;
          const bmi = weight / (heightMeters * heightMeters);

          // Display BMI result
          if (bmi <= 18.5) {
            bmiResult.textContent = 'Your BMI is under weight: ' + bmi.toFixed(2);
          } else if (bmi >= 30.0) {
            bmiResult.textContent = 'Your BMI is obestiy: ' + bmi.toFixed(2);
          } else {
            bmiResult.textContent = 'Your BMI is within the healthy range: ' + bmi.toFixed(2);
          }

        } else {
          // Clear BMI result if height or weight is invalid
          bmiResult.textContent = '';
        }
      }

      function validateVitalSigns() {
        const heightInput = document.getElementById('height');
        const weightInput = document.getElementById('weight');

        if (heightInput.value.trim() === '' || isNaN(parseFloat(heightInput.value)) || parseFloat(heightInput.value) < 2 || parseFloat(heightInput.value) > 9) {
          // Display error message for invalid height
          heightInput.classList.add('is-invalid');
          heightInput.nextElementSibling.textContent = 'Height must be a number between 2 and 9 feet.';
          return; // Stop execution if height is invalid
        } else {
          // Remove error message if height is valid
          heightInput.classList.remove('is-invalid');
          heightInput.nextElementSibling.textContent = '';
        }

        if (weightInput.value.trim() === '' || isNaN(parseFloat(weightInput.value)) || parseFloat(weightInput.value) < 20 || parseFloat(weightInput.value) > 120) {
          // Display error message for invalid weight
          weightInput.classList.add('is-invalid');
          weightInput.nextElementSibling.textContent = 'Weight must be a number between 20 and 120 kg.';
          return; // Stop execution if weight is invalid
        } else {
          // Remove error message if weight is valid
          weightInput.classList.remove('is-invalid');
          weightInput.nextElementSibling.textContent = '';
        }

        // Proceed to the next page if both height and weight are valid
        nextPage('symptom-assessment'); // Pass the next page ID as an argument
      }

      document.getElementById('your_goal').addEventListener('change', function() {
        let selectedOption = this.value;
        let weightInput = document.getElementById('weightInput');
        let weightLabel = document.getElementById('weightLabel');
        let currentWeight = parseFloat(document.getElementById('weight').value);
        let userGoalInput = document.getElementById('user_goal');
        let bmi = parseFloat(document.getElementById('bmiResult').textContent.split(':')[1].trim());
        const saveButton = document.getElementById('saveButton');
        const nextButton = document.getElementById('nextButton');

        nextButton.style.display = 'block'; // Ensure the nextButton is displayed by default
        let errorMessage = document.getElementById('error-message');
        if (!errorMessage) {
          errorMessage = document.createElement('div');
          errorMessage.id = 'error-message';
          errorMessage.style.color = 'red';
          weightInput.appendChild(errorMessage);
        }
        errorMessage.textContent = '';

        if (selectedOption === 'gain_weight') {
          if (bmi >= 30.0) {
            console.log('Your BMI is above 30. Please consult a healthcare professional before attempting to gain weight.');
            this.value = ''; // Reset the select value
          } else {
            weightLabel.textContent = 'How much weight do you want to gain?';
            weightInput.style.display = 'block';
            userGoalInput.setAttribute('required', 'required');

            userGoalInput.addEventListener('input', function() {
              if (parseFloat(userGoalInput.value) <= currentWeight) {
                errorMessage.textContent = 'Invalid weight: Goal weight must be greater than current weight.';
                saveButton.style.display = 'none';
                nextButton.style.display = 'none';
              } else {
                errorMessage.textContent = '';
                saveButton.style.display = 'none';
                nextButton.style.display = 'block';
              }
            });
          }
        } else if (selectedOption === 'loss_weight') {
          if (bmi <= 18.5) {
            console.log('Your BMI is below 18.5. You cannot select "loss weight" option.');
            this.value = ''; // Reset the select value
          } else {
            weightLabel.textContent = 'How much weight do you want to lose?';
            weightInput.style.display = 'block';
            userGoalInput.setAttribute('required', 'required');

            userGoalInput.addEventListener('input', function() {
              if (parseFloat(userGoalInput.value) >= currentWeight) {

                errorMessage.textContent = 'Invalid weight:  Goal weight must be less than current weight.';
                saveButton.style.display = 'none';
                nextButton.style.display = 'none';
              } else {
                errorMessage.textContent = '';
                saveButton.style.display = 'none';
                nextButton.style.display = 'block';
              }
            });
          }
        } else if (selectedOption === 'maintain_weight') {
          weightLabel.textContent = 'Maintain body weight';
          weightInput.style.display = 'none';
          userGoalInput.removeAttribute('required');
          saveButton.style.display = 'block';
          nextButton.style.display = 'none'; // Hide the nextButton when maintain_weight is selected
        } else {
          weightInput.style.display = 'none';
          userGoalInput.removeAttribute('required');
          saveButton.style.display = 'none';
        }
      });
      document.getElementById('your_goal').addEventListener('change', function() {
        let selectedOption = this.value;
        let heading = document.getElementById('d_heading');
        let g_heading_gain = document.getElementById('r_heading_gain');
        let g_heading_loss = document.getElementById('r_heading_loss');
        let img = document.getElementById('img');
        let gainRoutineSelect = document.getElementById('gain_rountine');
        let lossRoutineSelect = document.getElementById('loss_rountine');

        if (selectedOption === 'gain_weight') {
          heading.textContent = 'Your Weekly Goal to Gain Weight';
          img.src = './images/gain.png';
          g_heading_gain.style.display = 'block';
          g_heading_loss.style.display = 'none';
          gainRoutineSelect.style.display = 'block';
          lossRoutineSelect.style.display = 'none';
        } else if (selectedOption === 'loss_weight') {
          heading.textContent = 'Your Weekly Goal to Loss Weight';
          img.src = './images/lose-weight_4924477.png';
          g_heading_loss.style.display = 'block';
          g_heading_gain.style.display = 'none';
          gainRoutineSelect.style.display = 'none';
          lossRoutineSelect.style.display = 'block';
        } else if (selectedOption === 'maintain_weight') {
          heading.textContent = 'Your Weekly Goal to maintain Weight';
          // For maintain weight option, hide both routine selections
          g_heading_gain.style.display = 'none';
          g_heading_loss.style.display = 'none';
          gainRoutineSelect.style.display = 'none';
          lossRoutineSelect.style.display = 'none';
        } else {
          // Handle any other cases
        }
      });


      document.getElementById('name').addEventListener('keydown', function(event) {
        let key = event.key;
        let regex = /^[a-zA-Z]$/; // Regex to allow only letters
        let maxLength = 10; // Maximum length allowed

        if (!regex.test(key) && key !== 'Backspace' && key !== 'Tab') {
          event.preventDefault(); // Prevent entering the character
        }

        if (this.value.length >= maxLength && key !== 'Backspace' && key !== 'Tab') {
          event.preventDefault(); // Prevent entering if maximum length is reached
        }
      });


      // Initial page display
      showPage(currentPage);
    </script>


    <?php


    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      include './config.php';

      // Image upload handling
      $p_userImage = $_FILES["h_user_img"]["name"];
      $p_userImageTemp = $_FILES["h_user_img"]["tmp_name"];
      $p_userImageFolder = "user_img/" . $p_userImage;

      if (move_uploaded_file($p_userImageTemp, $p_userImageFolder)) {
        echo "Image uploaded successfully.";
      } else {
        echo "Error uploading image.";
      }

      // Retrieve form data
      $p_name = $_POST["name"];
      $p_gender = $_POST["gender"];
      $p_dateOfBirth = $_POST["dob"];
      $p_phoneNumber = $_POST["phone"];
      $p_address = $_POST["address"];
      $p_preExistingConditions = $_POST["conditions"];
      $p_previousSurgeries = $_POST["surgeries"];
      $p_familyMedicalHistory = $_POST["family-history"];
      $p_allergies = $_POST["allergies"];
      $p_medications = $_POST["medications"];
      $p_diet = $_POST["diet"];
      $p_activityLevel = $_POST["activity"];
      $p_height = $_POST["height"];
      $p_weight = $_POST["weight"];
      $p_currentSymptoms = $_POST["symptoms"];
      $p_userGoal = $_POST["your_goal"];
      $id = $_SESSION['id'];


      $p_weekly_user_routine = null;

      // Check if the gain routine is selected
      if (isset($_POST["gain_weekly_routine"]) && !empty($_POST["gain_weekly_routine"])) {
        $p_weekly_user_routine = $_POST["gain_weekly_routine"];
      } elseif (isset($_POST["loss_weekly_routine"]) && !empty($_POST["loss_weekly_routine"])) {
        $p_weekly_user_routine = $_POST["loss_weekly_routine"];
      }

      // SQL insertion query
      $sql = "INSERT INTO userdetails_db (id_db, name_db, gender_db, date_of_birth_db, phone_number_db, address_db, 
    pre_existing_conditions_db, Previous_surgeries_hospitalizations_db, Family_medical_history_db, allergies_db, 
    medication_current_db, diet_db, physical_activity_level_db, height_db, weight_db, current_symptoms_db, 
    select_goal, weekly_user_routine_db, goal_weight_db, user_image_db) 
    VALUES ($id, '$p_name', '$p_gender', '$p_dateOfBirth', '$p_phoneNumber', '$p_address', 
    '$p_preExistingConditions', '$p_previousSurgeries', '$p_familyMedicalHistory', '$p_allergies', 
    '$p_medications', '$p_diet', '$p_activityLevel', '$p_height', '$p_weight', '$p_currentSymptoms', 
    '$p_userGoal', '$p_weekly_user_routine', '$p_goal_weight', '$p_userImageFolder')";


      $_SESSION['userimage'] = $p_userImageFolder;

      if ($conn->query($sql) === TRUE) {

        // Close connection
        $conn->close();
        // Redirect to home page after inserting data
        header("Location: ./home-page.php");
        exit;
      } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }

      // Close connection
      $conn->close();
    }
    ?>
</body>

</html>