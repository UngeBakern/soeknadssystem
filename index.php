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
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-graduation-cap me-2"></i>
                Hjelpelærer Søknadssystem
            </a>
            
            <div class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a class="nav-link" href="dashboard/<?php echo $_SESSION['user_type']; ?>.php">
                        Dashboard
                    </a>
                    <a class="nav-link" href="profile/view.php">Profil</a>
                    <a class="nav-link" href="auth/logout.php">Logg ut</a>
                <?php else: ?>
                    <a class="nav-link" href="auth/login.php">Logg inn</a>
                    <a class="nav-link" href="auth/register.php">Registrer deg</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section bg-light py-5">
        <div class="container text-center">
            <h1 class="display-4 mb-3">Finn din neste hjelpelærer</h1>
            <p class="lead mb-4">
                Plattformen som kobler sammen kvalifiserte hjelpelærere med utdanningsinstitusjoner
            </p>
            
            <?php if (!isset($_SESSION['user_id'])): ?>
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <a href="auth/register.php?type=applicant" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-user-plus me-2"></i>
                                    Jeg søker jobb
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a href="auth/register.php?type=employer" class="btn btn-outline-primary btn-lg w-100">
                                    <i class="fas fa-building me-2"></i>
                                    Jeg har ledige stillinger
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <a href="jobs/list.php" class="btn btn-primary btn-lg">
                            <i class="fas fa-search me-2"></i>
                            Se ledige stillinger
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Features Section -->
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="feature-icon bg-primary text-white rounded-circle mx-auto mb-3">
                            <i class="fas fa-users"></i>
                        </div>
                        <h5 class="card-title">For søkere</h5>
                        <p class="card-text">
                            Opprett profil, last opp CV og søk på interessante hjelpelærerstillinger.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="feature-icon bg-success text-white rounded-circle mx-auto mb-3">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <h5 class="card-title">For arbeidsgivere</h5>
                        <p class="card-text">
                            Utlys stillinger, motta søknader og finn de beste kandidatene til ditt team.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="feature-icon bg-info text-white rounded-circle mx-auto mb-3">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h5 class="card-title">Enkel matching</h5>
                        <p class="card-text">
                            Vårt system gjør det enkelt å finne den perfekte matchen mellom jobb og kandidat.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Jobs Section -->
    <div class="bg-light py-5">
        <div class="container">
            <h2 class="text-center mb-5">Nyeste stillingsutlysninger</h2>
            
            <?php
            // Load recent jobs (will implement this later)
            $recent_jobs = []; // Placeholder
            ?>
            
            <?php if (empty($recent_jobs)): ?>
                <div class="text-center">
                    <p class="lead text-muted">Ingen stillinger tilgjengelig for øyeblikket.</p>
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'employer'): ?>
                        <a href="jobs/create.php" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            Opprett første stilling
                        </a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="row">
                    <!-- Job listings will be displayed here -->
                </div>
                
                <div class="text-center mt-4">
                    <a href="jobs/list.php" class="btn btn-outline-primary">
                        Se alle stillinger
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h6>Hjelpelærer Søknadssystem</h6>
                    <p class="mb-0">Kobler sammen hjelpelærere og utdanningsinstitusjoner.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">
                        <a href="#" class="text-light me-3">Om oss</a>
                        <a href="#" class="text-light me-3">Kontakt</a>
                        <a href="#" class="text-light">Support</a>
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