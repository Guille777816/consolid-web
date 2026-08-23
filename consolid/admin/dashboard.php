<?php
session_start();
require_once '../config.php';

// Verificar login
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$mensaje = '';

// Procesar acciones
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Subir imagen
    if (isset($_POST['subir_imagen'])) {
        $proyecto_id = $_POST['proyecto_id'];
        
        $nombre_archivo = $_FILES['imagen']['name'];
        $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
        $permitidos = ['jpg', 'jpeg', 'png', 'webp'];
        
        if (in_array(strtolower($extension), $permitidos)) {
            $nuevo_nombre = uniqid() . '.' . $extension;
            $ruta_destino = '../imagenes/uploads/' . $nuevo_nombre;
            
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
                $es_principal = isset($_POST['es_principal']) ? 1 : 0;
                
                $stmt = $conn->prepare("INSERT INTO galeria (proyecto_id, ruta_imagen, es_principal) VALUES (?, ?, ?)");
                $stmt->bind_param("isi", $proyecto_id, $nuevo_nombre, $es_principal);
                
                if ($stmt->execute()) {
                    $mensaje = '<div class="alert alert-success">✅ Imagen subida correctamente</div>';
                }
            }
        }
    }
    
    // Crear categoría
    if (isset($_POST['crear_categoria'])) {
        $nombre = $_POST['nombre_categoria'];
        $descripcion = $_POST['descripcion_categoria'];
        
        $stmt = $conn->prepare("INSERT INTO categorias (nombre, descripcion) VALUES (?, ?)");
        $stmt->bind_param("ss", $nombre, $descripcion);
        
        if ($stmt->execute()) {
            $mensaje = '<div class="alert alert-success">✅ Categoría creada correctamente</div>';
        }
    }
    
    // Crear proyecto
    if (isset($_POST['crear_proyecto'])) {
        $titulo = $_POST['titulo_proyecto'];
        $descripcion = $_POST['descripcion_proyecto'];
        $categoria_id = $_POST['categoria_id'];
        $ubicacion = $_POST['ubicacion'];
        
        $stmt = $conn->prepare("INSERT INTO proyectos (titulo, descripcion, categoria_id, ubicacion) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $titulo, $descripcion, $categoria_id, $ubicacion);
        
        if ($stmt->execute()) {
            $mensaje = '<div class="alert alert-success">✅ Proyecto creado correctamente</div>';
        }
    }
}

// Obtener datos
$proyectos = $conn->query("SELECT p.*, c.nombre as categoria FROM proyectos p LEFT JOIN categorias c ON p.categoria_id = c.id ORDER BY p.fecha DESC")->fetch_all(MYSQLI_ASSOC);
$categorias = $conn->query("SELECT * FROM categorias ORDER BY nombre")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - CONSOLID</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Nav Admin -->
    <nav class="admin-nav">
        <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="proyectos.php"><i class="fas fa-building"></i> Proyectos</a>
        <a href="categorias.php"><i class="fas fa-tags"></i> Categorías</a>
        <a href="textos.php"><i class="fas fa-file-alt"></i> Contenidos</a>
        <a href="gremios.php"><i class="fas fa-handshake"></i> Gremios</a>
        <a href="municipios.php"><i class="fas fa-city"></i> Municipios</a>
        <a href="blog.php"><i class="fas fa-newspaper"></i> Blog</a>
        <a href="logout.php" style="margin-left: auto;"><i class="fas fa-sign-out-alt"></i> Salir</a>
    </nav>
    
    <div class="admin-content">
        <h1>Panel de Administración</h1>
        <p>Bienvenido, <?php echo $_SESSION['admin_nombre']; ?></p>
        
        <?php echo $mensaje; ?>
        
        <!-- Sección: Subir Imagen -->
        <div style="background: white; padding: 2rem; border-radius: 10px; margin-top: 2rem;">
            <h2>📤 Subir Nueva Imagen</h2>
            <form method="POST" enctype="multipart/form-data" style="display: grid; gap: 1rem; margin-top: 1rem;">
                <div class="form-group">
                    <label>Seleccionar Imagen</label>
                    <input type="file" name="imagen" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Proyecto</label>
                    <select name="proyecto_id" class="form-control" required>
                        <?php foreach($proyectos as $proyecto): ?>
                        <option value="<?php echo $proyecto['id']; ?>"><?php echo $proyecto['titulo']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="es_principal" value="1"> Imagen principal
                    </label>
                </div>
                <button type="submit" name="subir_imagen" class="btn-primary">Subir Imagen</button>
            </form>
        </div>
        
        <!-- Sección: Crear Proyecto -->
        <div style="background: white; padding: 2rem; border-radius: 10px; margin-top: 2rem;">
            <h2>🏗️ Crear Nuevo Proyecto</h2>
            <form method="POST" style="display: grid; gap: 1rem; margin-top: 1rem;">
                <div class="form-group">
                    <label>Título</label>
                    <input type="text" name="titulo_proyecto" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion_proyecto" class="form-control" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label>Categoría</label>
                    <select name="categoria_id" class="form-control" required>
                        <?php foreach($categorias as $categoria): ?>
                        <option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Ubicación</label>
                    <input type="text" name="ubicacion" class="form-control" required>
                </div>
                <button type="submit" name="crear_proyecto" class="btn-primary">Crear Proyecto</button>
            </form>
        </div>
        
        <!-- Sección: Crear Categoría -->
        <div style="background: white; padding: 2rem; border-radius: 10px; margin-top: 2rem;">
            <h2>🏷️ Crear Nueva Categoría</h2>
            <form method="POST" style="display: grid; gap: 1rem; margin-top: 1rem;">
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre_categoria" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion_categoria" class="form-control" rows="2"></textarea>
                </div>
                <button type="submit" name="crear_categoria" class="btn-primary">Crear Categoría</button>
            </form>
        </div>
        
        <!-- Tabla de Proyectos -->
        <div style="background: white; padding: 2rem; border-radius: 10px; margin-top: 2rem;">
            <h2>📋 Proyectos Existentes</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Categoría</th>
                        <th>Ubicación</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($proyectos as $proyecto): ?>
                    <tr>
                        <td><?php echo $proyecto['id']; ?></td>
                        <td><?php echo $proyecto['titulo']; ?></td>
                        <td><?php echo $proyecto['categoria']; ?></td>
                        <td><?php echo $proyecto['ubicacion']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($proyecto['fecha'])); ?></td>
                        <td>
                            <a href="editar_proyecto.php?id=<?php echo $proyecto['id']; ?>" style="color: var(--azul);"><i class="fas fa-edit"></i></a>
                            <a href="eliminar_proyecto.php?id=<?php echo $proyecto['id']; ?>" style="color: red; margin-left: 1rem;" onclick="return confirm('¿Eliminar este proyecto?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>