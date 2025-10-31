<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hjelpelærer Søknadssystem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    <!-- Navigation er fjernet for ren forside -->

    <!-- Hero Section -->
    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
            <div class="col-lg-8">
                <div class="text-center">
                    <h1 class="display-5 mb-4">Hjelpelærer Søknadssystem</h1>
                    <p class="lead text-muted mb-5">
                        Enkelt og effektivt system for å koble sammen hjelpelærere med utdanningsinstitusjoner
                    </p>
                    
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <div class="row justify-content-center g-3">
                            <div class="col-md-4">
                                <a href="jobs/list.php" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-search me-2"></i>
                                    Se ledige stillinger
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="auth/register.php" class="btn btn-outline-primary btn-lg w-100">
                                    <i class="fas fa-user-plus me-2"></i>
                                    Registrer deg
                                </a>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <p class="text-muted small">
                                Allerede bruker? <a href="auth/login.php" class="text-decoration-none">Logg inn her</a>
                            </p>
                        </div>
                    <?php else: ?>
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <a href="jobs/list.php" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-search me-2"></i>
                                    Se ledige stillinger
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Simple Features Section -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row text-center">
                    <div class="col-md-4 mb-4">
                        <div class="py-4">
                            <i class="fas fa-users text-muted mb-3" style="font-size: 2.5rem;"></i>
                            <h5>For jobbsøkere</h5>
                            <p class="text-muted">
                                Opprett profil og søk på hjelpelærerstillinger ved skoler og utdanningsinstitusjoner.
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-4">
                        <div class="py-4">
                            <i class="fas fa-building text-muted mb-3" style="font-size: 2.5rem;"></i>
                            <h5>For arbeidsgivere</h5>
                            <p class="text-muted">
                                Utlys stillinger og finn kvalifiserte hjelpelærere til din institusjon.
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-4">
                        <div class="py-4">
                            <i class="fas fa-handshake text-muted mb-3" style="font-size: 2.5rem;"></i>
                            <h5>Enkel matching</h5>
                            <p class="text-muted">
                                Effektiv kobling mellom hjelpelærere og utdanningsinstitusjoner.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
        <footer class="bg-white border-top py-4 mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted">Hjelpelærer Søknadssystem</h6>
                    <p class="text-muted small mb-0">Kobler sammen hjelpelærere og utdanningsinstitusjoner.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted small mb-0">
                        <a href="#" class="text-muted me-3 text-decoration-none">Om oss</a>
                        <a href="#" class="text-muted me-3 text-decoration-none">Kontakt</a>
                        <a href="#" class="text-muted text-decoration-none">Support</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>