<?php
//Master Layout Header
require_once "../Master/header.php";
?>
<?php
use fitnessTracker\includes\Database;
use fitnessTracker\includes\Validator;
use fitnessTracker\models\Calorie\Calorie;

$errorMessages = ["", "", "", "", ""];
$height = "";
$weight = "";
$date = "";
$intake = "";
$burned = "";

//When user lands on this page (refresh or load)
if(isset($_POST['updateCalorie'])){
    $id= $_POST['id'];

    //Get the data from the database
    $c = new Calorie();
    $calorie = $c->GetById($id);

    //Show the data on the form as default value
    $height = $calorie->height;
    $weight = $calorie->weight;
    $date = $calorie->date;
    $intake = $calorie->intake;
    $burned = $calorie->burned;
}

//When the user submits to Update the Calorie
if (isset($_POST['updCalorie'])) {
    $id = $_POST['sid'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $date = $_POST['date'];
    $intake = $_POST['intake'];
    $burned = $_POST['burned'];

    //Validate the data
    $c = new Calorie();
    $errorMessages = Validator::ValidateCalorieForm($height, $weight, $date, $intake, $burned);
    if (Validator::IsFormValid($errorMessages)) {
        //Update the data on the database
        $c->Update(Database::getDb(), [
            "id" => $id,
            "account_id" => $_SESSION['id'],
            "height" => $height,
            "weight" => $weight,
            "date" => $date,
            "intake" => $intake,
            "burned" => $burned]
        );
    }
}

?>
<div>
    <!-- Form to Update Calorie -->
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
        <button type="submit" name="updCalorie"
                class="btn btn-primary float-right" id="btn-submit">
            Update Calorie
        </button>
    </form>
</div>
<?php
//Master Layout Footer
require_once "../Master/footer.php";
?>