<?php
include "Game.php";

$story = "There was an incident involving you and a boomerang at work and now Toby is trying to track down all 
the weapons you've hidden around the office. You need to find them all and get them to the trunk of your car. 
Make sure you don't run into Toby or he'll confiscate all of them.";

$success = "You found everything without running into god-awful Toby. And he'll never find the one you keep hidden 
    on your person. The office remains safe. Congratulations!";

$fail = "Toby caught you! Doesn't he know that it's better to be hurt by someone you know accidentally than by a 
stranger on purpose? Now you'll have to figure out a way to get them all back.";

$items = array("throwing stars"=>"the lobby", "pepper spray"=>"the warehouse", "cross bow"=>"reception",
    "blowgun"=>"accounting", "nunchucks"=>"Michael's office", "stun gun"=>"sales", "Toby"=>"the conference room",
    "brass knuckles"=>"the kitchen", "Chinese sword"=>"the annex", "nightstick"=>"the break room",
    "sai daggers"=>"the stairs", "taser"=>"Vance Refrigeration");

$currRoom = 'the parking lot';

$currTable = 'parkinglot';

if(empty($_SESSION['dwight'])) {
    $_SESSION['dwight'] = new Game($story, $success, $fail, $items, $currTable, $currRoom);
}


$servername = "localhost";
$username = "root";
$password = null;
$db = "rooms";

$conn = new mysqli($servername, $username, $password, $db);
if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}
?>

<html>
<?php
include "design/templates/head.php";
?>
<body>
<?php
include "design/templates/top.php";
?>
<div id="main">
    <?php
    if(!empty($_POST["inputDirection"]))
    {
        $direction = trim(strtolower($_POST["inputDirection"]));

        if (!$_SESSION['dwight']->getNextRoom($conn, $direction))
        {
            $_SESSION['dwight']->noMove($conn);
        }
        elseif($_SESSION['dwight']->getItem() == -1)
        {
            header('Location: http://' . $_SERVER['HTTP_HOST'] . '/officeGame/dwightfail.php');
            die();
        }
        elseif($_SESSION['dwight']->getItem() == 1)
        {
            $_SESSION['dwight']->addItem();
            header('Location: http://' . $_SERVER['HTTP_HOST'] . '/officeGame/dwightsuccess.php');
            die();
        }
        elseif($_SESSION['dwight']->getItem() == 2 || $_SESSION['dwight']->currTable == 'parkinglot')
        {
            $_SESSION['dwight']->printRepeat($conn);
        }
        else
        {
            $_SESSION['dwight']->printStatement($conn);
        }
    }
    else
    {
        $_SESSION['dwight']->start($conn);
    }
    echo "<br><br>";
    ?>
    <form action="dwight.php" method="POST">
        <input type="text" name="inputDirection" placeholder="Where to?" required>
    </form>
</div>
<?php
include "design/templates/footer.php";
?>
</body>
</html>