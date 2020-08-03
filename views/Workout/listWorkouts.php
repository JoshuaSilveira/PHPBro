<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}



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
$is_admin = (int)$_SESSION['is_admin'];



$total_tonnage = 0;


$dbcon = Database::GetDb();
$workout_db = new WorkoutController($dbcon);
$workouts = $workout_db->listWorkouts($accountid, $is_admin);
$user = new Account();

//set a flag for the first rendering cycle of the loop to toggle off the first comparitor setting

$first_cycle = true;
$min_date = $max_date = '';
$body_weight = (int)$user->GetById($dbcon, $accountid)['current_weight'];

$exercises = $workout_db->getExercises();

//Master Layout Header
require_once "../Master/header.php";
?>
<div class="container">
    <div class="container mt-2 mb-2"><a href="../Exercise/List.php" class="btn btn-primary">List Exercises</a></div>
    <table class="table table-dark">
        <thead>
        <tr>
            <?php if($is_admin === 1) { ?>
                <th scope="col">Account ID</th>
            <?php } ?>
            <th scope="col">Date</th>
            <th scope="col">Exercise Name</th>
            <th scope="col">Reps</th>
            <th scope="col">Sets</th>
            <th scope="col">Weight</th>
            <th scope="col">Duration</th>
            <th scope="col">Calories Burned</th>
            <th scope="col">Tonnage</th>
            <th scope="col">Update</th>
            <th scope="col">Delete</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($workouts as $workout) {
            //set the comparitor values for the date range of the tonnage calculation
            if($first_cycle)
            {
                $min_date = $workout['date'];
                $max_date = $workout['date'];
                $first_cycle = false;
            }
            else //when it's not the first pass, check the min and max against the current date to set the boundaries
            {
                if($workout['date'] < $min_date)
                {
                    $min_date = $workout['date'];
                }
                if($workout['date'] > $max_date)
                {
                    $max_date = $workout['date'];
                }
            }

            if($workout['cardio'] === '0' && $workout['body_weight'] === '0')
            {
                $tonnage = (int)$workout['reps'] * (int)$workout['sets'] * (int)$workout['weight'];
                $total_tonnage += $tonnage;
            }
            elseif ($workout['cardio'] === '0' && $workout['body_weight'] === '1')
            {
                if(isset($body_weight))
                {
                    $tonnage = (int)$workout['reps'] * (int)$workout['sets'] * $body_weight;
                    $total_tonnage += $tonnage;
                }
                else
                {
                    $tonnage = '';
                }
            }
            else
            {
                $tonnage = '';
            }
            if(!empty($tonnage))
            {
                $unit = 'Pound';

                if($tonnage > 1)
                {
                    $unit .= 's';
                }
            }
            else
            {
                $unit = '';
            }

            ?>
            <tr>
                <?php if($is_admin === 1) { ?>
                    <td><?= $workout['account_id'] ?></td>
                <?php } ?>
                <td><?= substr($workout['date'], 0, 10) ?></td>
                <td><?= $workout['name'] ?></td>
                <td><?= $workout['reps'] ?></td>
                <td><?= $workout['sets'] ?></td>
                <td><?= $workout['weight'] ?></td>
                <td><?= $workout['duration'] ?></td>
                <td><?= round((int)$workout['duration'] * ((6 * 3.5 * ($body_weight / 2.2046)) / 200), 1) ?></td>
                <td><?= $tonnage . ' ' . $unit ?> </td>
                <td>
                    <form action="updateWorkout.php" method="post">
                        <input type="hidden" name="workoutid" value="<?= $workout['id'] ?>"/>
                        <input type="submit" class="button btn btn-primary" name="updateWorkout" value="Update"/>
                    </form>
                </td>
                <td>
                    <form action="deleteWorkout.php" method="post">
                        <input type="hidden" name="workoutid" value="<?= $workout['id'] ?>"/>
                        <input type="submit" class="button btn btn-danger" name="deleteWorkout" value="Delete"/>
                    </form>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <div>
        <div id="tonnage" class="h3">Total Tonnage: <?= $total_tonnage / 2000 ?> tons </div>
        <div id="compare" class="h4"></div>
        <form class="form-inline">
            <label for="start_date">From: </label>
            <input type="date" class="form-control date"  id="start_date" name="start_date" value="<?= substr($min_date, 0, 10); ?>" min="<?= substr($min_date, 0, 10); ?>" max="<?= substr($max_date, 0, 10); ?>" >
            <label for="end_date">Until: </label>
            <input type="date" class="form-control date" id="end_date" name="end_date" value="<?= substr($max_date, 0, 10); ?>" min="<?= substr($min_date, 0, 10); ?>" max="<?= substr($max_date, 0, 10); ?>" >
        </form>
    </div>

    <script>
        $('.date').change(function(){
            start_date = $('#start_date').val();
            end_date = $('#end_date').val();
            $("#end_date").prop("min", start_date); //change the min value so that the end date cannot be less than the start date
            $("#start_date").prop("max", end_date);
            $.post('./get_tonnage.php', { "start_date": start_date, "end_date": end_date}, function(new_tonnage){
                $("#tonnage").html('Total Tonnage: ' + new_tonnage[0]);
                weight_check(new_tonnage[1]);
            })
        });

        function weight_check(weight) {
            $.post('./get_comparison.php', { "weight": weight}, function(weight_comparison){
                $("#compare").html(weight_comparison);
            })
        }
        weight_check(<?= $total_tonnage ?>);
    </script>
    <a href="addWorkout.php" id="btn_addWorkout" class="btn btn-success btn-lg float-right">Add Workout</a>

</div>
<?php
//Master Layout Footer
require_once "../Master/footer.php";
?>
