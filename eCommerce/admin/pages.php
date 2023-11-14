<?php
$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

if ($do == 'Manage') {
  echo 'Welcome you are in manage category page';
} elseif ($do == 'Add') {
  echo 'Welcome you are in Add category page';
} elseif ($do == 'Insert') {
  echo 'Welcome you are in Insert category page';
} else {
  echo 'Error';
}