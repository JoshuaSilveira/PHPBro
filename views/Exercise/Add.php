<?php
//Basic template modeled from Christopher Maeda's CRUD template to maintain code consistency for minor feature.
//No plagiarism is intended, for educational purposes only

//Basic List template created by Christopher Maeda, and modified with permission to maintain code consistency. For educational purposes only
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
use fitnessTracker\includes\Database;
use fitnessTracker\models\Exercise;

require_once '../../includes/Database.php';
require_once '../../models/Exercise.php';

//if someone isn't signed in, redirect them to the login
if (!isset($_SESSION["id"])) {
    header("Location: ../Login/Login.php");
}
$is_valid = true;
$name = $name_err = $bodyweight_err = $cardio_err = '';
$bodyweight = ['', ''];
$cardio = ['', ''];

//if form is submitted, run handling
if (isset($_POST['addExercise'])) {

    $name = $_POST['ExerciseName'];

    //check if exercise name is empty
    if(empty($_POST['ExerciseName'])){
        $name_err="Exercise Name cannot be blank";
        $is_valid = false;
    }
    if(isset($_POST['exercise_cardio'])) {
        if ($_POST['exercise_cardio'] == 1) {
            $cardio[1] = 'checked';
        } else {
            $cardio[0] = 'checked';
        }
    } else {
        $cardio_err = 'You must select if this exercise is cardio or not';
        $is_valid = false;
    }

    if(isset($_POST['exercise_bodyweight'])) {
        if ($_POST['exercise_bodyweight'] == 1) {
            $bodyweight[1] = 'checked';
        } else {
            $bodyweight[0] = 'checked';
        }
    } else {
        $bodyweight_err = 'You must select if this exercise is bodyweight or not';
        $is_valid = false;
    }

    if($is_valid === true)
    {
        $dbcon = Database::GetDb();
        $ex = new Exercise();

        $ex->Add(Database::getDb(), [
                "name" => $name,
                "body_weight" => $_POST['exercise_bodyweight'],
                "cardio" => $_POST['exercise_cardio']
            ]);
    }


}


//Master Layout Header
require_once "../Master/header.php";


?>
<div class="container">
<form method="post" action="">

    <div class="form-group">
        <label for="ExerciseName">Exercise Name:</label>
        <input type="text" id="ExerciseName" name="ExerciseName" class="form-control" value="<?= $name ?>"/>
        <div class="text-danger"><?= $name_err ?></div>
    </div>
    <div class="form-group">
        <fieldset>
            <legend>Cardio:</legend>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="exercise_cardio" id="cardio-true" value="1" <?= $cardio[1] ?>>
                <label class="form-check-label" for="cardio-true">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="exercise_cardio" id="cardio-false" value="0" <?= $cardio[0] ?>>
                <label class="form-check-label" for="cardio-false">No</label>
            </div>
        </fieldset>
        <div class="text-danger"><?= $cardio_err ?></div>
    </div>

    <fieldset>
        <legend>Bodyweight exercise:</legend>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="exercise_bodyweight" id="bodyweight-true" value="1" <?= $bodyweight[1] ?>>
            <label class="form-check-label" for="bodyweight-true">Yes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="exercise_bodyweight" id="bodyweight-false" value="0" <?= $bodyweight[0] ?>>
            <label class="form-check-label" for="bodyweight-false">No</label>
        </div>
        <div class="text-danger"><?= $bodyweight_err ?></div>

    </fieldset>
    <div class="form-group">
        <input type="submit" value="Create New Exercise" name="addExercise" class="btn btn-success"/>
    </div>

</form>
</div>

<?php
//Master Layout Footer
require_once "../Master/footer.php";
?>