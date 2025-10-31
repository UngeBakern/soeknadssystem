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
    <title>Min profil - <?php echo APP_NAME; ?></title>
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
                    <h1 class="h2 mb-2">Min profil</h1>
                    <p class="text-muted">Her finner du din brukerinfo</p>
                    <img src="https://ui-avatars.com/api/?name=Demo+Bruker&background=ececec&color=333&size=256" alt="Profilbilde" class="profile-avatar mb-3 shadow-sm rounded-circle">
                </div>
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <ul class="list-group list-group-flush mb-4">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Navn</span>
                                <strong><?php echo htmlspecialchars($user_name); ?></strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>E-post</span>
                                <strong><?php echo htmlspecialchars($user_email); ?></strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Fødselsdato</span>
                                <strong><?php echo htmlspecialchars($user_birthdate); ?></strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Telefon</span>
                                <strong><?php echo htmlspecialchars($user_phone); ?></strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Adresse</span>
                                <strong><?php echo htmlspecialchars($user_address); ?></strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Brukertype</span>
                                <span class="badge bg-primary"><?php echo $type_label; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Varslinger:</span>
                                <form method="post" action="toggle_notifications.php" class="m-0 d-flex gap-3 align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="notify_email" id="notify_email" value="1" <?php echo (isset($_SESSION['notify_email']) && $_SESSION['notify_email']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="notify_email">
                                            <i class="fas fa-envelope me-1"></i> E-post
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="notify_sms" id="notify_sms" value="1" <?php echo (isset($_SESSION['notify_sms']) && $_SESSION['notify_sms']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="notify_sms">
                                            <i class="fas fa-mobile-alt me-1"></i> Mobil
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                                        Lagre
                                    </button>
                                </form>
                            </li>
                        </ul>
                        <div class="d-grid gap-2">
                            <a href="edit.php" class="btn btn-outline-primary">
                                <i class="fas fa-edit me-2"></i>
                                Rediger profil
                            </a>
                            <button type="button" class="btn btn-outline-danger" onclick="alert('Er du sikker på at du vil slette kontoen? Dette kan ikke angres.')">
                                <i class="fas fa-trash me-2"></i>
                                Slett konto
                            </button>
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
    <script src="../assets/js/main.js"></script>
</body>
</html>
