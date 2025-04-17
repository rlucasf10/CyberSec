// Esperar a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function () {
  // Inicializar preloader
  initPreloader()

  // Inicializar animación de texto
  initTypingAnimation()

  // Inicializar contador de estadísticas
  initCounters()

  // Inicializar Swiper para testimonios
  initTestimonialSlider()

  // Inicializar partículas de fondo
  initParticles()

  // Inicializar efectos de scroll
  initScrollEffects()

  // Inicializar efectos hover en servicios
  initServiceHoverEffects()
})

// Preloader
function initPreloader () {
  const preloader = document.getElementById('preloader')

  if (preloader) {
    // Simular carga de recursos
    setTimeout(function () {
      preloader.style.opacity = '0'
      setTimeout(function () {
        preloader.style.display = 'none'

        // Animar elementos después de que el preloader desaparezca
        document
          .querySelectorAll('.animate__animated')
          .forEach(function (element) {
            element.classList.add('animate__fadeInUp')
          })
      }, 500)
    }, 1500)
  }
}

// Animación de texto con Typed.js
function initTypingAnimation () {
  const typingElements = document.querySelectorAll('.typing-animation')

  typingElements.forEach(function (element) {
    const strings = JSON.parse(element.getAttribute('data-strings'))

    new Typed(element, {
      strings: strings,
      typeSpeed: 50,
      backSpeed: 30,
      backDelay: 2000,
      startDelay: 1000,
      loop: true,
      showCursor: true,
      cursorChar: '|',
      autoInsertCss: true
    })
  })
}

// Contador de estadísticas
function initCounters () {
  const counterElements = document.querySelectorAll('.counter-value')

  // Función para animar el contador
  function animateCounter (element) {
    const target = parseInt(element.getAttribute('data-count'))
    const duration = 2000 // 2 segundos
    const step = (target / duration) * 10 // Calcular el incremento por paso
    let current = 0

    const timer = setInterval(function () {
      current += step

      if (current >= target) {
        element.textContent = target
        clearInterval(timer)
      } else {
        element.textContent = Math.floor(current)
      }
    }, 10)
  }

  // Configurar Intersection Observer para iniciar la animación cuando sea visible
  const observer = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          animateCounter(entry.target)
          observer.unobserve(entry.target) // Solo animar una vez
        }
      })
    },
    { threshold: 0.5 }
  )

  // Observar cada elemento contador
  counterElements.forEach(function (element) {
    observer.observe(element)
  })
}

// Slider de testimonios con Swiper
function initTestimonialSlider () {
  const testimonialSlider = document.querySelector('.testimonial-slider')

  if (testimonialSlider) {
    new Swiper(testimonialSlider, {
      slidesPerView: 1,
      spaceBetween: 30,
      loop: true,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev'
      },
      breakpoints: {
        768: {
          slidesPerView: 2
        },
        992: {
          slidesPerView: 3
        }
      }
    })
  }
}

