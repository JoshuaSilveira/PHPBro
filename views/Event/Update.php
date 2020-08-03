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

//When user lands on this page (refresh or load)
if(isset($_POST['updateEvent'])){
    //Must be logged in to edit a event
    if (!isset($_SESSION['id'])) {
        header("Location: List.php");
        exit;
    }

    $id= $_POST['id'];

    //Get the data from the database
    $e = new Event();
    $event = $e->GetById(Database::getDb(), $id);

    //Show the data on the form as default value
    $name = $event->name;
    //$datetime = $event->date;
    $datetime = date('Y-m-d\TH:i', strtotime($event->date));
    $duration = $event->duration;
    $location = $event->location;
    $ref_id = $event->ref_id;
    //Check to see if you have the permission to edit this event
    if (!$_SESSION['is_admin'] && $event->account_id != $_SESSION['id']) {
        header("Location: List.php");
        exit;
    }

}

//When the user submits to Update the Event
if (isset($_POST['updEvent'])) {
    $id = $_POST['sid'];
    $ref_id = $_POST['sref_id'];
    //get the data from the form
    $name = $_POST['name'];
    $datetime = $_POST['datetime'];
    $duration = (int)$_POST['duration'];
    $location = $_POST['location'];

    //Validate the data
    $e = new Event();
    $errorMessages = Validator::ValidateEventForm($name, $datetime, $duration, $location);
    if (Validator::IsFormValid($errorMessages)) {
        $e->Update(Database::getDb(), [
            "id" => $id,
            "name" => $name,
            "date" => $datetime,
            "duration" => $duration,
            "location" => $location,
            "ref_id" => $ref_id
        ]);
    }
}
?>

<?php
//Master Layout Header
require_once "../Master/header.php";
?>
<div class="p-5">
    <!-- Form to Update Event -->
    <form action="" method="post">
        <input type="hidden" name="sid" value="<?= $id; ?>" />
        <input type="hidden" name="sref_id" value="<?= $ref_id; ?>" />
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
        <button type="submit" name="updEvent"
                class="btn btn-primary float-right" id="btn-submit">
            Update Event
        </button>
    </form>
</div>
<?php
//Master Layout Footer
require_once "../Master/footer.php";
?>