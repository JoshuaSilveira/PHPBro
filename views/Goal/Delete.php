<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use fitnessTracker\includes\Database;
use fitnessTracker\includes\Validator;
use fitnessTracker\models\Goal\Goal;

require_once '../../includes/Database.php';
require_once '../../includes/Validator.php';
require_once '../../models/Goal.php';

if(isset($_POST['delete'])){
    
    $goal = new Goal();
    
    $goal->Delete(Database::GetDb(),(int)$_POST['id']);
    
    header("location:List.php");
    
    exit();


}

//Master Layout Header
require_once "../Master/header.php";

if(isset($_POST['deleteGoal'])){
    if(isset($_SESSION['id'])){?>
    <div class="contianer">
        <div class="card" style="width:20rem;">
            <h5 class="car-header">Are You sure you want to delete this Goal?</h5>
            <div class="card-body">
            
                <form method="post" action="">
                    <input type="hidden" name="id" value="<?echo $_POST['goalid'];?>">  
                    <button type="submit" name="delete" class="btn btn-danger">Yes</button>
                </form>
                <a class='btn btn-primary 'href='List.php'>Nope Send me back to my Goal list!</a>
            </div>
        </div>
    </div>
        
    <?php }else{
        echo '<div class="container mt-5">';
        echo "<h3>You are not logged in!</h3><br>";
        echo "<a class='btn btn-primary 'href='login.php'>Login here</a>";
        echo '</div>';
    }
}
?>


<?php
//Master Layout Footer
require_once "../Master/footer.php";
?>