<?php 
include '../utiles/config.php';

try {
    // Consulta para obtener las superficies
    $sql = "SELECT s.nombre AS superficie, COUNT(t.id) AS num_torneos_gs
        FROM superficies s
        LEFT JOIN torneos t ON s.id = t.superficie_id
        GROUP BY s.id, s.nombre
        ORDER BY s.nombre ASC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Obtener los resultados de la consulta en un array asociativo
    $superficies = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de superficies</title>
    <link rel="stylesheet" type="text/css" href="../css/estilos.css">
</head>
<body>
    <h1>Listado de superficies usando fetch (PDO::FETCH_ASSOC)</h1>

    <table border="1" cellpadding="10">
        <thead>
            <th>Superficie</th>
            <th>Número de torneos superficie</th>
        </thead>
        <tbody>
        <?php foreach ($superficies as $superficie): ?>
        <tr>
            <td><?php echo $superficie['superficie']; ?></td>
            <td><?php echo $superficie['num_torneos_gs']; ?></td>
        </tr>
    <?php endforeach; ?>
        </tbody>
    </table>
    <div class="contenedor">
        <div class="enlaces">
            <!-- Agregue el enlace adecuado -->
            <a href="nueva.php">Nueva superficie</a>
        </div>
    </div>
</body>
</html>
