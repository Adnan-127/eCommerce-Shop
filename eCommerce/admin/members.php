<?php
session_start();
if (isset($_SESSION['Username'])) {
  $pageTitle = 'Members';

  include 'init.php';
  $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
  if ($do == 'Manage') {
    // Manage Page
    $stmt = $con->prepare('SELECT * From users WHERE GroupID !=1');
    $stmt->execute();
    $rows = $stmt->fetchAll();
    ?>
    <h1 class="edit-heading text-center fw-bold my-4">Manage Members</h1>
    <div class="container">
      <div class="table-responsive">
        <table class="table table-striped table-bordered text-center">
          <thead class="bg-dark text-light">
            <tr>
              <th scope="col">#ID</th>
              <th scope="col">Username</th>
              <th scope="col">Email</th>
              <th scope="col">Full Name</th>
              <th scope="col">Registered Date</th>
              <th scope="col">Control</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($rows as $row) {
              echo "<tr>";
              echo '<td>' . $row['UserID'] . '</td>';
              echo '<td>' . $row['Username'] . '</td>';
              echo '<td>' . $row['Email'] . '</td>';
              echo '<td>' . $row['FullName'] . '</td>';
              echo '<td>' . '</td>';
              echo '
                <td>
                  <a class="btn btn-success btn-sm mb-2 mb-md-0" href = "members.php?do=Edit&userid=' . $row['UserID'] . '">Edit</a>
                  <a class="btn btn-danger btn-sm confirm" href = "members.php?do=Delete&userid=' . $row['UserID'] . '">Delete</a>
                </td>';
              echo "</tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
      <a href="members.php?do=Add" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Member</a>
    </div>
    <?php

  } elseif ($do == 'Add') {
    // Add Page
    ?>
    <!-- member add form -->
    <h1 class="edit-heading text-center fw-bold my-4">Add New Member</h1>
    <div class="container">
      <div class="edit-form row justify-content-center">
        <form class="col-10" action="?do=Insert" method="POST">
          <div class="row my-md-5">
            <div class="col-md-6">
              <div class="form-group my-md-0 my-2">
                <label for="username" class="fw-bold mb-2">Username</label>
                <div class="input-group">
                  <div class="input-group-text">
                    <i class="fa-solid fa-user"></i>
                  </div>
                  <input id="username" name="username" type="text" class="form-control" autocomplete="off" required="true">
                </div>
              </div>
            </div>
            <div class=" col-md-6">
              <div class="form-group my-md-0 my-2">
                <label for="password" class="fw-bold mb-2">Password</label>
                <div class="input-group">
                  <div class="input-group-text">
                    <i class="fa-solid fa-key"></i>
                  </div>
                  <input id="password" name="password" type="password" class="form-control password" required="true">
                  <i class="fa-regular fa-eye showPass"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="row my-md-5 mb-md-3">
            <div class="col-md-6">
              <div class="form-group my-md-0 my-2">
                <label for="email" class="fw-bold mb-2">Email</label>
                <div class="input-group">
                  <div class="input-group-text">
                    <i class="fa-solid fa-envelope"></i>
                  </div>
                  <input id="email" name="email" type="email" class="form-control" required="true">
                </div>
              </div>
            </div>
            <div class=" col-md-6">
              <div class="form-group my-md-0 my-2">
                <label for="fullName" class="fw-bold mb-2">Full Name</label>
                <div class="input-group">
                  <div class="input-group-text">
                    <i class="fa-solid fa-pen-to-square"></i>
                  </div>
                  <input id="fullName" name="full" type="text" class="form-control" required="true">
                </div>
              </div>
            </div>
          </div>
          <input type="submit" value="Add Member" class="btn btn-primary fw-bold">
        </form>
      </div>
    </div>
    <?php
  } elseif ($do == 'Insert') {
    // Insert Page
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $user = $_POST['username'];
      $email = $_POST['email'];
      $name = $_POST['full'];
      $pass = $_POST['password'];
      $hashPass = sha1($pass);

      //Validate The Form
      $formErrors = array();

      if (strlen($user) < 4) {
        $formErrors[] = 'Username can\'t be less than 4 characters';
      }
      if (strlen($user) > 20) {
        $formErrors[] = 'Username can\'t be More than 20 characters';
      }
      if (empty($user)) {
        $formErrors[] = 'Username can\'t be empty';
      }
      if (empty($email)) {
        $formErrors[] = 'Email can\'t be empty';
      }
      if (empty($name)) {
        $formErrors[] = 'Full Name can\'t be empty';
      }
      if (empty($pass)) {
        $formErrors[] = 'Password can\'t be empty';
      }

      //Update The Informations in The Database
      echo '<div class = "container mt-3">';
      if (empty($formErrors)) {
        $stmt = $con->prepare('INSERT INTO users(Username, Password, Email, FullName) VALUES(:zuser, :zpassword, :zemail, :zname)');
        $stmt->execute(
          array(
            'zuser' => $user,
            'zpassword' => $hashPass,
            'zemail' => $email,
            'zname' => $name
          )
        );
        $count = $stmt->rowCount();
        echo '<div class="alert alert-success">' . $count . ' Record Inserted' . '</div>';
      } else {
        foreach ($formErrors as $error) {
          echo '<div class="alert alert-danger">' . $error . '</div>';
        }
        echo '</div>';
      }
    } else {
      $errorMsg = 'Sorry you can\'t browse this page directly';
      redirectHome($errorMsg, 5);
    }
  } elseif ($do == 'Edit') {
    // Edit Page
    $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

    $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
    $stmt->execute(array($userid));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();

    if ($count > 0) {
      ?>
      <!-- member form edit -->
      <h1 class="edit-heading text-center fw-bold my-4">Edit Member</h1>
      <div class="container">
        <div class="edit-form row justify-content-center">
          <form class="col-lg-10" action="?do=Update" method="POST">
            <input type="hidden" name="userid" value="<?php echo $_GET['userid'] ?>">
            <div class="row my-md-5">
              <div class="col-md-6">
                <div class="form-group my-md-0 my-2">
                  <label for="username" class="fw-bold mb-2">Username</label>
                  <div class="input-group">
                    <div class="input-group-text">
                      <i class="fa-solid fa-user"></i>
                    </div>
                    <input id="username" name="username" type="text" class="form-control"
                      value="<?php echo $row['Username'] ?>" autocomplete="off" required="true">
                  </div>
                </div>
              </div>
              <div class=" col-md-6">
                <div class="form-group my-md-0 my-2">
                  <label for="password" class="fw-bold mb-2">Password</label>
                  <div class="input-group">
                    <div class="input-group-text">
                      <i class="fa-solid fa-key"></i>
                    </div>
                    <input name="old-password" type="hidden" value="<?php echo $row['Password'] ?>">
                    <input id="password" name="new-password" type="password" class="form-control">
                  </div>
                </div>
              </div>
            </div>
            <div class="row my-md-5 mb-md-3">
              <div class="col-md-6">
                <div class="form-group my-md-0 my-2">
                  <label for="email" class="fw-bold mb-2">Email</label>
                  <div class="input-group">
                    <div class="input-group-text">
                      <i class="fa-solid fa-envelope"></i>
                    </div>
                    <input id="email" name="email" type="email" class="form-control" value="<?php echo $row['Email'] ?>"
                      required="true">
                  </div>
                </div>
              </div>
              <div class=" col-md-6">
                <div class="form-group my-md-0 my-2">
                  <label for="fullName" class="fw-bold mb-2">Full Name</label>
                  <div class="input-group">
                    <div class="input-group-text">
                      <i class="fa-solid fa-pen-to-square"></i>
                    </div>
                    <input id="fullName" name="full" type="text" class="form-control" value="<?php echo $row['FullName'] ?>"
                      required="true">
                  </div>
                </div>
              </div>
            </div>
            <input type="submit" value="save" class="btn btn-primary fw-bold">
          </form>
        </div>
      </div>
      <?php
    } else {
      $errorMsg = 'Sorry, there is no member with this ID';
      redirectHome($errorMsg, 5);
    }

  } elseif ($do == 'Update') {
    // Update Page
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $id = $_POST['userid'];
      $user = $_POST['username'];
      $email = $_POST['email'];
      $name = $_POST['full'];

      // password trick
      $pass = '';
      if (empty($_POST['new-password'])) {
        $pass = $_POST['old-password'];
      } else {
        $pass = sha1($_POST['new-password']);
      }

      //Validate The Form
      $formErrors = array();

      if (strlen($user) < 4) {
        $formErrors[] = 'Username can\'t be less than 4 characters';
      }
      if (strlen($user) > 20) {
        $formErrors[] = 'Username can\'t be More than 20 characters';
      }
      if (empty($user)) {
        $formErrors[] = 'Username can\'t be empty';
      }
      if (empty($email)) {
        $formErrors[] = 'Email can\'t be empty';
      }
      if (empty($name)) {
        $formErrors[] = 'Full Name can\'t be empty';
      }

      //Update The Informations in The Database
      echo '<div class = "container mt-3">';
      if (empty($formErrors)) {
        $stmt = $con->prepare('UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ?');
        $stmt->execute(array($user, $email, $name, $pass, $id));
        $count = $stmt->rowCount();
        echo '<div class="alert alert-success">' . $count . ' Record Updated' . '</div>';
      } else {
        foreach ($formErrors as $error) {
          echo '<div class="alert alert-danger">' . $error . '</div>';
        }
        echo '</div>';
      }
    } else {
      $errorMsg = 'Sorry you can\'t browse this page directly';
      redirectHome($errorMsg, 5);
    }
  } elseif ($do == 'Delete') {
    // Delete Page
    $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

    $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
    $stmt->execute(array($userid));
    $count = $stmt->rowCount();
    if ($count > 0) {
      $stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");
      $stmt->bindParam(':zuser', $userid);
      $stmt->execute();
      $count = $stmt->rowCount();
      echo '<div class="container mt-3">';
      echo '<div class="alert alert-success">' . $count . ' Record Deleted' . '</div>';
      echo '</div>';
    } else {
      $errorMsg = 'Sorry this ID is not exist';
      redirectHome($errorMsg, 5);
    }
  }

  include $tpl . 'footer.php';
} else {
  header('location: login.php');
  exit();
}