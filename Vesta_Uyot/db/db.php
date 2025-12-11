<?php
session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'Vesta_Uyot';

$db = mysqli_connect($host, $username, $password, $database);

if (!$db) {
    die("Ошибка подключения: " . mysqli_connect_error());
}

mysqli_set_charset($db, 'utf8');

function find($login, $password) {
    global $db;
    $login = mysqli_real_escape_string($db, $login);
    $password = mysqli_real_escape_string($db, $password);
    
    $query = "SELECT u.*, g.group_name, ut.name_user as user_type_name 
              FROM user u 
              LEFT JOIN groups g ON u.group_id = g.id_group 
              LEFT JOIN user_type ut ON u.user_type_id = ut.id_user_type
              WHERE u.username = '$login' AND u.password = MD5('$password')";
    $result = mysqli_query($db, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return false;
}

// Функция для получения всех групп
function getAllGroups($db) {
    $query = "SELECT id_group, group_name FROM groups ORDER BY group_name";
    $result = mysqli_query($db, $query);
    $groups = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $groups[] = $row;
    }
    return $groups;
}

// Функция для получения информации о пользователе
function getUserInfo($db, $user_id) {
    $user_id = (int)$user_id;
    $query = "SELECT u.*, g.group_name FROM user u 
              LEFT JOIN groups g ON u.group_id = g.id_group 
              WHERE u.id_user = $user_id";
    $result = mysqli_query($db, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}

// Функция для проверки логина преподавателя
function checkTeacherLogin($login, $password) {
    global $db;
    // Преподаватель: логин teacher, пароль practice2025
    if ($login === 'teacher' && $password === 'practice2025') {
        $query = "SELECT u.*, g.group_name, ut.name_user as user_type_name 
                  FROM user u 
                  LEFT JOIN groups g ON u.group_id = g.id_group 
                  LEFT JOIN user_type ut ON u.user_type_id = ut.id_user_type
                  WHERE u.username = 'teacher' AND u.password = MD5('practice2025')";
        $result = mysqli_query($db, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
    }
    return false;
}
?>