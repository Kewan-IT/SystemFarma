<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

<h2>Bem-vindo, <?php echo $_SESSION['usuario']; ?> 👋</h2>

<p>Sistema de Gestão da Farmácia</p>

<a href="logout.php">Sair</a>

</body>
</html>