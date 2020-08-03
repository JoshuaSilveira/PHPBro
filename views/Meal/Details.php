<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use fitnessTracker\includes\Database;
use fitnessTracker\models\Meal\Meal;

require_once '../../includes/Database.php';
require_once '../../models/Meal.php';

//whether or not the logged in user is an admin
//TODO: check if someone is logged in but not an admin
$isAdmin = 0;
if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"] == 1) {
    $isAdmin = 1;
}

//get the desired meal from the database
if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $dbcon = Database::GetDb();
    $mealObject = new Meal();
    $meal =  $mealObject->GetById($dbcon, $_GET["id"]);

    if ($meal == null) {
        //if no record with that id found
        http_response_code(400);
    }
} else {
    //no or invalid id was given in the url
    http_response_code(400);
}

?>

<?php
//Master Layout Header
require_once "../Master/header.php";
?>

<div class="p-5">
    <?php
    if ($meal == null) {
        echo "No meal found.<br/><a href='listMeals.php' class=\"btn btn-primary\">Back</a>";
    } else {
        echo "<h1>" . $meal["name"] . "</h1>";
    ?>
        <div class="container">
            <div class="row">
                <div class="col-sm">
                    <?php
                    if ($meal["image_ext"] == "") {
                        echo "<img src=\"../../img/mealsIcon.png\" alt=\"default meal image\" style=\"width:60%;\">";
                    } else {
                        echo "<img src=\"" . $meal["image_ext"] . "\" alt=\"meal image\">";
                    }
                    ?>
                    <div class="pt-5">
                        <a href='List.php' class="btn btn-primary">Back</a>
                        <?php if ($isAdmin == 1) { ?>
                            <form action="Update.php" method="POST" style="display: inline-block;"><input type="hidden" name="id" value="<?= $meal['id'] ?>" /><button name="updateMeal" type="submit" class="btn btn-info">Update</button></form>
                            <!--TODO: change delete to popup box-->
                            <form action="Delete.php" method="POST" style="display: inline-block;"><input type="hidden" name="id" value="<?= $meal['id'] ?>" /><button name="deleteMeal" type="submit" class="btn btn-danger">Delete</button></form>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-sm">
                    <p><?= $meal['description'] ?></p>
                    <p><strong>Calories: </strong><?= $meal["calories"] ?></p>
                    <p><strong>Protein: </strong><?= $meal["protein"] ?>g</p>
                    <p><strong>Preperation Time: </strong><?= $meal["prep_time"] ?> minutes</p>
                    <p><strong>Vegan: </strong><?php if ($meal["is_vegan"] == 0) {
                                                    echo "No";
                                                } else {
                                                    echo "Yes";
                                                } ?></p>
                    <a href="<?= $meal["url"] ?>" target="_blank">Cooking instructions at external site</a>

                </div>
            </div>
        </div>
    <?php }
    ?>
</div>

<?php
//Master Layout Footer
require_once "../Master/footer.php";
?>