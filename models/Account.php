<?php

namespace fitnessTracker\Models\Account;

use fitnessTracker\Models\Feature\Feature;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once 'Feature.php';
// Load Composer's autoloader
require '../../vendor/autoload.php';

/**
 * A class holding CRUD methods for the Accounts table and feature. Method names used from Chris Maeda's Feature interface for consistency.
 */
class Account implements Feature
{
    /**
     * returns the account with the given id
     */
    public function GetById($dbcon, $id)
    {
        $sql = "SELECT * FROM accounts WHERE id = :id";
        $pdostm = $dbcon->prepare($sql);
        $pdostm->bindParam(':id', $id);
        $pdostm->execute();
        $accounts = $pdostm->fetchAll(\PDO::FETCH_ASSOC);
        return $accounts[0];
    }

    /**
     * Returns a list of all accounts
     */
    public function GetAll($dbcon)
    {
        $sql = "SELECT * FROM accounts";
        $pdostm =  $dbcon->prepare($sql);
        $pdostm->execute();

        $accounts = $pdostm->fetchAll(\PDO::FETCH_ASSOC);
        return $accounts;
    }

    /**
     * Returns a list of all users in with the same city set as the user with the given id, not including the the given user id
     */
    public function BrosInYourCity($dbcon, $id)
    {
        //TODO: city comparison is currently case sensitive - change it to not be case sensitive
        $loggedInUser = $this->GetById($dbcon, $id);
        $userCity = $loggedInUser["city"];

        $sql = "SELECT * FROM accounts WHERE city = :city AND id != :id AND is_findable = 1";
        $pdostm =  $dbcon->prepare($sql);
        $pdostm->bindParam(':city', $userCity);
        $pdostm->bindParam(':id', $id);
        $pdostm->execute();
        $accounts = $pdostm->fetchAll(\PDO::FETCH_ASSOC);
        return $accounts;
    }

    /**
     * FOR ADMIN VIEW OF FINDABRO/LIST
     * Returns a list of all users that are findable in the find a bro system
     */
    public function AdminBrosInYourCity($dbcon)
    {

        $sql = "SELECT * FROM accounts WHERE is_findable = 1";
        $pdostm =  $dbcon->prepare($sql);
        $pdostm->execute();
        $accounts = $pdostm->fetchAll(\PDO::FETCH_ASSOC);
        return $accounts;
    }

    /**
     * sets the given is_findable value for the account with the given id
     * @param PDO $dbcon
     * @param int $id the account ID
     * @param int $isFindable either 0 or 1
     */
    public function setIsFindable($dbcon, $id, $isFindable)
    {
        $query = "UPDATE accounts SET is_findable = :is_findable WHERE id = :id";
        $pdostm = $dbcon->prepare($query); //prepares
        //similar to sqlParam in asp.net
        $pdostm->bindParam(':is_findable', $isFindable);
        $pdostm->bindParam(':id', $id);

        $numRowsAffected = $pdostm->execute();

        if ($numRowsAffected) {
            echo "Updated an account.";
            header("Location:List.php");
        } else {
            echo "Problem updating account with id = " . $id;
            $pdostm->debugDumpParams();
            var_dump($_POST);
        }
    }

