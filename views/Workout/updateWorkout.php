<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../../models/WorkoutController.php';
require_once '../../models/Workout.php';
require_once  '../../includes/Database.php';

use fitnessTracker\includes\Database as Database;

function input_pattern_validator($input, $pattern, &$error_location, $is_valid) {

    if($input == '') {
        $error_location = "Required field";
        return false;
    } else if (!preg_match($pattern, $input)) { //if the input doesn't match the pattern, show a different error
        $error_location = 'Invalid input';
        return false;
    } else if(!$is_valid)
    {
        return false;
    }
    else {
        return true;
    }
}


if (!isset($_SESSION["id"])) {
    header("Location: ../Login/Login.php");
}

$exercise_id = $reps = $reps_error = $sets = $sets_error = $weight = $weight_error = $duration = $duration_error = $date = $date_error = $cardio_div = $cardio_disabled = $bodyweight_div = $bodyweight_disabled = "";

$accountid = (int)$_SESSION['id'];
$isadmin = (int)$_SESSION['is_admin'];


$dbcon = Database::GetDb();
$workout_db = new WorkoutController($dbcon);


$exercises = $workout_db->getExercises();

if(isset($_POST['updateWorkout']))
{
    $workoutid = $_POST['workoutid'];





//    $accountid = (int)$_SESSION['id'];
//    $isadmin = (int)$_SESSION['is_admin'];

    $workout = $workout_db->getEntry($workoutid);

    if($isadmin === 1)
    {
        $users = $workout_db->getAccounts();
    }


    $exercise_id = $workout->exercise_id;
    $reps = $workout->reps;
    $sets = $workout->sets;
    $weight = $workout->weight;
    $account_id = $workout->account_id;
    $duration = $workout->duration;
    $date = $workout->date;
    $date = substr($date, 0, 10);

}

