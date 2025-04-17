</main>

<!-- Botón volver arriba -->
<a href="#" id="back-to-top" class="back-to-top" aria-label="Volver arriba">
    <i class="fas fa-chevron-up"></i>
</a>

<!-- Footer / Pie de página -->
<footer class="footer mt-5">
    <div class="container">
        <div class="row">
            <!-- Columna 1: Logo e información corporativa -->
            <div class="col-lg-4 mb-4 mb-lg-0">
                <div class="footer-brand d-flex align-items-center mb-3">
                    <div class="footer-logo me-2">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <span class="h4 mb-0"><?php echo APP_NAME; ?></span>
                </div>
                <p class="text-light-50">
                    Expertos en ciberseguridad y pentesting, protegiendo su organización
                    contra las amenazas digitales con soluciones personalizadas y tecnología avanzada.
                </p>
                <div class="social-links d-flex mt-3">
                    <a href="#" class="social-icon me-2" aria-label="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social-icon me-2" aria-label="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="social-icon me-2" aria-label="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="#" class="social-icon me-2" aria-label="Github">
                        <i class="fab fa-github"></i>
                    </a>
                    <a href="#" class="social-icon" aria-label="YouTube">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>

            <!-- Columna 2: Enlaces rápidos -->
            <div class="col-sm-6 col-lg-2 mb-4 mb-lg-0">
                <h5 class="text-white mb-3">Enlaces</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="<?php echo BASE_URL; ?>" class="hover-link">Inicio</a>
                    </li>
                    <li class="mb-2">
                        <a href="<?php echo BASE_URL; ?>servicios.php" class="hover-link">Servicios</a>
                    </li>
                    <li class="mb-2">
                        <a href="<?php echo BASE_URL; ?>blog.php" class="hover-link">Blog</a>
                    </li>
                    <li class="mb-2">
                        <a href="<?php echo BASE_URL; ?>contacto.php" class="hover-link">Contacto</a>
                    </li>
                    <li class="mb-2">
                        <a href="<?php echo BASE_URL; ?>views/login.php" class="hover-link">Login</a>
                    </li>
                </ul>
            </div>

            <!-- Columna 3: Servicios -->
            <div class="col-sm-6 col-lg-3 mb-4 mb-lg-0">
                <h5 class="text-white mb-3">Servicios</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="<?php echo BASE_URL; ?>servicios.php#pentesting" class="hover-link">Pentesting</a>
                    </li>
                    <li class="mb-2">
                        <a href="<?php echo BASE_URL; ?>servicios.php#auditoria" class="hover-link">Auditoría</a>
                    </li>
                    <li class="mb-2">
                        <a href="<?php echo BASE_URL; ?>servicios.php#consultoria" class="hover-link">Consultoría</a>
                    </li>
                    <li class="mb-2">
                        <a href="<?php echo BASE_URL; ?>servicios.php#formacion" class="hover-link">Formación</a>
                    </li>
                    <li class="mb-2">
                        <a href="<?php echo BASE_URL; ?>servicios.php" class="hover-link">Ver todos</a>
                    </li>
                </ul>
            </div>

            <!-- Columna 4: Newsletter -->
            <div class="col-lg-3">
                <h5 class="text-white mb-3">Newsletter</h5>
                <p class="text-light-50">Suscríbase a nuestro boletín para recibir actualizaciones y noticias sobre
                    ciberseguridad.</p>
                <form class="newsletter-form mt-3">
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Su correo electrónico" aria-label="Email"
                            required>
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Línea divisoria -->
        <hr class="mt-4 mb-3 border-light opacity-10">

        <!-- Copyright -->
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0 text-light-50">&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. Todos los
                    derechos reservados.</p>
            </div>
            <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        <a href="<?php echo BASE_URL; ?>privacidad.php" class="text-light-50 hover-link">Política de
                            Privacidad</a>
                    </li>
                    <li class="list-inline-item ms-3">
                        <a href="<?php echo BASE_URL; ?>terminos.php" class="text-light-50 hover-link">Términos de
                            Uso</a>
                    </li>
                    <li class="list-inline-item ms-3">
                        <a href="<?php echo BASE_URL; ?>cookies.php" class="text-light-50 hover-link">Cookies</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<!-- Scripts comunes -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/header_footer.js?v=<?php echo $version; ?>"></script>

<!-- JS específico de la página -->
<?php if (file_exists(ASSETS_PATH . "/js/{$current_page}.js")): ?>
    <script src="<?php echo BASE_URL; ?>assets/js/<?php echo $current_page; ?>.js?v=<?php echo $version; ?>"></script>
<?php endif; ?>


<!-- Fin del body -->
</body>

<!-- Fin del documento HTML -->

</html>