<?php
include "session_login_logout.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<link rel="stylesheet" href="styles.css"> <!-- Your custom CSS file -->
	<title>Personal Information</title>

	<style>
		@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap');

		.close:hover {
			background-color: red;
		}

		.cover-box {
			padding: 20px;
			margin-top: 20px;
			border-radius: 20px;
			display: flex;
			background: linear-gradient(135deg, rgba(255, 255, 255, 0.7) 0%, rgba(158, 219, 112, 0.5) 100%);
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
			position: relative;
			overflow: hidden;
		}

		.user-info {
			flex: 2;
			padding-left: 20px;
			position: relative;
		}

		.user-info h2 {
			margin-bottom: 10px;
		}

		.user-info p {
			color: #666;
		}

		.user-image {
			width: 250px;
			height: 250px;
			border-radius: 50%;
			object-fit: cover;
			border: 5px solid rgb(158, 219, 112);
		}

		.user-image-container {
			flex: 1;
			text-align: center;
		}

		.user-image-container h3 {
			margin-top: 30px;
		}

		.container {
			margin-top: 20px;
		}

		.bubbles {
			position: absolute;
			top: 0;
			right: 0;
			width: 50%;
			height: 100%;
			pointer-events: none;
			/* Ensure it doesn't interfere with content */
			overflow: hidden;
		}

		.bubble {
			position: absolute;
			border-radius: 50%;
			background: rgba(158, 219, 112, 0.6);
			animation: bubbleAnimation 6s infinite;
		}

		/* Define bubble sizes and positions */
		.bubble:nth-child(1) {
			width: 20px;
			height: 20px;
			top: 10%;
			left: 10%;
		}

		.bubble:nth-child(2) {
			width: 15px;
			height: 15px;
			top: 30%;
			left: 20%;
		}

		.bubble:nth-child(3) {
			width: 25px;
			height: 25px;
			top: 50%;
			left: 15%;
		}

		.bubble:nth-child(4) {
			width: 10px;
			height: 10px;
			top: 20%;
			left: 60%;
		}

		.bubble:nth-child(5) {
			width: 18px;
			height: 18px;
			top: 70%;
			left: 50%;
		}

		.bubble:nth-child(6) {
			width: 22px;
			height: 22px;
			top: 40%;
			left: 80%;
		}

		.bubble:nth-child(7) {
			width: 12px;
			height: 12px;
			top: 60%;
			left: 20%;
		}

		.bubble:nth-child(8) {
			width: 16px;
			height: 16px;
			top: 80%;
			left: 70%;
		}

		.bubble:nth-child(9) {
			width: 14px;
			height: 14px;
			top: 30%;
			left: 40%;
		}

		.bubble:nth-child(10) {
			width: 20px;
			height: 20px;
			top: 50%;
			left: 30%;
		}

		.bubble:nth-child(11) {
			width: 15px;
			height: 15px;
			top: 20%;
			left: 50%;
		}

		.bubble:nth-child(12) {
			width: 18px;
			height: 18px;
			top: 80%;
			left: 10%;
		}

		.bubble:nth-child(13) {
			width: 22px;
			height: 22px;
			top: 10%;
			left: 70%;
		}

		.bubble:nth-child(14) {
			width: 12px;
			height: 12px;
			top: 50%;
			left: 10%;
		}

		.bubble:nth-child(15) {
			width: 16px;
			height: 16px;
			top: 30%;
			left: 80%;
		}

		.bubble:nth-child(16) {
			width: 20px;
			height: 20px;
			top: 60%;
			left: 60%;
		}

		.bubble:nth-child(17) {
			width: 15px;
			height: 15px;
			top: 40%;
			left: 30%;
		}

		.bubble:nth-child(18) {
			width: 18px;
			height: 18px;
			top: 70%;
			left: 80%;
		}

		.bubble:nth-child(19) {
			width: 22px;
			height: 22px;
			top: 20%;
			left: 90%;
		}

		.bubble:nth-child(20) {
			width: 12px;
			height: 12px;
			top: 80%;
			left: 40%;
		}

		@keyframes bubbleAnimation {
			0% {
				transform: translateY(0) scale(1);
				opacity: 1;
			}

			50% {
				transform: translateY(-50px) scale(1.5);
				opacity: 0.6;
			}

			100% {
				transform: translateY(0) scale(1);
				opacity: 1;
			}
		}
	</style>
</head>

