// Menú móvil
function toggleMenu() {
    const nav = document.getElementById('navMenu');
    nav.classList.toggle('active');
}

// Filtrar proyectos por categoría
function filtrarProyectos(categoriaId) {
    const proyectos = document.querySelectorAll('.gallery-item[data-categoria]');
    
    proyectos.forEach(proyecto => {
        if (categoriaId === 'todos' || proyecto.dataset.categoria == categoriaId) {
            proyecto.style.display = 'block';
        } else {
            proyecto.style.display = 'none';
        }
    });
}

// Abrir detalle de proyecto (modal)
function abrirProyecto(id) {
    // Puedes implementar un modal o redirigir a una página de detalle
    alert('Abriendo proyecto #' + id);
}

// Ver noticia completa
function verNoticia(id) {
    alert('Abriendo noticia #' + id);
}

// Enviar formulario de contacto
function enviarMensaje(event) {
    event.preventDefault();
    
    const nombre = document.getElementById('nombre').value;
    const email = document.getElementById('email').value;
    const mensaje = document.getElementById('mensaje').value;
    
    // Aquí puedes hacer una petición AJAX al servidor
    // Por ahora, mostramos una confirmación
    alert(`Mensaje enviado correctamente.\n\nNombre: ${nombre}\nEmail: ${email}\nMensaje: ${mensaje}`);
    
    // Limpiar formulario
    event.target.reset();
}

// Modal de proyecto (opcional)
function mostrarModal(titulo, descripcion, imagenes) {
    // Crear modal dinámicamente
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.innerHTML = `
        <div class="modal-content">
            <span class="close" onclick="this.parentElement.parentElement.remove()">&times;</span>
            <h2>${titulo}</h2>
            <p>${descripcion}</p>
            <div class="modal-gallery">
                ${imagenes.map(img => `<img src="${img}" alt="${titulo}">`).join('')}
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
}

// Scroll suave
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

// Detectar scroll para animaciones
window.addEventListener('scroll', () => {
    const header = document.querySelector('.header');
    if (window.scrollY > 50) {
        header.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
    } else {
        header.style.boxShadow = 'none';
    }
});