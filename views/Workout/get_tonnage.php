<?php
session_start();

require_once '../../models/WorkoutController.php';
require_once '../../models/Workout.php';
require_once  '../../includes/Database.php';
require_once  '../../models/Account.php';

use fitnessTracker\includes\Database as Database;
use fitnessTracker\Models\Account\Account as Account;


if (!isset($_SESSION["id"])) {
    header("Location: ../Login/Login.php");
}

$accountid = (int)$_SESSION['id'];


$total_tonnage = 0;

$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];


$dbcon = Database::GetDb();
$workout_db = new WorkoutController($dbcon);
$workouts = $workout_db->listWorkoutsByDate($accountid, $start_date, $end_date);

foreach ($workouts as $workout) {



    if ($workout['cardio'] === '0' && $workout['body_weight'] === '0') {
        $tonnage = (int)$workout['reps'] * (int)$workout['sets'] * (int)$workout['weight'];
        $total_tonnage += $tonnage;
    } elseif ($workout['cardio'] === '0' && $workout['body_weight'] === '1') {
        if (isset($body_weight)) {
            $tonnage = (int)$workout['reps'] * (int)$workout['sets'] * $body_weight;
            $total_tonnage += $tonnage;
        } else {
            $tonnage = '';
        }
    } else {
        $tonnage = '';
    }

}
if (!empty($total_tonnage)) {
    $unit = 'Ton';

    if ($total_tonnage > 1) {
        $unit .= 's';
    }
} else {
    $unit = '';
}

$total_tons = ((int)$total_tonnage / 2000);
$output = $total_tons . ' ' . $unit;
$json_obj = array($output , (int)$total_tonnage);

$jsonstu = json_encode($json_obj);

header('Content-Type: Application/json');
echo $jsonstu;
