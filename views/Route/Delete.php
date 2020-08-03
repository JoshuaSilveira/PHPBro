<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use fitnessTracker\includes\Database;
use fitnessTracker\models\Route\Route;

require_once '../../includes/Database.php';
require_once '../../models/Route.php';

//User is not logged in, so they can't delete a route (redirect back to list)
if (!isset($_SESSION['id'])) {
    header('Location: List.php');
    exit;
}

if(isset($_POST['id'])){
    $id = $_POST['id'];

    $r = new Route();
    $r->Delete(Database::getDb(), $id);
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