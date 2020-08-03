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
$dateErr="";
$startErr="";
$endErr="";
$intentErr="";


if (isset($_POST['addGoal'])) {
    $intent = $_POST['intent'];
    $start_date =  $_POST['startDate'];
    $end_date = $_POST['endDate'];


    $isValid=true;
    if(empty($intent)){
        $intentErr="Cannot have a goal with out intentions!";
        $isValid=false;
    }

    if(empty($start_date)){
        $startErr="Cannot have a goal with out a start!";
        $isValid=false;
    }
    if(empty($end_date)){
        $endErr="Cannot have a goal without an end!";
        $isValid=false;
    }

    if($start_date > $end_date){
        $dateErr = "Start date cannot be after end date!";
        $isValid=false;
    }

    if($isValid){
        $goal = new Goal();

        $goal->Add(Database::GetDb(),[
            "account_id" => $_SESSION['id'],
            "intent" => $intent,
            "parent_id" => NULL,
            "is_parent" => 0,
            "start_date" => $start_date,
            "end_date" => $end_date,
            "is_complete" => 0


        ]
        );
        header("location:List.php");
    
        exit();
        

    }
}

//Master Layout Header
require_once "../Master/header.php";

if(isset($_SESSION['id'])){?>

<div class="container-fluid mt-3">
    <div class="card">
        <h5 class="card-header">Create A Goal</h5>
        <div class="card-body">
            <form method="post" action="Add.php">
                <div class="form-group">
                    <label for="intent">Your Goal: </label>
                    <input type="text" class="form-control" id="intent" name="intent" aria-describedby="intentHelp" placeholder="Enter your intentions"
                    value="<?php if(isset($_POST['intent']))echo $_POST['intent'];?>"
                    >
                    <small id="intentHelp" class="form-text text-muted">It is ok to make small goals first!</small>
                    <p class="text-danger"><?echo $intentErr;?></p>
                </div>
                <div class="form-group">
                    <label for="startDate">Start date: </label>
                    <input type="date" class="form-control" id="startDate" name="startDate" value="<?php if(isset($_POST['startDate']))echo $_POST['startDate'];?>">
                    <p class="text-danger"><?echo $startErr;?></p>
                </div>
                <div class="form-group">
                    <label for="endDate">End date: </label>
                    <input type="date" class="form-control" id="endDate" name="endDate" value="<?php if(isset($_POST['endDate']))echo $_POST['endDate'];?>">
                    <p class="text-danger"><?echo $endErr;?></p>
                </div>
                <p class="text-danger"><?echo $dateErr;?></p>
                <button type="submit" name="addGoal" class="btn btn-primary">Create</button>
            </form>
        </div>

    </div>
</div><?php
}else{
    echo '<div class="container mt-5">';
    echo "<h3>You are not logged in!</h3><br>";
    echo "<a class='btn btn-primary 'href='login.php'>Login here</a>";
    echo '</div>';
}
?>

<?php
//Master Layout Footer
require_once "../Master/footer.php";
?>