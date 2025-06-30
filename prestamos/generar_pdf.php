<?php
// Equipo 5
/*
Ramirez Guzman Ramiro
Reyes Magaña Brayan Emmanuel
Sanchez Loza Montserrat Guadalupe
Suarez Camarena Kimberly Lizbeth
*/
require('fpdf/fpdf.php');
require_once 'config.php';

class PDF extends FPDF {
    function Header() {
        $this->Image('imagenes/cucei.png', 90, 8, 30); 
        $this->SetY(40); 
    }
}

session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['id_prestamo'])) {
    $id_prestamo = $_GET['id_prestamo'];
    
    try {
        $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
        
        $sql = 'SELECT p.*, s.tipo, l.titulo, l.autor, 
                CASE WHEN s.tipo = \'alumno\' THEN a.nombre ELSE pr.nombre END as nombre_solicitante
                FROM "Prestamo" p
                JOIN "solicitante" s ON p.id_solicitante = s.id_solicitante
                JOIN "libro" l ON p.id_libro = l.numero_de_ejemplar
                LEFT JOIN "Alumno" a ON s.id_solicitante = a.codigo AND s.tipo = \'alumno\'
                LEFT JOIN "Profesores" pr ON s.id_solicitante = pr.codigo AND s.tipo = \'profesor\'
                WHERE p.id_prestamo = ?';
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_prestamo]);
        $prestamo = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$prestamo) {
            die('Préstamo no encontrado');
        }
       
        $pdf = new PDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',14);
        $pdf->Ln(15);
        

        $pdf->Cell(0,10,utf8_decode('COMPROBANTE DE PRÉSTAMO'),0,1,'C');
        $pdf->Ln(8);

        $pdf->SetDrawColor(200,200,200);
        $pdf->Cell(0,0,'','T'); 
        $pdf->Ln(10);

        $pdf->SetFont('Arial','',10);
        
        // Función para mostrar campos
        function mostrarCampo($pdf, $etiqueta, $valor, $ancho = 45) {
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell($ancho,7,utf8_decode($etiqueta),0,0);
            $pdf->SetFont('Arial','',10);
            $pdf->Cell(0,7,utf8_decode($valor),0,1);
            $pdf->Ln(2);
        }
        
        // Datos del préstamo
        mostrarCampo($pdf, 'ID PRÉSTAMO:', $prestamo['id_prestamo']);
        mostrarCampo($pdf, 'FECHA PRÉSTAMO:', $prestamo['fecha_prestamo']);
        mostrarCampo($pdf, 'SOLICITANTE:', $prestamo['nombre_solicitante']);
        mostrarCampo($pdf, 'TIPO:', ucfirst($prestamo['tipo']));
        mostrarCampo($pdf, 'LIBRO:', $prestamo['titulo']);
        mostrarCampo($pdf, 'AUTOR:', $prestamo['autor']);
        mostrarCampo($pdf, 'ID LIBRO:', $prestamo['id_libro']);
        mostrarCampo($pdf, 'FECHA LÍMITE:', $prestamo['fecha_limite_entrega']);
        mostrarCampo($pdf, 'FECHA DEVOLUCIÓN:', $prestamo['fecha_entrega_solicitante'] ?? 'Pendiente');
        
        // Mostrar multa si existe
        if ($prestamo['multa'] > 0) {
            $pdf->Ln(5);
            $pdf->SetFont('Arial','B',12);
            $pdf->SetTextColor(255,0,0);
            mostrarCampo($pdf, 'MULTA A PAGAR:', '$'.number_format($prestamo['multa'], 2));
            $pdf->SetTextColor(0,0,0);

        }
        
        // Salida del PDF
        $pdf->Output('D', 'comprobante_prestamo.pdf');
        
    } catch (PDOException $e) {
        die('Error de base de datos: ' . $e->getMessage());
    }
} else {
    die('ID de prestamo no especificado');
}
?>