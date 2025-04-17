/**
 * CyberSec - Header & Footer JavaScript
 * Efectos avanzados para la interfaz con temática cyberpunk/hacker
 */

document.addEventListener('DOMContentLoaded', function () {
  'use strict'

  // Elementos principales
  const header = document.querySelector('.header')
  const navbarToggler = document.querySelector('.navbar-toggler')
  const navbarCollapse = document.querySelector('.navbar-collapse')
  const backToTopButton = document.getElementById('back-to-top')
  const navLinks = document.querySelectorAll('.nav-link')

  // Detección inicial del scroll
  handleHeaderScroll()

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
   * Menú móvil - Botón de hamburguesa
   * Solución al problema de abrir/cerrar
   */
  if (navbarToggler) {
    // Simple función para toggle del menú
    navbarToggler.addEventListener('click', function (e) {
      // Prevenir comportamiento por defecto
      e.preventDefault()

      // Toggle de clases para mostrar/ocultar
      this.classList.toggle('active')
      navbarCollapse.classList.toggle('show')

      // Control del scroll del body
      document.body.style.overflow = navbarCollapse.classList.contains('show')
        ? 'hidden'
        : ''
    })
  }

  /**
   * Efectos de partículas para fondo (opcional, si se añade la librería)
   */
  function initParticles () {
    if (typeof particlesJS !== 'undefined') {
      const containers = document.querySelectorAll('.particles-bg')
      containers.forEach(container => {
        particlesJS(container.id, {
          particles: {
            number: { value: 80, density: { enable: true, value_area: 800 } },
            color: { value: '#00aaff' },
            opacity: { value: 0.5, random: true },
            size: { value: 3, random: true },
            line_linked: {
              enable: true,
              distance: 150,
              color: '#33ff99',
              opacity: 0.4,
              width: 1
            },
            move: {
              enable: true,
              speed: 2,
              direction: 'none',
              random: true,
              straight: false,
              out_mode: 'out',
              bounce: false
            }
          }
        })
      })
    }
  }

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
   * Efectos de typing para elementos con clase .typed-text
   */
  function initTypingEffects () {
    if (typeof Typed !== 'undefined') {
      const typedElements = document.querySelectorAll('.typed-text')
      typedElements.forEach(element => {
        const strings = element.getAttribute('data-strings').split(',')
        new Typed(element, {
          strings: strings,
          typeSpeed: 50,
          backSpeed: 30,
          backDelay: 2000,
          loop: true
        })
      })
    }
  }

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

  // Inicializar todos los efectos
  initGlowEffects()
  initParticles()
  initTypingEffects()
  setupAlerts()

  // Indicador de página cargada
  document.body.classList.add('page-loaded')
})
