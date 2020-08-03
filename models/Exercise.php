<?php

namespace fitnessTracker\Models;
use fitnessTracker\Models\Feature\Feature;
require_once 'Feature.php';


//Generic CRUD feature elements created by Christopher Maeda, and used/modified to maintain consistency

//simple CRUD controller for Exercises
class Exercise implements Feature
{
    public function GetById($dbcon, $id)
    {
        $sql = "SELECT * FROM exercises WHERE id = :id";
        $pdostm = $dbcon->prepare($sql);
        $pdostm->bindParam(':id', $id);
        $pdostm->execute();
        return $pdostm->fetch(\PDO::FETCH_OBJ);
    }

    public function GetAll($dbcon)
    {
        $sql = "SELECT * FROM exercises";
        $pdostm =  $dbcon->prepare($sql);
        $pdostm->execute();

        $exercises = $pdostm->fetchAll(\PDO::FETCH_OBJ);
        return $exercises;
    }

    public function Add($dbcon, $inputs)
    {
        $sql = "INSERT INTO exercises (name, body_weight, cardio) 
            values (:name, :body_weight, :cardio)";
        $pdostm = $dbcon->prepare($sql);
        $pdostm->bindParam(':name', $inputs['name']);
        $pdostm->bindParam(':body_weight', $inputs['body_weight']);
        $pdostm->bindParam(':cardio', $inputs['cardio']);
        $numRowsAffected = $pdostm->execute();

        if ($numRowsAffected) {
            echo "Exercise added";
            header("Location: List.php");
            exit;
        }
        else {
            echo "error adding exercise";
        }
    }

    public function Update($dbcon, $inputs)
    {
        $sql = "Update exercises
                set name = :name,
                body_weight = :body_weight,
                cardio = :cardio              
                WHERE id = :id
            ";
        $pdostm = $dbcon->prepare($sql);
        $pdostm->bindParam(':name', $inputs['name']);
        $pdostm->bindParam(':body_weight', $inputs['body_weight']);
        $pdostm->bindParam(':cardio', $inputs['cardio']);
        $pdostm->bindParam(':id', $inputs['id']);
        $numRowsAffected = $pdostm->execute();

        if ($numRowsAffected) {
            echo "Updated exercise";
            header("Location: List.php");
            exit;
        } else {
            echo "error updating exercise";
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