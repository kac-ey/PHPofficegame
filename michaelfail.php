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
    echo $_SESSION['michael']->fail, "<br><br>";
    echo "You collected:<br>", implode(", ",$_SESSION['michael']->inventory), "<br><br>";
    session_unset();
    ?>
    <a href="index.php"><button>Return Home</button></a>
</div>
<?php
include "design/templates/footer.php";
?>
</body>
</html>