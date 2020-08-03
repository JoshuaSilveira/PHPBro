<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use fitnessTracker\includes\Database;
use fitnessTracker\models\Event\Event;

require_once '../../includes/Database.php';
require_once '../../models/Event.php';

//User is not logged in, so they can't delete a event (redirect back to list)
if (!isset($_SESSION['id'])) {
    header('Location: List.php');
    exit;
}

if(isset($_POST['id'])){
    $id = $_POST['id'];

    $e = new Event();

    $event = $e->GetById(Database::getDb(), $id);
    //Check to see if you have the permission to edit this event
    if (!$_SESSION['is_admin'] && $event->account_id != $_SESSION['id']) {
        header("Location: List.php");
        exit;
    }

    $e->Delete(Database::getDb(), $id);
}

?>

<?php
//Master Layout Header
require_once "../Master/header.php";
?>

<?php
//Master Layout Footer
require_once "../Master/footer.php";
?>