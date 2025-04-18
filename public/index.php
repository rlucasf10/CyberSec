<?php
// Definir constante para permitir acceso a archivos de configuración
define('ACCESO_PERMITIDO', true);

// Incluir archivo de configuración
require_once __DIR__ . '/../config/config.php';

// Iniciar el buffer de salida para evitar problemas con las cabeceras
ob_start();

// Iniciar sesión si aún no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir encabezado
require_once BASE_PATH . '/views/plantillas/header.php';
?>

<!-- Estilos personalizados -->
<link rel="stylesheet" href="<?php echo htmlspecialchars(BASE_URL . 'public/css/index.css'); ?>">

<!-- Preloader -->
<div id="preloader">
    <div class="loader">
        <div class="shield-icon">
            <i class="fas fa-shield-alt"></i>
        </div>
        <div class="loading-text">CARGANDO<span class="dots">...</span></div>
    </div>
</div>

<!-- Partículas de fondo para efectos visuales -->
<div id="particles-js" class="particles-container"></div>

<!-- Hero Section con animación mejorada -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 hero-content" data-aos="fade-right" data-aos-delay="200">
                <div class="badge-container mb-3 animate__animated animate__fadeInDown">
                    <span class="badge bg-danger">
                        <i class="fas fa-shield-alt me-1"></i> Especialistas en Ciberseguridad
                    </span>
                </div>
                <h1 class="hero-title animate__animated animate__fadeInUp">
                    Seguridad Digital para un Mundo Conectado
                </h1>
                <p class="hero-text animate__animated animate__fadeInUp animate__delay-1s">
                    Protegemos su empresa contra las amenazas más avanzadas con nuestros servicios de
                    <span class="typing-animation text-primary fw-bold"
                        data-strings='["pentesting", "auditoría de seguridad", "respuesta a incidentes", "consultoría especializada", "análisis de vulnerabilidades"]'></span>
                </p>
                <div class="mt-4 animate__animated animate__fadeInUp animate__delay-2s">
                    <a href="<?php echo htmlspecialchars(BASE_URL); ?>contacto.php"
                        class="btn btn-primary btn-lg me-3 shadow-pulse">
                        <i class="fas fa-shield-alt me-2"></i> Protege tu Empresa
                    </a>
                    <a href="<?php echo htmlspecialchars(BASE_URL); ?>servicios.php"
                        class="btn btn-outline-light btn-lg">
                        <i class="fas fa-info-circle me-2"></i> Conoce Más
                    </a>
                </div>
                <div class="trust-indicators mt-4 animate__animated animate__fadeInUp animate__delay-3s">
                    <div class="d-flex align-items-center">
                        <div class="trust-icon me-2"><i class="fas fa-check-circle text-success"></i></div>
                        <div class="trust-text">ISO 27001 Certificado</div>
                        <div class="trust-icon mx-3"><i class="fas fa-check-circle text-success"></i></div>
                        <div class="trust-text">Equipo Certificado OSCP</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block" data-aos="fade-left" data-aos-delay="400">
                <div class="hero-image-container">
                    <img src="<?php echo htmlspecialchars(BASE_URL . 'public/img/hero-image.png'); ?>"
                        alt="Ciberseguridad" class="img-fluid animate__animated animate__zoomIn">
                    <div class="hero-blob"></div>
                    <div class="hero-floating-icon icon-1"><i class="fas fa-lock"></i></div>
                    <div class="hero-floating-icon icon-2"><i class="fas fa-shield-alt"></i></div>
                    <div class="hero-floating-icon icon-3"><i class="fas fa-server"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Elementos decorativos flotantes -->
    <div class="floating-element d-none d-lg-block">
        <div class="float-1 animate__animated animate__pulse animate__infinite"></div>
        <div class="float-2 animate__animated animate__pulse animate__infinite animate__delay-1s"></div>
        <div class="float-3 animate__animated animate__pulse animate__infinite animate__delay-2s"></div>
    </div>

    <!-- Scroll indicator -->
    <div class="scroll-indicator">
        <div class="mouse">
            <div class="wheel"></div>
        </div>
        <div>
            <span class="scroll-arrow">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </div>
    </div>
