<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use fitnessTracker\includes\Database;
use fitnessTracker\models\Route\Route;

require_once '../../includes/Database.php';
require_once '../../models/Route.php';

$dbcon = Database::GetDb();
$r = new Route();
$routes =  $r->GetAll(Database::getDb());

//Comment this line out when account is integrated
//$_SESSION['id'] = 3;
//session_destroy();
//session_start();

//if the user is a admin get all the routes
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
    $routes =  $r->GetAll(Database::getDb());
}
else if (isset($_SESSION['id'])) {
    $routes =  $r->GetAllById(Database::getDb(), $_SESSION['id']);
}

$startAddress = "";
$endAddress = "";
if (isset($_GET['startAddress'])) {
    $startAddress = $_GET['startAddress'];
}

if (isset($_GET['endAddress'])) {
    $endAddress = $_GET['endAddress'];
}

?>

<?php
//Master Layout Header
require_once "../Master/header.php";
?>

<?php
//Show only if user is logged in
if (isset($_SESSION['id'])) {
?>
    <div class="p-5">
        <h2>Saved Routes</h2>
        <div>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Start Address</th>
                    <th scope="col">End Address</th>
                    <th scope="col">Update</th>
                    <th scope="col">Delete</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($routes as $route) {
                    ?>
                    <tr>
                        <th>
                            <a href="?startAddress=<?=$route->start_address;?>&endAddress=<?=$route->end_address;?>">
                                <?= $route->name; ?>
                            </a>
                        </th>
                        <th><?= $route->start_address; ?></th>
                        <th><?= $route->end_address; ?></th>
                        <td>
                            <form action="Update.php" method="post">
                                <input type="hidden" name="id" value="<?= $route->id; ?>"/>
                                <input type="submit" class="button btn btn-primary" name="updateRoute" value="Update"/>
                            </form>
                        </td>
                        <td>
                            <form action="Delete.php" method="post">
                                <input type="hidden" name="id" value="<?= $route->id; ?>"/>
                                <input type="submit" class="button btn btn-danger" name="deleteRoute" value="Delete"/>
                            </form>
                        </td>
                    </tr>
                <?php } ?>

                <!-- Dummy data
                <tr>
                    <th>Usual Walk Route</th>
                    <th>100 City Centre Dr</th>
                    <th>112 Queen St S</th>
                    <td>
                        <form action="Update.php" method="post">
                            <input type="hidden" name="id" value="2"/>
                            <input type="submit" class="button btn btn-primary" name="updateRoute" value="Update"/>
                        </form>
                    </td>
                    <td>
                        <form action="Delete.php" method="post">
                            <input type="hidden" name="id" value="2"/>
                            <input type="submit" class="button btn btn-danger" name="deleteRoute" value="Delete"/>
                        </form>
                    </td>
                </tr>
                -->

                </tbody>
            </table>
            <a href="Add.php" id="btn_addRoute" class="btn btn-success btn-lg">Add Route</a>
        </div>
    </div>
<?php
}
?>

<?php
// API key: AIzaSyB4Co301GCKm89-LhBlERD_1cFkU3xHyJA
?>
<div class="p-5">
    <div>
        <form class="row justify-content-center">
            <label for="startAddress" class="col-2">Start Address :</label>
            <input type="text" id="startAddress" name="startAddress" value="<?= $startAddress ?>" placeholder="Enter start address" class="col-2">
            <label for="endAddress" class="col-2">End Address :</label>
            <input type="text" id="endAddress" name="endAddress" value="<?= $endAddress ?>" placeholder="Enter end address" class="col-2">
            <button type="button" id="showRoute" class="col-1">Show Route</button>
        </form>
    </div>

    <div class="row justify-content-center">
        <div id="routeMap" class="col-lg-8">
        </div>
        <div class="col-lg-3">
            <div id="totalDistance">Distance: 0 km
            </div>
            <div id="totalDuration">Duration: 0 min
            </div>
        </div>
    </div>
</div>

<script src="RouteMap.js"></script>

<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB4Co301GCKm89-LhBlERD_1cFkU3xHyJA&callback=initMap">
</script>

<?php
//Master Layout Footer
require_once "../Master/footer.php";
?>