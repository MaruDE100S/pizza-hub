<?php
require_once '../database/config.php';
require_once '../public/includes/auth.php';

$page = $_GET['page'] ?? 'Home';

checkAccess($page);

$page_path = '../public/pages/';

include '../public/includes/header.php';

switch ($page) {
    case 'Home':
        include $page_path.'home.php';
        break;
    case 'Menu':
        include $page_path.'menu.php';
        break;
    case 'Pizza':
        include $page_path.'pizza.php';
        break;
    case 'Cart':
        include $page_path.'cart.php';
        break;
    case 'Orders':
        include $page_path.'orders.php';
        break;
    case 'Login':
        include $page_path.'login.php';
        break;
    case 'Register':
        include $page_path.'register.php';
        break;
    case 'Employee':
        include $page_path.'employee.php';
        break;
    case 'Admin':
        include $page_path.'admin.php';
        break;
    default:
        include $page_path.'404.php';
        break;
}

include '../public/includes/footer.php';
?>
