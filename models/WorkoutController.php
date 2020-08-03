<?php

require_once 'Workout.php';

class WorkoutController
{
    private $dbcon;
    public function __construct($dbcon)
    {
        $this->dbcon = $dbcon;
    }

    public function listWorkouts($accountid, $isadmin, $sort = '')
    {
        if ($isadmin === 0)
        {
            $sql = "SELECT work_out_items.*, exercises.name, exercises.body_weight, exercises.cardio FROM work_out_items inner join exercises on work_out_items.exercise_id = exercises.id where account_id = :accountid";
            $sql .= $sort;
            $pdostm = $this->dbcon->prepare($sql);
            $pdostm->bindParam(':accountid', $accountid);
        }
        else
        {
            $sql = "SELECT work_out_items.*, exercises.name FROM work_out_items inner join exercises on work_out_items.exercise_id = exercises.id";
            $sql .= $sort;
            $pdostm = $this->dbcon->prepare($sql);
        }


        $pdostm->execute();

        $workouts = $pdostm->fetchAll(PDO::FETCH_ASSOC);
        return $workouts;
    }

    public function listWorkoutsByDate($accountid, $start_date, $end_date)
    {
        $sql = "SELECT work_out_items.*, exercises.name, exercises.body_weight, exercises.cardio FROM work_out_items inner join exercises on work_out_items.exercise_id = exercises.id where account_id = :accountid AND ((date >= :start_date) AND (date <= :end_date))";
        $pdostm = $this->dbcon->prepare($sql);
        $pdostm->bindParam(':accountid', $accountid);
        $pdostm->bindParam(':start_date', $start_date);
        $pdostm->bindParam(':end_date', $end_date);

        $pdostm->execute();

        $workouts = $pdostm->fetchAll(PDO::FETCH_ASSOC);
        return $workouts;
    }

    public function getExercises()
    {
        $sql = "SELECT * FROM exercises";
        $pdostm = $this->dbcon->prepare($sql);

        $pdostm->execute();

        $exercisename = $pdostm->fetchAll(PDO::FETCH_ASSOC);
        return $exercisename;
    }

    public function getAccounts()
    {
        $sql = "SELECT * FROM accounts";
        $pdostm = $this->dbcon->prepare($sql);

        $pdostm->execute();

        $accounts = $pdostm->fetchAll(PDO::FETCH_ASSOC);
        return $accounts;
    }

    public function addWorkout($new_workout)
    {
        if($new_workout['is_bodyweight'] === 1 && $new_workout['is_cardio'] === 1)
        {
            $sql = "INSERT INTO work_out_items (exercise_id, account_id, date, duration) 
              VALUES (:exerciseid, :accountid, :date, :duration)";
            $pst = $this->dbcon->prepare($sql);

            $pst->bindParam(':exerciseid', $new_workout['exercise_id']);
            $pst->bindParam(':accountid', $new_workout['account_id']);
            $pst->bindParam(':date', $new_workout['date']);
            $pst->bindParam(':duration', $new_workout['duration']);
        }
        elseif ($new_workout['is_bodyweight'] === 1 && $new_workout['is_cardio'] === 0)
        {
            $sql = "INSERT INTO work_out_items (exercise_id, reps, sets, account_id, date, duration) 
              VALUES (:exerciseid, :reps, :sets, :accountid, :date, :duration)";
            $pst = $this->dbcon->prepare($sql);

            $pst->bindParam(':exerciseid', $new_workout['exercise_id']);
            $pst->bindParam(':reps', $new_workout['reps']);
            $pst->bindParam(':sets', $new_workout['sets']);
            $pst->bindParam(':accountid', $new_workout['account_id']);
            $pst->bindParam(':date', $new_workout['date']);
            $pst->bindParam(':duration', $new_workout['duration']);
        }
        elseif ($new_workout['is_bodyweight'] === 0 && $new_workout['is_cardio'] === 1)
        {
            $sql = "INSERT INTO work_out_items (exercise_id, weight, account_id, date, duration) 
              VALUES (:exerciseid, :weight, :accountid, :date, :duration)";
            $pst = $this->dbcon->prepare($sql);

            $pst->bindParam(':exerciseid', $new_workout['exercise_id']);
            $pst->bindParam(':reps', $new_workout['reps']);
            $pst->bindParam(':sets', $new_workout['sets']);
            $pst->bindParam(':weight', $new_workout['weight']);
            $pst->bindParam(':accountid', $new_workout['account_id']);
            $pst->bindParam(':date', $new_workout['date']);
            $pst->bindParam(':duration', $new_workout['duration']);
        }
        else
        {
            $sql = "INSERT INTO work_out_items (exercise_id, reps, sets, weight, account_id, date, duration) 
              VALUES (:exerciseid, :reps, :sets, :weight, :accountid, :date, :duration)";
            $pst = $this->dbcon->prepare($sql);

            $pst->bindParam(':exerciseid', $new_workout['exercise_id']);
            $pst->bindParam(':reps', $new_workout['reps']);
            $pst->bindParam(':sets', $new_workout['sets']);
            $pst->bindParam(':weight', $new_workout['weight']);
            $pst->bindParam(':accountid', $new_workout['account_id']);
            $pst->bindParam(':date', $new_workout['date']);
            $pst->bindParam(':duration', $new_workout['duration']);
        }

        $count = $pst->execute();
        if($count){
            header("Location: listWorkouts.php");
        } else {
            echo "problem adding the workout";
        }

    }
    public function addExercise($name, $body_weight, $cardio)
    {
        $sql = "INSERT INTO exercises (name, body_weight, cardio) 
              VALUES (:name, :body_weight, :cardio)";
        $pst = $this->dbcon->prepare($sql);

        $pst->bindParam(':name', $name);
        $pst->bindParam(':body_weight', $body_weight);
        $pst->bindParam(':cardio', $cardio);

        $count = $pst->execute();
        if($count){
            header("Location: addWorkout.php");
        } else {
            echo "problem adding the workout";
        }

    }
    public function deleteEntry($id)
    {
        $sql = "DELETE FROM work_out_items WHERE id = :id";

        $pst = $this->dbcon->prepare($sql);
        $pst->bindParam(':id', $id);
        $count = $pst->execute();
        if($count){
            header("Location: listWorkouts.php");
        }
        else {
            echo " problem deleting";
        }
    }
    public function getEntry($id)
    {
        $sql = "SELECT * FROM work_out_items where id = :id";
        $pst = $this->dbcon->prepare($sql);
        $pst->bindParam(':id', $id);
        $pst->execute();
        return $pst->fetch(PDO::FETCH_OBJ);
    }

