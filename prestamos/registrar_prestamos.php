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

require __DIR__ . '/phpmailer/src/Exception.php';
require __DIR__ . '/phpmailer/src/PHPMailer.php';
require __DIR__ . '/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$host = "localhost";
$port = "5432";
$dbname = "Biblioteca";  
$user = "postgres";    
$password = "1234";        
$conn = pg_connect("host=$host dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Error de conexión a la base de datos.");
}   

// Obtener datos del formulario
$id_solicitante = $_POST['id_solicitante'];
$isbn = $_POST['isbn'];
$numero_ejemplar = $_POST['numero_ejemplar'];
$fecha_prestamo = $_POST['fecha_prestamo'];

// Validar y limpiar los datos
$isbn = pg_escape_string(trim($isbn));
$numero_ejemplar = (int)$numero_ejemplar;

// Crear el numero_de_ejemplar concatenado
$numero_de_ejemplar_completo = $numero_ejemplar . '-' . $isbn;

// Iniciar transacción
pg_query($conn, "BEGIN");

try {
    // 1. Validar solicitante - Primero buscar en Alumno
    $query_alumno = 'SELECT codigo as id, nombre, correo, \'alumno\' as tipo FROM "Alumno" WHERE codigo = $1';
    $result_alumno = pg_query_params($conn, $query_alumno, array($id_solicitante));

    if (pg_num_rows($result_alumno) > 0) {
        $solicitante = pg_fetch_assoc($result_alumno);
    } 
    // Si no es alumno, buscar en Profesores
    else {
        $query_profesor = 'SELECT codigo as id, nombre, correo, \'profesor\' as tipo FROM "Profesores" WHERE codigo = $1';
        $result_profesor = pg_query_params($conn, $query_profesor, array($id_solicitante));
        
        if (pg_num_rows($result_profesor) > 0) {
            $solicitante = pg_fetch_assoc($result_profesor);
        } else {
            throw new Exception("El ID $id_solicitante no existe como alumno ni como profesor.");
        }
    }

    $query_upsert = 'INSERT INTO solicitante (id_solicitante, tipo) 
                    VALUES ($1, $2)
                    ON CONFLICT (id_solicitante) DO UPDATE 
                    SET tipo = EXCLUDED.tipo
                    RETURNING id_solicitante';
    $result_upsert = pg_query_params($conn, $query_upsert, 
                                   array($id_solicitante, $solicitante['tipo']));
    
    if (!$result_upsert) {
        throw new Exception("Error al registrar/actualizar en tabla solicitante: " . pg_last_error($conn));
    }

    // 3. Validar libro usando numero_de_ejemplar concatenado
    $query_libro = 'SELECT * FROM "libro" WHERE numero_de_ejemplar = $1';
    $result_libro = pg_query_params($conn, $query_libro, array($numero_de_ejemplar_completo));

    if (pg_num_rows($result_libro) == 0) {
        throw new Exception("No existe el ejemplar $numero_ejemplar del libro con ISBN $isbn.");
    }

    $libro = pg_fetch_assoc($result_libro);

    // 4. Verificar si el libro ya está prestado
    $query_prestado = 'SELECT 1 FROM "Prestamo" WHERE id_libro = $1 AND fecha_entrega_solicitante IS NULL';
    $result_prestado = pg_query_params($conn, $query_prestado, array($numero_de_ejemplar_completo));

    if (pg_num_rows($result_prestado) > 0) {
        throw new Exception("El ejemplar $numero_ejemplar del libro con ISBN $isbn ya está prestado.");
    }

    // 5. Registrar préstamo - CON CÁLCULO DIRECTO EN PHP
    // Calcular fecha límite (10 días después de la fecha de préstamo)
    $fecha_limite_calculada = date('Y-m-d', strtotime($fecha_prestamo . ' +10 days'));

    // Insertar préstamo incluyendo la fecha límite calculada
    $query_prestamo = 'INSERT INTO "Prestamo" (id_solicitante, id_libro, fecha_prestamo, fecha_limite_entrega)
                       VALUES ($1, $2, $3, $4) RETURNING id_prestamo';
    $result = pg_query_params($conn, $query_prestamo, 
                            array($id_solicitante, $numero_de_ejemplar_completo, $fecha_prestamo, $fecha_limite_calculada));

    if (!$result) {
        throw new Exception(pg_last_error($conn));
    }

    $prestamo = pg_fetch_assoc($result);
    // Usamos nuestra fecha calculada en lugar de la de la base de datos
    $prestamo['fecha_limite_entrega'] = $fecha_limite_calculada;

    // Confirmar transacción
    pg_query($conn, "COMMIT");

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ramiro.ramirez.guzman@gmail.com';
        $mail->Password = 'jkdy zdfn bocp jgqs';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Configuración del correo
        $mail->setFrom('ramiro.ramirez.guzman@gmail.com', 'Biblioteca CUCEI');
        $mail->addAddress($solicitante['correo'], $solicitante['nombre']);
        
        $mail->addReplyTo('biblioteca@udg.mx', 'Biblioteca CUCEI');
        
        // Formatear la fecha límite calculada para el correo
        $fecha_limite = date('d/m/Y', strtotime($prestamo['fecha_limite_entrega']));

        $mail->CharSet = 'UTF-8';  // Agrega esto antes de definir el asunto

        $mail->Subject = 'Prestamo registrado: ' . $libro['titulo'];
        $mail->isHTML(true);
        $mail->Body = "
            <h2>Comprobante de Préstamo - Biblioteca CUCEI</h2>
            <p><strong>Solicitante:</strong> {$solicitante['nombre']}</p>
            <p><strong>Tipo:</strong> " . ucfirst($solicitante['tipo']) . "</p>
            <p><strong>Libro:</strong> {$libro['titulo']}</p>
            <p><strong>Autor:</strong> {$libro['autor']}</p>
            <p><strong>ISBN:</strong> {$isbn}</p>
            <p><strong>Ejemplar:</strong> {$numero_ejemplar}</p>
            <p><strong>Fecha préstamo:</strong> $fecha_prestamo</p>
            <p><strong style='color: red;'>Fecha límite:</strong> $fecha_limite</p>
            <hr>
            <p><strong>Nota:</strong> La multa por retraso es de " . ($solicitante['tipo'] == 'profesor' ? '$10' : '$5') . " por día.</p>
            <p>Por favor, devuelva el libro a tiempo y en buen estado.</p>
        ";
        
        $mail->AltBody = "Comprobante de préstamo\n\n" .
                         "Solicitante: {$solicitante['nombre']}\n" .
                         "Tipo: " . ucfirst($solicitante['tipo']) . "\n" .
                         "Libro: {$libro['titulo']}\n" .
                         "Autor: {$libro['autor']}\n" .
                         "ISBN: {$isbn}\n" .
                         "Ejemplar: {$numero_ejemplar}\n" .
                         "Fecha préstamo: $fecha_prestamo\n" .
                         "Fecha límite: $fecha_limite\n\n" .
                         "Nota: La multa por retraso es de " . ($solicitante['tipo'] == 'profesor' ? '$10' : '$5') . " por día.";

        $mail->send();
        echo "<h2>Préstamo registrado y notificación enviada correctamente a {$solicitante['correo']}.</h2>";
        echo "<a href='buscar_prestamos.php'>Ver préstamos</a>";

    } catch (Exception $e) {
        echo "<h2>Préstamo registrado pero error al enviar correo: {$mail->ErrorInfo}</h2>";
        echo "<a href='buscar_prestamos.php'>Ver préstamos</a>";
    }

} catch (Exception $e) {
    // Revertir transacción en caso de error
    pg_query($conn, "ROLLBACK");
    
    echo "<h2>Error al procesar el préstamo:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<a href='altas_prestamos.php'>Regresar</a>";
    exit();
}
?>