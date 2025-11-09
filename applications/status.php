<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

/*
 * 
 * 
 * 
 *
 */

// Mockup: Hent brukerdata fra session
$user_name = $_SESSION['user_name'] ?? 'Demo Søker';

// Mockup: Søknader
$applications = [
    [
        'id' => 1,
        'job_title' => 'Hjelpelærer i matematikk',
        'employer' => 'Universitetet i Agder',
        'status' => 'Under behandling',
        'applied_at' => '2025-10-20'
    ],
    [
        'id' => 2,
        'job_title' => 'IT-hjelpelærer',
        'employer' => 'Universitetet i Agder',
        'status' => 'Avslått',
        'applied_at' => '2025-10-15'
    ],
    [
        'id' => 3,
        'job_title' => 'Norsk hjelpelærer',
        'employer' => 'Universitetet i Agder',
        'status' => 'Godkjent',
        'applied_at' => '2025-10-10'
    ]
];
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mine søknader - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    <!-- Dashboard Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <i class="fas fa-graduation-cap me-2"></i>
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="../jobs/list.php">Alle stillinger</a>
                <a class="nav-link" href="../dashboard/applicant.php">Dashboard</a>
                <a class="nav-link" href="../profile/view.php">Profil</a>
                <a class="nav-link" href="../auth/logout.php">Logg ut</a>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="mb-4 text-center">
                    <h1 class="h2 mb-2">Mine søknader</h1>
                    <p class="text-muted">Her ser du status på dine innsendte søknader</p>
                </div>
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Stilling</th>
                                    <th>Arbeidsgiver</th>
                                    <th>Sendt</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($applications as $app): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($app['job_title']); ?></td>
                                    <td><?php echo htmlspecialchars($app['employer']); ?></td>
                                    <td><?php echo htmlspecialchars($app['applied_at']); ?></td>
                                    <td>
                                        <?php if ($app['status'] === 'Godkjent'): ?>
                                            <span class="badge bg-success">Godkjent</span>
                                        <?php elseif ($app['status'] === 'Avslått'): ?>
                                            <span class="badge bg-danger">Avslått</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Under behandling</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="../jobs/view.php?id=<?php echo $app['id']; ?>" class="btn btn-outline-primary btn-sm">
                                            Se stilling
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
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
    <script src="../assets/js/main.js"></script>
</body>
</html>
