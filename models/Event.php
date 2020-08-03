<?php
namespace fitnessTracker\Models\Event;
use fitnessTracker\Models\Feature\Feature;
require_once 'Feature.php';

class Event implements Feature
{
    public function GetById($dbcon, $id)
    {
        $sql = "SELECT * FROM events WHERE id = :id";
        $pdostm = $dbcon->prepare($sql);
        $pdostm->bindParam(':id', $id);
        $pdostm->execute();
        return $pdostm->fetch(\PDO::FETCH_OBJ);
    }

    public function GetAll($dbcon)
    {
        $sql = "SELECT * FROM events";
        $pdostm =  $dbcon->prepare($sql);
        $pdostm->execute();

        $events = $pdostm->fetchAll(\PDO::FETCH_OBJ);
        return $events;
    }

    public function Add($dbcon, $inputs)
    {
        $sql = "INSERT INTO events (account_id, name, date, duration, location, ref_id) 
            values (:account_id, :name, :date, :duration, :location, :ref_id)";
        $pdostm = $dbcon->prepare($sql);
        $pdostm->bindParam(':account_id', $inputs['account_id']);
        $pdostm->bindParam(':name', $inputs['name']);
        $pdostm->bindParam(':date', $inputs['date']);
        $pdostm->bindParam(':duration', $inputs['duration']);
        $pdostm->bindParam(':location', $inputs['location']);
        $pdostm->bindParam(':ref_id', $inputs['ref_id']);
        $numRowsAffected = $pdostm->execute();

        if ($numRowsAffected) {
            echo "Inserted a event";
            header("Location: List.php");
            exit;
        }
        else {
            echo "Problem inserting";
        }
    }

    public function Update($dbcon, $inputs)
    {
        $sql = "Update events
                set name = :name,
                date = :date,
                duration = :duration,
                location = :location             
                ";

        //This is the original event then update all the other event that is based from this event
        if ($inputs['ref_id'] == "0") {
            $sql .= "WHERE id = :id OR ref_id = :ref_id";
            $inputs['ref_id'] = $inputs['id'];
        }
        //Change the event, so it is own event now
        else {
            $sql .= ", ref_id = :ref_id WHERE id = :id";
            $inputs['ref_id'] = "0";
        }

        $pdostm = $dbcon->prepare($sql);
        $pdostm->bindParam(':name', $inputs['name']);
        $pdostm->bindParam(':date', $inputs['date']);
        $pdostm->bindParam(':duration', $inputs['duration']);
        $pdostm->bindParam(':location', $inputs['location']);
        $pdostm->bindParam(':id', $inputs['id']);
        $pdostm->bindParam(':ref_id', $inputs['ref_id']);
        $numRowsAffected = $pdostm->execute();

        if ($numRowsAffected) {
            echo "Update a event";
            header("Location: List.php");
            exit;
        } else {
            echo "Problem updating a event";
        }
    }

    public function Delete($dbcon, $id)
    {
        //Delete the data (row) from the database
        $sql = "DELETE FROM events WHERE id = :id OR ref_id = :ref_id";
        $pdostm = $dbcon->prepare($sql);
        $pdostm->bindParam(':id', $id);
        $pdostm->bindParam(':ref_id', $id);
        $numRowsAffected = $pdostm->execute();

        if ($numRowsAffected) {
            echo "Delete a event";
            header("Location: List.php");
            exit;
        } else {
            echo "Problem deleting a event";
        }
    }

    public function GetAllAdminUserID($dbcon) {
        $sql = "SELECT id FROM accounts WHERE is_admin = '1'";
        $pdostm =  $dbcon->prepare($sql);
        $pdostm->execute();

        $accountIDs = $pdostm->fetchAll(\PDO::FETCH_COLUMN);
        return $accountIDs;
    }

    public function GetAllMyEvents($dbcon , $id) {
        $sql = "SELECT * FROM events WHERE account_id = :account_id ORDER BY date ASC";
        $pdostm =  $dbcon->prepare($sql);
        $pdostm->bindParam(':account_id', $id);
        $pdostm->execute();

        $events = $pdostm->fetchAll(\PDO::FETCH_OBJ);
        return $events;
    }

    public function GetAllAdminEvents($dbcon) {
        $adminIds = self::GetAllAdminUserID($dbcon);
        $events = array();
        if (sizeof($adminIds) > 0) {
            $sql = "SELECT * FROM events WHERE";
            //Loop to add the where clause for the admin ids
            for ($i = 0; $i < sizeof($adminIds); $i++) {
                $sql .= " account_id = :account_id$i";
                //Add OR operator between all admin ids
                if ($i + 1 < sizeof($adminIds)) {
                    $sql .= " OR ";
                }
            }
            $sql .= " ORDER BY date ASC";
            $pdostm =  $dbcon->prepare($sql);
            //Bind all the admin ids
            for ($i = 0; $i < sizeof($adminIds); $i++) {
                $pdostm->bindParam(":account_id$i", $adminIds[$i]);
            }
            $pdostm->execute();
            $events = $pdostm->fetchAll(\PDO::FETCH_OBJ);
        }
        return $events;
    }

