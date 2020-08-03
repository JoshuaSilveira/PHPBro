<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}



use fitnessTracker\includes\Database;
use fitnessTracker\includes\Validator;
use fitnessTracker\models\StatusAccount\StatusAccount;
use fitnessTracker\controllers\StatusAccountController\StatusAccountController;

require_once '../../includes/Database.php';
require_once '../../includes/Validator.php';
require_once '../../models/StatusAccount.php';
require_once '../../controllers/StatusAccountController.php';


//Master Layout Header
require_once "../Master/header.php";
echo '<div class="container mt-2">';
if(isset($_SESSION['id'])){
    $account_controller = new StatusAccountController(Database::GetDb());
    $currUser = $account_controller->getAccount($_SESSION['id'])[0];
    if($currUser->is_admin==1){
        echo '<h2>Accounts List</h2>';
        $account_controller = new StatusAccountController(Database::GetDb());
        $allAccounts =$account_controller->getAllAccounts();
        
        foreach($allAccounts as $account){
            echo '<div class="card">
                    <div class="card-body">
                        <div><h4 class="card-title">ID: '.$account->id.' Username: '.$account->username.'</h></div>
                        <form method="post" action="adminUpdate.php">
                            <input type="hidden" name="thisAccountId" value="'.$account->id.'">
                            <button class="btn btn-primary" name="editAccount" type="submit">Edit This Acccount</button>
                        </form>
                    </div>
                  </div>';
        }
    }else{
        echo '<div class="card">
                <div class="card-body">
                    <h4 class="card-title">Oops there isnt any there</h>
                    <a href="Details.php" class="card-link">Go back to your account</a>
                </div>
            </div>';  
    }
    
}else{
    echo 'looks like you aint logged in chief';
}
echo '</div>';
?>

