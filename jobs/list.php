<?php
// Inkluder autoload for å laste klasser
require_once '../includes/autoload.php';

// Opprett Job instans og hent alle stillinger
$job = new Job();
$jobs = $job->getAll();
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ledige stillinger - Hjelpelærer Søknadssystem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <i class="fas fa-graduation-cap me-2"></i>
            </a>
            
            <div class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a class="nav-link" href="list.php">Alle stillinger</a>
                    <a class="nav-link" href="../dashboard/<?php echo $_SESSION['user_type']; ?>.php">Dashboard</a>
                    <a class="nav-link" href="../profile/view.php">Profil</a>
                    <a class="nav-link" href="../auth/logout.php">Logg ut</a>
                <?php else: ?>
                    <a class="nav-link" href="list.php">Alle stillinger</a>
                    <a class="nav-link" href="../auth/login.php">Logg inn</a>
                    <a class="nav-link" href="../auth/register.php">Registrer deg</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <h1 class="h2 mb-3">Ledige stillinger</h1>
                    <p class="text-muted">
                        <?php echo count($jobs); ?> ledige hjelpelærerstillinger tilgjengelig
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Job Listings -->
    <div class="container pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row">
                    <?php if (!empty($jobs)): ?>
                        <?php foreach ($jobs as $job): ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm position-relative">
                                <!-- Logo/Organization Section -->
                                <div class="card-header bg-white border-0 p-4 text-center">
                                    <div class="organization-logo mb-3">
                                        <div class="d-inline-flex align-items-center justify-content-center organization-logo-size">
                                            <img src="../uialogo.jpeg" 
                                                 alt="<?php echo htmlspecialchars($job['employer_name'] ?? 'Logo'); ?>" 
                                                 class="img-fluid">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Job Details Section -->
                                <div class="card-body p-4">
                                    <!-- Date and Location -->
                                    <div class="text-muted small mb-2">
                                        <?php echo date('d. M Y', strtotime($job['created_at'])); ?> | 
                                        <?php echo isset($job['location']) ? htmlspecialchars($job['location']) : 'Ikke oppgitt'; ?>
                                    </div>
                                    
                                    <!-- Job Title -->
                                    <h5 class="card-title mb-3 fw-bold">
                                        <?php echo htmlspecialchars($job['title'] ?? 'Ingen tittel'); ?>
                                    </h5>
                                    
                                    <!-- Company Name -->
                                    <div class="job-info mb-3">
                                        <div class="text-muted small mb-1">
                                            <strong><?php echo htmlspecialchars($job['employer_name'] ?? 'Ukjent arbeidsgiver'); ?></strong>
                                        </div>
                                        <div class="text-muted small">
                                            1 stilling
                                        </div>
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div class="d-grid gap-2">
                                        <a href="view.php?id=<?php echo $job['id']; ?>" class="btn btn-outline-primary btn-sm">
                                            Se detaljer
                                        </a>
                                        <?php if (isset($_SESSION['user_id'])): ?>
                                            <a href="apply.php?id=<?php echo $job['id']; ?>" class="btn btn-primary btn-sm">
                                                Søk på stillingen
                                            </a>
                                        <?php else: ?>
                                            <a href="../auth/login.php" class="btn btn-primary btn-sm">
                                                Logg inn for å søke
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- Favorite Heart Icon -->
                                <div class="position-absolute top-0 end-0 p-3">
                                    <button class="btn btn-link p-0 text-muted favorite-btn" title="Legg til i favoritter">
                                        <i class="far fa-heart"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                                <h4>Ingen stillinger funnet</h4>
                                <p class="text-muted mb-4">Det er for øyeblikket ingen ledige stillinger.</p>
                                <a href="../index.php" class="btn btn-outline-primary">
                                    Tilbake til forsiden
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-top py-4 mt-5">
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
    <script src="../assets/js/main.js"></script>
</body>
</html>