</section>

<!-- Banner de alertas de ciberseguridad -->
<div class="cybersecurity-alert">
    <div class="container">
        <div class="alert-ticker">
            <span class="alert-badge"><i class="fas fa-exclamation-triangle"></i> ALERTA</span>
            <div class="alert-text">
                <div class="ticker-wrapper">
                    <div class="ticker-item">Nuevas vulnerabilidades críticas detectadas en sistemas Windows - Actualice
                        sus sistemas</div>
                    <div class="ticker-item">Aumento de ataques de ransomware en el sector financiero - Refuerce su
                        seguridad</div>
                    <div class="ticker-item">Nueva campaña de phishing dirigida a empresas españolas - Extreme
                        precauciones</div>
                </div>
            </div>
            <a href="<?php echo htmlspecialchars(BASE_URL); ?>blog/alertas.php" class="alert-link">Ver todas <i
                    class="fas fa-arrow-right ms-1"></i></a>
        </div>
    </div>
</div>

<!-- Sección de servicios destacados con efectos interactivos -->
<section class="services-section py-5 mb-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="section-badge mb-2">NUESTRAS SOLUCIONES</div>
            <h2 class="section-title">Nuestros <span class="text-gradient">Servicios</span></h2>
            <p class="lead">Soluciones personalizadas para proteger su infraestructura digital</p>
            <div class="section-separator"><span><i class="fas fa-shield-alt"></i></span></div>
        </div>

        <div class="row g-4">
            <!-- Pentesting -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="service-card card h-100 hover-effect">
                    <div class="service-card-bg"></div>
                    <div class="card-body text-center p-4">
                        <div class="service-icon">
                            <i class="fas fa-bug"></i>
                        </div>
                        <h3 class="h4 mb-3">Pentesting</h3>
                        <p class="card-text text-muted">Evaluamos la seguridad de sus sistemas mediante simulaciones de
                            ataques controlados para identificar vulnerabilidades antes que los atacantes.</p>
                        <a href="<?php echo htmlspecialchars(BASE_URL); ?>servicios/pentesting.php"
                            class="btn btn-sm btn-outline-primary mt-3 service-btn">
                            <i class="fas fa-arrow-right me-1"></i> Saber Más
                        </a>
                    </div>
                </div>
            </div>

            <!-- Auditoría de Seguridad -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="service-card card h-100 hover-effect">
                    <div class="service-card-bg"></div>
                    <div class="card-body text-center p-4">
                        <div class="service-icon">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <h3 class="h4 mb-3">Auditoría de Seguridad</h3>
                        <p class="card-text text-muted">Analizamos su infraestructura para garantizar el cumplimiento de
                            normativas y estándares de seguridad internacionales.</p>
                        <a href="<?php echo htmlspecialchars(BASE_URL); ?>servicios/auditoria.php"
                            class="btn btn-sm btn-outline-primary mt-3 service-btn">
                            <i class="fas fa-arrow-right me-1"></i> Saber Más
                        </a>
                    </div>
                </div>
            </div>

            <!-- Respuesta a Incidentes -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="service-card card h-100">
                    <div class="card-body text-center p-4">
                        <div class="service-icon">
                            <i class="fas fa-fire-extinguisher"></i>
                        </div>
                        <h3 class="h4 mb-3">Respuesta a Incidentes</h3>
                        <p class="card-text text-muted">Actuamos rápidamente para contener, analizar y remediar brechas
                            de seguridad, minimizando el impacto en su negocio.</p>
                        <a href="<?php echo htmlspecialchars(BASE_URL); ?>servicios/respuesta-incidentes.php"
                            class="btn btn-sm btn-outline-primary mt-3">
                            <i class="fas fa-arrow-right me-1"></i> Saber Más
                        </a>
                    </div>
                </div>
            </div>

            <!-- Consultoría -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="service-card card h-100">
                    <div class="card-body text-center p-4">
                        <div class="service-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h3 class="h4 mb-3">Consultoría Especializada</h3>
                        <p class="card-text text-muted">Ofrecemos asesoramiento personalizado en materia de
                            ciberseguridad alineado con los objetivos estratégicos de su empresa.</p>
                        <a href="<?php echo htmlspecialchars(BASE_URL); ?>servicios/consultoria.php"
                            class="btn btn-sm btn-outline-primary mt-3">
                            <i class="fas fa-arrow-right me-1"></i> Saber Más
                        </a>
                    </div>
                </div>
            </div>

            <!-- Análisis de Vulnerabilidades -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                <div class="service-card card h-100">
                    <div class="card-body text-center p-4">
                        <div class="service-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3 class="h4 mb-3">Análisis de Vulnerabilidades</h3>
                        <p class="card-text text-muted">Identificamos y clasificamos las vulnerabilidades en sus
                            sistemas para priorizar y planificar su mitigación.</p>
                        <a href="<?php echo htmlspecialchars(BASE_URL); ?>servicios/analisis-vulnerabilidades.php"
                            class="btn btn-sm btn-outline-primary mt-3">
                            <i class="fas fa-arrow-right me-1"></i> Saber Más
                        </a>
                    </div>
                </div>
            </div>

            <!-- Formación -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                <div class="service-card card h-100">
                    <div class="card-body text-center p-4">
                        <div class="service-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3 class="h4 mb-3">Formación</h3>
                        <p class="card-text text-muted">Capacitamos a su equipo para reconocer y responder adecuadamente
                            a las amenazas de seguridad mediante cursos especializados.</p>
                        <a href="<?php echo htmlspecialchars(BASE_URL); ?>servicios/formacion.php"
                            class="btn btn-sm btn-outline-primary mt-3">
                            <i class="fas fa-arrow-right me-1"></i> Saber Más
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección de estadísticas -->
<section class="counter-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-sm-6 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="100">
                <div class="counter-item">
                    <div class="counter-value" data-count="150">0</div>
                    <h4 class="counter-title">Proyectos Completados</h4>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="200">
                <div class="counter-item">
                    <div class="counter-value" data-count="85">0</div>
                    <h4 class="counter-title">Clientes Satisfechos</h4>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="300">
                <div class="counter-item">
                    <div class="counter-value" data-count="3500">0</div>
                    <h4 class="counter-title">Vulnerabilidades Detectadas</h4>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="400">
                <div class="counter-item">
                    <div class="counter-value" data-count="25">0</div>
                    <h4 class="counter-title">Profesionales Certificados</h4>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección de por qué elegirnos -->
