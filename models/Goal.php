<?php
namespace fitnessTracker\Models\Goal;
use fitnessTracker\Models\Feature\Feature;
require_once 'Feature.php';

class Goal implements Feature
{
    public function GetById($dbcon, $id)
    {
        $sql = "SELECT * FROM goals WHERE id = :id";
        $pdostm = $dbcon->prepare($sql);
        $pdostm->bindParam(':id', $id);
        $pdostm->execute();
        return $pdostm->fetchAll(\PDO::FETCH_OBJ);
    }

    public function GetByAccountId($dbcon, $id)
    {
        $sql = "SELECT * FROM goals WHERE account_id = :id";
        $pdostm = $dbcon->prepare($sql);
        $pdostm->bindParam(':id', $id);
        $pdostm->execute();
        return $pdostm->fetchAll(\PDO::FETCH_OBJ);
    }

    public function GetAll($dbcon)
    {
        $sql = "SELECT * FROM goals";
        $pdostm =  $dbcon->prepare($sql);
        $pdostm->execute();

        $goals = $pdostm->fetchAll(\PDO::FETCH_OBJ);
        return $goals;
    }

    public function Add($dbcon, $inputs)
    {
        $sql = "INSERT INTO goals (account_id, intent, parent_id, is_parent, start_date, end_date, is_complete) 
            values (:account_id, :intent, :parent_id, :is_parent, :start_date, :end_date, :is_complete)";
        $pdostm = $dbcon->prepare($sql);
        $pdostm->bindParam(':account_id', $inputs['account_id']);
        $pdostm->bindParam(':intent', $inputs['intent']);
        $pdostm->bindParam(':parent_id', $inputs['parent_id']);
        $pdostm->bindParam(':is_parent', $inputs['is_parent']);
        $pdostm->bindParam(':start_date', $inputs['start_date']);
        $pdostm->bindParam(':end_date', $inputs['end_date']);
        $pdostm->bindParam(':is_complete', $inputs['is_complete']);
   
        $numRowsAffected = $pdostm->execute();

        if ($numRowsAffected) {
            echo "Created a goal";
            header("Location: List.php");
            exit;
        }
        else {
            echo "Problem inserting";
        }
    }

    public function Update($dbcon, $inputs)
    {
        $sql = "UPDATE goals
                set account_id = :account_id,
                intent = :intent,
                parent_id = :parent_id,
                is_parent = :is_parent,
                start_date = :start_date, 
                end_date = :end_date,
                is_complete = :is_complete          
                WHERE id = :id
            ";
        $pdostm = $dbcon->prepare($sql);
        $pdostm->bindParam(':account_id', $inputs['account_id']);
        $pdostm->bindParam(':intent', $inputs['intent']);
        $pdostm->bindParam(':parent_id', $inputs['parent_id']);
        $pdostm->bindParam(':is_parent', $inputs['is_parent']);
        $pdostm->bindParam(':start_date', $inputs['start_date']);
        $pdostm->bindParam(':end_date', $inputs['end_date']);
        $pdostm->bindParam(':is_complete', $inputs['is_complete']);
        $pdostm->bindParam(':id', $inputs['id']);
        $numRowsAffected = $pdostm->execute();

        if ($numRowsAffected) {
            echo "Updated a goal";
            header("Location: List.php");
            exit;
        } else {
            $pdostm->debugDumpParams();
        }
    }

    public function Delete($dbcon, $id)
    {
        //Delete the data (row) from the database
        $sql = "DELETE FROM goals WHERE id = :id";
        $pdostm = $dbcon->prepare($sql);
        $pdostm->bindParam(':id', $id);
        $numRowsAffected = $pdostm->execute();

        if ($numRowsAffected) {
            echo "Deleted a goal";
            header("Location: List.php");
            exit;
        } else {
            echo "Problem deleting a goal";
        }
    }
}?>