<?php
require_once 'config.php';

// Obtener sección desde URL
$seccion = isset($_GET['seccion']) ? $_GET['seccion'] : 'inicio';

// Función para obtener contenido desde BD
function getContenido($conn, $seccion) {
    $stmt = $conn->prepare("SELECT titulo, contenido FROM contenidos WHERE seccion = ?");
    $stmt->bind_param("s", $seccion);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Obtener proyectos para el slider
$stmt = $conn->query("SELECT p.*, c.nombre as categoria, g.ruta_imagen 
                      FROM proyectos p 
                      LEFT JOIN categorias c ON p.categoria_id = c.id 
                      LEFT JOIN galeria g ON g.proyecto_id = p.id AND g.es_principal = TRUE
                      WHERE p.estado = 'Completado' 
                      ORDER BY p.fecha DESC 
                      LIMIT 6");
$proyectos = $stmt->fetch_all(MYSQLI_ASSOC);

// Obtener servicios
$stmt = $conn->query("SELECT * FROM servicios WHERE publicado = TRUE");
$servicios = $stmt->fetch_all(MYSQLI_ASSOC);

// Obtener testimonios
$stmt = $conn->query("SELECT * FROM testimonios WHERE publicado = TRUE");
$testimonios = $stmt->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CONSOLID | Constructora Steel Frame</title>
    <meta name="description" content="CONSOLID - Empresa constructora especializada en Steel Frame. Construimos viviendas, comercios y proyectos industriales.">
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- Header -->
    <header class="header">
        <a href="?seccion=inicio" class="logo">CON<span>SOLID</span></a>
        
        <nav class="nav-menu" id="navMenu">
            <a href="?seccion=inicio" class="<?php echo $seccion == 'inicio' ? 'active' : ''; ?>">Inicio</a>
            <a href="?seccion=empresa" class="<?php echo $seccion == 'empresa' ? 'active' : ''; ?>">La Empresa</a>
            <a href="?seccion=servicios" class="<?php echo $seccion == 'servicios' ? 'active' : ''; ?>">Servicios</a>
            <a href="?seccion=proyectos" class="<?php echo $seccion == 'proyectos' ? 'active' : ''; ?>">Proyectos</a>
            <a href="?seccion=gremios" class="<?php echo $seccion == 'gremios' ? 'active' : ''; ?>">Gremios</a>
            <a href="?seccion=municipios" class="<?php echo $seccion == 'municipios' ? 'active' : ''; ?>">Municipios</a>
            <a href="?seccion=blog" class="<?php echo $seccion == 'blog' ? 'active' : ''; ?>">Noticias</a>
            <a href="?seccion=contacto" class="<?php echo $seccion == 'contacto' ? 'active' : ''; ?>">Contacto</a>
        </nav>
        
        <button class="menu-toggle" onclick="toggleMenu()">
            <i class="fas fa-bars"></i>
        </button>
    </header>

    <!-- Contenido Principal -->
    <main>
        <?php
        switch($seccion) {
            case 'inicio':
                ?>
                <!-- HERO -->
                <section class="hero">
                    <div class="hero-content">
                        <h1>Construimos tu futuro con Steel Frame</h1>
                        <p>Soluciones constructivas innovadoras, eficientes y sustentables</p>
                        <a href="?seccion=contacto" class="hero-btn">Cotizá tu proyecto</a>
                    </div>
                </section>

                <!-- SERVICIOS -->
                <section class="section">
                    <h2 class="section-title">Nuestros Servicios</h2>
                    <div class="services-grid">
                        <?php foreach($servicios as $servicio): ?>
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas <?php echo $servicio['icono'] ?: 'fa-building'; ?>"></i>
                            </div>
                            <h3><?php echo $servicio['titulo']; ?></h3>
                            <p><?php echo substr($servicio['descripcion'], 0, 150) . '...'; ?></p>
                            <a href="?seccion=servicios" class="btn-primary" style="margin-top: 1rem;">Ver más</a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- PROYECTOS DESTACADOS -->
                <section class="section" style="background: var(--celeste-claro);">
                    <h2 class="section-title">Proyectos Destacados</h2>
                    <div class="gallery-grid">
                        <?php foreach($proyectos as $proyecto): ?>
                        <div class="gallery-item" onclick="abrirProyecto(<?php echo $proyecto['id']; ?>)">
                            <img src="imagenes/uploads/<?php echo $proyecto['ruta_imagen']; ?>" alt="<?php echo $proyecto['titulo']; ?>">
                            <div class="gallery-overlay">
                                <h3><?php echo $proyecto['titulo']; ?></h3>
                                <p><?php echo $proyecto['categoria']; ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div style="text-align: center; margin-top: 2rem;">
                        <a href="?seccion=proyectos" class="btn-primary">Ver todos los proyectos</a>
                    </div>
                </section>

                <!-- TESTIMONIOS -->
                <section class="section">
                    <h2 class="section-title">Testimonios de Clientes</h2>
                    <div class="services-grid">
                        <?php foreach($testimonios as $testimonio): ?>
                        <div class="service-card">
                            <i class="fas fa-quote-left" style="color: var(--celeste); font-size: 2rem;"></i>
                            <p>"<?php echo $testimonio['testimonio']; ?>"</p>
                            <p style="font-weight: bold; margin-top: 1rem;">- <?php echo $testimonio['nombre']; ?></p>
                            <p style="color: var(--gris-oscuro); font-size: 0.9rem;"><?php echo $testimonio['empresa']; ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- CTA -->
                <section class="section" style="background: linear-gradient(135deg, var(--azul-oscuro), var(--azul)); text-align: center; color: var(--blanco);">
                    <h2 style="margin-bottom: 1rem;">¿Listo para construir?</h2>
                    <p style="margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">
                        Contactanos hoy y recibí una cotización sin compromiso para tu próximo proyecto.
                    </p>
                    <a href="?seccion=contacto" class="hero-btn">Solicitar Cotización</a>
                </section>
                <?php
                break;

            case 'empresa':
                $contenido = getContenido($conn, 'nosotros');
                ?>
                <section class="section" style="padding-top: 8rem;">
                    <h2 class="section-title">La Empresa</h2>
                    <div style="max-width: 800px; margin: 0 auto;">
                        <p><?php echo $contenido['contenido']; ?></p>
                        
                        <h3 style="color: var(--azul-oscuro); margin-top: 2rem;">Nuestra Misión</h3>
                        <p><?php echo getContenido($conn, 'inicio_mision')['contenido']; ?></p>
                        
                        <h3 style="color: var(--azul-oscuro); margin-top: 2rem;">Nuestra Visión</h3>
                        <p><?php echo getContenido($conn, 'inicio_vision')['contenido']; ?></p>
                        
                        <h3 style="color: var(--azul-oscuro); margin-top: 2rem;">Nuestros Valores</h3>
                        <ul style="list-style: none; padding: 0;">
                            <li style="margin: 0.5rem 0;"><i class="fas fa-check" style="color: var(--celeste);"></i> Innovación</li>
                            <li style="margin: 0.5rem 0;"><i class="fas fa-check" style="color: var(--celeste);"></i> Calidad</li>
                            <li style="margin: 0.5rem 0;"><i class="fas fa-check" style="color: var(--celeste);"></i> Compromiso</li>
                            <li style="margin: 0.5rem 0;"><i class="fas fa-check" style="color: var(--celeste);"></i> Sustentabilidad</li>
                        </ul>
                    </div>
                </section>
                <?php
                break;

            case 'servicios':
                ?>
                <section class="section" style="padding-top: 8rem;">
                    <h2 class="section-title">Nuestros Servicios</h2>
                    <div class="services-grid">
                        <?php foreach($servicios as $servicio): ?>
                        <div class="service-card" style="text-align: left;">
                            <h3><?php echo $servicio['titulo']; ?></h3>
                            <p><?php echo $servicio['descripcion']; ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Ventajas Steel Frame -->
                    <h2 class="section-title" style="margin-top: 4rem;">Ventajas del Sistema Steel Frame</h2>
                    <div class="services-grid">
                        <div class="service-card">
                            <h3>⚡ Rápido</h3>
                            <p>Construcción hasta 3 veces más rápida que el sistema tradicional</p>
                        </div>
                        <div class="service-card">
                            <h3>🏋️ Liviano</h3>
                            <p>Estructura liviana que no requiere cimentación pesada</p>
                        </div>
                        <div class="service-card">
                            <h3>💰 Económico</h3>
                            <p>Menor costo en materiales y mano de obra</p>
                        </div>
                        <div class="service-card">
                            <h3>🛡️ Resistente</h3>
                            <p>Resistente a sismos, vientos y humedad</p>
                        </div>
                        <div class="service-card">
                            <h3>♻️ Sustentable</h3>
                            <p>Materiales reciclables y menor huella de carbono</p>
                        </div>
                        <div class="service-card">
                            <h3>🎨 Personalizable</h3>
                            <p>Amplia variedad de terminaciones y diseños</p>
                        </div>
                    </div>
                </section>
                <?php
                break;

            case 'proyectos':
                ?>
                <section class="section" style="padding-top: 8rem;">
                    <h2 class="section-title">Nuestros Proyectos</h2>
                    
                    <!-- Filtros por categoría -->
                    <div style="text-align: center; margin-bottom: 2rem;">
                        <button class="btn-primary" onclick="filtrarProyectos('todos')">Todos</button>
                        <?php
                        $categorias = $conn->query("SELECT * FROM categorias")->fetch_all(MYSQLI_ASSOC);
                        foreach($categorias as $categoria): ?>
                        <button class="btn-primary" style="margin: 0 0.5rem;" onclick="filtrarProyectos(<?php echo $categoria['id']; ?>)">
                            <?php echo $categoria['nombre']; ?>
                        </button>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Galería de proyectos -->
                    <div class="gallery-grid" id="proyectosGrid">
                        <?php
                        $todos_proyectos = $conn->query("SELECT p.*, c.nombre as categoria, g.ruta_imagen 
                                                       FROM proyectos p 
                                                       LEFT JOIN categorias c ON p.categoria_id = c.id 
                                                       LEFT JOIN galeria g ON g.proyecto_id = p.id AND g.es_principal = TRUE
                                                       ORDER BY p.fecha DESC")->fetch_all(MYSQLI_ASSOC);
                        
                        foreach($todos_proyectos as $proyecto): ?>
                        <div class="gallery-item" data-categoria="<?php echo $proyecto['categoria_id']; ?>" onclick="abrirProyecto(<?php echo $proyecto['id']; ?>)">
                            <img src="imagenes/uploads/<?php echo $proyecto['ruta_imagen']; ?>" alt="<?php echo $proyecto['titulo']; ?>">
                            <div class="gallery-overlay">
                                <h3><?php echo $proyecto['titulo']; ?></h3>
                                <p><?php echo $proyecto['categoria']; ?> - <?php echo $proyecto['ubicacion']; ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </section>
                <?php
                break;

            case 'gremios':
                ?>
                <section class="section" style="padding-top: 8rem;">
                    <h2 class="section-title">Convenios para Gremios</h2>
                    
                    <div style="max-width: 800px; margin: 0 auto; margin-bottom: 3rem;">
                        <h3 style="color: var(--azul-oscuro);">Beneficios para Profesionales</h3>
                        <ul style="list-style: none; padding: 0;">
                            <li style="margin: 1rem 0; padding: 1rem; background: var(--celeste-claro); border-radius: 8px;">
                                <i class="fas fa-check-circle" style="color: var(--azul);"></i> Descuentos especiales para afiliados
                            </li>
                            <li style="margin: 1rem 0; padding: 1rem; background: var(--celeste-claro); border-radius: 8px;">
                                <i class="fas fa-check-circle" style="color: var(--azul);"></i> Acceso a documentación técnica exclusiva
                            </li>
                            <li style="margin: 1rem 0; padding: 1rem; background: var(--celeste-claro); border-radius: 8px;">
                                <i class="fas fa-check-circle" style="color: var(--azul);"></i> Capacitaciones y cursos certificados
                            </li>
                            <li style="margin: 1rem 0; padding: 1rem; background: var(--celeste-claro); border-radius: 8px;">
                                <i class="fas fa-check-circle" style="color: var(--azul);"></i> Prioridad en la asignación de obras
                            </li>
                        </ul>
                    </div>
                    
                    <h3 style="text-align: center; color: var(--azul-oscuro); margin-bottom: 2rem;">Gremios Aliados</h3>
                    <div class="services-grid">
                        <?php
                        $gremios = $conn->query("SELECT * FROM gremios WHERE publicado = TRUE")->fetch_all(MYSQLI_ASSOC);
                        foreach($gremios as $gremio): ?>
                        <div class="service-card">
                            <h3><?php echo $gremio['nombre_gremio']; ?></h3>
                            <p><?php echo $gremio['descripcion']; ?></p>
                            <p style="margin-top: 1rem;"><strong>Beneficios:</strong> <?php echo $gremio['beneficios']; ?></p>
                            <a href="?seccion=contacto" class="btn-primary" style="margin-top: 1rem;">Quiero ser aliado</a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </section>
                <?php
                break;

            case 'municipios':
                ?>
                <section class="section" style="padding-top: 8rem;">
                    <h2 class="section-title">Obras para Municipios</h2>
                    
                    <div style="max-width: 800px; margin: 0 auto; margin-bottom: 3rem;">
                        <h3 style="color: var(--azul-oscuro);">Soluciones para el Sector Público</h3>
                        <p style="margin-bottom: 2rem;">
                            En CONSOLID trabajamos junto a los municipios para desarrollar infraestructura pública de calidad.
                            Nuestro sistema Steel Frame es ideal para obras municipales por su rapidez y costo-eficiencia.
                        </p>
                        
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                            <div style="text-align: center; padding: 1rem; background: var(--celeste-claro); border-radius: 8px;">
                                <i class="fas fa-school" style="font-size: 2rem; color: var(--azul);"></i>
                                <h4>Escuelas</h4>
                            </div>
                            <div style="text-align: center; padding: 1rem; background: var(--celeste-claro); border-radius: 8px;">
                                <i class="fas fa-hospital" style="font-size: 2rem; color: var(--azul);"></i>
                                <h4>Centros de Salud</h4>
                            </div>
                            <div style="text-align: center; padding: 1rem; background: var(--celeste-claro); border-radius: 8px;">
                                <i class="fas fa-hotel" style="font-size: 2rem; color: var(--azul);"></i>
                                <h4>Viviendas Sociales</h4>
                            </div>
                        </div>
                    </div>
                    
                    <h3 style="text-align: center; color: var(--azul-oscuro); margin-bottom: 2rem;">Municipios con Convenio</h3>
                    <div class="services-grid">
                        <?php
                        $municipios = $conn->query("SELECT * FROM municipios WHERE publicado = TRUE")->fetch_all(MYSQLI_ASSOC);
                        foreach($municipios as $municipio): ?>
                        <div class="service-card">
                            <h3><?php echo $municipio['nombre_municipio']; ?></h3>
                            <p style="color: var(--gris-oscuro);"><?php echo $municipio['provincia']; ?></p>
                            <p><?php echo $municipio['descripcion']; ?></p>
                            <p style="margin-top: 1rem;"><strong>Contacto:</strong> <?php echo $municipio['contacto']; ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div style="text-align: center; margin-top: 3rem;">
                        <h3>¿Sos de un municipio y querés trabajar con nosotros?</h3>
                        <a href="?seccion=contacto" class="btn-primary" style="margin-top: 1rem;">Contactar al área de Licitaciones</a>
                    </div>
                </section>
                <?php
                break;

            case 'blog':
                ?>
                <section class="section" style="padding-top: 8rem;">
                    <h2 class="section-title">Noticias y Novedades</h2>
                    
                    <div class="services-grid">
                        <?php
                        $noticias = $conn->query("SELECT * FROM blog WHERE publicado = TRUE ORDER BY fecha_publicacion DESC")->fetch_all(MYSQLI_ASSOC);
                        foreach($noticias as $noticia): ?>
                        <article class="service-card" style="text-align: left;">
                            <h3><?php echo $noticia['titulo']; ?></h3>
                            <p style="color: var(--gris-oscuro); font-size: 0.9rem;">
                                <i class="fas fa-calendar"></i> <?php echo date('d/m/Y', strtotime($noticia['fecha_publicacion'])); ?>
                                <i class="fas fa-user" style="margin-left: 1rem;"></i> <?php echo $noticia['autor']; ?>
                            </p>
                            <p><?php echo substr($noticia['contenido'], 0, 200) . '...'; ?></p>
                            <button onclick="verNoticia(<?php echo $noticia['id']; ?>)" class="btn-primary" style="margin-top: 1rem;">Leer más</button>
                        </article>
                        <?php endforeach; ?>
                    </div>
                </section>
                <?php
                break;

            case 'contacto':
                ?>
                <section class="section" style="padding-top: 8rem;">
                    <h2 class="section-title">Contactanos</h2>
                    
                    <div style="max-width: 1000px; margin: 0 auto; display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                        <div>
                            <h3 style="color: var(--azul-oscuro); margin-bottom: 1rem;">Datos de Contacto</h3>
                            <ul style="list-style: none; padding: 0;">
                                <li style="margin: 1rem 0;"><i class="fas fa-phone" style="color: var(--celeste);"></i> +54 11 1234-5678</li>
                                <li style="margin: 1rem 0;"><i class="fas fa-envelope" style="color: var(--celeste);"></i> info@consolid.com</li>
                                <li style="margin: 1rem 0;"><i class="fas fa-map-marker-alt" style="color: var(--celeste);"></i> Av. Argentina 1234, Buenos Aires</li>
                                <li style="margin: 1rem 0;"><i class="fas fa-clock" style="color: var(--celeste);"></i> Lun a Vie: 9:00 - 18:00</li>
                            </ul>
                            
                            <div style="margin-top: 2rem;">
                                <h3 style="color: var(--azul-oscuro); margin-bottom: 1rem;">Seguinos</h3>
                                <div style="display: flex; gap: 1rem;">
                                    <a href="#" style="color: var(--azul); font-size: 1.5rem;"><i class="fab fa-facebook"></i></a>
                                    <a href="#" style="color: var(--azul); font-size: 1.5rem;"><i class="fab fa-instagram"></i></a>
                                    <a href="#" style="color: var(--azul); font-size: 1.5rem;"><i class="fab fa-linkedin"></i></a>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h3 style="color: var(--azul-oscuro); margin-bottom: 1rem;">Enviar Mensaje</h3>
                            <form onsubmit="enviarMensaje(event)">
                                <div class="form-group">
                                    <label for="nombre">Nombre</label>
                                    <input type="text" id="nombre" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="telefono">Teléfono</label>
                                    <input type="tel" id="telefono" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="mensaje">Mensaje</label>
                                    <textarea id="mensaje" class="form-control" rows="5" required></textarea>
                                </div>
                                <button type="submit" class="btn-primary">Enviar Mensaje</button>
                            </form>
                        </div>
                    </div>
                </section>
                <?php
                break;

            default:
                ?>
                <section class="section" style="padding-top: 8rem; text-align: center;">
                    <h2>Página no encontrada</h2>
                    <p>La página que buscas no existe o fue movida.</p>
                    <a href="?seccion=inicio" class="btn-primary">Volver al inicio</a>
                </section>
                <?php
        }
        ?>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-grid">
            <div>
                <h3>CONSOLID</h3>
                <p>Empresa constructora especializada en Steel Frame. Construimos calidad y confianza desde 2010.</p>
            </div>
            <div>
                <h3>Enlaces Rápidos</h3>
                <ul style="list-style: none; padding: 0;">
                    <li><a href="?seccion=inicio">Inicio</a></li>
                    <li><a href="?seccion=empresa">La Empresa</a></li>
                    <li><a href="?seccion=servicios">Servicios</a></li>
                    <li><a href="?seccion=proyectos">Proyectos</a></li>
                </ul>
            </div>
            <div>
                <h3>Para Profesionales</h3>
                <ul style="list-style: none; padding: 0;">
                    <li><a href="?seccion=gremios">Gremios</a></li>
                    <li><a href="?seccion=municipios">Municipios</a></li>
                    <li><a href="?seccion=blog">Noticias</a></li>
                </ul>
            </div>
            <div>
                <h3>Contacto</h3>
                <p>info@consolid.com</p>
                <p>+54 11 1234-5678</p>
                <p>Av. Argentina 1234, Buenos Aires</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 CONSOLID. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Botón WhatsApp -->
    <a href="https://wa.me/541112345678" class="whatsapp-btn" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Scripts -->
    <script src="js/script.js"></script>
</body>
</html>