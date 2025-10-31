<?php
require_once '../includes/autoload.php';

// Midlertidig: Kommentert ut autentisering for testing
// if (!is_logged_in() || !has_role('employer')) {
//     redirect('../auth/login.php', 'Du må logge inn som arbeidsgiver for å se denne siden.', 'error');
// }

// Hent brukerdata (mock data for testing)
$user_name = $_SESSION['user_name'] ?? 'Demo Arbeidsgiver';

// Hent jobber opprettet av denne arbeidsgiveren (mock data for nå)
$job = new Job();
$my_jobs = $job->getAll(); // I en ekte app ville vi filtrert på employer_id
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arbeidsgiver Dashboard - Hjelpelærer Søknadssystem</title>
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
                <a class="nav-link" href="employer.php">Dashboard</a>
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
                    <p class="text-muted">Administrer dine stillingsannonser og søknader</p>
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
                            <i class="fas fa-plus-circle text-primary me-2"></i>
                            Hurtighandlinger
                        </h5>
                        <div class="d-grid gap-2">
                            <a href="../jobs/create.php" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                Opprett ny stillingsannonse
                            </a>
                            <a href="../applications/list.php" class="btn btn-outline-primary">
                                <i class="fas fa-inbox me-2"></i>
                                Se alle søknader
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
                            <i class="fas fa-chart-bar text-primary me-2"></i>
                            Oversikt
                        </h5>
                        <div class="row text-center">
                            <div class="col-md-3 mb-3">
                                <div class="p-3 bg-primary bg-opacity-10 rounded">
                                    <h3 class="text-primary mb-1"><?php echo count($my_jobs); ?></h3>
                                    <small class="text-muted">Aktive stillinger</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="p-3 bg-success bg-opacity-10 rounded">
                                    <h3 class="text-success mb-1">0</h3>
                                    <small class="text-muted">Nye søknader</small>
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
                                    <small class="text-muted">Totale visninger</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Jobs -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-briefcase text-primary me-2"></i>
                                Mine stillingsannonser
                            </h5>
                            <a href="../jobs/create.php" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-plus me-1"></i>
                                Ny stillingsannonse
                            </a>
                        </div>

                        <?php if (!empty($my_jobs)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Stillingstittel</th>
                                            <th>Lokasjon</th>
                                            <th>Opprettet</th>
                                            <th>Status</th>
                                            <th>Søknader</th>
                                            <th>Handlinger</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($my_jobs as $job): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($job['title']); ?></strong>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?php echo htmlspecialchars($job['location'] ?? 'Ikke oppgitt'); ?>
                                                </small>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?php echo date('d.m.Y', strtotime($job['created_at'])); ?>
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Aktiv</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">0 søknader</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="../jobs/view.php?id=<?php echo $job['id']; ?>" 
                                                       class="btn btn-outline-primary btn-sm" 
                                                       title="Se detaljer">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="../jobs/edit.php?id=<?php echo $job['id']; ?>" 
                                                       class="btn btn-outline-secondary btn-sm" 
                                                       title="Rediger">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="../applications/list.php?job_id=<?php echo $job['id']; ?>" 
                                                       class="btn btn-outline-info btn-sm" 
                                                       title="Se søknader">
                                                        <i class="fas fa-users"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                                <h6>Ingen stillingsannonser ennå</h6>
                                <p class="text-muted mb-4">Opprett din første stillingsannonse for å komme i gang.</p>
                                <a href="../jobs/create.php" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>
                                    Opprett stillingsannonse
                                </a>
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
