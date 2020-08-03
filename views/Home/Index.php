<?php
//Master Layout Header
require_once "../Master/header.php";

use fitnessTracker\includes\Database;

require_once '../../includes/Database.php';
?>
<!--Image credit: https://www.1zoom.me/en/wallpaper/538198/z4030.3/1920x1080-->
<img src="../../img/gym.jpg">

<main class="m-5">
    <div class="home-container">
        
        <?php
        if (isset($_SESSION["id"])) {
            //if the ID is set, get the user with that ID's full name
            $dbcon = Database::GetDb();
            $query = "SELECT * FROM accounts WHERE id = :id";
            $pdostm = $dbcon->prepare($query);
            $pdostm->bindParam(':id', $_SESSION["id"]);
            $pdostm->execute();
            $users = $pdostm->fetchAll(PDO::FETCH_ASSOC);
            $user = $users[0];
            $name = $user["first_name"] . " " . $user["last_name"];

        ?>
            <h2>Welcome back to FitnessTracker, <?= $name ?>!</h2>
        <?php
        } else {
        ?>
            <h2>Welcome to FitnessTracker</h2>
        <?php
        }
        ?>
        <br />
        <p style="width: 50%;">Here at FitnessTracker, we want to get the world swole. When it comes to fitness, we don't take any shortcuts. We want you to have muscles on your eyeballs.
            We've designed a set of tools we know will help you reach your goals and live a healthier lifestyle. We don't discriminate, and we welcome all who are up to the challenge
            of reaching the ultimate fitness mastery. Join us, and help us achieve our goal of making Dwayne "The Rock" Johnson look like a toothpick.
        </p>
    </div>
</main>

<?php
//Master Layout Footer
require_once "../Master/footer.php";
?>