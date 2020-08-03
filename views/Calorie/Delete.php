<?php
//Master Layout Header
require_once "../Master/header.php";
?>

<?php
use fitnessTracker\includes\Database;
use fitnessTracker\models\Calorie\Calorie;

require_once '../../includes/Database.php';
require_once '../../models/Calorie.php';

if(isset($_POST['id'])){
    $id = $_POST['id'];

    $c = new Calorie();
    $c->Delete(Database::getDb(), $id);
}

?>

<?php
//Master Layout Footer
require_once "../Master/footer.php";
?>