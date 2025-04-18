document.addEventListener('DOMContentLoaded', function () {
  // Evitar que la página se cargue en un iframe
  if (window.self !== window.top) {
    window.top.location.href = window.self.location.href
  }

  // Configuración de partículas para el fondo animado
  if (typeof particlesJS !== 'undefined') {
    particlesJS('particles-js', {
      particles: {
        number: { value: 80, density: { enable: true, value_area: 800 } },
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

  // Función para mostrar mensajes
  function mostrarMensaje (mensaje, tipo, elementId) {
    const mensajeDiv = document.getElementById(elementId)
    if (mensajeDiv) {
      mensajeDiv.textContent = mensaje
      mensajeDiv.className = `alert alert-${tipo} d-block`

      if (tipo === 'success') {
        setTimeout(() => {
          mensajeDiv.className = 'alert d-none'
        }, 3000)
      }
    }
  }

  // Validación de contraseñas
  const passwordInput = document.getElementById('password')
  const confirmPasswordInput = document.getElementById('confirmar_password')
  const progressBar = document.querySelector('.password-strength .progress-bar')
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
    if (progressBar) {
      progressBar.style.width = strength + '%'
      progressBar.setAttribute('aria-valuenow', strength)

      // Cambiar color según la fuerza
      if (strength < 40) {
        progressBar.className = 'progress-bar bg-danger'
        if (passwordFeedback) passwordFeedback.textContent = 'Contraseña débil'
      } else if (strength < 80) {
        progressBar.className = 'progress-bar bg-warning'
        if (passwordFeedback) passwordFeedback.textContent = 'Contraseña media'
      } else {
        progressBar.className = 'progress-bar bg-success'
        if (passwordFeedback) passwordFeedback.textContent = 'Contraseña fuerte'
      }
    }

    return {
      strength: strength,
      valid: strength === 100,
      feedback: feedback.join(', ')
    }
  }

  // Validar contraseña mientras se escribe
  if (passwordInput) {
    passwordInput.addEventListener('input', function () {
      validatePasswordStrength(this.value)

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

  // Gestión del formulario de solicitud de recuperación
  const recuperarForm = document.getElementById('recuperarForm')
  if (recuperarForm) {
    recuperarForm.addEventListener('submit', function (e) {
      e.preventDefault()

      mostrarMensaje('Procesando solicitud...', 'info', 'recuperarMessage')

      // Validar formulario
      if (!this.checkValidity()) {
        e.stopPropagation()
        this.classList.add('was-validated')
        mostrarMensaje(
          'Por favor, complete todos los campos requeridos.',
          'danger',
          'recuperarMessage'
        )
        return
      }

      // Enviar solicitud
      fetch(this.action, {
        method: 'POST',
        body: new FormData(this),
        cache: 'no-cache',
        credentials: 'same-origin',
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
        .then(response => {
          if (!response.ok) {
            return response.text().then(text => {
              throw new Error(`Error HTTP: ${response.status}`)
            })
          }
          return response.json()
        })
        .then(data => {
          if (data.status === 'success') {
            mostrarMensaje(
              data.message || 'Se ha enviado un enlace a tu correo.',
              'success',
              'recuperarMessage'
            )
            this.reset()
          } else {
            mostrarMensaje(
              data.message || 'Error al procesar la solicitud.',
              'danger',
              'recuperarMessage'
            )
          }
        })
        .catch(error => {
          console.error('Error:', error)
          mostrarMensaje(
            'Error en la comunicación con el servidor.',
            'danger',
            'recuperarMessage'
          )
        })
    })
  }

  // Gestión del formulario de restablecimiento
  const resetForm = document.getElementById('resetForm')
  if (resetForm) {
    resetForm.addEventListener('submit', function (e) {
      e.preventDefault()

      mostrarMensaje('Procesando solicitud...', 'info', 'resetMessage')

      // Validar formulario
      if (!this.checkValidity()) {
        e.stopPropagation()
        this.classList.add('was-validated')
        mostrarMensaje(
          'Por favor, complete todos los campos requeridos.',
          'danger',
          'resetMessage'
        )
        return
      }

      // Validar fuerza de contraseña
      if (passwordInput) {
        const passwordValidation = validatePasswordStrength(passwordInput.value)
        if (!passwordValidation.valid) {
          mostrarMensaje(
            'La contraseña no cumple con los requisitos mínimos de seguridad.',
            'danger',
            'resetMessage'
          )
          return
        }
      }

      // Enviar solicitud
      fetch(this.action, {
        method: 'POST',
        body: new FormData(this),
        cache: 'no-cache',
        credentials: 'same-origin',
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
        .then(response => {
          if (!response.ok) {
            return response.text().then(text => {
              throw new Error(`Error HTTP: ${response.status}`)
            })
          }
          return response.json()
        })
        .then(data => {
          if (data.status === 'success') {
            mostrarMensaje(
              data.message || 'Contraseña actualizada correctamente.',
              'success',
              'resetMessage'
            )

            // Redireccionar después de 2 segundos
            if (data.redirect) {
              setTimeout(() => {
                window.location.href = data.redirect
              }, 2000)
            }
          } else {
            mostrarMensaje(
              data.message || 'Error al restablecer la contraseña.',
              'danger',
              'resetMessage'
            )
          }
        })
        .catch(error => {
          console.error('Error:', error)
          mostrarMensaje(
            'Error en la comunicación con el servidor.',
            'danger',
            'resetMessage'
          )
        })
    })
  }
})
