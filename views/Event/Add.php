<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use fitnessTracker\includes\Database;
use fitnessTracker\includes\Validator;
use fitnessTracker\models\Event\Event;

require_once '../../includes/Database.php';
require_once '../../includes/Validator.php';
require_once '../../models/Event.php';

$errorMessages = ["", "", "", ""];
$name = "";
$datetime = "";
$duration = "";
$location = "";

//Must be logged in to create a new event
if (!isset($_SESSION['id'])) {
    header("Location: List.php");
    exit;
}

//Check to see if form is submitted
if (isset($_POST['addEvent'])) {
    //get the data from the form
    $name = $_POST['name'];
    $datetime = $_POST['datetime'];
    $duration = (int)$_POST['duration'];
    $location = $_POST['location'];

    //Check the validation
    $e = new Event();
    $errorMessages = Validator::ValidateEventForm($name, $datetime, $duration, $location);
    if (Validator::IsFormValid($errorMessages)) {
        $e->Add(Database::getDb(), [
            "account_id" => $_SESSION['id'],
            "name" => $name,
            "date" => $datetime,
            "duration" => $duration,
            "location" => $location,
            "ref_id" => 0
        ]);
    }
}
?>

<?php
//Master Layout Header
require_once "../Master/header.php";
?>

<div class="p-5">
    <!-- Form to Add Event -->
    <form action="" method="post">
        <div class="form-group">
            <label for="name">Name :</label>
            <input type="text" class="form-control" name="name" id="name" value="<?= $name ?>"
                   placeholder="Enter Name">
            <div style="color: red">
                <?= $errorMessages[0] ?>
            </div>
        </div>
        <div class="form-group">
            <label for="date">Date :</label>
            <input type="datetime-local" class="form-control" name="datetime" id="datetime" value="<?= $datetime ?>">
            <div style="color: red">
                <?= $errorMessages[1] ?>
            </div>
        </div>
        <div class="form-group">
            <label for="duration">Duration (hour) :</label>
            <input type="number" class="form-control" name="duration" id="duration" value="<?= $duration ?>"
                   placeholder="Enter Duration">
            <div style="color: red">
                <?= $errorMessages[2] ?>
            </div>
        </div>
        <div class="form-group">
            <label for="location">Location :</label>
            <input type="text" class="form-control" id="location" name="location" value="<?= $location ?>"
                   placeholder="Enter Location">
            <div style="color: red">
                <?= $errorMessages[3] ?>
            </div>
        </div>
        <a href="List.php" id="btn_back" class="btn btn-success float-left">Back</a>
        <button type="submit" name="addEvent"
                class="btn btn-primary float-right" id="btn-submit">
            Add Event
        </button>
    </form>
</div>
<?php
//Master Layout Footer
require_once "../Master/footer.php";
?>