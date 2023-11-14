<?php
session_start();

if (isset($_SESSION['Username'])) {
  $pageTitle = 'Dashboard';
  include 'init.php';
  //page
  include $tpl . 'footer.php';
} else {
  header('location: login.php');
  exit();
}