<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Redirect if already logged in
if (is_logged_in()) {
    $redirect_url = has_role('employer') ? '../dashboard/employer.php' : '../dashboard/applicant.php';
    header('Location: ' . $redirect_url);
    exit;
}

$error = '';

if ($_POST) {
    $email = sanitize_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Både e-post og passord må fylles ut';
    } else {
        $user = get_user_by_email($email);
        
        if ($user && verify_password($password, $user['password'])) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_type'] = $user['type'];
            $_SESSION['user_name'] = $user['name'];
            
            $redirect_url = $user['type'] === 'employer' ? '../dashboard/employer.php' : '../dashboard/applicant.php';
            redirect($redirect_url, 'Velkommen tilbake!', 'success');
        } else {
            $error = 'Ugyldig e-post eller passord';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logg inn - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row min-vh-100">
            <!-- Left side - Login form -->
            <div class="col-md-6 d-flex align-items-center justify-content-center">
                <div class="w-100" style="max-width: 400px;">
                    <div class="text-center mb-4">
                        <h1 class="h3 mb-3 fw-normal">Logg inn</h1>
                        <p class="text-muted">Velkommen tilbake til <?php echo APP_NAME; ?></p>
                    </div>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-postadresse</label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   value="<?php echo htmlspecialchars($email ?? ''); ?>"
                                   required>
                            <div class="invalid-feedback">
                                Vennligst oppgi en gyldig e-postadresse.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Passord</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password" 
                                   name="password" 
                                   required>
                            <div class="invalid-feedback">
                                Passord er påkrevd.
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">
                                Husk meg
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Logg inn
                        </button>
                    </form>

                    <div class="text-center">
                        <p class="mb-2">
                            <a href="#" class="text-decoration-none">Glemt passord?</a>
                        </p>
                        <p>
                            Har du ikke konto? 
                            <a href="register.php" class="text-decoration-none">Registrer deg her</a>
                        </p>
                    </div>

                    <!-- Demo credentials -->
                    <div class="mt-4 p-3 bg-info bg-opacity-10 rounded">
                        <h6>Demo-kontoer:</h6>
                        <small class="text-muted">
                            <strong>Arbeidsgiver:</strong> employer@example.com<br>
                            <strong>Søker:</strong> applicant@example.com<br>
                            <strong>Passord:</strong> password
                        </small>
                    </div>
                </div>
            </div>

            <!-- Right side - Hero content -->
            <div class="col-md-6 hero-section d-none d-md-flex align-items-center justify-content-center">
                <div class="text-center text-white">
                    <h2 class="display-5 fw-bold mb-4">
                        Finn din drømmejobb som hjelpelærer
                    </h2>
                    <p class="lead mb-4">
                        Koble deg til de beste utdanningsinstitusjonene og bygg din karriere innen undervisning.
                    </p>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-users fa-2x me-3"></i>
                                <div>
                                    <h5>500+</h5>
                                    <small>Registrerte brukere</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-briefcase fa-2x me-3"></i>
                                <div>
                                    <h5>150+</h5>
                                    <small>Aktive stillinger</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation back to home -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom position-fixed w-100" style="top: 0; z-index: 1000;">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <i class="fas fa-graduation-cap me-2"></i>
                <?php echo APP_NAME; ?>
            </a>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>