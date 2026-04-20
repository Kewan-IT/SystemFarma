<?php
session_start();
require_once("../config/conexao.php");

$funcionario_id = $_SESSION['funcionario_id'] ?? null;
$func = null;

if ($funcionario_id) {
    $sql = "SELECT nome, apelido, foto FROM funcionarios WHERE id = $funcionario_id";
    $res = $conn->query($sql);
    $func = $res->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Kewan Farma</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
/* RESET */
* {
    margin:0;
    padding:0;
    box-sizing:border-box;
}

/* BODY */
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f1f5f9;
    display:flex;
}

/* SIDEBAR */
.sidebar {
    width:260px;
    height:100vh;
    background: linear-gradient(180deg, #0f172a, #1e293b);
    color:#fff;
    position:fixed;
    transition:0.3s;
    box-shadow: 4px 0 10px rgba(0,0,0,0.1);
}

.sidebar.collapsed {
    width:80px;
}

/* LOGO */
.logo {
    padding:20px;
    text-align:center;
}

.logo img {
    width:50px;
}

/* MENU */
.menu {
    list-style:none;
    margin-top:10px;
}

.menu li {
    padding:12px 20px;
    cursor:pointer;
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-radius:10px;
    margin:5px 10px;
    transition:0.2s;
}

.menu li:hover {
    background: rgba(255,255,255,0.1);
    transform: translateX(5px);
}

/* ICON + TEXT */
.menu li div {
    display:flex;
    align-items:center;
    gap:10px;
}

/* SUBMENU */
.submenu {
    display:none;
    margin-left:10px;
}

.submenu a {
    display:block;
    padding:10px 40px;
    color:#cbd5e1;
    text-decoration:none;
    font-size:14px;
    border-radius:8px;
    margin:3px 10px;
    transition:0.2s;
}

.submenu a:hover {
    background:#334155;
    padding-left:45px;
}

/* CONTENT */
.content {
    margin-left:260px;
    padding:20px;
    width:100%;
    transition:0.3s;
}

.content.full {
    margin-left:80px;
}

/* TOPBAR */
.topbar {
    background:#fff;
    padding:12px 20px;
    border-radius:12px;
    box-shadow:0 2px 8px rgba(0,0,0,0.05);
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

/* BUTTON */
.topbar button {
    border:none;
    background:#2563eb;
    color:#fff;
    padding:8px 12px;
    border-radius:8px;
    cursor:pointer;
}

/* USER BOX */
.user-box {
    position:absolute;
    bottom:0;
    width:100%;
    padding:15px;
    background:#020617;
    text-align:center;
}

.user-box img {
    border-radius:50%;
    border:2px solid #22c55e;
}

/* COLLAPSED MODE */
.sidebar.collapsed span {
    display:none;
}

/* ROTATE ICON */
.rotate {
    transform:rotate(90deg);
    transition:0.3s;
}

/* ANIMATION */
.sidebar, .content {
    transition: all 0.3s ease;
}
</style>
</head>

<body>