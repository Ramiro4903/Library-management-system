<?php
/*
Equipo 5
Ramirez Guzman Ramiro
Reyes Magaña Brayan Emmanuel
Sanchez Loza Montserrat Guadalupe
Suarez Camarena Kimberly Lizbeth
*/
// Datos de conexión
$host = "localhost";
$port = "5432";
$dbname = "Biblioteca";
$user = "postgres";
$password = "1234";

$conn = pg_connect("host=$host dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Error de conexión a la base de datos.");
}

// Obtener los datos del formulario
$isbn = $_POST['isbn'];
$titulo = $_POST['titulo'];
$autor = $_POST['autor'];
$editorial = $_POST['editorial'];
$año_de_publicacion = $_POST['año_de_publicacion'];
$numero_de_ejemplar = $_POST['numero_de_ejemplar'];

// Generar ID único concatenando EJEMPLAR-ISBN (cambio principal)
$id_libro = $numero_de_ejemplar . '-' . $isbn;

// Preparar y ejecutar la consulta
$query = 'INSERT INTO "libro" (id_libro, ISBN, titulo, autor, editorial, año_de_publicacion, numero_de_ejemplar) 
          VALUES ($1, $2, $3, $4, $5, $6, $7)';
$params = array($id_libro, $isbn, $titulo, $autor, $editorial, $año_de_publicacion, $numero_de_ejemplar);

$result = pg_query_params($conn, $query, $params);

if ($result) {
    echo "<h2>Libro registrado correctamente.</h2>";
    echo "<a href='altas_libros.html'>Registrar otro libro</a>";
} else {
    echo "<h2>Error al registrar el libro.</h2>";
    echo "<p>" . pg_last_error($conn) . "</p>";
}

// Redirigir después del registro
// Nota: Esto no funcionará correctamente porque hay output antes (los echo)
// Opciones: 
// 1. Eliminar los echos y solo redirigir
// 2. Usar JavaScript para redirigir después de mostrar los mensajes
header("Location: buscar_libros.php");
exit;
?>