    /**
     * Sends an email fron the recipient to the sender for the Find A Bro feature.
     * Uses PHPMailer.
     * @param PDO $dbcon database connection
     * @param int $senderId the ID of the account sending the email
     * @param int $recipientId the ID of the account receiving the email
     * @param string $subject the subject line of the email
     * @param string $message the body text of the email
     */
    public function sendMail($dbcon, $senderId, $recipientId, $subject, $message)
    {
        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        $senderAccount = $this->GetById($dbcon, $senderId);
        $recipientAccount = $this->GetById($dbcon, $recipientId);

        //below code from PHPMailer README file
        //sending mail from a godaddy server - https://stackoverflow.com/questions/21841834/phpmailer-godaddy-server-smtp-connection-refused
        try {
            //Server settings (for Sam Bebenek's server) - comment these settings out and add your own to send email from your own server
            //uncomment line below if you want debug messages echoed
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'localhost';                    // Set the SMTP server to send through
            $mail->Port       = 25;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
            $mail->SMTPAuth   = false;                                   // Enable SMTP authentication
            $mail->Username   = 'no-reply@sambebenek.com';                     // SMTP username
            $mail->Password   = 'samnoreply';                               // SMTP password
            $mail->SMTPSecure = false;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->SMTPAutoTLS = false;
        
            //Recipients
            $mail->setFrom('no-reply@sambebenek.com', 'FitnessTracker No-Reply');
            $mail->addAddress($recipientAccount["email"], $recipientAccount["first_name"] . " " . $recipientAccount["last_name"]);     // Add a recipient
            //$mail->addAddress('ellen@example.com');               // Name is optional
            //$mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');
        
        
            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = '<h1>FitnessTracker Find A Bro</h1><h2>'.$senderAccount["username"].' has sent you a message!</h2>'.
            '<p>'.$message.'</p><hr /><p>To reply to this message, you can send '.$senderAccount["username"].' an email at '.$senderAccount["email"].".".
            '<p><em>This email was send from FitnessTracker\'s automated service. Please do not reply to this email.</em></p>';
            $mail->AltBody = 'FitnessTracker Find A Bro. '.$senderAccount["username"].' has sent you a message! '.
            $message.'. To reply to this message, you can send '.$senderAccount["username"].' an email at '.$senderAccount["email"].".".
            ' This email was send from FitnessTracker\'s automated service. Please do not reply to this email.';
        
            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }


    /**
     * Adds a meal to the database with the given parameters.
     * @param PDO $dbcon The database connection
     * @param string $name The name of the meal
     * @param int $calories The amount of calories
     * @param int $protein The amount of protein in grams
     * @param int $preptime The preperation time in minutes
     * @param bool $isVegan Whether the meal is vegan or not
     * @param string $description The description of the meal
     * @param string $url The url for cooking instructions
     * @return numRowsAffected
     */
    /*public function AddMeal($dbcon, $name, $calories, $protein, $preptime, $isVegan, $description, $url)
    {
        $image = ''; //image is empty at the add stage. Will be uploaded in update

        //SQL order - prepare, bind, execute 
        $query = "INSERT into meals (name, image_ext, calories, protein, prep_time, is_vegan, description, url) VALUES (:name, :image, :calories, :protein, :preptime, :isvegan, :description, :url)";
        $pdostm = $dbcon->prepare($query); //prepares
        //similar to sqlParam in asp.net
        $pdostm->bindParam(':name', $name);
        $pdostm->bindParam(':image', $image);
        $pdostm->bindParam(':calories', $calories);
        $pdostm->bindParam(':protein', $protein);
        $pdostm->bindParam(':preptime', $preptime);
        $pdostm->bindParam(':isvegan', $isVegan);
        $pdostm->bindParam(':description', $description);
        $pdostm->bindParam(':url', $url);

        $numRowsAffected = $pdostm->execute();
        return $numRowsAffected;
    }*/

    //has to be here for interface to not crash
    public function Add($dbcon, $inputs)
    {
    }

    //has to be here for interface to not crash
    public function Update($dbcon, $inputs)
    {
    }



    /**
     * Deletes a meal at the given ID from the database.
     * @param PDO $dbcon The database connection
     * @param string $id The ID of the row being deleted
     */
    public function Delete($dbcon, $id)
    {
        //TODO: delete associated image file

        /*$query = "DELETE FROM meals WHERE id = :id";
        $pdostm = $dbcon->prepare($query);
        $pdostm->bindParam(':id', $id);
        $numRowsAffected = $pdostm->execute();

        if ($numRowsAffected) {
            echo "Deleted a meal";
            header("Location: List.php?action=deleted");
            exit;
        } else {
            echo "Problem deleting a meal";
        }*/
    }
}
