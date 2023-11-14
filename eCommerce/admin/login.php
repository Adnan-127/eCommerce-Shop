<?php
session_start();
$noNavbar = '';
$pageTitle = 'Login';
if (isset($_SESSION['Username'])) {
  header('Location: dashboard.php'); //redirect to dashboard page
}

include 'init.php';

// check if the user coming from HTTP Post request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $hasedPass = sha1($password);

  // check if the user exist in database
  $stmt = $con->prepare("SELECT Username, UserID, Password FROM users WHERE Username = ? AND Password = ? AND GroupID=1 LIMIT 1");
  $stmt->execute(array($username, $hasedPass));
  $count = $stmt->rowCount();
  $row = $stmt->fetch();

  if ($count > 0) {
    $_SESSION['Username'] = $username;
    $_SESSION['UserID'] = $row['UserID'];
    header('Location: dashboard.php'); //redirect to dashboard page
    exit();
  }
}
?>

<div class="login-page">
  <div class="login-container container">
    <div class="form-container row bg-white justify-content-center py-5">
      <form action="<?php echo $_SERVER['PHP_SELF'] ?>" class="login-form col-sm-9 col-11" method="POST">
        <h3 class="text-center text-primary mb-5 fw-bold">Admin Login</h3>
        <input type="text" class="form-control mb-3" name="username" placeholder="Username" autocomplete="off">
        <input type="password" class="form-control mb-3" name="password" placeholder="Password" autocomplete="off">
        <input type="submit" class="btn btn-primary w-100" value="Login">
      </form>
    </div>
  </div>
</div>


<?php include $tpl . 'footer.php'; ?>