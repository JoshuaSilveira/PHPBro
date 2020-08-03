<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<html>

<head>
    <title>Fitness Tracker</title>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="icon" href="../../img/logo.png" type="image/icon type">
    <link href="../../styles/style.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="headerTitle">
        <h1>
            <a href="../../views/Home/Index.php">
                <img src="../../img/logo.png" alt="Logo">
                Fitness Tracker
            </a>
        </h1>
    </div>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="../../views/Home/Index.php">Fitness Tracker</a>
            </div>
            <ul class="nav navbar-nav mr-auto">
                <li class="active">
                    <a href="../../views/Home/Index.php">
                        <span class="fas fa-home"></span>
                        Home
                    </a>
                </li>
                <li>
                    <a href="../../views/Workout/listWorkouts.php">
                        <img src="../../img/exerciseIcon.png" alt="Workout Logo">
                        Workouts
                    </a>
                </li>
                <li>
                    <a href="../../views/Meal/List.php">
                        <img src="../../img/mealsIcon.png" alt="Meals Logo">
                        Meals
                    </a>
                </li>
                <li>
                    <a href="../Event/List.php">
                        <img src="../../img/eventsCalendarIcon.png" alt="Events Logo">
                        Events
                    </a>
                </li>
                <li>
                    <a href="../Route/List.php">
                        <img src="../../img/routePlannerLogo.png" alt="Route Planner Logo">
                        Routes
                    </a>
                </li>
            </ul>

            <?php
            //Different view of the right navbar for user logged in or not

            //if there is a id value then the user is logged in
            //When the user is signed in
            if (isset($_SESSION["id"])) {
            ?>
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="../Findabro/List.php">
                            <img src="../../img/logo.png" alt="Find a Bro Logo">
                            Find A BRO
                        </a>
                    </li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <img src="../../img/accountIcon.png" alt="Account Logo">
                            <?= $_SESSION["username"] ?>

                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="../../views/StatusAccount/Details.php">Account Details</a></li>
                            <li><a href="#">My Planner</a></li>
                            <li><a href="#">Log my workouts</a></li>
                            <li><a href="#">Log my meals</a></li>
                            <li><a href="../../views/Goal/List.php">Goals</a></li>
                            <li><a href="../../views/Login/Logout.php">Log out</a></li>
                        </ul>
                    </li>
                </ul>
            <?php
            }
            //When the user is not signed in
            else {
            ?>
                <ul class='nav navbar-nav navbar-right'>
                    <li><a href='../StatusAccount/Add.php'><span class='fas fa-user'></span> Sign Up</a></li>
                    <li><a href='../../views/Login/Login.php'><span class='fas fa-sign-in-alt'></span> Login</a></li>
                </ul>
            <?php
            }
            ?>
        </div>
    </nav>

    <body>