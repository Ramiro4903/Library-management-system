<?php
// Equipo 5
/*
Ramirez Guzman Ramiro
Reyes Magaña Brayan Emmanuel
Sanchez Loza Montserrat Guadalupe
Suarez Camarena Kimberly Lizbeth
*/
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

$usuario_actual = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Empleado - Biblioteca</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }
        .header {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .welcome-section {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            border-bottom: 1px solid #eee;
        }
        .employee-info {
            background-color: #e8f4fd;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
            border-left: 5px solid #3498db;
        }
        .library-image {
            width: 100%;
            height: 300px;
            overflow: hidden;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .library-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .button-container {
            text-align: center;
            margin-top: 20px;
        }
        .button {
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 0 10px;
        }
        .button:hover {
            background-color: #2980b9;
        }
        .quick-actions {
            display: flex;
            justify-content: space-around;
            margin: 30px 0;
            flex-wrap: wrap;
        }
        .action-card {
            width: 45%;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            margin: 10px 0;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .action-card h3 {
            color: #2c3e50;
            margin-top: 0;
        }
        a {
            display: block;
            margin: 16px auto 0;
            width: 50%;
            padding: 8px;
            background-color: #2c3e50;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            text-align: center; 
            transition: background-color 0.3s;
            font-weight: 600;
        }

        a:hover {
            background-color: #3730a3;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sistema de Biblioteca - Prestamos</h1>
    </div>
    
    <div class="container">       
        <div class="library-image">
        <img src="../img/biblio2.jpg" >
        </div>
        
        <div class="quick-actions">
            <div class="action-card">
                <h3><i class="fas fa-book"></i> Registrar Prestamos</h3>
                <p>Registre nuevos prestamos</p>
                <a href="altas_prestamos.php" class="button">Acceder</a>
            </div>
            
            <div class="action-card">
                <h3><i class="fas fa-search"></i> Consultas generales</h3>
                <p>Consulte la información de todos los prestamos previamente registrados</p>
                <a href="buscar_prestamos.php" class="button">Buscar</a>
            </div>
            
        </div>

        <a href="../empleado_dashboard.php">Regresar</a>
        
        <div class="button-container">
            <a href="../index.php" class="button" style="background-color: #e74c3c;">Cerrar Sesión</a>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    
</body>
</html>
