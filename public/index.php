<?php
require_once '../database/config.php';
require_once '../public/includes/auth.php';

$page = $_GET['page'] ?? 'home';

checkAccess($page);

$page_path = '../public/pages/';

include '../public/includes/header.php';

switch ($page) {
    case 'home':
        include $page_path.'home.php';
        break;
    case 'menu':
        include $page_path.'menu.php';
        break;
    case 'pizza':
        include $page_path.'pizza.php';
        break;
    case 'cart':
        include $page_path.'cart.php';
        break;
    case 'orders':
        include $page_path.'orders.php';
        break;
    case 'login':
        include $page_path.'login.php';
        break;
    case 'register': 
        include $page_path.'register.php';
        break;
    case 'employee': 
        include $page_path.'employee.php';
        break;
    case 'admin':
        include $page_path.'admin.php';
        break;
    default:
        include $page_path.'404.php';
        break;
}

include '../public/includes/footer.php';
?>

<!-- <!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PizzaHub</title>
    <link rel="stylesheet" href="../public/style/style.css">
</head>
<body>
   
<script src="../js/main.js"></script>
</body>
</html> -->