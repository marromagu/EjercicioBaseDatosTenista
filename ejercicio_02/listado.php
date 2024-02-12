<!-- ESCRIBA AQUI EL CÓDIGO PARA INCLUIR FICHEROS PHP -->
<?php
include '../utiles/config.php';
include '../utiles/funciones.php';

// Consulta para obtener todos los torneos
$sql = "SELECT t.id, t.nombre AS nombre_torneo, t.ciudad, s.nombre AS nombre_superficie
        FROM torneos t
        INNER JOIN superficies s ON t.superficie_id = s.id";

$stmt = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de torneos</title>
    <link rel="stylesheet" type="text/css" href="../css/estilos.css">
</head>
<body>
    <h1>Listado de torneos usando fetch (PDO::FETCH_BOUND)</h1>

    <!-- ESCRIBA AQUI EL CÓDIGO HTML Y/O PHP NECESARIO -->
    <table border="1" cellpadding="10">
        <thead>
            <th>Nombre</th>
            <th>Ciudad</th>
            <th>Superficie</th>
            <th>Acciones</th>
        </thead>
        <tbody>
            <!-- ESCRIBA AQUI EL CÓDIGO HTML Y/O PHP NECESARIO -->
            <?php
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>{$row['nombre_torneo']}</td>";
                echo "<td>{$row['ciudad']}</td>";
                echo "<td>{$row['nombre_superficie']}</td>";
                echo "<td><a href='modificar.php?id_torneo={$row['id']}'> &#9998; Modificar</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    
</body>
</html>
