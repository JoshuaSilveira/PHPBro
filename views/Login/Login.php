<?php
//Master Layout Header
require_once "../Master/header.php";

use fitnessTracker\includes\Database;

require_once '../../includes/Database.php';


if (isset($_SESSION["id"])) {
    //if already logged in, go to a different page
    header("Location: ../../views/Home/Index.php");
    exit();
}

$results = "";

if (isset($_POST["login"])) {
    //form was submitted

    //get the db connection
    $dbcon = Database::GetDb();

    $valid = false;
    $name = "";
    //if username and password aren't empty
    if ($_POST["username"] != "" && $_POST["password"] != "") {
        //$query = "SELECT * FROM accounts WHERE username = :username AND password = :password";
        $query = "SELECT * FROM accounts WHERE username = :username";
        $pdostm = $dbcon->prepare($query);
        $pdostm->bindParam(':username', $_POST["username"]);
        //$password = hash('md5', $_POST["password"]); //old hash
        //$pdostm->bindParam(':password', $password);
        $pdostm->execute();
        $users = $pdostm->fetchAll(PDO::FETCH_ASSOC);
        $user = $users[0];
        $name = $user["first_name"] . " " . $user["last_name"];
        echo password_hash($_POST["password"],PASSWORD_DEFAULT);
        //var_dump($user);
        if ($user != null) {
            if(password_verify($_POST["password"], $user["password"] ))
            {
                $valid = true;
            }
        }
    }
    if ($valid) {
        //LOGGED IN USER ID IS SAVED AS A SESSION VARIABLE HERE
        $_SESSION["id"] = $user["id"];
        //WHETHER OR NOT THE USER IS AN ADMIN IS SAVED AS A BOOLEAN SESSION VARIABLE HERE
        $_SESSION["is_admin"] = $user["is_admin"];
        $_SESSION["username"] = $user["username"];

        $results = "<div class=\"alert alert-success\" role=\"alert\">Welcome, $name.</div>";
        //redirect to the home page
        header("Location: ../../views/Home/Index.php");
        exit();
    } else {
        //validation
        $results = "<div class=\"alert alert-danger\" role=\"alert\">Invalid username/password.</div>";
    }
} else {
    //session_destroy();
}

?>

<main class="m-5">

    <h1>Login</h1>
    <?= $results ?>
    <?php if (isset($_GET["logout"]) && $_GET["logout"] == true) {
        echo "<div class=\"alert alert-success\" role=\"alert\">Successfully logged out.</div>";
    }
    ?>
    <div class="w-50">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="loginForm">
            <div class="form-group">
                <label for="username">Username: </label>
                <input type="text" id="username" name="username" class="form-control w-25" />
                <span id="usernameError" class="text-danger"></span>
            </div>
            <div class="form-group">
                <label for="password">Password: </label>
                <input type="password" id="password" name="password" class="form-control w-25" />
                <span id="passwordError" class="text-danger"></span>
            </div>
            <button type="submit" name="login" value="">Login</button>
        </form>
    </div>
</main>
<?php
//Master Layout Footer
require_once "../Master/footer.php";
?>