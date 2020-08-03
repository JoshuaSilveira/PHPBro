<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use fitnessTracker\includes\Database;
use fitnessTracker\includes\Validator;
use fitnessTracker\models\Route\Route;

require_once '../../includes/Database.php';
require_once '../../includes/Validator.php';
require_once '../../models/Route.php';

$errorMessages = ["", "", ""];
$name = "";
$startAddress = "";
$endAddress = "";

//User is not logged in, so they can't add a route (redirect back to list)
if (!isset($_SESSION['id'])) {
    header('Location: List.php');
    exit;
}

//Check to see if form is submitted (add route)
if (isset($_POST['addRoute'])) {
    //get the data from the form
    $name = $_POST['name'];
    $startAddress = $_POST['startAddress'];
    $endAddress = $_POST['endAddress'];

    //Check the validation
    $r = new Route();
    $errorMessages = Validator::ValidateRouteForm($name, $startAddress, $endAddress);
    if (Validator::IsFormValid($errorMessages)) {
        $r->Add(Database::getDb(), ["account_id" => $_SESSION['id'], "name" => $name, "startAddress" => $startAddress, "endAddress" => $endAddress]);
    }
}

//Check to see if form is submitted (check route)
if (isset($_POST['showRoute'])) {
    //get the data from the form
    $name = $_POST['name'];
    $startAddress = $_POST['startAddress'];
    $endAddress = $_POST['endAddress'];

    //Check the validation
    $errorMessages = Validator::ValidateRouteForm($name, $startAddress, $endAddress);
}

//Master Layout Header
require_once "../Master/header.php";

?>
<div class="p-5">
    <!-- Form to Add Route -->
    <form action="" method="post" class="row">
        <div class="col-lg-4">
            <div class="form-group">
                <label for="name">Name :</label>
                <input type="text" class="form-control" name="name" id="name" value="<?= $name ?>"
                       placeholder="Enter name">
                <div style="color: red">
                    <?= $errorMessages[0] ?>
                </div>
            </div>
            <div class="form-group">
                <label for="startAddress">Start Address :</label>
                <input type="text" class="form-control" id="startAddress" name="startAddress"
                       value="<?= $startAddress ?>" placeholder="Enter start address">
                <div style="color: red">
                    <?= $errorMessages[1] ?>
                </div>
            </div>
            <div class="form-group">
                <label for="endAddress">End Address :</label>
                <input type="text" class="form-control" id="endAddress" name="endAddress"
                       value="<?= $endAddress ?>" placeholder="Enter end address">
                <div style="color: red">
                    <?= $errorMessages[2] ?>
                </div>
            </div>
            <div>
                <a href="List.php" id="btn_back" class="btn btn-success float-left">Back</a>

                <button type="submit" name="showRoute" id="showRoute" class="btn btn-primary">Check Route</button>

                <button type="submit" name="addRoute"
                        class="btn btn-primary float-right" id="btn-submit">
                    Add Route
                </button>
            </div>
        </div>
        <div class="col-lg-8 row justify-content-center">
            <div id="routeMap" class="col-lg-9">
            </div>
            <div class="col-lg-3">
                <div id="totalDistance">Distance: 0 km
                </div>
                <div id="totalDuration">Duration: 0 min
                </div>
            </div>
        </div>
    </form>
</div>

<script src="RouteMap.js"></script>

<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB4Co301GCKm89-LhBlERD_1cFkU3xHyJA&callback=initMap">
</script>

<?php
//Master Layout Footer
require_once "../Master/footer.php";
?>