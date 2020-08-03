<?php
//Master Layout Header
require_once "../Master/header.php";
?>
<?php
use fitnessTracker\includes\Database;
use fitnessTracker\includes\Validator;
use fitnessTracker\models\Calorie\Calorie;

require_once '../../includes/Database.php';
require_once '../../includes/Validator.php';
require_once '../../models/Calorie.php';

$errorMessages = ["", "", "", "", ""];
$height = "";
$weight = "";
$date = "";
$intake = "";
$burned = "";

//Check to see if form is submitted
if (isset($_POST['addCalorie'])) {
    //get the data from the form
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $date = $_POST['date'];
    $intake = $_POST['intake'];
    $burned = $_POST['burned'];

    //Check the validation
    $c = new Calorie();
    $errorMessages = Validator::ValidateCalorieForm($height, $weight, $date, $intake, $burned);
    if (Validator::IsFormValid($errorMessages)) {
        $c->Add(Database::getDb(), [
            "account_id" => $_SESSION['id'],
            "height" => $height,
            "weight" => $weight,
            "date" => $date,
            "intake" => $intake,
            "burned" => $burned
            ]
        );
    }
}

?>
<div>
    <!-- Form to Add Calorie -->
    <form action="" method="post">
        <div class="form-group">
            <label for="height">Height :</label>
            <input type="number" class="form-control" name="height" id="height" value="<?= $height ?>"
                   placeholder="Enter height">
            <div style="color: red">
                <?= $errorMessages[0] ?>
            </div>
        </div>
        <div class="form-group">
            <label for="weight">Weight :</label>
            <input type="number" class="form-control" name="weight" id="weight" value="<?= $weight ?>"
                   placeholder="Enter Weight">
            <div style="color: red">
                <?= $errorMessages[1] ?>
            </div>
        </div>
        <div class="form-group">
            <label for="date">Date :</label>
            <input type="text" class="form-control" name="date" id="date" value="<?= $date ?>"
                   placeholder="Enter Date">
            <div style="color: red">
                <?= $errorMessages[2] ?>
            </div>
        </div>
        <div class="form-group">
            <label for="intake">Calories Intake :</label>
            <input type="text" class="form-control" id="intake" name="intake"
                   value="<?= $intake ?>" placeholder="Enter intake amount">
            <div style="color: red">
                <?= $errorMessages[3] ?>
            </div>
        </div>
        <div class="form-group">
            <label for="burned">Calories Burned :</label>
            <input type="text" class="form-control" id="burned" name="burned"
                   value="<?= $burned ?>" placeholder="Enter burned amount">
            <div style="color: red">
                <?= $errorMessages[4] ?>
            </div>
        </div>
        <a href="List.php" id="btn_back" class="btn btn-success float-left">Back</a>
        <button type="submit" name="addCalorie"
                class="btn btn-primary float-right" id="btn-submit">
            Add Calorie
        </button>
    </form>
</div>
<?php
//Master Layout Footer
require_once "../Master/footer.php";
?>