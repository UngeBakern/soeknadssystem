<?php
require_once '../includes/autoload.php';


/*
 * Rediger brukerprofil 
 */

// Mockup: Hent brukerdata fra session
$user_name = $_SESSION['user_name'] ?? 'Demo Bruker';
$user_email = $_SESSION['user_email'] ?? 'demo@example.com';
$user_type = $_SESSION['user_type'] ?? 'applicant';
$type_label = $user_type === 'employer' ? 'Arbeidsgiver' : 'Søker';
$user_birthdate = $_SESSION['user_birthdate'] ?? '01.01.2000';
$user_phone = $_SESSION['user_phone'] ?? '+47 400 00 000';
$user_address = $_SESSION['user_address'] ?? 'Eksempelveien 1, 1234 Oslo';

include_once '../includes/header.php';
?>


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

<?php include_once '../includes/footer.php'; ?>
