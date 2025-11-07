<?php
session_start();
require_once 'server/database.php';
require_once 'server/functions.php';

// เช็คการเข้าสู่ระบบ
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// ฟังก์ชันสำหรับการโหลดหน้า
function loadPage($page)
{
    $filePath = "pages/$page.php";
    if (file_exists($filePath)) {
        require $filePath;
    } else {
        require 'pages/404.php';
    }
}

// ใช้ getPath() เพื่อหาเส้นทาง
$path = getPath();

switch ($path) {
    case 'home':
        loadPage('home');
        break;
    case 'about':
        loadPage('about');
        break;
    case 'contact':
        loadPage('contact');
        break;

        // user
    case 'insert_user':
        checkPermission('Superadmin');
        loadPage('user/insert_user');
        break;
    case 'process_insert_user':
        loadPage('user/process_insert_user');
        break;
    case 'manage_user':
        checkPermission('Superadmin');
        loadPage('user/manage_user');
        break;
    case 'reset_password':
        loadPage('user/reset_password');
        break;
    case 'delete_user':
        loadPage('user/delete_user');
        break;
    case 'edit_user':
        loadPage('user/edit_user');
        break;
    case 'edit_user_action':
        loadPage('user/edit_user_action');
        break;
        // budget
    case 'add_budget':
        checkPermission('Superadmin');
        loadPage('budget/add_budget');
        break;
    case 'manage_budget':
        checkPermission('Superadmin');
        loadPage('budget/manage_budget');
        break;
    case 'edit_budget':
        checkPermission('Superadmin');
        loadPage('budget/edit_budget');
        break;
    case 'delete_budget':
        checkPermission('Superadmin');
        loadPage('budget/delete_budget');
        break;


        // project
    case 'select_add_project':
        loadPage('project/select_add_project');
        break;
    case 'add_new_project':
        loadPage('project/add_new_project');
        break;
    case 'add_new_project_type2':
        loadPage('project/add_new_project_type2');
        break;
    case 'process_add_new_project':
        loadPage('project/process_add_new_project');
        break;
    case 'add_sub_project':
        loadPage('project/add_sub_project_form');
        break;
    case 'process_add_sub_project':
        loadPage('project/process_add_sub_project');
        break;
    case 'add_project':
        loadPage('project/add_project_request_form');
        break;
    case 'project_list':
        loadPage('project/project_list');
        break;
    case 'process_add_project_request':
        loadPage('project/process_add_project_request');
        break;
    case 'manage_projects':
        loadPage('project/manage_projects');
        break;
    case 'add_expense':
        loadPage('project/add_expense');
        break;
    case 'added_expense':
        loadPage('project/added_expense');
        break;
    case 'add_expense_sub':
        loadPage('project/add_expense_sub');
        break;
    case 'get_expense_items':
        loadPage('project/get_expense_items');
        break;
    case 'get_unit_cost':
        loadPage('project/get_unit_cost');
        break;
    case 'process_add_expense':
        loadPage('project/process_add_expense');
        break;
    case 'process_added_expense':
        loadPage('project/process_added_expense');
        break;
    case 'process_add_expense_sub':
        loadPage('project/process_add_expense_sub');
        break;
    case 'project_details':
        loadPage('project/project_details');
        break;
    case 'edit_expense':
        loadPage('project/edit_expense');
        break;
    case 'update_project_expense':
        loadPage('project/update_project_expense');
        break;
    case 'delete_expense':
        loadPage('project/delete_expense');
        break;
    case 'submit_comment':
        loadPage('project/submit_comment');
        break;
    case 'approve_project':
        loadPage('project/approve_project');
        break;
    case 'update_project_detail':
        loadPage('project/update_project_detail');
        break;
    case 'resent_project':
        loadPage('project/resent_project');
        break;
    case 'sentverify_project':
        loadPage('project/sentverify_project');
        break;
    case 'get_subways':
        loadPage('project/get_subways');
        break;
    case 'approve_report':
        loadPage('project/pdfreport/approve_report');
        break;


        // default
    default:
        loadPage('404');
        break;
}
