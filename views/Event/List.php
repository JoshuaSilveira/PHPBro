<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use fitnessTracker\includes\Database;
use fitnessTracker\includes\Validator;
use fitnessTracker\models\Event\Event;

require_once '../../includes/Database.php';
require_once '../../includes/Validator.php';
require_once '../../models/Event.php';

$errorMessages = ["", "", ""];
$startDate = "";
$endDate = "";
$location = "";

$dbcon = Database::GetDb();
$e = new Event();
$events =  $e->GetAll(Database::getDb());
$filteredEvents = $events;
$myEvents = $events;

//Used for showing the local events
$adminUserIDs = $e->GetAllAdminUserID(Database::getDb());

//if user click on any "a" link (redirect to the details of the page)
if (isset($_GET['id'])) {
    header("Location: Details.php?id=" . $_GET['id']);
    exit;
}

//Check to see if the filter form is submitted
if (isset($_POST['filterEvents'])) {
    //get the data from the form
    if (isset($_POST['startDate'])) {
        $startDate = $_POST['startDate'];
    }
    if (isset($_POST['endDate'])) {
        $endDate = $_POST['endDate'];
    }
    if (isset($_POST['location'])) {
        $location = $_POST['location'];
    }

    //Check the validation (Nothing to validate due to input is specified and is a search functionality
    //$errorMessages = Validator::ValidateEventFilterForm($date, $location);
    //if (Validator::IsFormValid($errorMessages)) {
    $filteredEvents = $e->GetAllFilterEvents(Database::getDb(), ["startDate" => $startDate, "endDate" => $endDate, "location" => $location]);
    //}
}

//Remove all events that the user has which was originally created from the admin in the filteredEvents
if (isset($_SESSION['id'])) {
    //Get all of the users events
    $myEvents = $e->GetAllMyEvents(Database::getDb(), $_SESSION['id']);

    //Get all of the user event ref ids
    $myEventRefIds = $e->GetAllMyEventRefIds(Database::getDb(), $_SESSION['id']);
    $counter = 0;
    //Remove all events in the local events that the user already has
    foreach($filteredEvents as $event) {
        //if the event is in the reference ids (meaning that the user has this event already)
        if (in_array($event->id, $myEventRefIds)) {
            //remove event from the filteredEvents
            array_splice($filteredEvents, $counter, 1);
        }
        else {
            $counter++;
        }
    }
}

//Check to see if the add event is submitted
if (isset($_POST['addEvent'])) {
    //Get the event we are going to add to the user
    $newEvent = $e->GetById(Database::getDb(), $_POST['id']);
    $e->Add(Database::getDb(), [
        "account_id" => $_SESSION['id'],
        "name" => $newEvent->name,
        "date" => $newEvent->date,
        "duration" => $newEvent->duration,
        "location" => $newEvent->location,
        "ref_id" => $newEvent->id
    ]);
}

?>

<?php
//Master Layout Header
require_once "../Master/header.php";
?>

