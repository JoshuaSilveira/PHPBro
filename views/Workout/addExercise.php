<?php
session_start();
require_once '../../models/WorkoutController.php';
require_once '../../models/Workout.php';
require_once  '../../includes/Database.php';

use fitnessTracker\includes\Database as Database;

if (!isset($_SESSION["id"])) {
    header("Location: ../Login/Login.php");
}

$is_admin = $_SESSION['is_admin'];

if(isset($_POST['addExercise'])) {
    $name = $_POST['ExerciseName'];
    $body_weight = $_POST['exercise_bodyweight'];
    $cardio = $_POST['exercise_cardio'];


    $dbcon = Database::GetDb();
    $workout_db = new WorkoutController($dbcon);
    $workouts = $workout_db->addExercise($name, $body_weight, $cardio);
} else {
    header('Location: listWorkouts.php');
}