<section class="why-us-section py-5 my-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                <img src="<?php echo htmlspecialchars(BASE_URL . 'public/img/security-team.jpg'); ?>"
                    alt="Equipo de seguridad" class="img-fluid rounded shadow-lg">
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <h2 class="section-title mb-4">¿Por qué <span class="text-gradient">elegirnos</span>?</h2>

                <div class="feature-item d-flex mb-4">
                    <div class="feature-icon me-3">
                        <i class="fas fa-check-circle text-primary fa-2x"></i>
                    </div>
                    <div>
                        <h4 class="h5">Experiencia y Certificaciones</h4>
                        <p class="text-muted">Nuestro equipo cuenta con certificaciones OSCP, CEH, CISSP entre otras, y
                            una experiencia combinada de más de 50 años en el sector.</p>
                    </div>
                </div>

                <div class="feature-item d-flex mb-4">
                    <div class="feature-icon me-3">
                        <i class="fas fa-lock text-primary fa-2x"></i>
                    </div>
                    <div>
                        <h4 class="h5">Metodología Rigurosa</h4>
                        <p class="text-muted">Utilizamos metodologías basadas en estándares internacionales como OWASP,
                            NIST y PTES para garantizar resultados confiables.</p>
                    </div>
                </div>

                <div class="feature-item d-flex mb-4">
                    <div class="feature-icon me-3">
                        <i class="fas fa-chart-line text-primary fa-2x"></i>
                    </div>
                    <div>
                        <h4 class="h5">Soluciones Adaptadas</h4>
                        <p class="text-muted">Desarrollamos estrategias personalizadas según el tamaño, industria y
                            necesidades específicas de cada cliente.</p>
                    </div>
                </div>

                <div class="feature-item d-flex">
                    <div class="feature-icon me-3">
                        <i class="fas fa-headset text-primary fa-2x"></i>
                    </div>
                    <div>
                        <h4 class="h5">Soporte Continuo</h4>
                        <p class="text-muted">No nos limitamos a identificar problemas; ofrecemos acompañamiento durante
                            todo el proceso de implementación de soluciones.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección de clientes -->
