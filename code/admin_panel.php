<?php
session_start();

// Logout Logic
if (isset($_POST['logout'])) {
  unset($_SESSION['admin_loggedin']);
  session_destroy();
  header('Location: ../index.php');
  exit();
}

// First Visit Logic
if (!isset($_SESSION['visited'])) {
  $_SESSION['visited'] = true;
  header('Location: ../index.php');
  exit();
}

// Admin Authentication Check
if (!isset($_SESSION['admin_loggedin'])) {
  header('Location: login.php');
  exit();
}


include '../config.php';

// Get total users based on the status
function getTotalUsers($status)
{
  global $conn;
  $query = "SELECT COUNT(*) AS total_users FROM signup WHERE status_db='$status'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
  return $row['total_users'];
}

$status = isset($_GET['status_db']) ? $_GET['status_db'] : 'active';
$total_users = getTotalUsers($status);
$_SESSION["user"] = $total_users;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['deactivate'])) {
    $email = mysqli_real_escape_string($conn, $_POST['deactivate']);
    $deactivateQuery = "UPDATE signup SET status_db='deactivated' WHERE email_db='$email'";
    if (!mysqli_query($conn, $deactivateQuery)) {
      echo "Error deactivating user with email '$email': " . mysqli_error($conn);
    }
    header('Location: admin_panel.php?status=active');
    exit();
  } elseif (isset($_POST['activate'])) {
    $email = mysqli_real_escape_string($conn, $_POST['activate']);
    $activateQuery = "UPDATE signup SET status_db='active' WHERE email_db='$email'";
    if (!mysqli_query($conn, $activateQuery)) {
      echo "Error activating user with email '$email': " . mysqli_error($conn);
    }
    header('Location: admin_panel.php?status=deactivated');
    exit();
  }
}

$user_query = "SELECT email_db FROM signup WHERE status_db='$status'";
$user_result = mysqli_query($conn, $user_query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
  <style>
    /* Sidebar */
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      width: 250px;
      background-color: #343a40;
      color: #fff;
      padding-top: 56px;
      overflow-y: auto;
    }

    .sidebar-brand {
      font-size: 1.5rem;
      font-weight: bold;
      text-align: center;
      margin-bottom: 20px;
    }

    .sidebar-brand-icon {
      font-size: 1.75rem;
      margin-right: 5px;
    }

    .sidebar-brand-text {
      font-size: 1rem;
    }

    .nav-item {
      padding: 0.5rem 1rem;
    }

    .nav-item:hover {
      background-color: #495057;
    }

    .active.nav-item {
      background-color: #007bff;
    }

    .nav-link {
      color: #fff;
    }

    /* End of Sidebar */

    /* Content Wrapper */
    #content-wrapper {
      margin-left: 250px;
      padding: 20px;
    }

    /* Total Users Box */
    .total-users {
      background-color: #f8f9fa;
      padding: 20px;
      border-radius: 5px;
      margin-bottom: 20px;
    }
  </style>
</head>

<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="./home-page.php">
      <img src="./images/nericlogo.jpg" alt="Logo" class="rounded-circle" width="90" height="90">
    </a>
    <hr class="sidebar-divider my-0">
    <!-- Nav Item - Dashboard -->
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./statistics.php">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Statistics</span>
        </a>
      </li>

    </ul><br>

    <!-- Logout Button -->
    <div class="logout-button ml-5">
      <form method="post">
        <button class="btn btn-danger" name="logout">Logout</button>
      </form>
    </div>
    <!-- End of Logout Button -->
  </div>
  <!-- End of Sidebar -->

  <!-- Content Wrapper -->
  <div id="content-wrapper">
    <!-- Main Content -->
    <div id="content">
      <!-- Begin Page Content -->
      <div class="container-fluid">
        <!-- Middle Content -->
        <div class="middle-content">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="total-users">
              <p><b>Total number of <?php echo $status; ?> users: <?php echo $total_users; ?></b></p>

            </div>
            <!-- Search Bar -->
            <form class="form-inline">
              <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" id="searchInput">
              <button class="btn btn-outline-success my-2 my-sm-0" type="button" onclick="search()">Search</button>
            </form>
          </div>
          <a href="admin_panel.php?status_db=active" class="btn btn-success mb-3">Active Users</a>
          <a href="admin_panel.php?status_db=deactivated" class="btn btn-danger mb-3">Deactivated Users</a>
        </div>

        <!-- User List Table -->
        <div class="container">
          <div class="row">
            <div class="col">
              <div class="table-responsive">
                <form method="post">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th class="text-center">User Email</th>
                        <th class="text-center">Select</th>
                        <th class="text-center">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if ($user_result && mysqli_num_rows($user_result) > 0) {
                        while ($row = mysqli_fetch_assoc($user_result)) {
                          echo '<tr>
                         <td class="text-center"><i class="fas fa-user"></i>&nbsp' . $row["email_db"] . '</td>
                           <td class="text-center"><input type="checkbox" name="selectedEmails[]" value="' . $row["email_db"] . '"></td>
                            <td class="text-center">';
                          if ($status == 'active') {
                            echo '<button type="submit" name="deactivate" class="btn btn-danger btn-sm" value="' . $row["email_db"] . '">Deactivate</button>';
                          } else {
                            echo '<button type="submit" name="activate" class="btn btn-success btn-sm" value="' . $row["email_db"] . '">Activate</button>';
                          }
                          echo '</td>
                                                          </tr>';
                        }
                      } else {
                        echo '<tr><td colspan="3">No users found</td></tr>';
                      }
                      ?>
                    </tbody>
                  </table>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- End of User List Table -->
      </div>
    </div>
    <!-- End of Middle Content -->

    <script>
      document.getElementById("searchInput").addEventListener("input", search);

      function search() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.querySelector(".table");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 1; i < tr.length; i++) {
          td = tr[i].getElementsByTagName("td")[0];
          if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
              tr[i].style.display = "";
            } else {
              tr[i].style.display = "none";
            }
          }
        }
      }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  </div>
  <!-- End of Page Content -->
  </div>
  <!-- End of Main Content -->
  </div>
  <!-- End of Content Wrapper -->
</body>

</html>

<?php
// Close the connection
mysqli_close($conn);
?>