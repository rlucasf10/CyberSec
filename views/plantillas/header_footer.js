/**
 * CyberSec - Header & Footer JavaScript
 * Efectos avanzados para la interfaz con temática cyberpunk/hacker
 */

document.addEventListener('DOMContentLoaded', function () {
  'use strict'

  // Elementos principales
  const header = document.querySelector('.header')
  const backToTopButton = document.getElementById('back-to-top')
  const navLinks = document.querySelectorAll('.nav-link')

  // Detección inicial del scroll
  handleHeaderScroll()

  /**
   * Inicialización de AOS (Animate On Scroll)
   */
  if (typeof AOS !== 'undefined') {
    AOS.init({
      duration: 800,
      easing: 'ease-in-out',
      once: true,
      mirror: false
    })
  }

  /**
   * Control del scroll para efectos en el header
   */
  function handleHeaderScroll () {
    if (window.scrollY > 50) {
      header.classList.add('scrolled')
    } else {
      header.classList.remove('scrolled')
    }
  }

  // Event listener optimizado para scroll
  let scrollTimeout
  window.addEventListener('scroll', function () {
    if (!scrollTimeout) {
      scrollTimeout = setTimeout(function () {
        handleHeaderScroll()
        scrollTimeout = null
      }, 10)
    }
  })

  /**
   * Botón "Volver arriba"
   */
  if (backToTopButton) {
    window.addEventListener('scroll', function () {
      if (window.scrollY > 300) {
        backToTopButton.classList.add('show')
      } else {
        backToTopButton.classList.remove('show')
      }
    })

    backToTopButton.addEventListener('click', function (e) {
      e.preventDefault()
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      })
    })
  }

  /**
   * Marcado de ítem activo en el menú según URL actual
   */
  function highlightActiveMenuItem () {
    const currentUrl = window.location.pathname

    navLinks.forEach(link => {
      const href = link.getAttribute('href')
      if (!href) return

      // Limpiar URLs para comparación
      const cleanLinkPath = href.split('?')[0].split('#')[0]
      const cleanCurrentUrl = currentUrl.split('?')[0].split('#')[0]

      // Verificar coincidencia
      if (
        cleanCurrentUrl.endsWith(cleanLinkPath) ||
        (cleanLinkPath.endsWith('index.php') &&
          (cleanCurrentUrl === '/' || cleanCurrentUrl.endsWith('/'))) ||
        (cleanLinkPath === '/' && cleanCurrentUrl.endsWith('index.php'))
      ) {
        link.classList.add('active')

        // También marcar el ítem padre si es un dropdown
        const parentItem = link.closest('.nav-item')
        if (parentItem) {
          parentItem.classList.add('active')
        }
      }
    })

    // En caso de estar en la página de inicio
    if (
      currentUrl === '/' ||
      currentUrl.endsWith('/') ||
      currentUrl.endsWith('index.php')
    ) {
      const homeLinks = document.querySelectorAll(
        '.nav-link[href="/"], .nav-link[href="index.php"], .nav-link[href="./index.php"]'
      )
      homeLinks.forEach(link => {
        link.classList.add('active')
        const parentItem = link.closest('.nav-item')
        if (parentItem) {
          parentItem.classList.add('active')
        }
      })
    }
  }

  // Aplicar clase active al ítem del menú
  highlightActiveMenuItem()

  /**
   * Efecto hover para botones con clase .cyber-glow-effect
   */
  function initGlowEffects () {
    const glowElements = document.querySelectorAll('.cyber-glow-effect')
    glowElements.forEach(element => {
      element.addEventListener('mouseenter', function () {
        this.classList.add('glowing')
      })

      element.addEventListener('mouseleave', function () {
        this.classList.remove('glowing')
      })
    })
  }

  /**
   * Cerrar alertas auto-dismiss
   */
  function setupAlerts () {
    const alerts = document.querySelectorAll(
      '.message-alert[data-auto-dismiss="true"]'
    )
    alerts.forEach(alert => {
      setTimeout(() => {
        alert.style.opacity = '0'
        setTimeout(() => {
          alert.style.display = 'none'
        }, 300)
      }, 5000)
    })

    // Botones de cierre
    const closeButtons = document.querySelectorAll('.alert-close')
    closeButtons.forEach(button => {
      button.addEventListener('click', function () {
        const alert = this.closest('.message-alert')
        alert.style.opacity = '0'
        setTimeout(() => {
          alert.style.display = 'none'
        }, 300)
      })
    })
  }

  /**
   * Gestión del menú móvil con Bootstrap 5
   * Esta función se asegura de que el menú móvil funcione correctamente
   * sin interferir con las funciones nativas de Bootstrap
   */
  function setupMobileMenu () {
    // Obtener elementos después de que Bootstrap los haya inicializado
    const navbarToggler = document.querySelector('.navbar-toggler')
    const navbarCollapse = document.querySelector('.navbar-collapse')
    const dropdownMenus = document.querySelectorAll('.dropdown-menu')
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle')

    // Asegurar que los links dentro de dropdowns cierren el menú en móvil
    const dropdownLinks = document.querySelectorAll(
      '.dropdown-menu .dropdown-item'
    )
    dropdownLinks.forEach(link => {
      link.addEventListener('click', function () {
        if (window.innerWidth < 992) {
          // Si estamos en móvil, cerrar el menú principal
          const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse)
          if (bsCollapse) {
            bsCollapse.hide()
          }
        }
      })
    })

    // Soporte para click fuera para cerrar el menú en móvil
    document.addEventListener('click', function (e) {
      if (window.innerWidth < 992) {
        // Si el menú está abierto y hacemos clic fuera
        const isMenuOpen = navbarCollapse.classList.contains('show')
        const isClickInside =
          navbarCollapse.contains(e.target) || navbarToggler.contains(e.target)

        if (isMenuOpen && !isClickInside) {
          const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse)
          if (bsCollapse) {
            bsCollapse.hide()
          }
        }
      }
    })

    // Mejorar experiencia de hover/clic en dropdowns según el dispositivo
    if ('ontouchstart' in window || navigator.maxTouchPoints > 0) {
      // Es un dispositivo táctil, asegurarnos que los dropdowns funcionen con un toque
      if (window.innerWidth >= 992) {
        // Solo para tamaños de escritorio, en móvil Bootstrap ya lo maneja
        dropdownToggles.forEach(toggle => {
          toggle.addEventListener('click', function (e) {
            // Permitir navegación al hacer clic en el enlace principal en escritorio táctil
            if (!e.target.classList.contains('dropdown-toggle')) {
              window.location.href = toggle.getAttribute('href')
            }
          })
        })
      }
    }
  }

  // Inicializar todos los efectos
  initGlowEffects()
  setupAlerts()

  // Iniciar manejo del menú móvil después de que Bootstrap esté completamente cargado
  setTimeout(setupMobileMenu, 100)

  // Actualizar configuración en redimensionamiento
  window.addEventListener('resize', function () {
    // Reiniciar configuración del menú móvil si cambia el tamaño
    if (typeof bootstrap !== 'undefined') {
      setupMobileMenu()
    }
  })

  // Indicador de página cargada
  document.body.classList.add('page-loaded')
})
