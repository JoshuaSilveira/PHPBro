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

//field variables
$nameErr = $urlErr = $caloriesErr = $proteinErr = $prepTimeErr = $imageErr = $descriptionErr = "";

//db object
$dbcon = Database::GetDb();

$nameErr = $urlErr = $caloriesErr = $proteinErr = $prepTimeErr = $imageErr = $descriptionErr = "";

/** FILL FORM ELEMENTS**/
if (isset($_POST['updateMeal'])) {
    //get the data from the form
    $id = $_POST["id"];
    //select meal from database with this ID
    $mealObject = new Meal();
    $currentMeal =  $mealObject->GetById($dbcon, $id);

    if ($currentMeal != null) {
        $oldName = $currentMeal["name"];
        $oldDescription = $currentMeal["description"];
        $oldUrl = $currentMeal["url"];
        $oldImage = $currentMeal["image_ext"];
        $oldCalories = $currentMeal["calories"];
        $oldProtein = $currentMeal["protein"];
        $oldIsVegan = $currentMeal["is_vegan"];
        $oldPrepTime = $currentMeal["prep_time"];
    } else {
        echo "No meal with that id exists. " . $id;
    }
}


/** UPDATE FORM IS SUBMITTED **/
else if (isset($_POST['updateForm'])) {
    //get the data from the form
    $id = $_POST["id"];
    $image = $_FILES;
    $name = $oldName = $_POST["name"];
    $url = $oldUrl = $_POST["url"];
    $calories = $oldCalories = $_POST["calories"];
    $preptime = $oldPrepTime = $_POST["preptime"];
    $protein = $_POST["protein"];
    if ($protein == '') {
        $protein = null;
    }
    $oldProtein = $protein;
    $description = $oldDescription = $_POST["description"];

   
    if (isset($_POST['isVegan'])) {
        if ($_POST['isVegan'] == 'on') {
            $isVegan = 1;
        } else {
            $isVegan = 0;
        }
    }
    else {
        $isVegan = 0;
    }
    $oldIsVegan = $isVegan;

    $isValid = true;
    //$nameErr = $urlErr = $caloriesErr = $proteinErr = $prepTimeErr = $imageErr = $descriptionErr = "";
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
    //if an image was not uploaded 
    if ($image["image"]["name"] == '') {
        $imageValidation = true;
    } else { //an image was uploaded, so validate it
        $imageValidation = Validator::validateImage($image);
    }
    //if the image is not valid, set the error message 
    if ($imageValidation !== true) {
        $imageErr = $imageValidation;
        $isValid = false;
    }


    //if the form is valid,
    //update the meal
    $mealObject = new Meal();
    $currentMeal =  $mealObject->GetById($dbcon, $id);
    $oldImage = $currentMeal["image_ext"];

    if ($isValid) {
        //if no file was uploaded, use the old image extension
        if ($image["image"]["name"] == '') {
            $imageExt = $oldImage;
        } else {
            //else use the newly uploaded file
            $imageExt = $mealObject->UploadImage($id, $image);
        }

        $mealObject->UpdateMeal($dbcon, $id, $name, $calories, $protein, $preptime, $isVegan, $description, $url, $imageExt);
    }
}
else {
    //page wasn't reached from the update button
    //redirect to the list page
    header("Location: List.php");
    exit();
}

?>

<?php
//Master Layout Header
require_once "../Master/header.php";
?>

<div class="mx-auto p-5 w-75">
    <?php if (isset($_POST['id']) && is_numeric($_POST["id"]) && $currentMeal != null) {
        //if id given is valid and a meal with that id exists, post the form
    ?>

        <h1>Update <?= $oldName ?></h1>
        <form action="" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="id" value="<?= $id ?>" />
            <div class="form-group">
                <label>Meal Name:</label>
                <input type="text" name="name" class="form-control" value="<?= $oldName ?>" />
                <span class="text-danger"><?= $nameErr ?></span>
            </div>

            <div class="form-group">
                <label for="image">Display Image: </label>
                <input type="file" accept="image/*" name="image" class="form-control-file" onchange="loadFile(event);" />
                <span class="text-danger"><?= $imageErr ?></span>
            </div>
            <div>
                <?php
                if ($oldImage == "") {
                    echo "<img id =\"output\" src=\"../../img/mealsIcon.png\" alt=\"default meal image\" style=\"width:200px;\">";
                } else {
                    echo "<img id =\"output\" src=\"" . $oldImage . "\" alt=\"meal image\" style=\"width:200px;\">";
                }
                ?>
            </div>

            <div class="form-group">
                <label>Calories:</label>
                <input type="number" name="calories" class="form-control" value="<?= $oldCalories ?>" />
                <span class="text-danger"><?= $caloriesErr ?></span>
            </div>
            <div class="form-group">
                <label>Protein (grams):</label>
                <input type="number" name="protein" class="form-control" value="<?= $oldProtein ?>" />
                <span class="text-danger"><?= $proteinErr ?></span>
            </div>
            <div class="form-group">
                <label>Prep Time (minutes):</label>
                <input type="number" name="preptime" class="form-control" value="<?= $oldPrepTime ?>" />
                <span class="text-danger"><?= $prepTimeErr ?></span>
            </div>
            <div class="form-group">
                <input type="checkbox" name="isVegan" id="isVegan" class="form-check-input" <?php if ($oldIsVegan == 1) {
                                                                                                echo "checked";
                                                                                            } ?> />
                <label for="isVegan" class="form-check-label">Vegan?</label>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" id="description" class="form-control" rows="3"><?= $oldDescription ?></textarea>
                <span class="text-danger"><?= $descriptionErr ?></span>
            </div>
            <div class="form-group">
                <label>Recipe URL:</label>
                <input type="text" name="url" class="form-control" value="<?= $oldUrl ?>" />
                <span class="text-danger"><?= $urlErr ?></span>
            </div>
            <button type="submit" name="updateForm" class="btn btn-primary">Submit</button>
        </form>
    <?php
    } else {
        //page is invalid ie. error 400
        echo "No Meal Found";
    } ?>
    <a href="List.php">Back</a>
</div>

<!--code below from stack overflow - https://stackoverflow.com/questions/4459379/preview-an-image-before-it-is-uploaded-->
<script>
    var loadFile = function(event) {
        var output = document.getElementById('output');
        output.src = URL.createObjectURL(event.target.files[0]);
    };
</script>

<?php
//Master Layout Footer
require_once "../Master/footer.php";
?>