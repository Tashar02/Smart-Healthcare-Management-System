<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('location: ../admin/login-admin.php');
    exit();
}
try {
    if (!file_exists('connection-pdo.php')) {
        throw new Exception();
    } else {
        require_once('connection-pdo.php');
    }
} catch (Exception $e) {
    $_SESSION['msg'] = 'Database connection failed!';
    header('location: ../admin/dashboard.php');
    exit();
}

if (!isset($_GET['type']) || !isset($_GET['id'])) {
    $_SESSION['msg'] = 'Invalid request!';
    header('location: ../admin/dashboard.php');
    exit();
}

$type = $_GET['type'];
$id = intval($_GET['id']);

if ($type == 'department') {
    // Check if department has doctors
    $sql_check = "SELECT COUNT(*) FROM doctors WHERE dept_id=?";
    $query_check = $pdoconn->prepare($sql_check);
    $query_check->execute([$id]);
    $doc_count = $query_check->fetchColumn();
    
    if ($doc_count > 0) {
        $_SESSION['msg'] = 'Cannot delete department while doctors are assigned to it!';
    } else {
        $sql = "DELETE FROM departments WHERE id=?";
        $query = $pdoconn->prepare($sql);
        if ($query->execute([$id])) {
            $_SESSION['msg'] = 'Record deleted successfully!';
        } else {
            $_SESSION['msg'] = 'Failed to delete record!';
        }
    }
    $redirect = '../admin/department-list.php';
    
} elseif ($type == 'doctor') {
    $sql = "DELETE FROM doctors WHERE id=?";
    $query = $pdoconn->prepare($sql);
    if ($query->execute([$id])) {
        $sql_user = "DELETE FROM users WHERE id=? AND role='doctor'";
        $query_user = $pdoconn->prepare($sql_user);
        $query_user->execute([$id]);
        $_SESSION['msg'] = 'Record deleted successfully!';
    } else {
        $_SESSION['msg'] = 'Failed to delete record!';
    }
    $redirect = '../admin/doctor-list.php';
    
} elseif ($type == 'user') {
    $sql_info = "SELECT role FROM users WHERE id=?";
    $query_info = $pdoconn->prepare($sql_info);
    $query_info->execute([$id]);
    $user = $query_info->fetch(PDO::FETCH_ASSOC);
    
    $sql = "DELETE FROM users WHERE id=?";
    $query = $pdoconn->prepare($sql);
    if ($query->execute([$id])) {
        if ($user && $user['role'] == 'doctor') {
            $sql_doc = "DELETE FROM doctors WHERE id=?";
            $query_doc = $pdoconn->prepare($sql_doc);
            $query_doc->execute([$id]);
        }
        $_SESSION['msg'] = 'Record deleted successfully!';
    } else {
        $_SESSION['msg'] = 'Failed to delete record!';
    }
    $redirect = '../admin/user-list.php';
    
} else {
    $_SESSION['msg'] = 'Invalid type!';
    header('location: ../admin/dashboard.php');
    exit();
}

header('location: ' . $redirect);
exit();
?>
