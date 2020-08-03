<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use fitnessTracker\includes\Database;
use fitnessTracker\models\Event\Event;

require_once '../../includes/Database.php';
require_once '../../models/Event.php';

$name = "";
$datetime = "";
$duration = "";
$location = "";

if (!isset($_GET['id'])) {
    header('Location: List.php');
    exit;
}

$e = new Event();
$event = $e->GetById(Database::GetDb(), $_GET['id']);

?>

<?php
//Master Layout Header
require_once "../Master/header.php";
?>

<div class="p-5">
    <h2><?= $event->name ?></h2>
    <div>Date: <?= $event->date ?></div>
    <div>Duration (hour): <?= $event->duration ?></div>
    <div>Location: <?= $event->location ?></div>
    <div class="row">
        <div><a href="List.php" id="btn_back" class="btn btn-success">Back</a></div>
    <?php
    //if the user is admin or user owns this event
    if (isset($_SESSION['id']) && ($_SESSION['is_admin'] || $_SESSION['id'] == $event->account_id)) {
    ?>
        <div>
            <form action="Update.php" method="post">
                <input type="hidden" name="id" value="<?= $event->id; ?>"/>
                <input type="submit" class="button btn btn-primary" name="updateEvent" value="Update"/>
            </form>
        </div>
        <div>
            <form action="Delete.php" method="post">
                <input type="hidden" name="id" value="<?= $event->id; ?>"/>
                <input type="submit" class="button btn btn-danger" name="deleteEvent" value="Delete"/>
            </form>
        </div>
    <?php
    }
    ?>
    </div>
</div>
<?php
//Master Layout Footer
require_once "../Master/footer.php";
?>
