document.addEventListener('DOMContentLoaded', function () {
  // Botones y elementos
  const btnEditar = document.getElementById('editarPerfil')
  const btnCancelar = document.getElementById('cancelarEdicion')
  const botonesGuardar = document.getElementById('botonesGuardar')
  const formPerfil = document.getElementById('formPerfil')
  const formPassword = document.getElementById('formPassword')
  const formPreferencias = document.getElementById('formPreferencias')

  // Solo proceder si el formulario de perfil existe
  if (formPerfil) {
    // Campos editables - incluir todos los tipos de campos necesarios
    const camposEditables = formPerfil.querySelectorAll(
      'input[type="text"], input[type="tel"], input[type="email"], textarea, input:not([type="hidden"])'
    )

    if (btnEditar) {
      btnEditar.addEventListener('click', function () {
        camposEditables.forEach(campo => {
          campo.disabled = false
        })
        if (botonesGuardar) botonesGuardar.style.display = 'block'
        btnEditar.style.display = 'none'
      })
    }

    if (btnCancelar) {
      btnCancelar.addEventListener('click', function () {
        camposEditables.forEach(campo => {
          campo.disabled = true
          campo.value = campo.defaultValue // Restaurar valor original
        })
        if (botonesGuardar) botonesGuardar.style.display = 'none'
        if (btnEditar) btnEditar.style.display = 'block'
      })
    }

    // Manejar envío del formulario de perfil
    formPerfil.addEventListener('submit', function (e) {
      e.preventDefault()
      console.log('1. Formulario interceptado - Evento submit')

      // Habilitar temporalmente los campos para que se envíen con el formulario
      camposEditables.forEach(campo => {
        campo.disabled = false
        console.log(`2. Campo habilitado: ${campo.name} = ${campo.value}`)
      })

      const formData = new FormData(formPerfil)
      console.log('3. FormData creado, datos:')
      formData.forEach((value, key) => {
        console.log(`   ${key}: ${value}`)
      })

      console.log('4. URL de envío:', formPerfil.action)

      fetch(formPerfil.action, {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
        .then(response => {
          console.log('5. Respuesta recibida:', response)

          if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`)
          }

          const contentType = response.headers.get('content-type')
          console.log('7. Tipo de contenido:', contentType)

          return response.text().then(text => {
            try {
              return JSON.parse(text)
            } catch (e) {
              console.error('8. Error al parsear JSON:', text)
              throw new Error('Respuesta del servidor no válida')
            }
          })
        })
        .then(data => {
          console.log('9. Datos procesados:', data)

          if (data.status === 'success') {
            console.log('10. Actualización exitosa')
            mostrarNotificacion(
              data.message || 'Perfil actualizado correctamente',
              'success'
            )

            // Deshabilitar edición después de guardar
            camposEditables.forEach(campo => {
              campo.disabled = true
              campo.defaultValue = campo.value
              console.log(`11. Campo deshabilitado: ${campo.name}`)
            })

            if (botonesGuardar) {
              botonesGuardar.style.display = 'none'
              console.log('12. Botones de guardar ocultados')
            }
            if (btnEditar) {
              btnEditar.style.display = 'block'
              console.log('13. Botón editar mostrado')
            }
          } else {
            console.log('10. Error en la actualización:', data.message)
            // Si hay error, mantener campos editables
            camposEditables.forEach(campo => {
              campo.disabled = false
              console.log(`11. Campo mantenido editable: ${campo.name}`)
            })
            mostrarNotificacion(
              data.message || 'Error al actualizar el perfil',
              'error'
            )
          }
        })
        .catch(error => {
          console.error('Error en la operación:', error)
          // Si hay error, mantener campos editables
          camposEditables.forEach(campo => {
            campo.disabled = false
            console.log(`Campo mantenido editable por error: ${campo.name}`)
          })
          mostrarNotificacion(
            'Error al procesar la solicitud: ' + error.message,
            'error'
          )
        })
    })
  }

  // Manejar cambio de contraseña
  if (formPassword) {
    formPassword.addEventListener('submit', function (e) {
      e.preventDefault()

      const passwordNuevo = formPassword.querySelector('#password_nuevo')?.value
      const passwordConfirmar = formPassword.querySelector(
        '#password_confirmar'
      )?.value

      if (!passwordNuevo || !passwordConfirmar) {
        mostrarNotificacion('Por favor, complete todos los campos', 'error')
        return
      }

      if (passwordNuevo !== passwordConfirmar) {
        mostrarNotificacion('Las contraseñas no coinciden', 'error')
        return
      }

      fetch(formPassword.action, {
        method: 'POST',
        body: new FormData(formPassword)
      })
        .then(response => response.json())
        .then(data => {
          if (data.status === 'success') {
            mostrarNotificacion(
              'Contraseña actualizada correctamente',
              'success'
            )
            formPassword.reset()
          } else {
            mostrarNotificacion(
              data.message || 'Error al actualizar la contraseña',
              'error'
            )
          }
        })
        .catch(error => {
          console.error('Error:', error)
          mostrarNotificacion('Error al procesar la solicitud', 'error')
        })
    })
  }

  // Manejar preferencias
  if (formPreferencias) {
    formPreferencias.addEventListener('submit', function (e) {
      e.preventDefault()

      const notifEmail = document.getElementById('notif_email')
      const idioma = document.getElementById('idioma')
      const csrfToken = document.querySelector('input[name="csrf_token"]')

      if (!notifEmail || !idioma || !csrfToken) {
        mostrarNotificacion('Error: Faltan campos requeridos', 'error')
        return
      }

      const preferencias = {
        notificaciones_email: notifEmail.checked,
        idioma: idioma.value
      }

      fetch(BASE_URL + 'controllers/usuario_controller.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          action: 'actualizar_preferencias',
          csrf_token: csrfToken.value,
          ...preferencias
        })
      })
        .then(response => response.json())
        .then(data => {
          if (data.status === 'success') {
            mostrarNotificacion(
              'Preferencias actualizadas correctamente',
              'success'
            )
          } else {
            mostrarNotificacion(
              data.message || 'Error al actualizar las preferencias',
              'error'
            )
          }
        })
        .catch(error => {
          console.error('Error:', error)
          mostrarNotificacion('Error al procesar la solicitud', 'error')
        })
    })
  }

  // Funcionalidad para el cierre de cuenta
  const confirmCloseAccount = document.getElementById('confirmCloseAccount')
  const closeAccountButton = document.getElementById('closeAccountButton')

  if (confirmCloseAccount && closeAccountButton) {
    confirmCloseAccount.addEventListener('change', function () {
      closeAccountButton.disabled = !this.checked
    })

    closeAccountButton.addEventListener('click', async function () {
      try {
        const csrfToken = document.querySelector('input[name="csrf_token"]')
        if (!csrfToken) {
          throw new Error('Token CSRF no encontrado')
        }

        const response = await fetch(
          BASE_URL + 'controllers/usuario_controller.php',
          {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
              action: 'cerrar_cuenta',
              csrf_token: csrfToken.value
            })
          }
        )

        const data = await response.json()

        if (data.status === 'success') {
          Swal.fire({
            title: 'Cuenta Cerrada',
            text: 'Tu cuenta ha sido cerrada correctamente.',
            icon: 'success',
            confirmButtonText: 'Aceptar'
          }).then(() => {
            window.location.href = BASE_URL + 'public/logout.php'
          })
        } else {
          Swal.fire({
            title: 'Error',
            text: data.message || 'Ha ocurrido un error al cerrar la cuenta.',
            icon: 'error',
            confirmButtonText: 'Aceptar'
          })
        }
      } catch (error) {
        console.error('Error:', error)
        Swal.fire({
          title: 'Error',
          text: 'Ha ocurrido un error al procesar la solicitud.',
          icon: 'error',
          confirmButtonText: 'Aceptar'
        })
      }
    })
  }

  // Función para mostrar notificaciones
  function mostrarNotificacion (mensaje, tipo) {
    const notificacion = document.createElement('div')
    notificacion.className = `alert alert-${tipo} alert-dismissible fade show`
    notificacion.innerHTML = `
      ${mensaje}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `

    const contenedor = document.querySelector('main')
    if (contenedor) {
      contenedor.insertBefore(notificacion, contenedor.firstChild)

      setTimeout(() => {
        notificacion.remove()
      }, 5000)
    }
  }
})
