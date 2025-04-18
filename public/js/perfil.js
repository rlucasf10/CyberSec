document.addEventListener('DOMContentLoaded', function () {
  // Botones y elementos
  const btnEditar = document.getElementById('editarPerfil')
  const btnCancelar = document.getElementById('cancelarEdicion')
  const botonesGuardar = document.getElementById('botonesGuardar')
  const formPerfil = document.getElementById('formPerfil')
  const formPassword = document.getElementById('formPassword')
  const formPreferencias = document.getElementById('formPreferencias')

  // Campos editables
  const camposEditables = formPerfil.querySelectorAll(
    'input[type="text"], input[type="tel"], textarea'
  )

  // Habilitar edición
  btnEditar.addEventListener('click', function () {
    camposEditables.forEach(campo => {
      campo.disabled = false
    })
    botonesGuardar.style.display = 'block'
    btnEditar.style.display = 'none'
  })

  // Cancelar edición
  btnCancelar.addEventListener('click', function () {
    camposEditables.forEach(campo => {
      campo.disabled = true
      campo.value = campo.defaultValue // Restaurar valor original
    })
    botonesGuardar.style.display = 'none'
    btnEditar.style.display = 'block'
  })

  // Manejar envío del formulario de perfil
  formPerfil.addEventListener('submit', function (e) {
    e.preventDefault()

    // Aquí se enviará el formulario mediante fetch
    fetch(formPerfil.action, {
      method: 'POST',
      body: new FormData(formPerfil)
    })
      .then(response => response.json())
      .then(data => {
        if (data.status === 'success') {
          mostrarNotificacion('Perfil actualizado correctamente', 'success')
          // Deshabilitar edición
          camposEditables.forEach(campo => {
            campo.disabled = true
            campo.defaultValue = campo.value // Actualizar valor por defecto
          })
          botonesGuardar.style.display = 'none'
          btnEditar.style.display = 'block'
        } else {
          mostrarNotificacion(
            data.message || 'Error al actualizar el perfil',
            'error'
          )
        }
      })
      .catch(error => {
        console.error('Error:', error)
        mostrarNotificacion('Error al procesar la solicitud', 'error')
      })
  })

  // Manejar cambio de contraseña
  formPassword.addEventListener('submit', function (e) {
    e.preventDefault()

    const passwordNuevo = formPassword.querySelector('#password_nuevo').value
    const passwordConfirmar = formPassword.querySelector(
      '#password_confirmar'
    ).value

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
          mostrarNotificacion('Contraseña actualizada correctamente', 'success')
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

  // Manejar preferencias
  formPreferencias.addEventListener('submit', function (e) {
    e.preventDefault()

    // Aquí se enviarán las preferencias al servidor
    const preferencias = {
      notificaciones_email: document.getElementById('notif_email').checked,
      idioma: document.getElementById('idioma').value
    }

    fetch(BASE_URL + 'controllers/usuario_controller.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        action: 'actualizar_preferencias',
        csrf_token: document.querySelector('input[name="csrf_token"]').value,
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

  // Función para mostrar notificaciones
  function mostrarNotificacion (mensaje, tipo) {
    // Crear elemento de notificación
    const notificacion = document.createElement('div')
    notificacion.className = `alert alert-${tipo} alert-dismissible fade show`
    notificacion.innerHTML = `
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `

    // Insertar al principio del contenedor principal
    const contenedor = document.querySelector('main')
    contenedor.insertBefore(notificacion, contenedor.firstChild)

    // Eliminar después de 5 segundos
    setTimeout(() => {
      notificacion.remove()
    }, 5000)
  }
})
