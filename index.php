<?php
include "Game.php";
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
    session_unset();
    ?>
    <h3>Welcome to the Office Text-Based Game! <br><br> Which character would you like to play as?</h3>
    <a href="michael.php"><button>Michael</button></a>
    <a href="dwight.php"><button>Dwight</button></a>
</div>
<?php
include "design/templates/footer.php";
?>
</body>
</html>
