<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Natural Coach</title>
</head>
<body>
<form action="identification.php" method="POST">
    <input type="text" name="login" id="login">
    <input type="text" name="password" id="password">
    <input type="submit" value="Valider">
</form>
<?php



?>  
</body>
</html>