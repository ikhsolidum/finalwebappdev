<!DOCTYPE html>
<html>
<head>
    <title>Weekend Closet</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Assistant&display=swap" rel="stylesheet">
</head>
<body>
    <?php
    include_once 'classes/class.user.php';
    include 'config/config.php';
    include 'navigation/navigationbar.php';

    $page = (isset($_GET['page']) && $_GET['page'] != '') ? $_GET['page'] : '';
    $subpage = (isset($_GET['subpage']) && $_GET['subpage'] != '') ? $_GET['subpage'] : '';
    $action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : '';
    $id = (isset($_GET['id']) && $_GET['id'] != '') ? $_GET['id'] : '';
    $user = new User();
    if (!$user->get_session()) {
        header("location: login/login.php");
    }
    $user_id = $user->get_user_id($_SESSION['user_email']);
    $user_status = $user->get_user_status($user_id);

    // Check user status
    $products_allowed = false;
    $orderlists_allowed = false;
    $users_allowed = false;

    if ($user_status == 'Manager') {
        $products_allowed = true;
        $orderlists_allowed = true;
        $users_allowed = true;
    } elseif ($user_status == 'Staff') {
        $orderlists_allowed = true;
        $products_allowed = true;
    }

    ?>

    <div class="topnav" id="myTopnav">
        <!-- Navigation links -->
        <a href="index.php?page=home" class="active">Home</a>
        <a href="logout/logout.php" class="logout">Logout</a>
        
        
        <?php if ($products_allowed): ?>
            <a href="index.php?page=products">Products</a>
        <?php endif; ?>

        <?php if ($orderlists_allowed): ?>
            <a href="index.php?page=orderlists">Order Lists</a>
        <?php endif; ?>

        <?php if ($users_allowed): ?>
            <a href="index.php?page=users">Users</a>
        <?php endif; ?>

        <?php if ($user_status == 'Staff'): ?>
            
        <?php endif; ?>

        <div class="user-status-wrapper">
            <span class="user-lastname"><?php echo $user->get_user_lastname($user_id).', '.$user->get_user_firstname($user_id);?></span>
            <?php if($user_status == 'Staff' || $user_status == 'Manager'): ?>
            <span class="user-status"><?php echo $user_status; ?></span>
            <?php endif; ?>
        </div>
    </div>

    <div id="content">
        <?php
        // Page content logic
        switch($page) {
            case 'home':
                require_once 'home.php';
                break; 
            case 'products':
                if ($products_allowed) {
                    require_once 'products/index.php';
                } else {
                    echo 'Access Denied';
                }
                break; 
            case 'orderlists':
                if ($orderlists_allowed) {
                    require_once 'products/order_details.php';
                } else {
                    echo 'Access Denied';
                }
                break;
            case 'users':
                if ($users_allowed) {
                    require_once 'user/main.php';
                } else {
                    echo 'Access Denied';
                }
                break;
            case 'logout':
                require_once 'logout/logout.php';
                break;
            default:
                // Handle invalid page
                break;
        }
        ?>
    </div>
</body>
</html>
