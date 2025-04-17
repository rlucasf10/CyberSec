/**
 * Script para las páginas de login y registro
 * Implementa validación de formularios, visualización de contraseña y conexión AJAX
 */
document.addEventListener('DOMContentLoaded', function() {
    // Configuración de partículas para el fondo animado
    if (typeof particlesJS !== 'undefined') {
        particlesJS('particles-js', {
            "particles": {
                "number": {
                    "value": 80,
                    "density": { "enable": true, "value_area": 800 }
                },
                "color": { "value": "#4299e1" },
                "shape": {
                    "type": "circle",
                    "stroke": { "width": 0, "color": "#000000" },
                    "polygon": { "nb_sides": 5 }
                },
                "opacity": {
                    "value": 0.5,
                    "random": true,
                    "anim": { "enable": true, "speed": 1, "opacity_min": 0.1, "sync": false }
                },
                "size": {
                    "value": 3,
                    "random": true,
                    "anim": { "enable": true, "speed": 2, "size_min": 0.1, "sync": false }
                },
                "line_linked": {
                    "enable": true,
                    "distance": 150,
                    "color": "#4299e1",
                    "opacity": 0.4,
                    "width": 1
                },
                "move": {
                    "enable": true,
                    "speed": 1,
                    "direction": "none",
                    "random": true,
                    "straight": false,
                    "out_mode": "out",
                    "bounce": false,
                    "attract": { "enable": false, "rotateX": 600, "rotateY": 1200 }
                }
            },
            "interactivity": {
                "detect_on": "canvas",
                "events": {
                    "onhover": { "enable": true, "mode": "grab" },
                    "onclick": { "enable": true, "mode": "push" },
                    "resize": true
                },
                "modes": {
                    "grab": { "distance": 140, "line_linked": { "opacity": 1 } },
                    "bubble": { "distance": 400, "size": 40, "duration": 2, "opacity": 8, "speed": 3 },
                    "repulse": { "distance": 200, "duration": 0.4 },
                    "push": { "particles_nb": 4 },
                    "remove": { "particles_nb": 2 }
                }
            },
            "retina_detect": true
        });
    }

    // Gestión de los botones para mostrar/ocultar contraseña
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // Función para validar formularios
    function validateForm(form) {
        let isValid = true;
        
        // Validar campos requeridos
        form.querySelectorAll('[required]').forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        // Validar email
        const emailField = form.querySelector('[type="email"]');
        if (emailField && emailField.value.trim()) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailField.value.trim())) {
                emailField.classList.add('is-invalid');
                isValid = false;
            }
        }

        // Validar contraseñas coincidentes (para registro)
        const passwordField = form.querySelector('#password');
        const confirmField = form.querySelector('#confirmar_password');
        
        if (passwordField && confirmField && 
            passwordField.value && confirmField.value) {
            if (passwordField.value !== confirmField.value) {
                confirmField.classList.add('is-invalid');
                isValid = false;
            } else {
                confirmField.classList.remove('is-invalid');
            }
        }

        return isValid;
    }

        // Manejo de cambio de tipo de usuario en registro
        const tipoUsuarioSelect = document.getElementById('tipo_usuario');
        if (tipoUsuarioSelect) {
            tipoUsuarioSelect.addEventListener('change', function() {
                // Mostrar/ocultar campos específicos según el tipo de usuario
                const tipoUsuario = this.value;
                const clienteFields = document.querySelectorAll('.cliente-field');
                const empleadoFields = document.querySelectorAll('.empleado-field');
                
                if (tipoUsuario === 'cliente') {
                    clienteFields.forEach(field => field.classList.remove('d-none'));
                    empleadoFields.forEach(field => field.classList.add('d-none'));
                } else if (tipoUsuario === 'empleado') {
                    clienteFields.forEach(field => field.classList.add('d-none'));
                    empleadoFields.forEach(field => field.classList.remove('d-none'));
                }
            });
        }
    
        // Medidor de fortaleza de contraseña
        const passwordField = document.getElementById('password');
        if (passwordField) {
            passwordField.addEventListener('input', function() {
                const password = this.value;
                const strengthContainer = document.querySelector('.password-strength');
                const progressBar = document.querySelector('.password-strength .progress-bar');
                const strengthText = document.querySelector('.strength-text');
                
                // Mostrar el indicador cuando se empiece a escribir
                if (password.length > 0) {
                    strengthContainer.classList.remove('d-none');
                } else {
                    strengthContainer.classList.remove('strength-weak', 'strength-medium', 'strength-strong');
                    strengthContainer.classList.add('d-none');
                    progressBar.style.width = '0%';
                    strengthText.textContent = 'Sin contraseña';
                    return;
                }
                
                // Evaluar fortaleza (simple)
                let strength = 0;
                
                // Longitud mínima
                if (password.length >= 8) strength += 1;
                if (password.length >= 12) strength += 1;
                
                // Complejidad
                if (/[A-Z]/.test(password)) strength += 1; // Mayúsculas
                if (/[a-z]/.test(password)) strength += 1; // Minúsculas
                if (/[0-9]/.test(password)) strength += 1; // Números
                if (/[^A-Za-z0-9]/.test(password)) strength += 1; // Caracteres especiales
                
                // Actualizar UI según fortaleza
                strengthContainer.classList.remove('strength-weak', 'strength-medium', 'strength-strong');
                
                if (strength < 3) {
                    strengthContainer.classList.add('strength-weak');
                    progressBar.style.width = '33%';
                    strengthText.textContent = 'Débil';
                } else if (strength < 5) {
                    strengthContainer.classList.add('strength-medium');
                    progressBar.style.width = '66%';
                    strengthText.textContent = 'Media';
                } else {
                    strengthContainer.classList.add('strength-strong');
                    progressBar.style.width = '100%';
                    strengthText.textContent = 'Fuerte';
                }
            });
        }
    
        // Gestión del formulario de login
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (!validateForm(this)) {
                    return;
                }
                
                const loginMessage = document.getElementById('loginMessage');
                loginMessage.classList.add('d-none');
                
                // Recoger datos del formulario
                const formData = new FormData(this);
                
                // Mostrar animación de carga
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Procesando...';
                
                // Enviar petición AJAX
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    // Restablecer botón
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                    
                    // Mostrar mensaje
                    loginMessage.classList.remove('d-none', 'alert-success', 'alert-danger');
                    loginMessage.classList.add(data.status === 'success' ? 'alert-success' : 'alert-danger');
                    loginMessage.textContent = data.message;
                    
                    // Redireccionar si es exitoso
                    if (data.status === 'success') {
                        setTimeout(() => {
                            window.location.href = data.redirect || '/';
                        }, 1500);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                    
                    loginMessage.classList.remove('d-none', 'alert-success');
                    loginMessage.classList.add('alert-danger');
                    loginMessage.textContent = 'Error de conexión. Inténtelo de nuevo más tarde.';
                });
            });
        }
    
        // Gestión del formulario de registro
        const registroForm = document.getElementById('registroForm');
        if (registroForm) {
            registroForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (!validateForm(this)) {
                    return;
                }
                
                const registroMessage = document.getElementById('registroMessage');
                registroMessage.classList.add('d-none');
                
                // Recoger datos del formulario
                const formData = new FormData(this);
                
                // Mostrar animación de carga
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Procesando...';
                
                // Enviar petición AJAX
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    // Restablecer botón
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                    
                    // Mostrar mensaje
                    registroMessage.classList.remove('d-none', 'alert-success', 'alert-danger');
                    registroMessage.classList.add(data.status === 'success' ? 'alert-success' : 'alert-danger');
                    registroMessage.textContent = data.message;
                    
                    // Si es exitoso, limpiar formulario o redireccionar
                    if (data.status === 'success') {
                        this.reset();
                        setTimeout(() => {
                            window.location.href = data.redirect || '/views/login.php';
                        }, 2000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                    
                    registroMessage.classList.remove('d-none', 'alert-success');
                    registroMessage.classList.add('alert-danger');
                    registroMessage.textContent = 'Error de conexión. Inténtelo de nuevo más tarde.';
                });
            });
        }
    });