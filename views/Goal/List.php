<?php
//Master Layout Header
require_once "../Master/header.php";
?>
<?php
use fitnessTracker\includes\Database;
use fitnessTracker\includes\Validator;
use fitnessTracker\models\Goal\Goal;

require_once '../../includes/Database.php';
require_once '../../includes/Validator.php';
require_once '../../models/Goal.php';

$goal = new Goal();//
$_SESSION['id']=1;
if(isset($_SESSION['id'])){
    $goals = $goal->GetByAccountId(Database::GetDb(),$_SESSION['id']);
    //var_dump($_SESSION['id']);
    echo '<div class="container mt-3">';
    echo '<h5>Your Goals</h5>';
    echo '</div>';
}else{
    
    echo '<div class="container mt-5">';
    echo "<h3>You are not logged in!</h3><br>";
    echo "<a class='btn btn-primary 'href='login.php'>Login here</a>";
    echo '</div>';
}
if(count($goals)==0){
    echo '<div class="container mt-3">';
    echo '<h6>You do not have any goals!</h6><br>';
    echo "<a class='btn btn-primary' href='Add.php'>Create a Goal!</a>";
    echo '</div>';
    
}

?>

<div class="container mt-3">
    <div class="card-deck mt-3">
        <?php 
            
            foreach($goals as $goal){?>
            <div class="card">
                <h5 class="card-header"><?php echo $goal->intent;?></h5>
                <div class="card-body">
                    <p><span class="font-weight-bold">Start Date:</span> <?php echo $goal->start_date?></p>
                    <p><span class="font-weight-bold">End Date:</span> <?php echo $goal->end_date?></p>
                    
                    <form action="Update.php" method="POST">
                        <input type="hidden" name="goalid" value="<?php echo $goal->id;?>">
                        <button type="submit" name="update" class="btn btn-primary">Update this Goal</button>
                    </form>
                </div>
            </div> 
            <?php
            }
        ?>
    </div>
    <div class="container mt-3">
    <a class='btn btn-primary' href='Add.php'>Add a Goal</a>
    </div>
<div>    
<?php
//Master Layout Footer
require_once "../Master/footer.php";
?>    