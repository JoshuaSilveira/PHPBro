<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


require_once '../../models/StatusAccount.php';
require_once  '../../includes/Database.php';
require_once '../../controllers/StatusAccountController.php';
require_once '../../models/Goal.php';

use fitnessTracker\includes\Database;
use fitnessTracker\controllers\StatusAccountController\StatusAccountController;
use fitnessTracker\Models\Goal\Goal;
use fitnessTracker\Models\StatusAccount\StatusAccount;
require_once "../Master/header.php";
if(isset($_SESSION['id'])){
    
    $account_controller = new StatusAccountController(Database::GetDb());
    $currUser = $account_controller->getAccount($_SESSION['id'])[0];
    $goals = new Goal();
    $goals = $goals->GetByAccountId(Database::GetDb(),$_SESSION['id']);
    
    //var_dump($currUser);
    ?><div class="container mt-3">
        <div class="card">
            <div class="card-header"><h5><?echo $currUser->first_name;?>'s Account</h5></div>
            <div class="card-body">
                <h4 class="card-title">Username: <?echo $currUser->username;?></h4>
                <p>Your email: <?echo $currUser->email;?></p>
                <p>Your height: <?echo $currUser->current_height;?> inches</p>
                <p>Your weight: <?echo $currUser->current_weight;?> lbs</p>
                <p>Your experience: Level: <?echo $currUser->experience;?></p>
                <h5>Your Goals: </h5>
                <?php
                    if(count($goals)==0){
                        echo '<div><p class="text-muted">You dont have any goals!</p></div>';
                    }else{
                        
                        foreach($goals as $goal){
                            echo '<div><p>Goal: '.$goal->intent.'</p></div>';
                        }
                        if(count($goals)<=2){
                            echo '<div><p class="text-muted">You dont many goals! You should set more!</p></div>';
                        }
                        
                    }
                    echo '<a class ="btn btn-primary" href="../Goal/Add.php">Add a Goal</a>';
                    
                ?>

                <a class ="btn btn-primary"href="Update.php">Change Account Settings</a>
                <a class ="btn btn-danger"href="Delete.php">Delete Your Account</a>
            </div>
        </div>
        <div>
            <?

            if($currUser->is_admin==1){
                echo '<div class="card mt-3">';
                echo '<h3 class="card-header">Admin Panel</h3>';
                echo '<a class="card-link" href="List.php">List of accounts</a>';
                echo '</div>';
            }
                
                ?>
        </div>



    </div><?php


}else{
    echo '<div class="container mt-5">';
    echo "<h3>You are not logged in!</h3><br>";
    echo "<a class='btn btn-primary 'href='../../index.php'>Login here</a>";
    echo '</div>';
}
?>


<?php
require_once "../Master/footer.php";?>