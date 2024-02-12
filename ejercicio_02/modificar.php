<!-- ESCRIBA AQUI EL CÓDIGO PARA INCLUIR FICHEROS PHP -->
<?php
include '../utiles/config.php';
include '../utiles/funciones.php';

// Validar si se ha recibido el id del torneo por GET
$idTorneo = obtenerValorCampo('id_torneo');

// Redirigir a la página de listado si no se recibe el id o no es válido
if (empty($idTorneo) || !validarEnteroRango($idTorneo, 1, PHP_INT_MAX)) {
    header("Location: listado.php");
    exit();
}

// Consulta para obtener la información del torneo por id
$sql = "SELECT t.id, t.nombre AS nombre_torneo, t.ciudad, t.superficie_id
        FROM torneos t
        WHERE t.id = :id_torneo";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':id_torneo', $idTorneo, PDO::PARAM_INT);
$stmt->execute();

// Verificar si el torneo existe
if ($stmt->rowCount() === 0) {
    header("Location: listado.php");
    exit();
}

// Obtener los datos del torneo
$torneo = $stmt->fetch(PDO::FETCH_ASSOC);

// Procesar el formulario si se ha enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $nombreTorneo = obtenerValorCampo('nombre_torneo');
    $ciudad = obtenerValorCampo('ciudad');
    $superficieId = obtenerValorCampo('superficie_id');

    // Validar los datos
    $errores = [];

    if (!validarLongitudCadena($nombreTorneo, 3, 60)) {
        $errores['nombre_torneo'] = 'El nombre del torneo debe tener entre 3 y 60 caracteres.';
    }

    if (!validarLongitudCadena($ciudad, 3, 60)) {
        $errores['ciudad'] = 'La ciudad debe tener entre 3 y 60 caracteres.';
    }

    // Validar la existencia de la superficie
    $sql = "SELECT id FROM superficies WHERE id = :superficie_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':superficie_id', $superficieId, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() === 0) {
        $errores['superficie_id'] = 'La superficie seleccionada no es válida.';
    }

    // Validar que no haya dos torneos con nombres iguales
    $sql = "SELECT id FROM torneos WHERE nombre = :nombre_torneo AND id != :id_torneo";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nombre_torneo', $nombreTorneo, PDO::PARAM_STR);
    $stmt->bindParam(':id_torneo', $idTorneo, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $errores['nombre_torneo'] = 'Ya existe un torneo con este nombre.';
    }

    // Si no hay errores, actualizar el torneo
    if (empty($errores)) {
        $sql = "UPDATE torneos SET nombre = :nombre_torneo, ciudad = :ciudad, superficie_id = :superficie_id WHERE id = :id_torneo";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nombre_torneo', $nombreTorneo, PDO::PARAM_STR);
        $stmt->bindParam(':ciudad', $ciudad, PDO::PARAM_STR);
        $stmt->bindParam(':superficie_id', $superficieId, PDO::PARAM_INT);
        $stmt->bindParam(':id_torneo', $idTorneo, PDO::PARAM_INT);
        $stmt->execute();

        // Redireccionar a la página de listado
        header("Location: listado.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar un torneo</title>
    <link rel="stylesheet" type="text/css" href="../css/estilos.css">
</head>
<body>
    <h1>Modificar un torneo</h1>

    <!-- ESCRIBA AQUI EL CÓDIGO HTML Y/O PHP NECESARIO -->
    <!-- Formulario -->
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <input type="hidden" name="id_torneo" value="<?php echo $torneo['id']; ?>">
        <p>
            <!-- Campo nombre del torneo -->
            <input type="text" name="nombre_torneo" placeholder="Nombre torneo" value="<?php echo htmlspecialchars($torneo['nombre_torneo']); ?>">
            <span style="color: red">
                <?php echo isset($errores['nombre_torneo']) ? $errores['nombre_torneo'] : ''; ?>
            </span>
        </p>
        <p>
            <!-- Campo ciudad del torneo -->
            <input type="text" name="ciudad" placeholder="Ciudad" value="<?php echo htmlspecialchars($torneo['ciudad']); ?>">
            <span style="color: red">
                <?php echo isset($errores['ciudad']) ? $errores['ciudad'] : ''; ?>
            </span>
        </p>
        <p>
            <!-- Campo superficie -->
            <select id="superficie_id" name="superficie_id">
                <option value="">Seleccione Superficie</option>
                <?php
                // Obtener las superficies disponibles
                $sql = "SELECT id, nombre FROM superficies";
                $stmt = $conn->query($sql);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $selected = ($row['id'] == $torneo['superficie_id']) ? 'selected' : '';
                    echo "<option value='{$row['id']}' $selected>{$row['nombre']}</option>";
                }
                ?>
            </select>
            <span style="color: red">
                <?php echo isset($errores['superficie_id']) ? $errores['superficie_id'] : ''; ?>
            </span>
        </p>
        <p>
            <!-- Botón submit -->
            <input type="submit" value="Guardar">
        </p>
    </form>

    <div class="contenedor">
        <div class="enlaces">
            <a href="listado.php">Volver al listado de torneos</a>
        </div>
    </div>
</body>
</html>
