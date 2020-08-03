<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use fitnessTracker\includes\Database;
use fitnessTracker\includes\Validator;
use fitnessTracker\models\Meal\Meal;

require_once '../../includes/Database.php';
require_once '../../includes/Validator.php';
require_once '../../models/Meal.php';

if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] != 1) {
    //if the is_admin variable isnt set (ie. not logged in) or the user is not an admin
    //only an admin should be able to add a meal to the database. Redirect to the meal list if not a logged in admin
    header("Location: List.php");
    exit();
}

//set empty field variables
$fieldName = $fieldCalories = $fieldProtein = $fieldIsVegan = $fieldPrepTime = $fieldUrl = $fieldDescription = "";
$nameErr = $urlErr = $caloriesErr = $proteinErr = $prepTimeErr = $descriptionErr = "";


if (isset($_POST['addMeal'])) {
    //get the database connection
    $dbcon = Database::GetDb();

    //get the data from the form
    $name = $fieldName = $_POST["name"];
    $url = $fieldUrl = $_POST["url"];
    $calories = $fieldCalories = $_POST["calories"];
    $protein = $fieldProtein = $_POST["protein"];
    if ($protein == '') {
        $protein = null;
    }
    $preptime = $fieldPrepTime = $_POST["preptime"];
    $image = '';
    if (isset($_POST['isVegan'])) {
        $fieldIsVegan = $_POST['isVegan'];
        if ($_POST['isVegan'] == 'on') {
            $isVegan = 1;
        } else {
            $isVegan = 0;
        }
    }
    else {
        $isVegan = 0;
    }

    $description = $fieldDescription = $_POST["description"];

    //TODO: DO EMPTY VALIDATION IN VALIDATOR CLASS
    $isValid = true;
    if (empty($name)) {
        $nameErr = "* Required Field";
        $isValid = false;
    }
    if (!is_numeric($calories) || $calories < 0) {
        $caloriesErr = "* Required Field. Must be 0 or more.";
        $isValid = false;
    }
    if (!is_numeric($protein) || $protein < 0) {
        $proteinErr = "* Required Field. Must be 0 or more.";
        $isValid = false;
    }
    if (!is_numeric($preptime) || $preptime < 0) {
        $prepTimeErr = "* Required Field. Must be 0 or more.";
        $isValid = false;
    }
    if (empty($description)) {
        $descriptionErr = "* Required Field";
        $isValid = false;
    }
    if (empty($url)) {
        $urlErr = "* Required Field";
        $isValid = false;
    }
    //scrub inputs
    $name = Validator::scrubInput($name);
    $calories = Validator::scrubInput($calories);
    $protein = Validator::scrubInput($protein);
    $preptime = Validator::scrubInput($preptime);
    $description = Validator::scrubInput($description);
    $url = Validator::scrubInput($url);
    if (Validator::validateUrl($url) == false) {
        //if it doesn't pass validation
        $urlErr = "* Invalid URL";
        $isValid = false;
    } else {
        //else, add http to the front if it doesn't already have it
        $url = Validator::validateUrl($url);
    }

    if ($isValid) {
        $mealObject = new Meal();

        $numRowsAffected = $mealObject->AddMeal($dbcon, $name, $calories, $protein, $preptime, $isVegan, $description, $url);

        //var_dump($numRowsAffected);
        if ($numRowsAffected) {
            echo "Inserted a meal.";
            header("Location:List.php?action=added");
        } else {
            echo "Problem inserting";
        }
    }
}
?>

<?php
//Master Layout Header
require_once "../Master/header.php";
?>

<div class="mx-auto p-5 w-75">
    <h1>Add New Meal</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label>Meal Name:</label>
            <input type="text" name="name" class="form-control" value="<?= $fieldName ?>" />
            <span class="text-danger"><?= $nameErr ?></span>
        </div>

        <!--will do image uploading in update-->

        <div class="form-group">
            <label>Calories:</label>
            <input type="number" name="calories" class="form-control" value="<?= $fieldCalories ?>" />
            <span class="text-danger"><?= $caloriesErr ?></span>
        </div>
        <div class="form-group">
            <label>Protein (grams):</label>
            <input type="number" name="protein" class="form-control" value="<?= $fieldProtein ?>" />
            <span class="text-danger"><?= $proteinErr ?></span>
        </div>
        <div class="form-group">
            <label>Prep Time (minutes):</label>
            <input type="number" name="preptime" class="form-control" value="<?= $fieldPrepTime ?>" />
            <span class="text-danger"><?= $prepTimeErr ?></span>
        </div>
        <div class="form-group">
            <input type="checkbox" name="isVegan" id="isVegan" class="form-check-input" <?php if ($fieldIsVegan == 'on') {
                                                                                            echo "checked";
                                                                                        } ?> />
            <label for="isVegan" class="form-check-label">Vegan?</label>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea name="description" id="description" class="form-control" rows="3"><?= $fieldDescription ?></textarea>
            <span class="text-danger"><?= $descriptionErr ?></span>
        </div>
        <div class="form-group">
            <label>Recipe URL:</label>
            <input type="text" name="url" class="form-control" value="<?= $fieldUrl ?>" />
            <span class="text-danger"><?= $urlErr ?></span>
        </div>
        <button type="submit" name="addMeal" class="btn btn-primary">Submit</button>
    </form>
    <a href="List.php">Back</a>
</div>

<?php
//Master Layout Footer
require_once "../Master/footer.php";
?>