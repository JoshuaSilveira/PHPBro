<?php
use fitnessTracker\includes\Database;
use fitnessTracker\models\Exercise;

require_once '../../includes/Database.php';
require_once '../../models/Exercise.php';

//if someone isn't signed in, redirect them to the login
if (!isset($_SESSION["id"])) {
    header("Location: ../Login/Login.php");
}

//if delete command has been submitted
if(isset($_POST['id'])){
    $id= $_POST['id'];
    $dbcon = Database::GetDb();
    $ex = new Exercise();
    $exercise = $ex->Delete($dbcon, $id);
}
?>