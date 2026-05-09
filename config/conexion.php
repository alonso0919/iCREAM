<?php
// config/conexion.php
// Las credenciales de base de datos se leen del archivo .env

$host     = getenv('DB_HOST')     ?: 'localhost';
$user     = getenv('DB_USER')     ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$database = getenv('DB_NAME')     ?: 'engineeringstore';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Error de conexion: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// ---- Funciones de productos y ventas ----

function getProductos($conn, $destacados = false) {
    $sql = "SELECT * FROM productos";
    if ($destacados) {
        $sql .= " WHERE destacado = 1";
    }
    $sql .= " ORDER BY nombre";
    $result    = $conn->query($sql);
    $productos = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
    }
    return $productos;
}

function getProductoById($conn, $id) {
    $sql  = "SELECT * FROM productos WHERE id_producto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function actualizarStock($conn, $id_producto, $cantidad) {
    $sql  = "UPDATE productos SET stock = stock - ? WHERE id_producto = ? AND stock >= ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $cantidad, $id_producto, $cantidad);
    return $stmt->execute();
}

function guardarVenta($conn, $carrito, $total, $datos_cliente = null) {
    $conn->begin_transaction();
    try {
        $sql  = "INSERT INTO ventas (nombre_cliente, email_cliente, telefono_cliente, total) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $nombre   = $datos_cliente['nombre']   ?? 'Cliente';
        $email    = $datos_cliente['email']    ?? 'cliente@email.com';
        $telefono = $datos_cliente['telefono'] ?? '';
        $stmt->bind_param("sssd", $nombre, $email, $telefono, $total);
        $stmt->execute();
        $id_venta = $conn->insert_id;

        foreach ($carrito as $item) {
            $sql_prod  = "SELECT id_producto FROM productos WHERE nombre LIKE ? LIMIT 1";
            $stmt_prod = $conn->prepare($sql_prod);
            $nombre_prod = "%{$item['name']}%";
            $stmt_prod->bind_param("s", $nombre_prod);
            $stmt_prod->execute();
            $producto   = $stmt_prod->get_result()->fetch_assoc();
            $id_producto = $producto['id_producto'] ?? 1;

            $sql_detalle  = "INSERT INTO detalle_venta (id_venta, id_producto, nombre_producto, precio_unitario, cantidad, subtotal) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_detalle = $conn->prepare($sql_detalle);
            $subtotal     = $item['price'] * $item['quantity'];
            $stmt_detalle->bind_param("iisdis", $id_venta, $id_producto, $item['name'], $item['price'], $item['quantity'], $subtotal);
            $stmt_detalle->execute();

            actualizarStock($conn, $id_producto, $item['quantity']);
        }

        $num_ticket      = 'TICKET-' . date('Ymd') . '-' . str_pad($id_venta, 5, '0', STR_PAD_LEFT);
        $contenido_ticket = "=== TICKET DE COMPRA ===\n\n";
        $contenido_ticket .= "Fecha: "     . date('d/m/Y H:i:s') . "\n";
        $contenido_ticket .= "Ticket N: "  . $num_ticket . "\n\nProductos:\n------------------------\n";
        foreach ($carrito as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $contenido_ticket .= $item['name'] . "\n  {$item['quantity']} x $" . number_format($item['price'], 2) . " = $" . number_format($subtotal, 2) . "\n";
        }
        $contenido_ticket .= "------------------------\nTOTAL: $" . number_format($total, 2) . "\n\nGracias por tu compra!\niCREAM\n";

        $sql_ticket  = "INSERT INTO tickets (id_venta, numero_ticket, contenido) VALUES (?, ?, ?)";
        $stmt_ticket = $conn->prepare($sql_ticket);
        $stmt_ticket->bind_param("iss", $id_venta, $num_ticket, $contenido_ticket);
        $stmt_ticket->execute();

        $conn->commit();
        return ['success' => true, 'id_venta' => $id_venta, 'num_ticket' => $num_ticket, 'contenido' => $contenido_ticket];

    } catch (Exception $e) {
        $conn->rollback();
        return ['success' => false, 'error' => $e->getMessage()];
    }
}
