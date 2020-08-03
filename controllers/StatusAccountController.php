<?php
namespace fitnessTracker\controllers\StatusAccountController;
use fitnessTracker\Models\StatusAccount\StatusAccount;

//include_once '../models/StatusAccount.php';

class StatusAccountController{

    private $dbcon;
    public function __construct($dbcon)
    {
        $this->dbcon = $dbcon;
    }

    public function getAccount($id)
    {
        $sql = "SELECT * FROM accounts WHERE id = :id";
        $pdostm = $this->dbcon->prepare($sql);
        $pdostm->bindParam(':id', $id);
        $pdostm->execute();
        $accounts =  $pdostm->fetchAll(\PDO::FETCH_OBJ);
        return $accounts;
    }

    public function getAllAccounts()
    {
        $sql = "SELECT * FROM accounts";
        $pdostm =  $this->dbcon->prepare($sql);
        $pdostm->execute();

        $accounts = $pdostm->fetchAll(\PDO::FETCH_OBJ);
        return $accounts;
    }
    /**$inputs = username and password, password is already hashed before using this */
    public function returnUserID($inputs){
        $query = "SELECT * FROM accounts WHERE username = :username AND password = :password";
        $pdostm = $this->dbcon->prepare($query);
        $pdostm->bindParam(':username', $inputs["username"]);
        $pdostm->bindParam(':password', $inputs["password"]);
        $pdostm->execute();
        $users = $pdostm->fetchAll(\PDO::FETCH_OBJ);
        $user = $users[0];
        return $user->id;
    }

    public function addAccount(StatusAccount $account)
    {
        $username=$account->getUsername();
        $password=$account->getPassword();
        $email=$account->getEmail();
        $city=$account->getCity();
        $is_findable=$account->getIsFindable();
        $experience=$account->getExperience();
        $first_name=$account->getFirstName();
        $last_name=$account->getLastName();
        $current_weight=$account->getCurrentWeight();
        $current_height=$account->getCurrentHeight();
        $is_admin=$account->getIsAdmin();

        $sql = "INSERT INTO accounts (username,password,email,city,is_findable,experience,first_name,last_name,current_weight,current_height,is_admin) 
            values (:username,:password,:email,:city,:is_findable,:experience,:first_name,:last_name,:current_weight,:current_height,:is_admin)";
        $pdostm = $this->dbcon->prepare($sql);
        $pdostm->bindParam(':username', $username);
        $pdostm->bindParam(':password', $password);
        $pdostm->bindParam(':email', $email);
        $pdostm->bindParam(':city', $city);
        $pdostm->bindParam(':is_findable', $is_findable);
        $pdostm->bindParam(':experience', $experience);
        $pdostm->bindParam(':first_name', $first_name);
        $pdostm->bindParam(':last_name', $last_name);
        $pdostm->bindParam(':current_weight', $current_weight);
        $pdostm->bindParam(':current_height', $current_height);
        $pdostm->bindParam(':is_admin', $is_admin);


        $numRowsAffected = $pdostm->execute();

        return $numRowsAffected;
    }

    public function updateAccount($inputs){
        $query = "UPDATE accounts SET 
        username = :username, 
        password = :password,
        email = :email,
        city = :city,
        is_findable = :is_findable,
        experience = :experience,
        first_name = :first_name,
        last_name = :last_name,
        current_weight = :current_weight,
        current_height = :current_height,
        is_admin = :is_admin
        WHERE id =:id";

        $pdostm = $this->dbcon->prepare($query);
        $pdostm->bindParam(':username', $inputs['username']);
        $pdostm->bindParam(':password', $inputs['password']);
        $pdostm->bindParam(':email', $inputs['email']);
        $pdostm->bindParam(':city', $inputs['city']);
        $pdostm->bindParam(':is_findable', $inputs['is_findable']);
        $pdostm->bindParam(':experience', $inputs['experience']);
        $pdostm->bindParam(':first_name', $inputs['first_name']);
        $pdostm->bindParam(':last_name', $inputs['last_name']);
        $pdostm->bindParam(':current_weight', $inputs['current_weight']);
        $pdostm->bindParam(':current_height', $inputs['current_height']);
        $pdostm->bindParam(':is_admin', $inputs['is_admin']);
        $pdostm->bindParam(':id', $inputs['id']);

        $numRowsAffected = $pdostm->execute();


        if ($numRowsAffected) {
            echo "Updated an account.";
            //header("Location:List.php?action=updated");
        } else {
            echo "Problem updating " . $inputs['username'];
            $pdostm->debugDumpParams();
            var_dump($_POST);
        }

    }
    public function delete($id)
    {
        $query = "DELETE FROM accounts WHERE id = :id";
        $pdostm = $this->dbcon->prepare($query);
        $pdostm->bindParam(':id', $id);
        $numRowsAffected = $pdostm->execute();

        if ($numRowsAffected) {
            echo "Deleted an account";
            //header("Location: List.php?action=deleted");
            exit;
        } else {
            echo "Problem deleting an account";
        }
    }



}



?>