<?php
require_once '../includes/autoload.php';



// Redirect if already logged in
if (is_logged_in()) {
    $redirect_url = has_role('employer') 
    ? '../dashboard/employer.php' 
    : '../dashboard/applicant.php';
    redirect($redirect_url, 'Du er allerede logget inn.', 'info');
}

$role = $_GET['type'] ?? 'applicant';

// Behold verdier ved feil
$name = '';
$email = '';
$phone = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name             = sanitize_input($_POST['name']      ?? '');
    $email            = sanitize_input($_POST['email']     ?? '');
    $password         = $_POST['password']                 ?? '';
    $confirm_password = $_POST['confirm_password']         ?? '';
    $role             = sanitize_input($_POST['role'] ?? 'applicant');
    $phone            = sanitize_input($_POST['phone']     ?? '');

    // Validation
    if (!validate_required($name) || !validate_required($email) || !validate_required($password)) {

        show_error('Navn, e-post og passord må fylles ut');
        
    } elseif (!Validator::validateEmail($email)) {

        show_error('Ugyldig e-postadresse');

    } elseif (strlen($password) < 6) {

        show_error('Passord må være minst 6 tegn');

    } elseif ($password !== $confirm_password) {

        show_error('Passordene stemmer ikke overens.');

    } elseif (User::findByEmail($email)) {

        show_error('E-postadressen er allerede registrert.');

    } else {

        // Opprett ny bruker 
        $user_id = User::create([
            'name'          => $name,
            'email'         => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role'          => $role,
            'phone'         => $phone
        ]);

        if ($user_id) {

            $_SESSION['user_id']   = $user_id;
            $_SESSION['role']      = $role;
            $_SESSION['user_name'] = $name;
            
            // Redirect til dashboard
            $redirect_url = $role === 'employer' 
            ? '../dashboard/employer.php' 
            : '../dashboard/applicant.php';

            redirect($redirect_url, 'Velkommen! Din konto er opprettet.', 'success');

        } else { 

            show_error('Noe gikk galt ved registrering. Prøv igjen.');
        }
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

                        <?php render_flash_messages(); ?>

                        <form method="POST" action="">
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
                                <label for="role" class="form-label">Jeg er en</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="applicant" <?php echo $role === 'applicant' ? 'selected' : ''; ?>>
                                        Jobbsøker (Student/Hjelpelærer)
                                    </option>
                                    <option value="employer" <?php echo $role === 'employer' ? 'selected' : ''; ?>>
                                        Arbeidsgiver (Skole/Institusjon)
                                    </option>
                                </select>
                                <div class="invalid-feedback">
                                    Vennligst velg brukertype.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Telefonnummer (valgfritt)</label>
                                <input type="tel" 
                                       class="form-control" 
                                       id="phone" 
                                       name="phone" 
                                       value="<?php echo htmlspecialchars($phone); ?>"
                                       placeholder="12345678">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Passord</label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password" 
                                       name="password" 
                                       required>
                                <small class="text-muted">Minimum 6 tegn</small>
                                <div class="invalid-feedback">
                                    Vennligst oppgi et passord.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Bekreft passord</label>
                                <input type="password" 
                                       class="form-control" 
                                       id="confirm_password" 
                                       name="confirm_password" 
                                       required>
                                <div class="invalid-feedback">
                                    Passordene stemmer ikke overens.
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
    <!----<script src="../assets/js/main.js"></script>---->
    
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
