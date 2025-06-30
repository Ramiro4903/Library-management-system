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
    header("Location: login.php");
    exit();
}

$host = "localhost";
$dbname = "Biblioteca";
$user = "postgres";
$password = "1234";

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['id_prestamo'])) {
        $id_prestamo = $_GET['id_prestamo'];
        
        // 1. Obtener información del préstamo
        $sql = 'SELECT p.*, s.tipo 
                FROM "Prestamo" p
                JOIN "solicitante" s ON p.id_solicitante = s.id_solicitante
                WHERE p.id_prestamo = :id AND p.fecha_entrega_solicitante IS NULL';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id_prestamo, PDO::PARAM_INT);
        $stmt->execute();
        $prestamo = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$prestamo) {
            throw new Exception("Préstamo no encontrado o ya registrado");
        }
        
        // 2. Calcular multa manualmente
        $fecha_actual = new DateTime();
        $fecha_limite = new DateTime($prestamo['fecha_limite_entrega']);
        
        if ($fecha_actual > $fecha_limite) {
            $dias_retraso = $fecha_actual->diff($fecha_limite)->days;
            
            // Calcular multa según tipo de usuario
            $multa = ($prestamo['tipo'] == 'profesor') ? $dias_retraso * 10 : $dias_retraso * 5;
        } else {
            $multa = 0.00;
        }
        
        // 3. Actualizar el préstamo con fecha de entrega y multa
        $sql = 'UPDATE "Prestamo" 
                SET fecha_entrega_solicitante = CURRENT_DATE, 
                    multa = :multa 
                WHERE id_prestamo = :id';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id_prestamo, PDO::PARAM_INT);
        $stmt->bindParam(':multa', $multa);
        $stmt->execute();
        
        // Opcional: Mostrar mensaje de éxito con detalles
        $_SESSION['mensaje'] = "Entrega registrada correctamente. " . 
                             ($multa > 0 ? "Multa aplicada: \$".$multa : "Sin multa");
    }

    header("Location: prestamos.php"); 
    exit();

} catch (PDOException $e) {
    echo "Error de base de datos: " . $e->getMessage();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>