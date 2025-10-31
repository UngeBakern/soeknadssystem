<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Mockup: Hent brukerdata fra session
$user_name = $_SESSION['user_name'] ?? 'Demo Bruker';
$user_email = $_SESSION['user_email'] ?? 'demo@example.com';
$user_type = $_SESSION['user_type'] ?? 'applicant';
$type_label = $user_type === 'employer' ? 'Arbeidsgiver' : 'Søker';
$user_birthdate = $_SESSION['user_birthdate'] ?? '01.01.2000';
$user_phone = $_SESSION['user_phone'] ?? '+47 400 00 000';
$user_address = $_SESSION['user_address'] ?? 'Eksempelveien 1, 1234 Oslo';
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rediger profil - <?php echo APP_NAME; ?></title>
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
                <a class="nav-link" href="../dashboard/<?php echo $user_type; ?>.php">Dashboard</a>
                <a class="nav-link active" href="view.php">Profil</a>
                <a class="nav-link" href="../auth/logout.php">Logg ut</a>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="mb-4 text-center">
                    <h1 class="h2 mb-2">Rediger profil</h1>
                    <p class="text-muted">Oppdater din brukerinfo</p>
                </div>
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form method="post" action="save.php">
                            <div class="mb-3">
                                <label for="name" class="form-label">Navn</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user_name); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">E-post</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user_email); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="birthdate" class="form-label">Fødselsdato</label>
                                <input type="text" class="form-control" id="birthdate" name="birthdate" value="<?php echo htmlspecialchars($user_birthdate); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Telefon</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user_phone); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Adresse</label>
                                <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($user_address); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Brukertype</label>
                                <input type="text" class="form-control" value="<?php echo $type_label; ?>" disabled>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    Lagre endringer
                                </button>
                                <a href="view.php" class="btn btn-outline-secondary">
                                    Avbryt
                                </a>
                            </div>
                        </form>
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
