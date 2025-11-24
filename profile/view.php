<?php
require_once '../includes/autoload.php';


/*
 * Vis brukerprofil
 */

// Sjekk om bruker er innlogget
auth_check();

// Hent full brukerdata fra database
$user_id = Auth::id();

$user = User::findById($user_id);

if (!$user) {
    redirect('../auth/logout.php', 'Bruker ikke funnet. Logg inn på nytt.', 'danger');
}

// Beregn enkelte variabler for visning
$type_label         =   $user['role'] === 'employer' ? 'Arbeidsgiver' : 'Søker';
$user['phone']      =   $user['phone']      ?? 'Ikke oppgitt';
$user['birthdate']  =   $user['birthdate']  ?? 'Ikke oppgitt';
$user['address']    =   $user['address']    ?? 'Ikke oppgitt';

//Sidevariabler 
$page_title = 'Min profil';
$body_class = 'bg-light';

include_once '../includes/header.php';
?>



    <!-- Page Header -->
    <div class="container py-5">
        <?php render_flash_messages(); ?>
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
                                <strong><?php echo htmlspecialchars($user['name']); ?></strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>E-post</span>
                                <strong><?php echo htmlspecialchars($user['email']); ?></strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Fødselsdato</span>
                                <strong><?php echo htmlspecialchars($user['birthdate']); ?></strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Telefon</span>
                                <strong><?php echo htmlspecialchars($user['phone']); ?></strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Adresse</span>
                                <strong><?php echo htmlspecialchars($user['address']); ?></strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Brukertype</span>
                                <span class="badge bg-primary"><?php echo  htmlspecialchars($type_label); ?></span>
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

<?php include_once '../includes/footer.php'; ?>
