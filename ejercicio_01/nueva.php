<!-- ESCRIBA AQUI EL CÓDIGO PARA INCLUIR FICHEROS PHP -->
<?php
include '../utiles/config.php';
include '../utiles/funciones.php';

// Inicializar variables
$errores = [];
$superficie = '';

// Validar el formulario si se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el valor del campo 'superficie' del formulario
    $superficie = obtenerValorCampo('superficie');

    // Validar longitud de la superficie (entre 3 y 25 caracteres)
    if (!validarLongitudCadena($superficie, 3, 25)) {
        $errores['superficie'] = 'La longitud de la superficie debe estar entre 3 y 25 caracteres.';
    }

    // Verificar si ya existe una superficie con el mismo nombre
    $sqlVerificar = "SELECT COUNT(*) FROM superficies WHERE nombre = :nombre";
    $stmtVerificar = $conn->prepare($sqlVerificar);
    $stmtVerificar->bindParam(':nombre', $superficie, PDO::PARAM_STR);
    $stmtVerificar->execute();
    $existeSuperficie = $stmtVerificar->fetchColumn();

    if ($existeSuperficie) {
        $errores['superficie'] = 'Ya existe una superficie con este nombre.';
    }

    // Si no hay errores, insertar la nueva superficie y redirigir al listado
    if (empty($errores)) {
        $sqlInsertar = "INSERT INTO superficies (nombre) VALUES (:nombre)";
        $stmtInsertar = $conn->prepare($sqlInsertar);
        $stmtInsertar->bindParam(':nombre', $superficie, PDO::PARAM_STR);
        $stmtInsertar->execute();

        // Redirigir a la página del listado
        header("Location: listado.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta nueva superficie</title>
    <link rel="stylesheet" type="text/css" href="../css/estilos.css">
</head>
<body>
    <h1>Alta de un nueva superficie</h1>

    <!-- Formulario -->
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <p>
            <!-- Campo superficie -->
            <label for="superficie">Superficie:</label>
            <input type="text" id="superficie" name="superficie" value="<?php echo htmlspecialchars($superficie); ?>">
            <span style="color: red">
                <?php echo isset($errores['superficie']) ? $errores['superficie'] : ''; ?>
            </span>
        </p>
        <p>
            <!-- Botón submit -->
            <input type="submit" value="Guardar">
            <!-- Botón borrar -->
            <input type="reset" value="Borrar">
        </p>
    </form>

    <div class="contenedor">
        <div class="enlaces">
            <a href="listado.php">Volver al listado de superficies</a>
        </div>
    </div>
</body>
</html>
