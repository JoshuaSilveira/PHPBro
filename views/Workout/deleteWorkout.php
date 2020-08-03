<?php
session_start();
require_once '../../models/WorkoutController.php';
require_once '../../models/Workout.php';
require_once  '../../includes/Database.php';

use fitnessTracker\includes\Database as Database;

if (!isset($_SESSION["id"])) {
    header("Location: ../Login/Login.php");
}

if(isset($_POST['workoutid'])){
    $id = $_POST['workoutid'];

    $workout_db = new WorkoutController(Database::getDb());

    $workouts = $workout_db->deleteEntry($id);


}