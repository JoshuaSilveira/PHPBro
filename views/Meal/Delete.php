<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use fitnessTracker\includes\Database;
use fitnessTracker\models\Meal\Meal;

require_once '../../includes/Database.php';
require_once '../../models/Meal.php';

if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] != 1) {
    //if the is_admin variable isnt set (ie. not logged in) or the user is not an admin
    //only an admin should be able to add a meal to the database. Redirect to the meal list if not a logged in admin
    header("Location: List.php");
    exit();
}
//check to see if form is submitted
if (isset($_POST['deleteMeal'])) {
    
    //get the id from the form
    $id = $_POST["id"];

    if ($id == '') {
        echo "Empty id - can't delete.";
    } else {
        $dbcon = Database::GetDb();
        $mealObject = new Meal();
        //TODO: delete associated image file in Meal Model
        $mealObject->Delete($dbcon, $id);
        //will redirect to List if successful
    }
}
else {
    echo "No post data - can't delete.";
    header("Location: List.php");
}
?>

<?php
//Master Layout Header
require_once "../Master/header.php";
?>

<a href="listMeals.php">Back</a>