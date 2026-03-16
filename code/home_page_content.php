<?php

try {

	include './config.php';

	if (isset($_SESSION["p_user_id_db"])) {
		$p_user_id_db = $_SESSION["p_user_id_db"];
	}
	$sql = "SELECT          
        ud.date_of_birth_db,ud.id_db,ud.gender_db,ud.name_db,
        ud.height_db,ud.address_db,ud.weight_db,ud.physical_activity_level_db,
        ud.weight_db, ud.select_goal, ud.weekly_user_routine_db,goal_weight_db
        FROM signup s
        JOIN userdetails_db ud ON s.id_db = ud.id_db
        WHERE s.email_db = ?";

	$stmt = $conn->prepare($sql);

	$stmt->bind_param("s", $email);

	$stmt->execute();

	$result = $stmt->get_result();

	if ($result) {
		if ($result->num_rows > 0) {
			// Fetch the user details from the result set
			$row = $result->fetch_assoc();
			$dateOfBirth = new DateTime($row["date_of_birth_db"]);
			$currentDate = new DateTime();
			$p_age = $currentDate->diff($dateOfBirth)->y;
			$p_current_weight = $row["weight_db"];
			$_SESSION["weight_db"] = $p_current_weight;
			$p_height = $row["height_db"];
			$_SESSION["height_db"] = $p_height;
			$p_address = $row["address_db"];
			$p_height_cm = $p_height * 30.48;
			$name = $row['name_db'];
			$p_user_id_db = $row["id_db"];
			$_SESSION["p_user_id_db"] = $p_user_id_db;
			$p_physical_acitivity = $row["physical_activity_level_db"];
			$p_user_goal = $row["select_goal"];
			$p_user_selected_rountine = $row["weekly_user_routine_db"];
			$p_extra = null;
			$p_reduce_calories = null;
			$p_user_calories_goal = 0;

			if ($p_user_goal == "gain_weight") {
				if (
					$p_user_selected_rountine == "453" ||
					$p_user_selected_rountine == "227"
				) {
					$p_extra = ($p_user_selected_rountine * 7.716179) / 7; // Extra calories for gaining weight
				} else {
					echo "<h2>Something Went Wrong!</h2>";
					exit();
				}
			}
			if ($p_user_goal == "loss_weight") {
				if (
					$p_user_selected_rountine == "453" ||
					$p_user_selected_rountine == "227" ||	$p_user_selected_rountine == "340"
				) {
					$p_reduce_calories  = ($p_user_selected_rountine * 7.716179) / 7; // Calories for lossing weight
				} else {
					echo "<h2>Something Went Wrong!</h2>";
					exit();
				}
			}

			if ($row["gender_db"] == "male") {
				$p_male_bmr =
					10 * $p_current_weight +
					6.25 * $p_height_cm -
					5 * $p_age +
					5;

				if ($p_physical_acitivity == "lightly_active") {
					$p_tdee = $p_male_bmr * 1.375;

					if ($p_user_goal == "gain_weight") {
						$p_user_calories_goal = round(
							$p_tdee + $p_extra,
							1
						);
					} elseif ($p_user_goal == "maintain_weight") {
						$p_user_calories_goal = round($p_tdee, 1);
					} else if ($p_user_goal == "loss_weight") {
						$p_user_calories_goal = round(
							$p_tdee -	$p_reduce_calories,
							1
						);

						$_SESSION["user_calories_goal"] = $p_user_calories_goal;
						exit;
					} else {
						echo "<div class='alert alert-warning d-flex justify-content-center' role='alert'><h3>Recreate,your account. </h3></div>";
						exit;
					}
				} elseif ($p_physical_acitivity == "moderately_active") {
					$p_tdee = $p_male_bmr * 1.55;

					if ($p_user_goal == "gain_weight") {
						$p_user_calories_goal = round(
							$p_tdee + $p_extra,
							1
						);
						$_SESSION["user_calories_goal"] = $p_user_calories_goal;
					} elseif ($p_user_goal == "maintain_weight") {
						$p_user_calories_goal = round($p_tdee, 1);
					} else if ($p_user_goal == "loss_weight") {

						$p_user_calories_goal = round(
							$p_tdee -	$p_reduce_calories,
							1
						);
						$_SESSION["user_calories_goal"] = $p_user_calories_goal;
					} else {
						echo "<div class='alert alert-warning d-flex justify-content-center' role='alert'><h3>Recreate,your account. </h3></div>";
						exit;
					}
				} elseif ($p_physical_acitivity == "very_active") {
					$p_tdee = $p_male_bmr * 1.725;

					if ($p_user_goal == "gain_weight") {
						$p_user_calories_goal = round(
							$p_tdee + $p_extra,
							1
						);
						$_SESSION["user_calories_goal"] = $p_user_calories_goal;
					} elseif ($p_user_goal == "maintain_weight") {
						$p_user_calories_goal = round($p_tdee, 1);
					} else if ($p_user_goal == "loss_weight") {

						$p_user_calories_goal = round(
							$p_tdee -	$p_reduce_calories,
							1
						);
						$_SESSION["user_calories_goal"] = $p_user_calories_goal;
					} else {
						echo "<div class='alert alert-warning d-flex justify-content-center' role='alert'><h3>Recreate,your account. </h3></div>";
						exit;
					}
				} else {
					echo "Not selected any physical rountine";
				}
			} elseif ($row["gender_db"] == "female") {
				$p_female_bmr =
					10 * $p_current_weight +
					6.25 * $p_height_cm -
					5 * $p_age -
					161;

				if ($p_physical_acitivity == "lightly_active") {
					$p_tdee = $p_female_bmr * 1.375;
					if ($p_user_goal == "gain_weight") {
						$p_user_calories_goal = round(
							$p_tdee + $p_extra,
							1
						);
						$_SESSION["user_calories_goal"] = $p_user_calories_goal;
					} elseif ($p_user_goal == "maintain_weight") {
						$p_user_calories_goal = round($p_tdee, 1);
					} else if ($p_user_goal == "loss_weight") {
						$p_user_calories_goal = round(
							$p_tdee -	$p_reduce_calories,
							1
						);

						$_SESSION["user_calories_goal"] = $p_user_calories_goal;
					} else {
						echo "<div class='alert alert-warning d-flex justify-content-center' role='alert'><h3>Recreate,your account. </h3></div>";
						exit;
					}
				} elseif ($p_physical_acitivity == "moderately_active") {
					$p_tdee = $p_female_bmr * 1.55;
					if ($p_user_goal == "gain_weight") {
						$p_user_calories_goal = round(
							$p_tdee + $p_extra,
							1
						);
						$_SESSION["user_calories_goal"] = $p_user_calories_goal;
					} elseif ($p_user_goal == "maintain_weight") {
						$p_user_calories_goal = round($p_tdee, 1);
					} else if ($p_user_goal == "loss_weight") {

						$p_user_calories_goal = round(

							$p_tdee -	$p_reduce_calories,
							1
						);

						$_SESSION["user_calories_goal"] = $p_user_calories_goal;
					} else {
						echo "<div class='alert alert-warning d-flex justify-content-center' role='alert'><h3>Recreate,your account. </h3></div>";
						exit;
					}
				} elseif ($p_physical_acitivity == "very_active") {
					$p_tdee = $p_female_bmr * 1.725;

					if ($p_user_goal == "gain_weight") {
						$p_user_calories_goal = round(
							$p_tdee + $p_extra,
							1
						);
						$_SESSION["user_calories_goal"] = $p_user_calories_goal;
					} elseif ($p_user_goal == "maintain_weight") {
						$p_user_calories_goal = round($p_tdee, 1);
					} else if ($p_user_goal == "loss_weight") {
						$p_user_calories_goal = round(
							$p_tdee -	$p_reduce_calories,
							1
						);

						$_SESSION["user_calories_goal"] = $p_user_calories_goal;
						exit;
					} else {
						echo "<div class='alert alert-warning d-flex justify-content-center' role='alert' style='display: inline-block; width: auto;'><h3>Recreate,your account. </h3></div>";
					}
				} else {
					echo "Not selected any physical rountine";
				}
			} else {
				echo "<h2 class='text-center'>We were working on your gender. </h2";
				exit;
			}
		}
	}

	$p_total_daily_calories = 0;

	$sql = "SELECT 
              
   ud.daily_burn_calories,ud.date
    FROM userdetails_db s
    JOIN  daily_burn_calories ud ON s.id_db = ud.calories_user_id_db
    WHERE s.id_db = ? and ud.date=CURDATE()";

	$stmt = $conn->prepare($sql);

	$stmt->bind_param("s", $p_user_id_db);

	$stmt->execute();

	$result = $stmt->get_result();

	if ($result) {
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$p_daily_calories = $row["daily_burn_calories"];
				$p_total_daily_calories += $p_daily_calories;
			}
		}
	}
	$p_total_consumption_daily_calories = 0;

	$sql = "SELECT 
              
   ud.food_calories_db,ud.date_db
    FROM userdetails_db s
    JOIN  food ud ON s.id_db = ud.user_id_db 
    WHERE s.id_db = ? and  ud.date_db= CURDATE() ";

	$stmt = $conn->prepare($sql);

	$stmt->bind_param("s", $p_user_id_db);

	$stmt->execute();

	$result = $stmt->get_result();

	if ($result) {
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$p_consumption_daily_calories = $row["food_calories_db"];
				$_SESSION["p_consumption_daily_calories"] = $p_consumption_daily_calories;

				$p_total_consumption_daily_calories += $p_consumption_daily_calories;
			}
		}
	}
} catch (Exception $e) {
	echo "<div class='alert alert-danger' role='alert'><h3>Something Went Wrong!</h3></div>";
}
