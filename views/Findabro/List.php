<?php
//Master Layout Header
require_once "../Master/header.php";

use fitnessTracker\includes\Database;
use fitnessTracker\models\Account\Account;

require_once '../../includes/Database.php';
require_once '../../models/Account.php';

if (!isset($_SESSION["id"])) {
    //if the user is not logged in
    header("Location: ../Home/Index.php");
    exit();
}



$dbcon = Database::GetDb();
$accountObject = new Account();

$loggedInAccount = $accountObject->GetById($dbcon, $_SESSION["id"]);
$city = $loggedInAccount["city"];
$accounts = $accountObject->BrosInYourCity($dbcon, $_SESSION["id"]);
$isAdmin = false; //whether the logged in user is an admin or not
if (isset($_SESSION["is_admin"])) {
    if ($_SESSION["is_admin"] == 1) {
        $isAdmin = true;
        //if logged in user is an admin, show all accounts that are findable
        $accounts =  $accountObject->AdminBrosInYourCity($dbcon);
    }
}

//if the become discoverable button was clicked, set this account to be findable
if (isset($_POST["becomeFindable"])) {
    $accountObject->setIsFindable($dbcon, $_SESSION["id"], 1);
}

//if the stop being discoverable button was clicked, set this account to not be findable
if (isset($_POST["stopBeingFindable"])) {
    $accountObject->setIsFindable($dbcon, $_SESSION["id"], 0);
}

$toUsername = $messageSubject = $messageText = "";
$messageSent = false;
//if a message was just sent on the details.php page
if (isset($_POST["toUsername"])) {
    $messageSent = true;
    $toUsername = $_POST["toUsername"];
    $messageSubject = $_POST["subject"];
    $messageText = $_POST["message"];
}


//**IF THE USER IS AN ADMIN, SHOW ALL USERS - ELSE ONLY SHOW THE USERS IN THE SAME CITY AS THE LOGGED IN USER */
?>
<main class="p-5">
    <h1>Find A Bro</h1>
    <?php if ($messageSent == true) { //if a message was just sent, show the alert
    ?>
        <div class="alert alert-success" role="alert">
            <p><strong>Message sent! </strong>Please wait for <?= $toUsername ?> to send you a reply email.</p>
            <p><strong>Your message: </strong></p>
            <p>Subject: <?= $messageSubject ?></p>
            <p>Message: <?= $messageText ?></p>
        </div>
    <?php } ?>

    <?php if ($isAdmin == true || $loggedInAccount["is_findable"] == 1) { //show list if to all admins and to users that are findable 
    ?>
        <p>Fitness Tracker's Find A Bro system allows for you to find another bro in your city that is interested in working out with someone. Click on their name to send them a message!<br /><small>Note: Bro is a gender neutral term.</small></p>
        <?php if ($isAdmin == true) { ?>
            <h3>Admin View</h3>
            <p>Showing all accounts set to be discoverable in the Find A Bro feature.</p>
            <?php if ($loggedInAccount["is_findable"] == 0) { //if an admin account is not findable, show option to become findable
            ?>
                <p>Your account is currently not set to allow others to see you in the Find A Bro system. Admins will not be able to send messages in the Find A Bro system
                    until the account is set to be discoverable. Would you like to become discoverable? Note: You can always change this later. Admins will be able to see all users in the system.</p>
                <form action="" method="POST">
                    <button type="submit" name="becomeFindable" value="" class="btn btn-sm btn-success">Become Discoverable</button>
                </form>
            <?php } else { //admin account is findable, show option to stop being findable 
            ?>
                <p>Your account is currently discoverable by other users in your city. Would you like to stop being discoverable? Note: admin accounts will still be able to see all accounts, but won't be able to send messages.</p>
                <form action="" method="POST">
                    <button type="submit" name="stopBeingFindable" value="" class="btn btn-sm btn-success">Stop Being Discoverable</button>
                </form>
            <?php } ?>
        <?php } else { ?>
            <?php if ($loggedInAccount["is_findable"] == 1) { //if the account is discoverable, show option to change to be not findable
            ?>
                <p>Your account is currently discoverable by other users in your city. Would you like to stop being discoverable? Note: you must be discoverable to use the Find A Bro feature.</p>
                <form action="" method="POST">
                    <button type="submit" name="stopBeingFindable" value="" class="btn btn-sm btn-success">Stop Being Discoverable</button>
                </form>
            <?php } ?>
            <h2>Bros in <?= $city ?>:</h2>
        <?php } ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <th>Username</th>
                    <th>City</th>
                    <th></th>

                </thead>
                <tbody>
                    <?php
                    foreach ($accounts as $a) {
                        echo "<tr>";

                        //final-meals\images\default.jpg
                        echo "<td>" . $a["username"] . "</td>";
                        echo "<td>" . $a["city"] . "</td>";
                    ?>
                        <?php if ($isAdmin && $loggedInAccount["is_findable"] == 0) { //if the logged in admin isn't set to findable, they shouldnt be able to message anyone
                        ?>
                            <td>
                                <form action="Details.php" method="POST"><button type="submit" name="sendMessageTo" value="<?= $a["id"] ?>" class="btn btn-primary" disabled>Send Message</button></form>
                            </td>
                        <?php } else { ?>
                            <td>
                                <form action="Details.php" method="POST"><button type="submit" name="sendMessageTo" value="<?= $a["id"] ?>" class="btn btn-primary">Send Message</button></form>
                            </td>
                    <?php }

                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    <?php } else { //the user is not an admin and not set to findable, so ask if they want to become findable 
    ?>
        <p>Fitness Tracker's Find A Bro system allows for you to find another bro in your city that is interested in working out with someone.<br /><small>Note: Bro is a gender neutral term.</small></p>
        <p>Your account is currently not set to allow others to see you in the Find A Bro system. You will not be able to use the Find A Bro system
            until your account is set to be discoverable. Would you like to become discoverable? Note: You can always change this later.</p>
        <form action="" method="POST">
            <button type="submit" name="becomeFindable" value="" class="btn btn-success">Become Discoverable</button>
        </form>

    <?php } ?>

</main>
<?php
//Master Layout Footer
require_once "../Master/footer.php";
?>