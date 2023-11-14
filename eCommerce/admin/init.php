<?php
include 'connect.php';

// Routes
$tpl = 'includes/templates/'; //Template Directory
$lang = 'includes/languages/'; //Language Direcctory
$css = 'layout/css/'; //CSS Directory
$js = 'layout/js/'; //JS Directory
$func = 'includes/functions/';

include $lang . 'english.php';
include $func . 'functions.php';
include $tpl . 'header.php';

if (!isset($noNavbar)) {
  include $tpl . 'navbar.php';
}