<body>

	<div class="container">
		<?php
		class Personal_info
		{
			public $result;
			public $stmt;
			protected $conn;
			public function processingInfo()
			{
				try {
					if (isset($_SESSION["email"])) {
						$email = $_SESSION["email"];

						$this->conn = mysqli_connect("localhost", "root", "", "neric");

						if ($this->conn->connect_error) {
							die("Connection failed: " . $this->conn->connect_error);
						}

						$sql = "SELECT ud.id_db, ud.name_db, ud.gender_db, ud.date_of_birth_db, ud.phone_number_db, ud.address_db, 
            ud.pre_existing_conditions_db, ud.Previous_surgeries_hospitalizations_db, ud.Family_medical_history_db, 
            ud.allergies_db, ud.medication_current_db, ud.diet_db, ud.physical_activity_level_db, ud.height_db, 
            ud.weight_db, ud.current_symptoms_db, ud.user_image_db, ud.allegery_file_db, ud.select_goal, s.email_db
            FROM signup s
            JOIN userdetails_db ud ON s.id_db = ud.id_db
            WHERE s.email_db = ?";

						$this->stmt = $this->conn->prepare($sql);

						$this->stmt->bind_param("s", $email);

						$this->stmt->execute();

						$this->result = $this->stmt->get_result();
					}
				} catch (Exception $e) {
					echo "<div class='alert alert-danger' role='alert'><h3>Something Went Wrong!</h3></div>";
				}
			}
			public  function outputInfo()
			{
				$this->processingInfo();

				if ($this->result) {
					if ($this->result->num_rows > 0) {
						// Fetch the user details from the result set
						$row = $this->result->fetch_assoc();

						// Display the user details
						echo '<div class="cover-box">';
						echo '<div class="user-image-container">';
						echo '<img src="' . $row["user_image_db"] . '" alt="User Image" class="user-image">';
						echo '<h3>' . $row["name_db"] . '</h3>';
						echo '</div>';
						echo '<div class="user-info">';
						echo '<div class="bubbles">';
						// Generate bubble elements
						for ($i = 0; $i < 20; $i++) {
							echo '<div class="bubble"></div>';
						}
						echo '</div>';
						echo '<div class="report-content">';
						echo '<p><strong>Gender:</strong> ' . $row["gender_db"] . '</p>';
						echo '<p><strong>Date of Birth:</strong> ' . $row["date_of_birth_db"] . '</p>';
						echo '<p><strong>Email:</strong> ' . $row["email_db"] . '</p>';
						echo '<p><strong>Phone Number:</strong> ' . $row["phone_number_db"] . '</p>';
						echo '<p><strong>Address:</strong> ' . $row["address_db"] . '</p>';
						if (!empty($row["pre_existing_conditions_db"])) {
							echo '<p><strong>Pre-existing Conditions:</strong> ' . $row["pre_existing_conditions_db"] . '</p>';
						}
						if (!empty($row["Previous_surgeries_hospitalizations_db"])) {
							echo '<p><strong>Previous Surgeries or Hospitalizations:</strong> ' . $row["Previous_surgeries_hospitalizations_db"] . '</p>';
						}
						if (!empty($row["Family_medical_history_db"])) {
							echo '<p><strong>Family Medical History:</strong> ' . $row["Family_medical_history_db"] . '</p>';
						}
						if (!empty($row["allergies_db"])) {
							echo '<p><strong>Allergies:</strong> ' . $row["allergies_db"] . '</p>';
						}
						if (!empty($row["medication_current_db"])) {
							echo '<p><strong>Medications Currently Being Taken:</strong> ' . $row["medication_current_db"] . '</p>';
						}
						echo '<p><strong>Diet:</strong> ' . $row["diet_db"] . '</p>';
						echo '<p><strong>Physical Activity Level:</strong> ' . $row["physical_activity_level_db"] . '</p>';
						echo '<p><strong>Height:</strong> ' . $row["height_db"] . '</p>';
						echo '<p><strong>Weight:</strong> ' . $row["weight_db"] . '</p>';
						if (!empty($row["current_symptoms_db"])) {
							echo '<p><strong>Current Symptoms:</strong> ' . $row["current_symptoms_db"] . '</p>';
						} else {
							echo '<p><strong>Current Symptoms:</strong> Non </p>';
						}
						if (!empty($row["allegery_file_db"])) {
							echo '<p><strong>Allergy File:</strong> <a href="' . $row["allegery_file_db"] . '" target="_blank">View File</a></p>';
						}
						if (!empty($row["select_goal"])) {
							echo '<p><strong>Health Goals:</strong> ' . $row["select_goal"] . '</p>';
						}
						echo '</div>';
						echo '</div>';
						echo '</div>';
					} else {
						echo "<div class='alert alert-info' role='alert'><h3>No records found</h3></div>";
					}
				} else {
					echo "<div class='alert alert-danger' role='alert'><h3>Something Went Wrong!</h3></div>";
				}
			}
		}

		$personal_info = new Personal_info();
		$personal_info->outputInfo();
		?>
	</div>

</body>

</html>