if(isset($_POST['uptWorkout']))
{


    $is_valid = true;
    $workoutid = $_POST['id'];

    $workout = $workout_db->getEntry($workoutid);

    $accid = $_POST['accountid'];
    $exercise_id = $_POST['exercisename'];
    $duration = $_POST['duration'];
    $is_valid = input_pattern_validator($_POST['duration'], "/^[1-9][0-9]*/", $duration_error, $is_valid);
    if($_POST['is_bodyweight'] === '1')
    {
        $weight = 0;
    } else {
        $weight = $_POST['weight'];
        $is_valid = input_pattern_validator($_POST['weight'], "/^[1-9][0-9]*/", $weight_error, $is_valid);
    }
    if($_POST['is_cardio'] === '1')
    {
        $reps = 0;
        $sets = 0;
    } else {
        $reps = $_POST['reps'];
        $is_valid = input_pattern_validator($_POST['reps'], "/^[1-9][0-9]*/", $reps_error, $is_valid);
        $sets = $_POST['sets'];
        $is_valid = input_pattern_validator($_POST['sets'], "/^[1-9][0-9]*/", $sets_error, $is_valid);
    }
    $date = $_POST['date'];
    $is_valid = input_pattern_validator($_POST['date'], "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date_error, $is_valid);
    $id = $_POST['id'];

    if($is_valid)
    {

        $dbcon = Database::GetDb();
        $workout_db = new WorkoutController($dbcon);

        $new_workout = ['id'=>$id, 'account_id'=>$accid, 'exercise_id'=>$exercise_id, 'duration'=>$duration, 'date'=>$date, 'is_bodyweight'=>$_POST['is_bodyweight'], 'is_cardio'=>$_POST['is_cardio']];

        if($_POST['is_bodyweight'] === '0')
        {
            $new_workout['weight'] = $weight;
        }

        if($_POST['is_cardio'] === '0')
        {
            $new_workout['reps'] = $reps;
            $new_workout['sets'] = $sets;
        }


        $workouts = $workout_db->updateWorkout($new_workout);
    }
}

//Master Layout Header
require_once "../Master/header.php";
?>

<div class="jumbotron bg-primary text-white"><div class="h1 text-center">Update Workout</div></div>

<div class="container bg-dark text-white pb-2">
    <form action="" method="post">
        <?php if ($isadmin === 1) { ?>
            <div class="form-group">
                <label for="accountid">Account :</label>
                <select class="form-control" id="accountid" name="accountid">
                    <?php foreach ($users as $user) { ?>
                        <option value="<?= $user['id'] ?>" <?php if($user['id'] === $account_id) {echo 'selected';} ?>>ID: <?= $user['id'] ?> username: <?= $user['username'] ?></option>
                    <?php } ?>
                </select>
            </div>
        <?php } else { ?>
            <input class="d-none" value="<?= $accountid ?>" name="accountid" />
        <?php } ?>

        <input id="id" name="id" class="d-none" value="<?= $workoutid ?>" />

        <div class="form-group">
            <label for="exercisename">Exercise Name :</label>
            <select class="form-control" id="exercisename" name="exercisename">
                <?php foreach ($exercises as $exercise) {
                    if($exercise['id'] === $exercise_id) {
                        $is_cardio = $exercise['cardio'];
                        $is_bodyweight = $exercise['body_weight'];
                        if($is_cardio === '1'){
                            $cardio_div = 'd-none';
                            $cardio_disabled = 'disabled';
                        }
                        if($is_bodyweight === '1'){
                            $bodyweight_div = 'd-none';
                            $bodyweight_disabled = 'disabled';
                        }

                    }
                    ?>
                    <option <?php if($exercise['id'] === $exercise_id) {echo 'selected';} ?> class="body_weight<?= $exercise['body_weight'] ?> cardio<?= $exercise['cardio'] ?>" value="<?= $exercise['id'] ?>"><?= $exercise['name'] ?></option>
                <?php } ?>
            </select>
            <div class="button btn btn-success m-2" data-toggle="modal" data-target="#addexerciseform">Add New Exercise</div>
        </div>
        <input id="is_cardio" name="is_cardio" class="d-none"  value="<?= $is_cardio ?>" />
        <input id="is_bodyweight" name="is_bodyweight" class="d-none"  value="<?= $is_bodyweight ?>" />
        <script>
            $('#exercisename').change(function(){
                if ($('#exercisename option:selected').hasClass("body_weight1") === true) {
                    $("#weight").prop("disabled", true);
                    $("#is_bodyweight").val("1");
                    $("#weight_div").hide();
                } else {
                    $("#weight").prop("disabled", false);
                    $("#is_bodyweight").val("0");
                    $("#weight_div").show();
                }
                if ($('#exercisename option:selected').hasClass("cardio1") === true) {
                    $("#is_cardio").val("1");
                    $("#reps").prop("disabled", true);
                    $("#reps_div").hide();
                    $("#sets").prop("disabled", true);
                    $("#sets_div").hide();
                } else {
                    $("#is_cardio").val("0");
                    $("#reps").prop("disabled", false);
                    $("#reps_div").show();
                    $("#sets").prop("disabled", false);
                    $("#sets_div").show();
                }
            });
        </script>
        <div id="reps_div" class="form-group">
            <label for="reps">Reps :</label>
            <input type="number" class="form-control" id="reps" name="reps"
                   value="<?= $reps ?>" placeholder="Enter how many reps" >
            <div class="text-danger"><?= $reps_error ?></div>

        </div>
        <div id="sets_div" class="form-group">
            <label for="sets">Sets :</label>
            <input type="number" name="sets" value="<?= $sets ?>" class="form-control"
                   id="sets" placeholder="Enter how many sets" >
            <div class="text-danger"><?= $sets_error ?></div>

        </div>
        <div id="weight_div" class="form-group">
            <label for="weight">Weight :</label>
            <input type="number" name="weight" value="<?= $weight ?>" class="form-control"
                   id="weight" placeholder="Enter how much weight" >
            <div class="text-danger"><?= $weight_error ?></div>

        </div>
        <script>
            if ($('#exercisename option:selected').hasClass("body_weight1") === true) {
                $("#weight").prop("disabled", true);
                $("#is_bodyweight").val("1");
                $("#weight_div").hide();
            } else {
                $("#weight").prop("disabled", false);
                $("#is_bodyweight").val("0");
                $("#weight_div").show();
            }
            if ($('#exercisename option:selected').hasClass("cardio1") === true) {
                $("#is_cardio").val("1");
                $("#reps").prop("disabled", true);
                $("#reps_div").hide();
                $("#sets").prop("disabled", true);
                $("#sets_div").hide();
            } else {
                $("#is_cardio").val("0");
                $("#reps").prop("disabled", false);
                $("#reps_div").show();
                $("#sets").prop("disabled", false);
                $("#sets_div").show();
            }
        </script>
        <div class="form-group">
            <label for="duration">Exercise Duration :</label>
            <input type="number" name="duration" value="<?= $duration ?>" class="form-control"
                   id="duration" placeholder="Enter the duration of the exercise" >
            <div class="text-danger"><?= $duration_error ?></div>

        </div>
        <div class="form-group">
            <label for="date">Exercise Date :</label>
            <input type="date" name="date" value="<?= $date ?>" class="form-control"
                   id="date" >
            <div class="text-danger"><?= $date_error ?></div>

        </div>
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-md-4"><a href="listWorkouts.php" id="btn_back" class="btn btn-success ">Back</a></div>
                <div class="col-md-4">
                    <button type="submit" name="uptWorkout"
                            class="btn btn-primary float-right" id="btn-submit">
                        Update Workout
                    </button>
                </div>

            </div>
        </div>


    </form>
</div>

<div id="addexerciseform" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="add_exercise_title" aria-hidden="true">
    <form method="post" action="addExercise.php">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add_exercise_title">Add New Exercise</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="ExerciseName">Exercise Name:</label>
                        <input type="text" id="ExerciseName" name="ExerciseName" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <fieldset>
                            <legend>Cardio: </legend>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="exercise_cardio" id="cardio-true" value="1" checked>
                                <label class="form-check-label" for="cardio-true">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="exercise_cardio" id="cardio-false" value="0">
                                <label class="form-check-label" for="cardio-false">No</label>
                            </div>
                    </div>
                    </fieldset>
                    <fieldset>
                        <legend>Bodyweight exercise: </legend>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="exercise_bodyweight" id="bodyweight-true" value="1" checked>
                            <label class="form-check-label" for="bodyweight-true">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="exercise_bodyweight" id="bodyweight-false" value="0">
                            <label class="form-check-label" for="bodyweight-false">No</label>
                        </div>
                </div>
                </fieldset>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <input type="submit" value="Create New Exercise" name="addExercise" class="button btn btn-success" />
                </div>
            </div>

        </div>
</div>
</form>
</div>

<?php
//Master Layout Footer
require_once "../Master/footer.php";
?>
