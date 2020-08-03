<?php
namespace fitnessTracker\Models\Calorie;
use fitnessTracker\Models\Feature\Feature;
require_once 'Feature.php';

class Calorie implements Feature
{
    public function GetById($dbcon, $id)
    {
        $sql = "SELECT * FROM calories WHERE id = :id";
        $pdostm = $dbcon->prepare($sql);
        $pdostm->bindParam(':id', $id);
        $pdostm->execute();
        return $pdostm->fetchObject(\PDO::FETCH_OBJ);
    }

    public function GetAll($dbcon)
    {
        $sql = "SELECT * FROM calories";
        $pdostm =  $dbcon->prepare($sql);
        $pdostm->execute();

        $calories = $pdostm->fetchAll(\PDO::FETCH_OBJ);
        return $calories;
    }

    public function Add($dbcon, $inputs)
    {
        $sql = "INSERT INTO calories (account_id, height, weight, date, intake, burned) 
            values (:account_id, :height, :weight, :date, :intake, :burned)";
        $pdostm = $dbcon->prepare($sql);
        $pdostm->bindParam(':account_id', $inputs['account_id']);
        $pdostm->bindParam(':height', $inputs['height']);
        $pdostm->bindParam(':weight', $inputs['weight']);
        $pdostm->bindParam(':date', $inputs['date']);
        $pdostm->bindParam(':intake', $inputs['intake']);
        $pdostm->bindParam(':burned', $inputs['burned']);
        $numRowsAffected = $pdostm->execute();

        if ($numRowsAffected) {
            echo "Inserted a calorie";
            header("Location: List.php");
            exit;
        }
        else {
            echo "Problem inserting";
        }
    }

    public function Update($dbcon, $inputs)
    {
        $sql = "Update calories
                set account_id = :account_id,
                height = :height,
                weight = :weight,
                date = :date,
                intake = :intake,
                burned = :burned,              
                WHERE id = :id
            ";
        $pdostm = $dbcon->prepare($sql);
        $pdostm->bindParam(':account_id', $inputs['account_id']);
        $pdostm->bindParam(':height', $inputs['height']);
        $pdostm->bindParam(':weight', $inputs['weight']);
        $pdostm->bindParam(':date', $inputs['date']);
        $pdostm->bindParam(':intake', $inputs['intake']);
        $pdostm->bindParam(':burned', $inputs['burned']);
        $pdostm->bindParam(':id', $inputs['id']);
        $numRowsAffected = $pdostm->execute();

        if ($numRowsAffected) {
            echo "Update a calorie";
            header("Location: List.php");
            exit;
        } else {
            echo "Problem updating a calorie";
        }
    }

    public function Delete($dbcon, $id)
    {
        //Delete the data (row) from the database
        $sql = "DELETE FROM calories WHERE id = :id";
        $pdostm = $dbcon->prepare($sql);
        $pdostm->bindParam(':id', $id);
        $numRowsAffected = $pdostm->execute();

        if ($numRowsAffected) {
            echo "Delete a calorie";
            header("Location: List.php");
            exit;
        } else {
            echo "Problem deleting a calorie";
        }
    }
}