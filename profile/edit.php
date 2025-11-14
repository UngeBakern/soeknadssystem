<?php
require_once '../includes/autoload.php';


/*
 * Rediger brukerprofil 
 */

// Sjekk om bruker er innlogget
auth_check();

// Hent bruker-ID fra session
$user_id = $_SESSION['user_id'];

// Henter bruker fra databasen
$user = User::findById($user_id);

if (!$user) {
    redirect('../auth/logout.php', 'Bruker ikke funnet. Logg inn på nytt', 'danger');
}

$user_type = $user['role'] ?? 'applicant';
$type_label = $user_type === 'employer' ? 'Arbeidsgiver' : 'Søker';

// HÅNDTER POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name       = sanitize_input($_POST['name']       ?? '');
    $email      = sanitize_input($_POST['email']      ?? '');
    $birthdate  = sanitize_input($_POST['birthdate']  ?? '');
    $phone      = sanitize_input($_POST['phone']      ?? '');
    $address    = sanitize_input($_POST['address']    ?? '');

    if (!validate_required($name) || !validate_required($email)) {

        show_error('Vennligst fyll ut både navn og e-post.');

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        show_error('Epost-adressen er ikke gyldig.');
    
    } else {
        
        $updated_user = [
            'name'      => $name,
            'email'     => $email, 
            'birthdate' => $birthdate, 
            'phone'     => $phone, 
            'address'   => $address

        ];

        if (User::update($user_id, $updated_user)) {

            $_SESSION['user_name']  = $name;
            $_SESSION['user_email'] = $email;

            redirect('view.php', 'Profilen din er oppdatert!', 'success');

        } else { 

            show_error('Det oppstod en feil under oppdatering av profilen. Vennligst prøv igjen.');
        }
    }

        $user = array_merge($user, [
            'name'      => $name,
            'email'     => $email, 
            'birthdate' => $birthdate, 
            'phone'     => $phone, 
            'address'   => $address
        ]);
    }


$page_title = 'Rediger profil';
$body_class = 'bg-light';

include_once '../includes/header.php';
?>


    <!-- Page Header -->
    <div class="container py-5">
        <?php render_flash_messages(); ?>
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="mb-4 text-center">
                    <h1 class="h2 mb-2">Rediger profil</h1>
                    <p class="text-muted">Oppdater din brukerinfo</p>
                </div>
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="name" class="form-label">Navn</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">E-post</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="birthdate" class="form-label">Fødselsdato</label>
                                <input type="text" class="form-control" id="birthdate" name="birthdate" value="<?php echo htmlspecialchars($user['birthdate']); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Telefon</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Adresse</label>
                                <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>">
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

<?php include_once '../includes/footer.php'; ?>
