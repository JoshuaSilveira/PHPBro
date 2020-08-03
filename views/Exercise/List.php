<?php
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


$dbcon = Database::GetDb();
$ex = new Exercise();
$exercises =  $ex->GetAll(Database::getDb());


//Master Layout Header
require_once "../Master/header.php";
?>

<div class="jumbotron text-center">
    <div class="h2">Exercises</div>
</div>

<div class="container">
    <a href="Add.php"  class="btn btn-success">Add New Exercise</a>
    <table class="table table-dark">
        <thead>
        <tr>
            <th scope="col">Exercise ID</th>
            <th scope="col">Exercise Name</th>
            <th scope="col">Bodyweight</th>
            <th scope="col">Cardio</th>
            <th scope="col">Update</th>
            <th scope="col">Delete</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach($exercises as $exercise) {
            //display bodyweight and cardio in a more readable format
            $bodyweight = ($exercise->body_weight == 1) ? 'Yes' : 'No';
            $cardio = ($exercise->cardio == 1) ? 'Yes' : 'No';
            ?>
            <tr>
                <th><?= $exercise->id; ?></th>
                <th><?= $exercise->name; ?></th>
                <th><?= $bodyweight; ?></th>
                <th><?= $cardio; ?></th>
                <td>
                    <form action="Update.php" method="post">
                        <input type="hidden" name="id" value="<?= $exercise->id; ?>"/>
                        <input type="submit" class="btn btn-warning" name="updateExercise" value="Update"/>
                    </form>
                </td>
                <td>
                    <form action="Delete.php" method="post">
                        <input type="hidden" name="id" value="<?= $exercise->id; ?>"/>
                        <input type="submit" class="btn btn-danger" name="deleteExercise" value="Delete"/>
                    </form>
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
</div>
<?php
//Master Layout Footer
require_once "../Master/footer.php";
?>