    public function updateWorkout($new_workout)
    {
        if($new_workout['is_bodyweight'] === 1 && $new_workout['is_cardio'] === 1)
        {
            $sql = "Update work_out_items set exercise_id = :exerciseid, account_id = :accountid, date = :date, duration = :duration WHERE id = :id";
            $pst = $this->dbcon->prepare($sql);

            $pst->bindParam(':exerciseid', $new_workout['exercise_id']);
            $pst->bindParam(':accountid', $new_workout['account_id']);
            $pst->bindParam(':date', $new_workout['date']);
            $pst->bindParam(':duration', $new_workout['duration']);
            $pst->bindParam(':id', $new_workout['id']);
        }
        elseif ($new_workout['is_bodyweight'] === 1 && $new_workout['is_cardio'] === 0)
        {
            $sql = 'Update work_out_items set exercise_id = :exerciseid, reps = :reps, sets = :sets, account_id = :accountid, date = :date, duration = :duration WHERE id = :id';
            $pst = $this->dbcon->prepare($sql);

            $pst->bindParam(':exerciseid', $new_workout['exercise_id']);
            $pst->bindParam(':reps', $new_workout['reps']);
            $pst->bindParam(':sets', $new_workout['sets']);
            $pst->bindParam(':accountid', $new_workout['account_id']);
            $pst->bindParam(':date', $new_workout['date']);
            $pst->bindParam(':duration', $new_workout['duration']);
            $pst->bindParam(':id', $new_workout['id']);
        }
        elseif ($new_workout['is_bodyweight'] === 0 && $new_workout['is_cardio'] === 1)
        {
            $sql = "Update work_out_items set exercise_id = :exerciseid, weight = :weight, account_id = :accountid, date = :date, duration = :duration WHERE id = :id";
            $pst = $this->dbcon->prepare($sql);

            $pst->bindParam(':exerciseid', $new_workout['exercise_id']);
            $pst->bindParam(':reps', $new_workout['reps']);
            $pst->bindParam(':sets', $new_workout['sets']);
            $pst->bindParam(':weight', $new_workout['weight']);
            $pst->bindParam(':accountid', $new_workout['account_id']);
            $pst->bindParam(':date', $new_workout['date']);
            $pst->bindParam(':duration', $new_workout['duration']);
            $pst->bindParam(':id', $new_workout['id']);
        }
        else
        {
            $sql = 'Update work_out_items set exercise_id = :exerciseid, reps = :reps, sets = :sets, weight = :weight, account_id = :accountid, date = :date, duration = :duration WHERE id = :id';
            $pst = $this->dbcon->prepare($sql);

            $pst->bindParam(':exerciseid', $new_workout['exercise_id']);
            $pst->bindParam(':reps', $new_workout['reps']);
            $pst->bindParam(':sets', $new_workout['sets']);
            $pst->bindParam(':weight', $new_workout['weight']);
            $pst->bindParam(':accountid', $new_workout['account_id']);
            $pst->bindParam(':date', $new_workout['date']);
            $pst->bindParam(':duration', $new_workout['duration']);
            $pst->bindParam(':id', $new_workout['id']);
        }

        $count = $pst->execute();
        if($count){
            header("Location: listWorkouts.php");
        } else {
            echo "problem updating the workout";
        }


    }

}