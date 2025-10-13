<?php
//  Чтобы на разных страницах оставалась информация о данных пользователя будем использовать сессии (Другой вариант: cookie)
session_start();

$db = mysqli_connect("localhost", "root", "", "Moy_ne_sam"); 
if (!$db) {
    die("Ошибка подключения к базе");
}

function find() {
    global $db;
    $result = mysqli_query($db, "SELECT * FROM `user` WHERE `username` = 'adminka' AND `password` = MD5('password')");
    return mysqli_num_rows($result);
}
