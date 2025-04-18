/**
 * Script para las páginas de login y registro
 * Implementa validación de formularios, visualización de contraseña y conexión AJAX
 */

// Seguridad: Evitar que la página se cargue en un iframe
if (window.self !== window.top) {
  window.top.location.href = window.self.location.href
}

document.addEventListener('DOMContentLoaded', function () {
  // Configuración de partículas para el fondo animado
  if (typeof particlesJS !== 'undefined') {
    particlesJS('particles-js', {
      particles: {
        number: {
          value: 80,
          density: { enable: true, value_area: 800 }
        },
        color: { value: '#4299e1' },
        shape: {
          type: 'circle',
          stroke: { width: 0, color: '#000000' },
          polygon: { nb_sides: 5 }
        },
        opacity: {
          value: 0.5,
          random: true,
          anim: { enable: true, speed: 1, opacity_min: 0.1, sync: false }
        },
        size: {
          value: 3,
          random: true,
          anim: { enable: true, speed: 2, size_min: 0.1, sync: false }
        },
        line_linked: {
          enable: true,
          distance: 150,
          color: '#4299e1',
          opacity: 0.4,
          width: 1
        },
        move: {
          enable: true,
          speed: 1,
          direction: 'none',
          random: true,
          straight: false,
          out_mode: 'out',
          bounce: false,
          attract: { enable: false, rotateX: 600, rotateY: 1200 }
        }
      },
      interactivity: {
        detect_on: 'canvas',
        events: {
          onhover: { enable: true, mode: 'grab' },
          onclick: { enable: true, mode: 'push' },
          resize: true
        },
        modes: {
          grab: { distance: 140, line_linked: { opacity: 1 } },
          bubble: {
            distance: 400,
            size: 40,
            duration: 2,
            opacity: 8,
            speed: 3
          },
          repulse: { distance: 200, duration: 0.4 },
          push: { particles_nb: 4 },
          remove: { particles_nb: 2 }
        }
      },
      retina_detect: true
    })
  }

  // Gestión de los botones para mostrar/ocultar contraseña
  document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function () {
      const input = this.previousElementSibling
      const icon = this.querySelector('i')

      if (input.type === 'password') {
        input.type = 'text'
        icon.classList.remove('fa-eye')
        icon.classList.add('fa-eye-slash')
      } else {
        input.type = 'password'
        icon.classList.remove('fa-eye-slash')
        icon.classList.add('fa-eye')
      }
    })
  })

  // Manejo de cambio de tipo de usuario en registro
  const tipoUsuarioSelect = document.getElementById('tipo_usuario')
  if (tipoUsuarioSelect) {
    tipoUsuarioSelect.addEventListener('change', function () {
      // Mostrar/ocultar campos específicos según el tipo de usuario
      const tipoUsuario = this.value
      const clienteFields = document.querySelectorAll('.cliente-field')
      const empleadoFields = document.querySelectorAll('.empleado-field')

      if (tipoUsuario === 'cliente') {
        clienteFields.forEach(field => field.classList.remove('d-none'))
        empleadoFields.forEach(field => field.classList.add('d-none'))
      } else if (tipoUsuario === 'empleado') {
        clienteFields.forEach(field => field.classList.add('d-none'))
        empleadoFields.forEach(field => field.classList.remove('d-none'))
      }
    })
  }

  // Validación de contraseñas - Solo en página de registro
  const registroForm = document.getElementById('registroForm')
  if (registroForm) {
    // Solo ejecutar en la página de registro
    const passwordInput = document.getElementById('password')
    const confirmPasswordInput = document.getElementById('confirmar_password')
    const progressBar = document.querySelector(
      '.password-strength .progress-bar'
    )
    const passwordFeedback = document.querySelector('.password-feedback')

    // Requisitos de contraseña
    const reqLength = document.querySelector('.req-length i')
    const reqUpper = document.querySelector('.req-upper i')
    const reqLower = document.querySelector('.req-lower i')
    const reqNumber = document.querySelector('.req-number i')
    const reqSpecial = document.querySelector('.req-special i')

    // Función para validar complejidad de contraseña
    function validatePasswordStrength (password) {
      let strength = 0
      let feedback = []

      // Validar longitud
      if (password.length >= 10) {
        strength += 20
        reqLength.classList.remove('fa-circle')
        reqLength.classList.add('fa-check-circle', 'text-success')
      } else {
        reqLength.classList.remove('fa-check-circle', 'text-success')
        reqLength.classList.add('fa-circle')
        feedback.push('Mínimo 10 caracteres')
      }

      // Validar mayúsculas
      if (/[A-Z]/.test(password)) {
        strength += 20
        reqUpper.classList.remove('fa-circle')
        reqUpper.classList.add('fa-check-circle', 'text-success')
      } else {
        reqUpper.classList.remove('fa-check-circle', 'text-success')
        reqUpper.classList.add('fa-circle')
        feedback.push('Al menos una mayúscula')
      }

      // Validar minúsculas
      if (/[a-z]/.test(password)) {
        strength += 20
        reqLower.classList.remove('fa-circle')
        reqLower.classList.add('fa-check-circle', 'text-success')
      } else {
        reqLower.classList.remove('fa-check-circle', 'text-success')
        reqLower.classList.add('fa-circle')
        feedback.push('Al menos una minúscula')
      }

      // Validar números
      if (/[0-9]/.test(password)) {
        strength += 20
        reqNumber.classList.remove('fa-circle')
        reqNumber.classList.add('fa-check-circle', 'text-success')
      } else {
        reqNumber.classList.remove('fa-check-circle', 'text-success')
        reqNumber.classList.add('fa-circle')
        feedback.push('Al menos un número')
      }

      // Validar caracteres especiales
      if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
        strength += 20
        reqSpecial.classList.remove('fa-circle')
        reqSpecial.classList.add('fa-check-circle', 'text-success')
      } else {
        reqSpecial.classList.remove('fa-check-circle', 'text-success')
        reqSpecial.classList.add('fa-circle')
        feedback.push('Al menos un carácter especial')
      }

      // Actualizar barra de progreso
      progressBar.style.width = strength + '%'
      progressBar.setAttribute('aria-valuenow', strength)

      // Cambiar color según la fuerza
      if (strength < 40) {
        progressBar.className = 'progress-bar bg-danger'
        passwordFeedback.textContent = 'Contraseña débil'
      } else if (strength < 80) {
        progressBar.className = 'progress-bar bg-warning'
        passwordFeedback.textContent = 'Contraseña media'
      } else {
        progressBar.className = 'progress-bar bg-success'
        passwordFeedback.textContent = 'Contraseña fuerte'
      }

      return {
        strength: strength,
        valid: strength === 100,
        feedback: feedback.join(', ')
      }
    }

    // Evento para validar contraseña mientras se escribe
    if (passwordInput) {
      passwordInput.addEventListener('input', function () {
        validatePasswordStrength(this.value)

        // Validar coincidencia si ya se escribió la confirmación
        if (confirmPasswordInput && confirmPasswordInput.value) {
          validatePasswordMatch()
        }
      })
    }

    // Función para validar coincidencia de contraseñas
    function validatePasswordMatch () {
      if (passwordInput && confirmPasswordInput) {
        if (passwordInput.value !== confirmPasswordInput.value) {
          confirmPasswordInput.setCustomValidity('Las contraseñas no coinciden')
        } else {
          confirmPasswordInput.setCustomValidity('')
        }
      }
    }

    // Validar coincidencia de contraseñas
    if (confirmPasswordInput) {
      confirmPasswordInput.addEventListener('input', validatePasswordMatch)
    }
  }

  // Gestión del formulario de registro
  if (registroForm) {
    registroForm.addEventListener('submit', function (e) {
      e.preventDefault()

      mostrarMensaje('Procesando solicitud...', 'info')

      // Validar formulario
      if (!this.checkValidity()) {
        e.stopPropagation()
        this.classList.add('was-validated')
        mostrarMensaje(
          'Por favor, complete todos los campos requeridos correctamente.',
          'danger'
        )
        return
      }

      // Validar fuerza de contraseña
      if (passwordInput) {
        const passwordValidation = validatePasswordStrength(passwordInput.value)
        if (!passwordValidation.valid) {
          passwordFeedback.textContent = passwordValidation.feedback
          mostrarMensaje(
            'La contraseña no cumple con los requisitos mínimos de seguridad.',
            'danger'
          )
          return
        }
      }

      // Crear objeto FormData con los datos del formulario
      const formData = new FormData(this)

      // Asegurar que la URL de la acción sea absoluta
      let actionUrl = this.action

      // Enviar los datos mediante fetch API
      fetch(actionUrl, {
        method: 'POST',
        body: formData,
        cache: 'no-cache',
        credentials: 'same-origin',
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
        .then(response => {
          // Verificar primero si la respuesta es ok (status 200-299)
          if (!response.ok) {
            return response.text().then(text => {
              console.error('Respuesta del servidor no válida:', text)
              throw new Error(`Error HTTP: ${response.status}`)
            })
          }

          // Verificar el tipo de contenido
          const contentType = response.headers.get('content-type')
          if (!contentType || !contentType.includes('application/json')) {
            return response.text().then(text => {
              console.error('Respuesta no es JSON:', text)
              throw new Error('La respuesta del servidor no tiene formato JSON')
            })
          }

          return response.json()
        })
        .then(data => {
          if (data.status === 'success') {
            mostrarMensaje(data.message || 'Registro exitoso.', 'success')

            if (data.redirect) {
              let redirectUrl = data.redirect
              if (
                !redirectUrl.startsWith('http') &&
                !redirectUrl.startsWith('/')
              ) {
                const baseUrl = window.location.origin + '/CyberSec/'
                redirectUrl = baseUrl + redirectUrl
              } else if (redirectUrl.startsWith('/')) {
                redirectUrl = window.location.origin + redirectUrl
              }

              setTimeout(() => {
                window.location.href = redirectUrl
              }, 2000)
            }
          } else {
            mostrarMensaje(
              data.message || 'Error al procesar el registro.',
              'danger'
            )
          }
        })
        .catch(error => {
          console.error('Error en fetch:', error)
          mostrarMensaje(
            'Error en la comunicación con el servidor. Por favor, inténtelo de nuevo.',
            'danger'
          )
        })
    })
  }

  // Gestión del formulario de login
  const loginForm = document.getElementById('loginForm')
  if (loginForm) {
    loginForm.addEventListener('submit', function (e) {
      e.preventDefault()

      mostrarMensaje('Procesando...', 'info')

      // Validar formulario
      if (!this.checkValidity()) {
        e.stopPropagation()
        this.classList.add('was-validated')
        mostrarMensaje(
          'Por favor, complete todos los campos requeridos.',
          'danger'
        )
        return
      }

      // Crear objeto FormData con los datos del formulario
      const formData = new FormData(this)

      // Asegurar que la URL de la acción sea absoluta
      let actionUrl = this.action

      // Enviar los datos mediante fetch API
      fetch(actionUrl, {
        method: 'POST',
        body: formData,
        cache: 'no-cache',
        credentials: 'same-origin',
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
        .then(response => {
          if (!response.ok) {
            return response.text().then(text => {
              console.error('Respuesta del servidor no válida:', text)
              throw new Error(`Error HTTP: ${response.status}`)
            })
          }

          const contentType = response.headers.get('content-type')
          if (!contentType || !contentType.includes('application/json')) {
            return response.text().then(text => {
              console.error('Respuesta no es JSON:', text)
              throw new Error('La respuesta del servidor no tiene formato JSON')
            })
          }

          return response.json()
        })
        .then(data => {
          if (data.status === 'success') {
            mostrarMensaje(
              data.message || 'Inicio de sesión exitoso.',
              'success'
            )

            if (data.redirect) {
              let redirectUrl = data.redirect
              if (
                !redirectUrl.startsWith('http') &&
                !redirectUrl.startsWith('/')
              ) {
                const baseUrl = window.location.origin + '/CyberSec/'
                redirectUrl = baseUrl + redirectUrl
              } else if (redirectUrl.startsWith('/')) {
                redirectUrl = window.location.origin + redirectUrl
              }

              setTimeout(() => {
                window.location.href = redirectUrl
              }, 1500)
            }
          } else {
            mostrarMensaje(data.message || 'Error al iniciar sesión.', 'danger')
          }
        })
        .catch(error => {
          console.error('Error en fetch:', error)
          mostrarMensaje(
            'Error en la comunicación con el servidor. Por favor, inténtelo de nuevo o contacte con soporte.',
            'danger'
          )
        })
    })
  }
})

function mostrarMensaje (mensaje, tipo) {
  const mensajeDiv =
    document.getElementById('registroMessage') ||
    document.getElementById('loginMessage')

  if (mensajeDiv) {
    mensajeDiv.textContent = mensaje
    mensajeDiv.className = `alert alert-${
      tipo === 'success' ? 'success' : tipo === 'info' ? 'info' : 'danger'
    } d-block`

    // Si es un mensaje de éxito, ocultarlo después de 3 segundos
    if (tipo === 'success') {
      setTimeout(() => {
        mensajeDiv.className = 'alert d-none'
      }, 3000)
    }
  }
}
