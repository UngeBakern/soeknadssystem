<?php
require_once '../includes/autoload.php';

// Midlertidig: Kommentert ut autentisering for testing
// if (!is_logged_in() || !has_role('applicant')) {
//     redirect('../auth/login.php', 'Du må logge inn som søker for å se denne siden.', 'error');
// }

// Mock data for bruker
$user_name = $_SESSION['user_name'] ?? 'Demo Søker';

// Mock data for anbefalte stillinger
$recommended_jobs = [
    [
        'id' => 1,
        'title' => 'Hjelpelærer i matematikk',
        'employer_name' => 'Universitetet i Agder',
        'location' => 'Kristiansand',
        'created_at' => '2024-10-15'
    ],
    [
        'id' => 2,
        'title' => 'Norsk hjelpelærer',
        'employer_name' => 'Universitetet i Agder', 
        'location' => 'Grimstad',
        'created_at' => '2024-10-14'
    ],
    [
        'id' => 3,
        'title' => 'IT-hjelpelærer',
        'employer_name' => 'Universitetet i Agder',
        'location' => 'Kristiansand',
        'created_at' => '2024-10-13'
    ]
];

// Mock data for statistikk
$total_available_jobs = 5;
$my_applications = []; // Tom for nå
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Søker Dashboard - Hjelpelærer Søknadssystem</title>
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
                <a class="nav-link" href="../jobs/list.php">Alle stillinger</a>
                <a class="nav-link" href="applicant.php">Dashboard</a>
                <a class="nav-link" href="../profile/view.php">Profil</a>
                <a class="nav-link" href="../auth/logout.php">Logg ut</a>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <div class="mb-4">
                    <h1 class="h2 mb-2">Velkommen, <?php echo htmlspecialchars($user_name); ?>!</h1>
                    <p class="text-muted">Finn din neste hjelpelærerstilling</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="container pb-5">
        <div class="row">
            <!-- Quick Actions -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-rocket text-primary me-2"></i>
                            Hurtighandlinger
                        </h5>
                        <div class="d-grid gap-2">
                            <a href="../jobs/list.php" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>
                                Søk etter stillinger
                            </a>
                            <a href="../profile/view.php" class="btn btn-outline-primary">
                                <i class="fas fa-user me-2"></i>
                                Oppdater profil
                            </a>
                            <a href="../profile/upload.php" class="btn btn-outline-secondary">
                                <i class="fas fa-file-alt me-2"></i>
                                Last opp dokumenter
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4">
                            <i class="fas fa-chart-line text-primary me-2"></i>
                            Min aktivitet
                        </h5>
                        <div class="row text-center">
                            <div class="col-md-3 mb-3">
                                <div class="p-3 bg-primary bg-opacity-10 rounded">
                                    <h3 class="text-primary mb-1"><?php echo $total_available_jobs; ?></h3>
                                    <small class="text-muted">Tilgjengelige stillinger</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="p-3 bg-success bg-opacity-10 rounded">
                                    <h3 class="text-success mb-1"><?php echo count($my_applications); ?></h3>
                                    <small class="text-muted">Mine søknader</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="p-3 bg-warning bg-opacity-10 rounded">
                                    <h3 class="text-warning mb-1">0</h3>
                                    <small class="text-muted">Under vurdering</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="p-3 bg-info bg-opacity-10 rounded">
                                    <h3 class="text-info mb-1">0</h3>
                                    <small class="text-muted">Favoritter</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Applications -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4">
                            <i class="fas fa-paper-plane text-primary me-2"></i>
                            Mine søknader
                        </h5>

                        <?php if (!empty($my_applications)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($my_applications as $application): ?>
                                <div class="list-group-item border-0 px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1"><?php echo htmlspecialchars($application['job_title']); ?></h6>
                                            <p class="mb-1 text-muted small"><?php echo htmlspecialchars($application['employer']); ?></p>
                                            <small class="text-muted">Søkt <?php echo date('d.m.Y', strtotime($application['applied_at'])); ?></small>
                                        </div>
                                        <span class="badge bg-warning">Under vurdering</span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-paper-plane fa-2x text-muted mb-3"></i>
                                <h6>Ingen søknader ennå</h6>
                                <p class="text-muted mb-3">Du har ikke søkt på noen stillinger ennå.</p>
                                <a href="../jobs/list.php" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-search me-1"></i>
                                    Utforsk stillinger
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Recommended Jobs -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4">
                            <i class="fas fa-star text-primary me-2"></i>
                            Anbefalte stillinger
                        </h5>

                        <?php if (!empty($recommended_jobs)): ?>
                            <?php foreach ($recommended_jobs as $job): ?>
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="me-3">
                                        <div class="bg-primary bg-opacity-10 rounded p-2">
                                            <img src="../uialogo.jpeg" 
                                                 alt="Logo" 
                                                 class="img-fluid" 
                                                 style="width: 30px; height: 30px; object-fit: contain;">
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1"><?php echo htmlspecialchars($job['title']); ?></h6>
                                        <p class="text-muted small mb-2"><?php echo htmlspecialchars($job['employer_name']); ?></p>
                                        <p class="text-muted small mb-2">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            <?php echo htmlspecialchars($job['location']); ?>
                                        </p>
                                        <div class="d-flex gap-2">
                                            <a href="../jobs/view.php?id=<?php echo $job['id']; ?>" 
                                               class="btn btn-outline-primary btn-sm">
                                                Se detaljer
                                            </a>
                                            <a href="../jobs/apply.php?id=<?php echo $job['id']; ?>" 
                                               class="btn btn-primary btn-sm">
                                                Søk nå
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            
                            <div class="text-center mt-3">
                                <a href="../jobs/list.php" class="btn btn-outline-primary btn-sm">
                                    Se alle stillinger
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-briefcase fa-2x text-muted mb-3"></i>
                                <h6>Ingen stillinger tilgjengelig</h6>
                                <p class="text-muted">Kom tilbake senere for nye muligheter.</p>
                            </div>
                        <?php endif; ?>
                    </div>
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
