<?php
//Master Layout Header
require_once "../Master/header.php";

use fitnessTracker\includes\Database;
use fitnessTracker\models\Account\Account;
use fitnessTracker\includes\Validator;

require_once '../../includes/Database.php';
require_once '../../models/Account.php';
require_once '../../includes/Validator.php';


if (!isset($_POST["sendMessageTo"])) //if no post data (ie. send message button wasn't clicked), redirect to list page
{
    header("Location:List.php");
}

//if user is not logged in, redirect - they shouldn't see this page, redirect to list
if (!isset($_SESSION["id"])) {
    header("Location:List.php");
}


$dbcon = Database::GetDb();
$accountObject = new Account();

$loggedInAccount = $accountObject->GetById($dbcon, $_SESSION["id"]);
$receiverId = $_POST["sendMessageTo"]; //the id of the account shown in this details page
$messageToAccount = $accountObject->GetById($dbcon, $receiverId); //the account the message on this page will be sent to

//if the logged in user OR the message recipient account isn't findable, redirect to list
if ($loggedInAccount["is_findable"] == 0 || $messageToAccount["is_findable"] == 0) {
    header("Location:List.php");
}

//var_dump($_POST);
//var_dump($loggedInAccount);
//var_dump($messageToAccount);

//whether or not a message was just sent
$messageSent = false;
$messageSubject = $messageText = "";
$subjectError = $messageError = "";
//if the message form was submitted
if (isset($_POST["messageSubmit"])) {
    $isValid = true;
    //scrub the inputs of any special characters
    $messageSubject = Validator::scrubInput($_POST["subject"]);
    $messageText = Validator::scrubInput($_POST["message"]);

    if ($messageSubject == null || $messageSubject == "") {
        $subjectError = "* Required field";
        $isValid = false;
    }

    if ($messageText == null || $messageText == "") {
        $messageError = "* Required field";
        $isValid = false;
    }

    if ($isValid == true) {
        $messageSent = true;
        //do emailing here

        $accountObject->sendMail($dbcon, $_SESSION["id"], $receiverId, $messageSubject, $messageText);
        //only way I can think of to send post data back to list.php
?>
        <form action="List.php" method="post" name="message_sent">
            <input type="text" name="toUsername" value="<?= $messageToAccount["username"] ?>">
            <input type="text" name="subject" value="<?= $messageSubject ?>">
            <input type="text" name="message" value="<?= $messageText ?>">
            <input type="submit" name="messageSent" value="">
        </form>
        <script>
            /******TO SEE DEBUG MESSAGES, UNCOMMENT THE DEBUG LINE IN THE ACCOUNT.PHP MODEL AND REMOVE THE SCRIPT BELOW TO STOP THE PAGE FROM REDIRECTING*****/
            //will autosubmit form on pageload
            window.onload = function() {
                document.forms['message_sent'].submit();
            }
        </script>
<?php
    }
}


?>

<main class="p-5">
    <?php if ($messageSent == true) { //if the message was sent
    ?>
        <div class="alert alert-success" role="alert">
            <p><strong>Message sent! </strong>Please wait for <?= $messageToAccount["username"] ?> to send you a reply email.</p>
            <p><strong>Your message: </strong></p>
            <p>Subject: <?= $messageSubject ?></p>
            <p>Message: <?= $messageText ?></p>
        </div>
    <?php } ?>
    <h2>Send A Message to <?= $messageToAccount["username"] ?></h2>
    <p>Send <?= $messageToAccount["username"] ?> a message about your interest in working out with them. This message will be sent to their email address and will also contain your email address, so that they may reply to you.</p>
    <p><em>FitnessTracker is a friendly and welcoming community. Please do not sending anything that could be considered rude, offensive, or illegal. Follow best safety practices when meeting up with someone you've met online.</em></p>
    <hr />
    <form action="" method="POST">
        <div class="form-group">
            <label for="subject">Subject: </label>
            <input type="text" class="form-control" id="subject" name="subject" value="<?= $messageSubject ?>">
            <span class="text-danger"><?= $subjectError ?></span>
        </div>

        <div class="form-group">
            <label for="message">Message: </label>
            <textarea class="form-control" id="message" name="message" rows="5"><?= $messageText ?></textarea>
            <span class="text-danger"><?= $messageError ?></span>

        </div>
        <input type="hidden" name="sendMessageTo" value="<?= $receiverId ?>">
        <a href="List.php" class="btn btn-default">Go Back</a>
        <button type="submit" class="btn btn-primary" name="messageSubmit" value="">Send</button>

    </form>
</main>