    public function GetAllFilterEvents($dbcon, $conditions) {
        $sql = "";
        //No filter condition
        if ($conditions['startDate'] == "" && $conditions['endDate'] == "" && $conditions['location'] == "") {
            $sql = "SELECT * FROM events";
        }
        else {
            //Filter conditions
            $sql = "SELECT * FROM events WHERE";
            //User specify a location
            if ($conditions['location'] != "") {
                $sql .= " (location LIKE :location)";
                //if the user specify a date then add a AND condition
                if ($conditions['startDate'] != "" OR $conditions['endDate'] != "") {
                    $sql .= " AND";
                }
            }
            //User specify depending on start and end date inputs
            if ($conditions['startDate'] != "" && $conditions['endDate'] != "") {
                $sql .= " (date BETWEEN :startDate AND :endDate)";
            }
            else if ($conditions['startDate'] != "") {
                $sql .= " (date >= :startDate)";
            }
            else if ($conditions['endDate'] != "") {
                $sql .= " (date <= :endDate)";
            }
        }
        $pdostm =  $dbcon->prepare($sql);
        //Bind all the filter conditions
        if ($conditions['startDate'] != "") {
            $pdostm->bindParam(':startDate', $conditions['startDate']);
        }
        if ($conditions['endDate'] != "") {
            $pdostm->bindParam(':endDate', $conditions['endDate']);
        }
        if ($conditions['location'] != "") {
            $location = "%" . $conditions['location'] . "%";
            $pdostm->bindParam(':location', $location);
        }
        $pdostm->execute();
        $events = $pdostm->fetchAll(\PDO::FETCH_OBJ);
        return $events;
    }

    public function GetAllMyEventRefIds($dbcon, $id) {
        $sql = "SELECT ref_id FROM events WHERE account_id = :account_id";
        $pdostm =  $dbcon->prepare($sql);
        $pdostm->bindParam(':account_id', $id);
        $pdostm->execute();

        $refIds = $pdostm->fetchAll(\PDO::FETCH_COLUMN);
        return $refIds;
    }

    public function GetAllCalendarEvents($dbcon, $id) {
        /*
        $sql = "SELECT * FROM events WHERE ";
        $adminIds = self::GetAllAdminUserID($dbcon);

        for ($i = 0; $i < sizeof($adminIds); $i++) {
            $sql .= "account_id = :account_id$i";
            //Add OR operator between all admin ids
            if ($i + 1 < sizeof($adminIds)) {
                $sql .= " OR ";
            }
        }
        if (isset($id)) {
            $sql .= " OR account_id = :account_id";
        }

        $sql .= " ORDER BY date";

        $pdostm =  $dbcon->prepare($sql);

        if (isset($id)) {
            $pdostm->bindParam(':account_id', $id);
        }
        //Bind all the admin ids
        for ($i = 0; $i < sizeof($adminIds); $i++) {
            $pdostm->bindParam(":account_id$i", $adminIds[$i]);
        }

        $pdostm->execute();
        $events = $pdostm->fetchAll(\PDO::FETCH_OBJ);

        //Splice out all the events that user has with the admins (duplicate events)
        for ($i = 0; $i < sizeof($events) - 1; $i++) {
            if ($events[$i]->id == $events[$i + 1]->ref_id) {
                array_splice($events, $i, 1);
                $i--;
            }
        }
        */
        $events = array();
        $userEvents = array();
        if (isset($id)) {
            $userEvents = self::GetAllMyEvents($dbcon, $id);
        }
        $adminEvents = self::GetAllAdminEvents($dbcon);

        //Add all event from both user and admin while removing duplicate events (user has a admin event)
        while (sizeof($adminEvents) > 0 || sizeof($userEvents) > 0) {
            //if either of the events are empty then just add the rest to the events result
            if (sizeof($adminEvents) == 0) {
                $events = array_merge($events, $userEvents);
                //Clear the array
                $userEvents = array();
            }
            else if (sizeof($userEvents) == 0) {
                $events = array_merge($events, $adminEvents);
                //Clear the array
                $adminEvents = array();
            }
            //First compare the date to see which event is first
            else {
                //date is in string, so must convert to datetime
                $userDate = new \DateTime($userEvents[0]->date);
                $adminDate = new \DateTime($adminEvents[0]->date);
                //if the userdate is first then
                if ($userDate < $adminDate) {
                    $events[] = array_shift($userEvents);
                }
                //if the admin date is first
                else {
                    //Check if the event is the duplicate event
                    //(Do note we do not check if there are 2 different events that have the same datetime)
                    if ($adminEvents[0]->id == $userEvents[0]->ref_id) {
                        $events[] = array_shift($userEvents);
                        array_shift($adminEvents);
                    }
                    //Special case if the admin is the user
                    else if ($adminEvents[0]->id == $userEvents[0]->id) {
                        $events[] = array_shift($userEvents);
                        array_shift($adminEvents);
                    }
                    else {
                        $events[] = array_shift($adminEvents);
                    }
                }
            }
        }
        //Return the results
        return $events;
    }


}