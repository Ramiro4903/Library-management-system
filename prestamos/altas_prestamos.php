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
        <title>Alta de Prestamos</title>

        <!-- parte del formulario ----------------------------------------------------------------- -->
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

            form {
                margin-top: 20px; 
                margin-left: 760px; 
                max-width: 360px;
                width: 100%;
                padding: 30px;
                background-color: #fff;
                border-radius: 10px;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            }
            label {
                font-weight: 600;
                display: block;
                margin-bottom: 8px;
                color: #1e293b;
            }
            input[type="text"],
            input[type="number"],
            select {
                width: 100%;
                padding: 12px;
                margin: 8px 0 16px;
                border: 1px solid #cbd5e1;
                border-radius: 8px;
                box-sizing: border-box;
                transition: border-color 0.3s;
            }
            input[type="text"]:focus,
            input[type="number"]:focus,
            select:focus {
                border-color: #636663;
                outline: none;
            }
            input[type="file"] {
                margin: 8px 0 16px;
            }
            input[type="submit"] {
                width: 100%;
                background-color: #2c3e50;
                color: white;
                border: none;
                border-radius: 8px;
                padding: 12px;
                cursor: pointer;
                font-weight: 600;
                transition: background-color 0.3s;
            }
            input[type="submit"]:hover {
                background-color: #3730a3;
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

            
            input[type="date"] {
                width: 100%;
                padding: 12px;
                margin: 8px 0 16px;
                border: 1px solid #cbd5e1;
                border-radius: 8px;
                box-sizing: border-box;
                transition: border-color 0.3s;
                font-family: Arial, sans-serif;
                color: #333;
                background-color: white;
            }
            input[type="date"]:focus {
                border-color: #6366f1;
                outline: none;
            }
            #mensaje{
                color: #F00;
                font-size: 18px;
            }
            #corrobora{
                color: #3789ff;
                font-size: 15px;
            }
        </style>        
    </head>

    <body>
        <div class="header">
            <h1>Alta de prestamos</h1>
        </div>

        <div class="container">
            <!-- Formulario -------------------------------------------------------------------------------->
            <form action="registrar_prestamos.php" method="post">
                <label for="id_solicitante">ID del Solicitante</label>
                <input type="number" id="id_solicitante" name="id_solicitante" placeholder="ID del solicitante" required><br>

                <label for="isbn">ISBN del Libro</label>
                <input type="text" id="isbn" name="isbn" placeholder="Ej: 521-24-7329-125-8" required><br>

                <label for="numero_ejemplar">Número de Ejemplar</label>
                <input type="number" id="numero_ejemplar" name="numero_ejemplar" placeholder="Ej: 1" min="1" required><br>

                <label for="fecha_prestamo">Fecha de Préstamo</label>
                <input type="date" id="fecha_prestamo" name="fecha_prestamo"  required><br>

                <input type="submit" value="Registrar Préstamo">
                <a href="prestamos.php">Regresar</a>
            </form>

        </div>
    </body>
</html>