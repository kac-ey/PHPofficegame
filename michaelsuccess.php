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
    echo $_SESSION['michael']->success, "<br><br>";
    echo "You collected:<br>", implode(", ",$_SESSION['michael']->inventory), "<br><br>";
    ?>
    <a class="button" href="index.php">Return Home</a>
</div>
<?php
include "design/templates/footer.php";
?>
</body>
</html>