<?php
include "Game.php";

$story = "Always the prankster, Jim has taken all of the toys you keep on your desk and hidden them around the 
office. To make matters worse, tomorrow is \"Take Your Daughter to Work Day\" and there will be kids all over 
the office. If they find one of your toys, you may never see it again. Search the office to recover your toys 
before the end of the day. But be careful. Toby is somewhere in the office and if you run into him, he might try 
to talk to you.";

$success = "You found everything without running into god-awful Toby. Crisis averted. You're a regular MacGruber! 
Congratulations!";

$fail = "No, God, please no! NO!! Toby was in accounting talking to Oscar about the next Finer Things Club 
meeting and he spotted you. If you keep trying to find your desk toys, he'll try to help and never leave you 
alone. You'll just have to hope none of the kids find any. Why is Toby the way that he is? He ruins everything!";

$items = array("World's Best Boss Mug"=>"the lobby", "Newton's Cradle"=>"the warehouse",
    "Chattering Teeth"=>"reception", "Toby"=>"accounting", "Train Whistle"=>"Michael's office",
    "Dundie"=>"sales", "Rolodex"=>"the conference room", "Slinky"=>"the kitchen",
    "Dunder Mifflin Truck"=>"the annex", "Hoberman Sphere"=>"the break room", "Mini Pool Table"=>"the stairs",
    "Grenade Paperweight"=>"Vance Refrigeration");

$currRoom = 'the parking lot';

$currTable = 'parkinglot';

# create session variable for character
if(empty($_SESSION['michael'])) {
    $_SESSION['michael'] = new Game($story, $success, $fail, $items, $currTable, $currRoom);
}

# connect to the database
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
    # verify input is entered
    if(!empty($_POST["inputDirection"]))
    {
        $direction = $_POST["inputDirection"];

        # check for invalid direction
        if (!$_SESSION['michael']->getNextRoom($conn, $direction))
        {
            $_SESSION['michael']->noMove($conn);
        }
        # check if Toby found
        elseif($_SESSION['michael']->getItem() == -1)
        {
            header('Location: http://' . $_SERVER['HTTP_HOST'] . '/officeGame/michaelfail.php');
            die();
        }
        # check if all items found
        elseif($_SESSION['michael']->getItem() == 1)
        {
            $_SESSION['michael']->addItem();
            header('Location: http://' . $_SERVER['HTTP_HOST'] . '/officeGame/michaelsuccess.php');
            die();
        }
        # check if item already found or in parking lot
        elseif($_SESSION['michael']->getItem() == 2 || $_SESSION['michael']->currTable == 'parkinglot')
        {
            $_SESSION['michael']->printRepeat($conn);
        }
        else
        {
            $_SESSION['michael']->printStatement($conn);
        }
    }
    else
    {
        $_SESSION['michael']->start($conn);
    }
    echo "<br><br>";
    ?>
    <form action="michael.php" method="POST">
        <input type="text" name="inputDirection" placeholder="Where to?" required>
    </form>
</div>
<?php
include "design/templates/footer.php";
?>
</body>
</html>