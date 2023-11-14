<?php
// set page title
function getTitle()
{
  global $pageTitle;
  isset($pageTitle) ? $pageTitle = $pageTitle : $pageTitle = 'default';
  echo $pageTitle;
}

// Redirect function
function redirectHome($errorMsg, $second = 3)
{
  echo '<div class="container mt-2">';
  echo '<div class="alert alert-danger">' . $errorMsg . '</div>';
  echo '<div class="alert alert-info"> You will be redirect to Homepage after ' . $second . ' seconds.</div>';
  echo '</div>';

  header("refresh:$second;url=dashboard.php");
  exit;
}