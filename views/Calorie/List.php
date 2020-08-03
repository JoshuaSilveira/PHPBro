<?php
//Master Layout Header
require_once "../Master/header.php";
?>

<?php
use fitnessTracker\includes\Database;
use fitnessTracker\models\Calorie\Calorie;

require_once '../../includes/Database.php';
require_once '../../models/Calorie.php';

//if the user is not signed in then redirect them to the home page
/* Uncomment code when home page is complete
if (!isset($_SESSION['id'])) {
    header("Location: ../Home/index.php");
    exit;
}
*/

$dbcon = Database::GetDb();
$c = new Calorie();
$calories =  $c->GetAll(Database::getDb());

?>
<h2>Calories History</h2>
<div>
    <a href="Add.php" id="btn_addCalorie" class="btn btn-success btn-lg float-left">Add Calorie</a>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">Date</th>
            <th scope="col">Intake</th>
            <th scope="col">Burned</th>
            <th scope="col">Update</th>
            <th scope="col">Delete</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach($calories as $calorie) {
            ?>
            <tr>
                <th><?= $calorie->date; ?></th>
                <th><?= $calorie->intake; ?></th>
                <th><?= $calorie->burned; ?></th>
                <td>
                    <form action="Update.php" method="post">
                        <input type="hidden" name="id" value="<?= $calorie->id; ?>"/>
                        <input type="submit" class="button btn btn-primary" name="updateCalorie" value="Update"/>
                    </form>
                </td>
                <td>
                    <form action="Delete.php" method="post">
                        <input type="hidden" name="id" value="<?= $calorie->id; ?>"/>
                        <input type="submit" class="button btn btn-danger" name="deleteCalorie" value="Delete"/>
                    </form>
                </td>
            </tr>
        <?php } ?>

        <?php
        //Dummy data
        ?>
        <tr>
            <th>2020-03-08</th>
            <th>1900</th>
            <th>2840</th>
            <td>
                <form action="Update.php" method="post">
                    <input type="hidden" name="id" value="1"/>
                    <input type="submit" class="button btn btn-primary" name="updateCalorie" value="Update"/>
                </form>
            </td>
            <td>
                <form action="Delete.php" method="post">
                    <input type="hidden" name="id" value="1"/>
                    <input type="submit" class="button btn btn-danger" name="deleteCalorie" value="Delete"/>
                </form>
            </td>
        </tr>

        </tbody>
    </table>
</div>
<div id="calorieGraph">
</div>
<?php
//Master Layout Footer
require_once "../Master/footer.php";
?>
