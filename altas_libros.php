<?php
/*
Equipo 5
Ramirez Guzman Ramiro
Reyes Magaña Brayan Emmanuel
Sanchez Loza Montserrat Guadalupe
Suarez Camarena Kimberly Lizbeth
*/
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$usuario_actual = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Alta de libros</title>

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
            <h1>Alta de libros</h1>
        </div>

        <div class="container">
            <!-- Formulario -------------------------------------------------------------------------------->
           <form action="registrar_libros.php" method="post">
                <input type="text" id="isbn" name="isbn" placeholder="ISBN" required><br>
                <input type="text" id="titulo" name="titulo" placeholder="Titulo" required><br>
                <input type="text" id="autor" name="autor" placeholder="Autor" required><br>
                <input type="text" id="editorial" name="editorial" placeholder="Editorial" required><br>
                <input type="number" id="año_de_publicacion" name="año_de_publicacion" placeholder="Año de publicacion" required><br>
                <input type="text" id="numero_de_ejemplar" name="numero_de_ejemplar" placeholder="Ejemplar" required><br>


                <input type="submit" value="Registrar Libro">
                <a href="libros.php">Regresar</a>
            </form>

        </div>
    </body>
</html>