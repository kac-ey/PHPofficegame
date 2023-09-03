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
    <h3>Welcome to the Office Text-Based Game! <br><br> Which character would you like to play as?<br><br></h3>
    <a class="button" href="michael.php">Michael</a>
    <a class="button" href="dwight.php">Dwight</a>
</div>
<?php
include "design/templates/footer.php";
?>
</body>
</html>
