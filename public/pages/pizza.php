<?php
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    $pizza = getPizzaById($id);
} else {
    echo 'Pizza unfound';
}
?>

<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PizzaView</title>
    <link rel="stylesheet" href="../public/style/style.css">
</head>
<body>
    <script src="../js/main.js"></script>
</body>
</html>