// Inicializar partículas
function initParticles () {
  const particlesContainer = document.getElementById('particles-js')

  if (particlesContainer && typeof particlesJS !== 'undefined') {
    particlesJS('particles-js', {
      particles: {
        number: {
          value: 80,
          density: {
            enable: true,
            value_area: 800
          }
        },
        color: {
          value: '#ffffff'
        },
        shape: {
          type: 'circle',
          stroke: {
            width: 0,
            color: '#000000'
          }
        },
        opacity: {
          value: 0.3,
          random: true,
          anim: {
            enable: true,
            speed: 1,
            opacity_min: 0.1,
            sync: false
          }
        },
        size: {
          value: 3,
          random: true,
          anim: {
            enable: false
          }
        },
        line_linked: {
          enable: true,
          distance: 150,
          color: '#ffffff',
          opacity: 0.2,
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
      },
      interactivity: {
        detect_on: 'canvas',
        events: {
          onhover: {
            enable: true,
            mode: 'grab'
          },
          onclick: {
            enable: true,
            mode: 'push'
          },
          resize: true
        },
        modes: {
          grab: {
            distance: 140,
            line_linked: {
              opacity: 1
            }
          },
          push: {
            particles_nb: 4
          }
        }
      },
      retina_detect: true
    })
  }
}

// Efectos de scroll
function initScrollEffects () {
  // Inicializar AOS (Animate On Scroll)
  if (typeof AOS !== 'undefined') {
    AOS.init({
      duration: 800,
      easing: 'ease-in-out',
      once: true,
      mirror: false
    })
  }

  // Cambiar la opacidad de elementos al hacer scroll
  const scrollIndicator = document.querySelector('.scroll-indicator')

  if (scrollIndicator) {
    window.addEventListener('scroll', function () {
      const scrollPosition = window.scrollY

      // Desvanecer el indicador de scroll cuando el usuario comienza a desplazarse
      if (scrollPosition > 100) {
        scrollIndicator.style.opacity = '0'
      } else {
        scrollIndicator.style.opacity = '1'
      }
    })
  }

  // Añadir clase 'scrolled' al encabezado cuando se desplaza
  const header = document.querySelector('header')

  if (header) {
    window.addEventListener('scroll', function () {
      if (window.scrollY > 50) {
        header.classList.add('scrolled')
      } else {
        header.classList.remove('scrolled')
      }
    })
  }
}

// Efectos hover en servicios
function initServiceHoverEffects () {
  const serviceCards = document.querySelectorAll('.service-card')

  serviceCards.forEach(function (card) {
    card.addEventListener('mouseenter', function () {
      this.classList.add('active')
    })

    card.addEventListener('mouseleave', function () {
      this.classList.remove('active')
    })
  })
}

// Smooth Scroll para enlaces internos
document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
  anchor.addEventListener('click', function (e) {
    e.preventDefault()

    const targetId = this.getAttribute('href')
    if (targetId === '#') return

    const targetElement = document.querySelector(targetId)

    if (targetElement) {
      window.scrollTo({
        top: targetElement.offsetTop - 80, // Ajustar el desplazamiento según sea necesario
        behavior: 'smooth'
      })
    }
  })
})

// Función para mostrar animación cuando los elementos son visibles
function animateWhenVisible () {
  const animatedElements = document.querySelectorAll('.animate-on-scroll')

  const observer = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible')
          observer.unobserve(entry.target)
        }
      })
    },
    { threshold: 0.1 }
  )

  animatedElements.forEach(function (element) {
    observer.observe(element)
  })
}

// Llamar la función cuando se carga la página
window.addEventListener('load', animateWhenVisible)

// Carga dinámica de recursos externos
function loadExternalResource (type, path) {
  return new Promise((resolve, reject) => {
    let element

    if (type === 'script') {
      element = document.createElement('script')
      element.src = path
      element.async = true
    } else if (type === 'style') {
      element = document.createElement('link')
      element.href = path
      element.rel = 'stylesheet'
    }

    if (element) {
      element.onload = () => resolve(path)
      element.onerror = () => reject(new Error(`Failed to load ${path}`))
      document.head.appendChild(element)
    } else {
      reject(new Error(`Unsupported resource type: ${type}`))
    }
  })
}

// Cargar librería de partículas si no está ya cargada
if (
  document.getElementById('particles-js') &&
  typeof particlesJS === 'undefined'
) {
  loadExternalResource(
    'script',
    'https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js'
  )
    .then(() => {
      console.log('Particles.js loaded successfully')
      initParticles()
    })
    .catch(error => {
      console.error('Error loading particles.js:', error)
    })
}

// Inicializar AOS si no está ya cargado
if (typeof AOS === 'undefined') {
  Promise.all([
    loadExternalResource('style', 'https://unpkg.com/aos@2.3.1/dist/aos.css'),
    loadExternalResource('script', 'https://unpkg.com/aos@2.3.1/dist/aos.js')
  ])
    .then(() => {
      console.log('AOS loaded successfully')
      AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
      })
    })
    .catch(error => {
      console.error('Error loading AOS:', error)
    })
}
