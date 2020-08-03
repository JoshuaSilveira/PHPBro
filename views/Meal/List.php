<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use fitnessTracker\includes\Database;
use fitnessTracker\models\Meal\Meal;
use fitnessTracker\includes\Validator;

require_once '../../includes/Database.php';
require_once '../../models/Meal.php';
require_once '../../includes/Validator.php';


if (isset($_GET['action'])) {
    if ($_GET['action'] == "added") {
        echo "<div class=\"alert alert-success m-3\" role=\"alert\">Meal added.</div>";
    }
    if ($_GET['action'] == "updated") {
        echo "<div class=\"alert alert-success m-3\" role=\"alert\">Meal updated.</div>";
    }
    if ($_GET['action'] == "deleted") {
        echo "<div class=\"alert alert-danger m-3\" role=\"alert\">Meal deleted.</div>";
    }
}
$isAdmin = 0;
if (isset($_SESSION["is_admin"])) {
    if ($_SESSION["is_admin"] == 1) {
        $isAdmin = 1;
    }
}

$dbcon = Database::GetDb();
$mealObject = new Meal();
$meals =  $mealObject->GetAll($dbcon);
//var_dump($meals);

if (isset($_GET["search"])) {
    //if search button was clicked with no input, return the whole list instead (don't search)
    if ($_GET["search"] != "") {
        //if there was input, search for it
        $scrubbedInput = Validator::scrubInput($_GET["search"]);
        $meals = $mealObject->SearchMeals($dbcon, $scrubbedInput);
    }
}

?>

<?php
//Master Layout Header
require_once "../Master/header.php";
?>

<div class="p-5">
    <h1>List of Meals</h1>
    <p>Below is a list of meals our team curated that taste good and are good for you.</p>
    <?php if ($isAdmin == 1) {
        //if the user is logged in as an admin, show the "add new" button
    ?>
        <form action="Add.php"><button type="submit" class="btn btn-success float-right mb-3">Add New</button></form>
    <?php
    }
    ?>
    <form action="" method="GET" class="meal-search">
        <input type="text" class="form-control" name="search" placeholder="Search" style="width: 20%" <?php if (isset($_GET["search"])) { ?> value="<?= $_GET["search"] ?>" <?php } ?>>
        <button type="submit" class="btn btn-success">Search</button>
    </form>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <th>Image</th>
                <th>Name</th>
                <th>Calories</th>
                <th>Protein (grams)</th>
                <th>Vegan</th>
                <?php if ($isAdmin == 1) { ?>
                    <th>Update</th>
                    <th>Delete</th>
                <?php } ?>


            </thead>
            <tbody>
                <?php
                foreach ($meals as $m) {
                    echo "<tr onclick=\"window.location.href = 'Details.php?id=" . $m["id"] . "'\" style=\"cursor:pointer;\">";

                    if ($m["image_ext"] == "") {
                        echo "<td><img src=\"../../img/mealsIcon.png\" alt=\"default meal image\" style=\"width:200px;\"></td>";
                    } else {
                        echo "<td><img src=\"" . $m["image_ext"] . "\" alt=\"meal image\" style=\"width:200px;\"></td>";
                    }
                    //final-meals\images\default.jpg
                    echo "<td>" . $m["name"] . "</td>";
                    echo "<td>" . $m["calories"] . "</td>";
                    echo "<td>" . $m["protein"] . "</td>";
                    if ($m["is_vegan"] == 0) {
                        echo "<td>Not Vegan</td>";
                    } else {
                        echo "<td>Vegan</td>";
                    }
                    //only show update and delete buttons to admins
                    if ($isAdmin == 1) {
                        //update button
                        echo "<td>" . "<form action=\"Update.php\" method=\"POST\"><input type=\"hidden\" name=\"id\" value=\"" . $m["id"] . "\" /><button name=\"updateMeal\" type=\"submit\" class=\"btn btn-info\" value=\"\">Update</button></form>" . "</td>";
                        //delete button
                        echo "<td>" . "<form action=\"Delete.php\" method=\"POST\"><input type=\"hidden\" name=\"id\" value=\"" . $m["id"] . "\" /><button name=\"deleteMeal\" type=\"submit\" class=\"btn btn-danger\" value=\"delete\">Delete</button></form>" . "</td>";
                    }
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <?php if (empty($meals)) { ?>
            <p>No meals found.</p>
        <?php } ?>
    </div>
</div>


<?php
//Master Layout Footer
require_once "../Master/footer.php";
?>