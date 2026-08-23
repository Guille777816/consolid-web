-- Base de datos para CONSOLID
CREATE DATABASE IF NOT EXISTS consolid_db;
USE consolid_db;

-- Tabla de usuarios administradores
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100),
    email VARCHAR(100),
    rol VARCHAR(20) DEFAULT 'admin',
    creado TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertar admin inicial (usuario: admin, password: admin123)
INSERT INTO usuarios (usuario, password, nombre, email) 
VALUES ('admin', '$2b$10$m8D8Bqz9Pk6cgjCRTQH0wut/GTCPDs8T.D2eTFcdBUFJAGwYKDIt.', 'Administrador', 'admin@consolid.com');

-- Tabla de categorías de proyectos
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    icono VARCHAR(50)
);

-- Tabla de proyectos/obras
CREATE TABLE proyectos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    categoria_id INT,
    ubicacion VARCHAR(200),
    fecha DATE,
    estado VARCHAR(50) DEFAULT 'Completado',
    creado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
);

-- Tabla de galería (imágenes de proyectos)
CREATE TABLE galeria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proyecto_id INT,
    ruta_imagen VARCHAR(255) NOT NULL,
    es_principal BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE CASCADE
);

-- Tabla de contenidos/textos editables
CREATE TABLE contenidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seccion VARCHAR(50) NOT NULL,
    titulo VARCHAR(200),
    contenido TEXT,
    actualizado TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de gremios
CREATE TABLE gremios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_gremio VARCHAR(150) NOT NULL,
    descripcion TEXT,
    beneficios TEXT,
    contacto VARCHAR(200),
    logo VARCHAR(255),
    publicado BOOLEAN DEFAULT TRUE
);

-- Tabla de municipios
CREATE TABLE municipios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_municipio VARCHAR(150) NOT NULL,
    provincia VARCHAR(100),
    descripcion TEXT,
    contacto VARCHAR(200),
    publicado BOOLEAN DEFAULT TRUE
);

-- Tabla de blog/noticias
CREATE TABLE blog (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    contenido TEXT,
    imagen_portada VARCHAR(255),
    autor VARCHAR(100),
    fecha_publicacion DATE,
    publicado BOOLEAN DEFAULT TRUE
);

-- Tabla de testimonios
CREATE TABLE testimonios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    empresa VARCHAR(150),
    testimonio TEXT,
    foto VARCHAR(255),
    publicado BOOLEAN DEFAULT TRUE
);

-- Tabla de servicios
CREATE TABLE servicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    icono VARCHAR(100),
    imagen VARCHAR(255),
    publicado BOOLEAN DEFAULT TRUE
);

-- Insertar datos de ejemplo
INSERT INTO categorias (nombre, descripcion) VALUES 
('Viviendas', 'Casas familiares'),
('Comercios', 'Locales comerciales'),
('Country', 'Casas en countries'),
('Industriales', 'Naves industriales');

INSERT INTO contenidos (seccion, titulo, contenido) VALUES
('inicio_hero', 'Construimos tu futuro', 'Soluciones innovadoras en Steel Frame...'),
('inicio_mision', 'Nuestra Misión', 'Brindar soluciones constructivas...'),
('nosotros', 'Sobre Nosotros', 'CONSOLID es una empresa constructora...');

INSERT INTO servicios (titulo, descripcion) VALUES
('Construcción Steel Frame', 'Sistema constructivo liviano y resistente...'),
('Reformas Integrales', 'Remodelaciones y ampliaciones...'),
('Proyectos Llave en Mano', 'Desde el diseño hasta la entrega...');

INSERT INTO testimonios (nombre, empresa, testimonio) VALUES
('Juan Pérez', 'Familiar', 'Excelente trabajo, la casa quedó espectacular...'),
('María García', 'Comercial', 'Muy profesionales, cumplieron en tiempo y forma...');