<section class="clients-section py-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Confían en <span class="text-gradient">Nosotros</span></h2>
            <p class="lead">Empresas líderes que han elegido nuestros servicios de ciberseguridad</p>
        </div>

        <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="200">
            <div class="col-lg-10">
                <div class="client-logos">
                    <div class="client-logo" data-aos="zoom-in" data-aos-delay="100">
                        <div class="client-logo-container">
                            <img src="<?php echo htmlspecialchars(BASE_URL . 'public/img/clients/client1.png'); ?>"
                                alt="Cliente 1" class="img-fluid">
                            <p class="client-name">TechSecure Corp.</p>
                        </div>
                    </div>
                    <div class="client-logo" data-aos="zoom-in" data-aos-delay="200">
                        <div class="client-logo-container">
                            <img src="<?php echo htmlspecialchars(BASE_URL . 'public/img/clients/client2.png'); ?>"
                                alt="Cliente 2" class="img-fluid">
                            <p class="client-name">InnovaSystems</p>
                        </div>
                    </div>
                    <div class="client-logo" data-aos="zoom-in" data-aos-delay="300">
                        <div class="client-logo-container">
                            <img src="<?php echo htmlspecialchars(BASE_URL . 'public/img/clients/client3.png'); ?>"
                                alt="Cliente 3" class="img-fluid">
                            <p class="client-name">Global Defence</p>
                        </div>
                    </div>
                    <div class="client-logo" data-aos="zoom-in" data-aos-delay="400">
                        <div class="client-logo-container">
                            <img src="<?php echo htmlspecialchars(BASE_URL . 'public/img/clients/client4.png'); ?>"
                                alt="Cliente 4" class="img-fluid">
                            <p class="client-name">DataShield Inc.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección de testimonios -->
<section class="testimonials-section py-5 my-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Testimonios de <span class="text-gradient">Clientes</span></h2>
            <p class="lead">Lo que dicen nuestros clientes sobre nuestros servicios</p>
        </div>

        <div class="swiper testimonial-slider" data-aos="fade-up" data-aos-delay="200">
            <div class="swiper-wrapper">
                <!-- Testimonio 1 -->
                <div class="swiper-slide">
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <div class="testimonial-rating mb-3">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <p>"El equipo de CyberSec realizó un pentesting exhaustivo que nos permitió descubrir
                                vulnerabilidades críticas que habían pasado desapercibidas durante años. Su
                                profesionalidad y conocimiento técnico son excepcionales."</p>
                        </div>
                        <div class="testimonial-client">
                            <img src="<?php echo htmlspecialchars(BASE_URL . 'public/img/testimonials/client1.jpg'); ?>"
                                alt="Cliente 1" class="testimonial-client-image">
                            <div class="testimonial-client-info">
                                <h5 class="testimonial-client-name">Carlos Rodríguez</h5>
                                <p class="testimonial-client-role">CISO en Banco Nacional</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonio 2 -->
                <div class="swiper-slide">
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <div class="testimonial-rating mb-3">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <p>"La formación que recibimos de CyberSec transformó la cultura de seguridad en nuestra
                                empresa. Ahora todo el personal es consciente de su papel en la protección de nuestros
                                activos digitales."</p>
                        </div>
                        <div class="testimonial-client">
                            <img src="<?php echo htmlspecialchars(BASE_URL . 'public/img/testimonials/client2.jpg'); ?>"
                                alt="Cliente 2" class="testimonial-client-image">
                            <div class="testimonial-client-info">
                                <h5 class="testimonial-client-name">Ana Martínez</h5>
                                <p class="testimonial-client-role">Directora de IT en TechSolutions</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonio 3 -->
                <div class="swiper-slide">
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <div class="testimonial-rating mb-3">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star-half-alt text-warning"></i>
                            </div>
                            <p>"Durante un incidente crítico de seguridad, el equipo de respuesta de CyberSec actuó con
                                rapidez y eficacia, minimizando el impacto y guiándonos durante todo el proceso de
                                recuperación. Su experiencia fue invaluable."</p>
                        </div>
                        <div class="testimonial-client">
                            <img src="<?php echo htmlspecialchars(BASE_URL . 'public/img/testimonials/client3.jpg'); ?>"
                                alt="Cliente 3" class="testimonial-client-image">
                            <div class="testimonial-client-info">
                                <h5 class="testimonial-client-name">Jorge Sánchez</h5>
                                <p class="testimonial-client-role">CEO de RetailGroup</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