<div class="row p-5">
    <div class="col-lg-6">
        <?php
        //Show only if user is logged in
        if (isset($_SESSION['id'])) {
        ?>
            <a href="Add.php" id="btn_addEvent" class="btn btn-success btn-lg">Add New Event</a>
            <h2>My Events</h2>
            <div>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Date Time</th>
                        <th scope="col">Update</th>
                        <th scope="col">Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($myEvents as $event) {
                        //if the event is for this account
                        if ($_SESSION['id'] == $event->account_id) {
                            ?>
                            <tr>
                                <th><a href="?id=<?= $event->id; ?>"><?= $event->name; ?></a></th>
                                <th><?= $event->date; ?></th>
                                <td>
                                    <form action="Update.php" method="post">
                                        <input type="hidden" name="id" value="<?= $event->id; ?>"/>
                                        <input type="submit" class="button btn btn-primary" name="updateEvent"
                                               value="Update"/>
                                    </form>
                                </td>
                                <td>
                                    <form action="Delete.php" method="post">
                                        <input type="hidden" name="id" value="<?= $event->id; ?>"/>
                                        <input type="submit" class="button btn btn-danger" name="deleteEvent"
                                               value="Delete"/>
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        <?php
        }
        ?>
        <h2>Local Events</h2>
        <div>
            <!-- Form to filter the events -->
            <div>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="startDate">Start Date :</label>
                        <input type="date" class="form-control" name="startDate" id="startDate" value="<?= $startDate ?>">
                        <div style="color: red">
                            <?= $errorMessages[0] ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="endDate">End Date :</label>
                        <input type="date" class="form-control" name="endDate" id="endDate" value="<?= $endDate ?>">
                        <div style="color: red">
                            <?= $errorMessages[1] ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="location">Location :</label>
                        <input type="text" class="form-control" name="location" id="location" value="<?= $location ?>">
                        <div style="color: red">
                            <?= $errorMessages[2] ?>
                        </div>
                    </div>
                    <button type="submit" name="filterEvents" class="btn btn-primary" id="btn-submit">
                        Filter
                    </button>
                </form>
            </div>
            <!-- Results from the filtered events (no filter is all events) -->
            <div>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Date Time</th>
                        <th scope="col">Duration (hour)</th>
                        <th scope="col">Location</th>
                        <?php
                        if (isset($_SESSION['id'])) {
                        ?>
                            <th scope="col">Add Event</th>
                        <?php
                        }
                        ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($filteredEvents as $event) {
                        //User does not have this event yet and the event is created by admin (personal event is filtered out)
                        if ((!isset($_SESSION['id']) || $_SESSION['id'] != $event->account_id)
                            && in_array($event->account_id, $adminUserIDs)) {
                        ?>
                            <tr>
                                <th><a href="?id=<?= $event->id; ?>"><?= $event->name; ?></a></th>
                                <th><?= $event->date; ?></th>
                                <th><?= $event->duration; ?></th>
                                <th><?= $event->location; ?></th>
                                <?php
                                if (isset($_SESSION['id'])) {
                                ?>
                                    <td>
                                        <form action="" method="post">
                                            <input type="hidden" name="id" value="<?= $event->id; ?>"/>
                                            <input type="submit" class="button btn btn-primary" name="addEvent"
                                                   value="Add Event"/>
                                        </form>
                                    </td>
                                <?php
                                }
                                ?>
                            </tr>
                    <?php
                        }
                    }
                    ?>

                    <!--
                    Dummy data
                    <tr>
                        <th>Mississauga Marathon</th>
                        <th>2020-06-02 09:00 AM</th>
                        <th>12</th>
                        <th>Mississauga</th>
                        <td>
                            <form action="" method="post">
                                <input type="hidden" name="id" value="2"/>
                                <input type="submit" class="button btn btn-primary" name="addEvent" value="Add Event"/>
                            </form>
                        </td>
                    </tr>
                    -->

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php
    //Code to create the calendar
    //Code referenced from: https://www.youtube.com/watch?v=Y0cz_SV0X3Y
    function MakeCalendar($month, $year, $userId) {
        //Array of all of the days in a week
        $daysOfWeek = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
        //First day of the month
        $firstDayOfMonth = mktime(0,0,0, $month, 1, $year);
        //Number of days in the month
        $numDays = date("t", $firstDayOfMonth);
        //First day of the month
        $dateCompontents = getdate($firstDayOfMonth);
        //Name of the month
        $monthName = $dateCompontents["month"];
        //Index value from 0-6 of the first day of the month
        $dayOfWeek = $dateCompontents["wday"];
        //Getting the current date
        $dateToday = date("Y-m-d");

        //Creating the HTML Table
        $calendar = "<div class='border'>";

        //Create the header of the Calendar
        $calendar .= "<form action='' method='post' class='row'>";
        $calendar .= "<input type='hidden' name='year' value='" . $year . "'/>";
        $calendar .= "<input type='hidden' name='month' value='" . $month . "'/>";
        //Create the previous button for the calendar
        $calendar .= "<input type='submit' class='button col' name='changePrevMonth' value='Prev Month'/>";
        //Create the main header of the calendar
        $calendar .= "<div class='col text-center'>$monthName $year</div>";
        //Create the next button for the calendar
        $calendar .= "<input type='submit' class='button col' name='changeNextMonth' value='Next Month'/>";
        $calendar .= "</form>";

        $calendar .= "<div class='row'>";
        //Create the calendar first week
        foreach ($daysOfWeek as $day) {
            $calendar .= "<div class='col dayHeader text-center'>$day</div>";
        }
        $calendar .= "</div><div class='row'>";
        //Create empty days for until first day (Ex. Feb 1 on Friday so, 4 empty 'td'
        if ($dayOfWeek > 0) {
            for($i = 0; $i < $dayOfWeek; $i++) {
                $calendar .= "<div class='col day'></div>";
            }
        }
        //Day counter
        $currentDay = 1;
        //Getting month number
        $month = str_pad($month, 2, "0", STR_PAD_LEFT);

        $e = new Event();
        $calendarEvents = $e->GetAllCalendarEvents(Database::getDb(), $userId);

        //Remove all the events does is not in the current month (only the past month event any future event is kept)
        $dateTime = new DateTime();
        //Special case when the calendar is empty
        if (sizeof($calendarEvents)) {
            $dateTime = new DateTime($calendarEvents[0]->date);
        }
        while (sizeof($calendarEvents) > 0 && (
            (int)$dateTime->format('Y') != (int)$year ||
            (int)$dateTime->format('m') != (int)$month)) {
            array_shift($calendarEvents);
            if (sizeof($calendarEvents) > 0) {
                $dateTime = new DateTime($calendarEvents[0]->date);
            }
        }

        while ($currentDay <= $numDays) {
            //if seventh column then start new row (new week)
            if ($dayOfWeek == 7) {
                $dayOfWeek = 0;
                $calendar .= "</div><div class='row'>";
            }

            $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
            $date = "$year-$month-$currentDayRel";
            $calendar .= "<div class='col day'>$currentDay";
            //Here we put any event data on that day
            foreach($calendarEvents as $event) {
                //Convert the event date string to DateTime
                $dateTime = new DateTime($event->date);
                //if the event is on the same day of the currentDay then
                if ((int)$dateTime->format('m') == (int)$month
                    && (int)$dateTime->format('d') == $currentDay)
                {
                    //add to check which colour we make it
                    $calendar .= "<div class='";
                    //if it is the user's event then
                    if ($event->account_id == $userId) {
                        $calendar .= "event";
                    }
                    //admin event (for everyone)
                    else {
                        $calendar .= "localEvent";
                    }
                    $calendar .= "'><a href='?id=$event->id'>$event->name</a></div>";
                    array_shift($calendarEvents);
                }
                else {
                    break;
                }
            }

            $calendar .= "</div>";
            $currentDay++;
            $dayOfWeek++;
        }
        //Complete the row of the last week of the month
        if ($daysOfWeek != 7) {
            $remainingDays = 7 - $dayOfWeek;
            for($i = 0; $i < $remainingDays; $i++) {
                $calendar .= "<div class='col day'></div>";
            }
        }
        $calendar .= "</div></div>";
        
        return $calendar;
    }
    ?>

    <div id="eventCalendar" class="col-lg-6">
        <h2>Event Calendar</h2>
        <div class="row">
            <div class="col-md-12 calendar">
                <?php
                $dateCompontents = getdate();
                $month = $dateCompontents["mon"];
                $year = $dateCompontents["year"];
                //if the user click on the next or prev month button
                if (isset($_POST['changePrevMonth'])) {
                    $month = $_POST['month'] - 1;
                    if ($month == 0) {
                        $month = 12;
                        $year = $_POST['year'] - 1;
                    }
                }
                else if (isset($_POST['changeNextMonth'])) {
                    $month = $_POST['month'] + 1;
                    if ($month == 13) {
                        $month = 1;
                        $year = $_POST['year'] + 1;
                    }
                }
                if (isset($_SESSION['id']))
                {
                    echo MakeCalendar($month, $year, $_SESSION['id']);
                }
                else {
                    echo MakeCalendar($month, $year, null);
                }
                ?>
            </div>
        </div>

    </div>
</div>

<?php
//Only Admin can see all the list of events and have control to update and delete any events
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
?>
    <div class="p-5">
        <h2>Event Lists</h2>
        <div>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th scope="col">Account ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Date Time</th>
                    <th scope="col">Duration (hour)</th>
                    <th scope="col">Location</th>
                    <th scope="col">Reference Id</th>
                    <th scope="col">Update</th>
                    <th scope="col">Delete</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($events as $event) {
                    ?>
                    <tr>
                        <th><?= $event->account_id; ?></th>
                        <th><a href="?id=<?= $event->id; ?>"><?= $event->name; ?></a></th>
                        <th><?= $event->date; ?></th>
                        <th><?= $event->duration; ?></th>
                        <th><?= $event->location; ?></th>
                        <th><?= $event->ref_id; ?></th>
                        <td>
                            <form action="Update.php" method="post">
                                <input type="hidden" name="id" value="<?= $event->id; ?>"/>
                                <input type="submit" class="button btn btn-primary" name="updateEvent" value="Update"/>
                            </form>
                        </td>
                        <td>
                            <form action="Delete.php" method="post">
                                <input type="hidden" name="id" value="<?= $event->id; ?>"/>
                                <input type="submit" class="button btn btn-danger" name="deleteEvent" value="Delete"/>
                            </form>
                        </td>
                    </tr>
                <?php } ?>

                <!--
                Dummy data
                <tr>
                    <th>1</th>
                    <th>Mississauga Marathon</th>
                    <th>2020-06-02 09:00 AM</th>
                    <th>12</th>
                    <th>Mississauga</th>
                    <td>
                        <form action="Update.php" method="post">
                            <input type="hidden" name="id" value="2"/>
                            <input type="submit" class="button btn btn-primary" name="updateRoute" value="Update"/>
                        </form>
                    </td>
                    <td>
                        <form action="Delete.php" method="post">
                            <input type="hidden" name="id" value="2"/>
                            <input type="submit" class="button btn btn-danger" name="deleteRoute" value="Delete"/>
                        </form>
                    </td>
                </tr>
                -->

                </tbody>
            </table>
        </div>
    </div>
<?php
}
?>
<?php
//Master Layout Footer
require_once "../Master/footer.php";
?>