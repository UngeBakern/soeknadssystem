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
$success = '';
$user_type = $_GET['type'] ?? 'applicant'; // Default to applicant

if ($_POST) {
    $name = sanitize_input($_POST['name'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $user_type = sanitize_input($_POST['user_type'] ?? 'applicant');
    
    // Validation
    if (empty($name) || empty($email) || empty($password)) {
        $error = 'Alle felt må fylles ut';
    } elseif (!validate_email($email)) {
        $error = 'Ugyldig e-postadresse';
    } elseif (strlen($password) < 6) {
        $error = 'Passord må være minst 6 tegn';
    } elseif ($password !== $confirm_password) {
        $error = 'Passordene stemmer ikke overens';
    } elseif (get_user_by_email($email)) {
        $error = 'E-postadressen er allerede registrert';
    } else {
        // Registration successful - in a real app, you'd save to database
        // For now, we'll simulate login by setting session data
        $_SESSION['user_id'] = uniqid(); // Generate temporary ID
        $_SESSION['user_type'] = $user_type;
        $_SESSION['user_name'] = $name;
        
        // Redirect to appropriate dashboard
        $redirect_url = $user_type === 'employer' ? '../dashboard/employer.php' : '../dashboard/applicant.php';
        redirect($redirect_url, 'Velkommen! Din konto er opprettet.', 'success');
    }
}
?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrer deg - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container-fluid d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="row justify-content-center w-100">
        <div class="col-md-5 col-lg-3 col-xl-3 my-5">
                <div class="card shadow">
            <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h1 class="h3 mb-3 fw-normal">Registrer deg</h1>
                            <p class="text-muted">Opprett din konto hos <?php echo APP_NAME; ?></p>
                        </div>

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo htmlspecialchars($success); ?>
                                <a href="login.php" class="alert-link">Klikk her for å logge inn</a>
                            </div>
                        <?php endif; ?>

                        <form method="POST" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="name" class="form-label">Fullt navn</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="name" 
                                       name="name" 
                                       value="<?php echo htmlspecialchars($name ?? ''); ?>"
                                       required>
                                <div class="invalid-feedback">
                                    Vennligst oppgi ditt fulle navn.
                                </div>
                            </div>

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
                                <label for="user_type" class="form-label">Jeg er en</label>
                                <select class="form-select" id="user_type" name="user_type" required>
                                    <option value="applicant" <?php echo $user_type === 'applicant' ? 'selected' : ''; ?>>
                                        Jobbsøker (Student/Hjelpelærer)
                                    </option>
                                    <option value="employer" <?php echo $user_type === 'employer' ? 'selected' : ''; ?>>
                                        Arbeidsgiver (Skole/Institusjon)
                                    </option>
                                </select>
                                <div class="invalid-feedback">
                                    Vennligst velg brukertype.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Passord</label>
                                <!-- Fullt navn og e-postadresse (kun én gang) -->

                                <div class="mb-3">
                                    <label for="password" class="form-label">Passord</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <div class="invalid-feedback">
                                        Vennligst oppgi et passord.
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Bekreft passord</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    <div class="invalid-feedback">
                                        Passordene stemmer ikke overens.
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Brukertype</label>
                                    <select class="form-select" name="user_type" required>
                                        <option value="applicant" <?php echo ($user_type === 'applicant') ? 'selected' : ''; ?>>Søker</option>
                                        <option value="employer" <?php echo ($user_type === 'employer') ? 'selected' : ''; ?>>Arbeidsgiver</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Vennligst velg brukertype.
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label" style="font-size: 0.95rem;">Varslingsvalg</label>
                                    <div class="d-flex gap-2" style="font-size: 0.95rem;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="notify_email" name="notify_email" style="transform: scale(0.9);">
                                            <label class="form-check-label" for="notify_email">E-post</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="notify_sms" name="notify_sms" style="transform: scale(0.9);">
                                            <label class="form-check-label" for="notify_sms">SMS</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="invalid-feedback">
                                    Du må godta vilkårene for å registrere deg.
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                <i class="fas fa-user-plus me-2"></i>
                                Opprett konto
                            </button>
                        </form>

                        <div class="text-center">
                            <p>
                                Har du allerede en konto? 
                                <a href="login.php" class="text-decoration-none">Logg inn her</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="../assets/js/main.js"></script>
    
    <script>
    // Password confirmation validation
    document.getElementById('confirm_password').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmPassword = this.value;
        
        if (password !== confirmPassword) {
            this.setCustomValidity('Passordene stemmer ikke overens');
        } else {
            this.setCustomValidity('');
        }
    });
    </script>
</body>
</html>
