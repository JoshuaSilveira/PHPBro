<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
use fitnessTracker\includes\Database;
use fitnessTracker\includes\Validator;
use fitnessTracker\models\StatusAccount\StatusAccount;
use fitnessTracker\controllers\StatusAccountController\StatusAccountController;
//Master Layout Header
require_once "../Master/header.php";
require_once '../../includes/Database.php';
require_once '../../includes/Validator.php';
require_once '../../models/StatusAccount.php';
require_once '../../controllers/StatusAccountController.php';

    $unameErr="";
    $passErr="";
    $emailErr="";
    $cityErr="";
    $expErr="";
    $fnErr="";
    $lnErr="";
    $heightErr="";
    $weightErr="";
    $accountID="";
    $currUser="";


if(isset($_POST['editAccount'])){
    $account_controller = new StatusAccountController(Database::GetDb());
    $currUser = $account_controller->getAccount($_POST['thisAccountId'])[0];
    //var_dump($currUser);
    
}

if(isset($_POST['updateThisAccount'])){
    $account_controller = new StatusAccountController(Database::GetDb());
    $currUser = $account_controller->getAccount($_POST['uid'])[0];
    var_dump($currUser);
   
    $isValid=true;
    if(!empty($_POST['username'])){
        $username=Validator::scrubInput($_POST['username']);
    }else{
        $unameErr = "Please Enter a Username";
        $isValid=false;
    }
    
    
    if(!empty($_POST['password'])){
        $password=password_hash($_POST['password'],PASSWORD_DEFAULT);
    }else{
        $password=$currUser->password;
    }
    
    if(!empty($_POST['email'])){
        $email=$_POST['email'];
    }else{
        $emailErr = "Please Enter an email";
        $isValid=false;
    }

    if(!empty($_POST['city'])){
        $city=Validator::scrubInput($_POST['city']);
    }else{
        $cityErr = "Please Enter an city";
        $isValid=false;
    }

    if($_POST['isFindable']=='on'){
        $isFindable=1;
    }else{
        $isFindable=0;
    }

   
    $exp=$_POST['exp'];
    
    
    
    if(!empty($_POST['fname'])){
        $fname=Validator::scrubInput($_POST['fname']);
    }else{
        $fnErr = "Please enter your first name";
        $isValid=false;
    }

    if(!empty($_POST['lname'])){
        $lname=Validator::scrubInput($_POST['lname']);
    }else{
        $lnErr = "Please enter your last name";
        $isValid=false;
    }

    if(!empty($_POST['weight'])){
        $weight=$_POST['weight'];
    }else{
        $weightErr = "Please enter current weight";
        $isValid=false;
    }

    if(!empty($_POST['height'])){
        $height=$_POST['height'];
    }else{
        $heightErr = "Please enter current height";
        $isValid=false;
    }

    if($_POST['isAdmin']=='on'){
        $isAdmin=1;
    }else{
        $isAdmin=0;
    }
    var_dump($username,$password,$exp);
    if($isValid){
        //$changeAccount = [new StatusAccount(]$username, $password, $email, $city, $isFindable, $exp, $fname, $lname, $weight, $height, $isAdmin);
        $account_controller = new StatusAccountController(Database::getDb());
        $account_controller->updateAccount([
            "username" => $username,
            "password" => $password,
            "email"=>$email,
            "city"=>$city,
            "is_findable"=>$isFindable,
            "experience"=>$exp,
            "first_name"=>$fname,
            "last_name"=>$lname,
            "current_weight"=>$weight,
            "current_height"=>$height,
            "is_admin"=>$isAdmin,
            "id"=>$_POST['uid']
        ]
    
        ); 
        //$_SESSION['id']=$account_controller->returnUserID(["username" => $username,"password" => $password]);
        header("location:Details.php");
    
        exit();
        
    }

}

?>
<div class="mx-auto p-5 w-75">
    <h1>Admin Edit</h1>
    <form action="" method="post">
        <div class="form-group">
            <label>Username:</label>
            <input type="text" name="username" class="form-control" value="<?echo $currUser->username;?>" ></input>
            <div><?echo $unameErr?></div>
        </div>
        
        <div class="form-group">
            <label>password:</label>
            <input type="password" name="password" class="form-control" ></input>
            <div><?echo $passErr?></div>
        </div>

        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" value="<?echo $currUser->email;?>"></input>
            <div><?echo $emailErr?></div>
        </div>

        <div class="form-group">
            <label>City:</label>
            <input type="text" name="city" class="form-control" value="<?echo $currUser->city;?>"></input>
            <div><?echo $cityErr?></div>
        </div>

        <div class="form-group">
            <input type="checkbox" name="isFindable" id="isFindable" class="form-check-input" <?php if ($currUser->is_findable == 1) {
                                                                                                echo "checked";
                                                                                            } ?>></input>
            <label for="isFindable" class="form-check-label">Findable acccount</label>
        </div>

        <div class="form-group">
            <label>Experience:</label>
            <input type="number" name="exp" class="form-control" value="<?echo $currUser->experience;?>" ></input>
            <div><?echo $expErr?></div>
        </div>

        <div class="form-group">
            <label>First Name:</label>
            <input type="text" name="fname" class="form-control" value="<?echo $currUser->first_name;?>"></input>
            <div><?echo $fnErr?></div>
        </div>
        <div class="form-group">
            <label>Last name:</label>
            <input type="text" name="lname" class="form-control" value="<?echo $currUser->last_name;?>"></input>
            <div><?echo $lnErr?></div>
        </div>

        <div class="form-group">
            <label>Current Weight in pounds:</label>
            <input type="number" name="weight" class="form-control" value="<?echo $currUser->current_weight;?>"></input>
            <div><?echo $weightErr?></div>
        </div>

        <div class="form-group">
            <label>Current height in inches:</label>
            <input type="number" name="height" class="form-control" value="<?echo $currUser->current_height;?>"></input>
            <div><?echo $heightErr?></div>

        </div>
        <div class="form-group">
            <input type="checkbox" name="isAdmin" id="isAdmin" class="form-check-input" <?php if ($currUser->is_admin == 1) {
                                                                                                echo "checked";
                                                                                            } ?>></input>
            <label for="isAdmin" class="form-check-label">Admin acccount</label>
        </div>

        <input type="hidden" name="uid" value="<?echo $currUser->id;?>">

        <div></div>
        <button type="submit" name="updateThisAccount" class="btn btn-primary">Submit</button>
    </form> 
</div>   