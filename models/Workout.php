<?php

class Workout
{
    private $workoutid;
    private $exerciseid;
    private $accountid;
    private $date;
    private $reps;
    private $sets;
    private $weight;
    private $duration;





    public function __construct($exerciseid, $reps, $sets, $weight, $userid, $workoutid = '')
    {
        $this->setExerciseid($exerciseid);
        $this->setReps($reps);
        $this->setSets($sets);
        $this->setWeight($weight);
        $this->setUserid($userid);
        $this->setWorkoutid($workoutid);

    }

    public function computeTonnage(){
        return ($this->reps * $this->sets *$this->weight);
    }


    /**
     * @param mixed $exerciseid
     */
    public function setExerciseid($exerciseid): void
    {
        $this->exerciseid = $exerciseid;
    }

    /**
     * @return mixed
     */
    public function getExerciseid()
    {
        return $this->exerciseid;
    }

    /**
     * @param mixed $reps
     */
    public function setReps($reps): void
    {
        $this->reps = $reps;
    }

    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @return mixed
     */
    public function getSets()
    {
        return $this->sets;
    }

    /**
     * @param mixed $workoutid
     */
    public function setWorkoutid($workoutid): void
    {
        $this->workoutid = $workoutid;
    }

    /**
     * @param mixed $weight
     */
    public function setWeight($weight): void
    {
        $this->weight = $weight;
    }

    /**
     * @param mixed $sets
     */
    public function setSets($sets): void
    {
        $this->sets = $sets;
    }

    /**
     * @return mixed
     */
    public function getWorkoutid()
    {
        return $this->workoutid;
    }

    /**
     * @return mixed
     */
    public function getReps()
    {
        return $this->reps;
    }

    /**
     * @return mixed
     */
    public function getAccountid()
    {
        return $this->accountid;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param mixed $accountid
     */
    public function setAccountid($accountid): void
    {
        $this->accountid = $accountid;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @param mixed $duration
     */
    public function setDuration($duration): void
    {
        $this->duration = $duration;
    }

}