</section>

<!-- Sección CTA -->
<section class="cta-section py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-9 mb-4 mb-lg-0" data-aos="fade-right">
                <h2 class="mb-2">¿Listo para proteger su empresa de las amenazas digitales?</h2>
                <p class="lead mb-0">Contacte con nosotros para una evaluación gratuita de seguridad.</p>
            </div>
            <div class="col-lg-3 text-lg-end" data-aos="fade-left">
                <a href="contacto.php" class="btn btn-light btn-lg px-4">
                    <i class="fas fa-envelope me-2"></i> Contáctenos
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Sección de blog -->
<section class="blog-section py-5 my-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Últimas <span class="text-gradient">Publicaciones</span></h2>
            <p class="lead">Artículos y noticias sobre ciberseguridad</p>
        </div>

        <div class="row g-4">
            <!-- Artículo 1 -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="card blog-card h-100">
                    <img src="<?php echo htmlspecialchars(BASE_URL . 'public/img/blog/blog1.jpg'); ?>"
                        class="card-img-top" alt="Ransomware">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="badge bg-primary">Amenazas</span>
                            <small class="text-muted">15 Jun 2023</small>
                        </div>
                        <h5 class="card-title">La evolución del Ransomware en 2023</h5>
                        <p class="card-text">Análisis de las últimas tendencias en ataques de ransomware y estrategias
                            efectivas para proteger su organización.</p>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="blog/evolucion-ransomware-2023.php" class="btn btn-link p-0">
                            Leer más <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Artículo 2 -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="card blog-card h-100">
                    <img src="<?php echo htmlspecialchars(BASE_URL . 'public/img/blog/blog2.jpg'); ?>"
                        class="card-img-top" alt="Zero Trust">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="badge bg-success">Estrategias</span>
                            <small class="text-muted">3 Jun 2023</small>
                        </div>
                        <h5 class="card-title">Implementando Zero Trust en su empresa</h5>
                        <p class="card-text">Guía práctica para adoptar el modelo de seguridad Zero Trust y mejorar
                            significativamente su postura de seguridad.</p>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="blog/implementando-zero-trust.php" class="btn btn-link p-0">
                            Leer más <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Artículo 3 -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="card blog-card h-100">
                    <img src="<?php echo htmlspecialchars(BASE_URL . 'public/img/blog/blog3.jpg'); ?>"
                        class="card-img-top" alt="Phishing">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="badge bg-danger">Alertas</span>
                            <small class="text-muted">28 May 2023</small>
                        </div>
                        <h5 class="card-title">Las 5 campañas de phishing más sofisticadas</h5>
                        <p class="card-text">Analizamos las técnicas más avanzadas de ingeniería social y cómo
                            detectarlas antes de convertirse en víctima.</p>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="blog/campanas-phishing-sofisticadas.php" class="btn btn-link p-0">
                            Leer más <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-5" data-aos="fade-up">
            <a href="blog.php" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-book me-2"></i> Ver todas las publicaciones
            </a>
        </div>
    </div>
</section>

<!-- Scripts externos -->
<script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<!-- Estilos externos -->
<link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<!-- Scripts específicos -->
<script src="<?php echo htmlspecialchars(BASE_URL . 'public/js/index.js?v=' . $version); ?>"></script>

<?php
// Limpiar buffer antes de incluir el pie de página
if (ob_get_length()) {
    ob_end_flush();
}

// Incluir pie de página
require_once BASE_PATH . '/views/plantillas/footer.php';
?>