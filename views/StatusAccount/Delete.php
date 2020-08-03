<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//Master Layout Header
require_once "../Master/header.php";

use fitnessTracker\includes\Database;
use fitnessTracker\includes\Validator;
use fitnessTracker\models\StatusAccount\StatusAccount;
use fitnessTracker\controllers\StatusAccountController\StatusAccountController;

require_once '../../includes/Database.php';
require_once '../../includes/Validator.php';
require_once '../../models/StatusAccount.php';
require_once '../../controllers/StatusAccountController.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}  



$account_controller = new StatusAccountController(Database::GetDb());
$currUser = $account_controller->getAccount($_SESSION['id'])[0];

if(isset($_POST['deleteAccount'])){
    $account_controller->delete($currUser->id);
    session_destroy();
    header("location:../Login/Login.php");
    
}
?>
<div class="container mt-3">
        <div class="card">
            <div class="card-header text-center"><h5>Are you sure you want delete your account <?echo $currUser->first_name;?> ?</h5></div>
            <div class="card-body">
                
                <p class="text-center">You can recreate an account but you will loose all your data!</p>
                <div class="text-center">
                    <form method="post" action="">
                        <button class ="btn btn-danger" type="submit" name="deleteAccount">Yes I am sure</button>
                    </form>
                    
                    <a class ="btn btn-primary"href="Details.php">Hmmm maybe not</a>

                </div>
                
            </div>
        </div>

        <?php
require_once "../Master/footer.php";?>