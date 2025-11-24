<?php
require_once '../includes/autoload.php';

/*
 * Rediger en eksisterende stilling
 */

// Sjekk at bruker er innlogget
auth_check(['employer', 'admin']);

// Hent jobb-ID fra URL
$job_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$job_id) {
    redirect('list.php', 'Ingen stilling valgt.', 'danger');
}

// Hent stillingen fra database
$job = Job::findById($job_id);

if (!$job) {
    redirect('list.php', 'Stillingen finnes ikke.', 'danger');
}

// Sjekk at bruker eier stillingen (eller er admin)
if ($job['employer_id'] != Auth::id() && !has_role('admin')) {
    redirect('view.php?id=' . $job_id, 'Du har ikke tilgang til å redigere denne stillingen.', 'danger');
}

// HÅNDTER POST-REQUEST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    csrf_check();

    $title          = Validator::sanitize($_POST['title']            ?? '');
    $location       = Validator::sanitize($_POST['location']         ?? '');
    $job_type       = Validator::sanitize($_POST['job_type']         ?? '');
    $description    = Validator::sanitize($_POST['description']      ?? '');
    $requirements   = Validator::sanitize($_POST['requirements']     ?? '');
    $salary         = Validator::sanitize($_POST['salary']           ?? '');
    $deadline       = Validator::sanitize($_POST['deadline']         ?? '');
    $status         = Validator::sanitize($_POST['status']           ?? 'active');
    $subject        = Validator::sanitize($_POST['subject']          ?? '');
    $education_level= Validator::sanitize($_POST['education_level']  ?? '');

    // Validering
    if (!Validator::required($title)        || 
        !Validator::required($location)     || 
        !Validator::required($job_type)     || 
        !Validator::required($description)  || 
        !Validator::required($requirements) || 
        !Validator::required($deadline)) {

        show_error('Vennligst fyll ut alle obligatoriske felt.');

    } elseif (strlen($description) < 50) {

        show_error('Stillingsbeskrivelsen må være minst 50 tegn.');

    } elseif (!Validator::validateDate($deadline)) {

        show_error('Ugyldig datoformat for søknadsfrist.');

    } elseif (strtotime($deadline) < time()) {

        show_error('Søknadsfristen må være en fremtidig dato.');

    } else {

        // Ingen feil, oppdater stilling
        $updated_job = [
            'title'          => $title,
            'location'       => $location,
            'job_type'       => $job_type,
            'description'    => $description,
            'requirements'   => $requirements,
            'salary'         => $salary,
            'deadline'       => $deadline,
            'status'         => $status,
            'subject'        => $subject,
            'education_level'=> $education_level
        ];

        if (Job::update($job_id, $updated_job)) {
            redirect('view.php?id=' . $job_id, 'Stillingen er oppdatert!', 'success');
        } else {
            show_error('Det oppstod en feil under oppdateringen. Vennligst prøv igjen.');
        }
    }


        $job = array_merge($job, [
            'title'          => $title,
            'location'       => $location,
            'job_type'       => $job_type,
            'description'    => $description,
            'requirements'   => $requirements,
            'salary'         => $salary,
            'deadline'       => $deadline,
            'status'         => $status,
            'subject'        => $subject,
            'education_level'=> $education_level
        ]);

}

$page_title = 'Rediger stilling';
$body_class = 'dashboard-page';

require_once '../includes/header.php';
?>

<div class="container py-5">
    <?php render_flash_messages(); ?>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mb-4">
                <h1 class="h2 mb-2">Rediger stilling</h1>
                <p class="text-muted">Oppdater informasjonen for stillingen</p>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="" novalidate>
                        <?php echo csrf_field(); ?>
                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Stillingstittel *</label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   value="<?php echo htmlspecialchars($job['title'] ?? ''); ?>" required>
                        </div>

                        <!-- Company -->
                            <div class="mb-3">
                            <label class="form-label">Bedrift/Organisasjon</label>
                            <input type="text" 
                                   class="form-control bg-light" 
                                   value="<?php echo htmlspecialchars($job['company'] ?? ''); ?>" 
                                   readonly 
                                   tabindex="-1">
                            <small class="text-muted">
                                <i class="fas fa-lock me-1"></i>
                                Kan ikke endres
                            </small>
                        </div>

                        <!-- Location & Type -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Lokasjon *</label>
                                <input type="text" class="form-control" id="location" name="location" 
                                       value="<?php echo htmlspecialchars($job['location'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="job_type" class="form-label">Stillingstype *</label>
                                <select class="form-select" id="job_type" name="job_type" required>
                                    <option value="">Velg stillingstype</option>
                                    <option value="Heltid" <?php echo (($job['job_type'] ?? '') === 'Heltid') ? 'selected' : ''; ?>>Heltid</option>
                                    <option value="Deltid" <?php echo (($job['job_type'] ?? '') === 'Deltid') ? 'selected' : ''; ?>>Deltid</option>
                                    <option value="Ekstrahjelp" <?php echo (($job['job_type'] ?? '') === 'Ekstrahjelp') ? 'selected' : ''; ?>>Ekstrahjelp</option>
                                    <option value="Vikariat" <?php echo (($job['job_type'] ?? '') === 'Vikariat') ? 'selected' : ''; ?>>Vikariat</option>
                                </select>
                            </div>
                        </div>

                        <!-- Salary & Deadline -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="salary" class="form-label">Lønn</label>
                                <input type="text" class="form-control" id="salary" name="salary" 
                                       value="<?php echo htmlspecialchars($job['salary'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="deadline" class="form-label">Søknadsfrist *</label>
                                <input type="date" class="form-control" id="deadline" name="deadline" 
                                       value="<?php echo htmlspecialchars($job['deadline'] ?? ''); ?>"
                                       min="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Stillingsbeskrivelse *</label>
                            <textarea class="form-control" id="description" name="description" rows="5" required><?php echo htmlspecialchars($job['description'] ?? ''); ?></textarea>
                            <div class="form-text">Minst 50 tegn</div>
                        </div>

                        <!-- Requirements -->
                        <div class="mb-3">
                            <label for="requirements" class="form-label">Krav og kvalifikasjoner *</label>
                            <textarea class="form-control" id="requirements" name="requirements" rows="4" required><?php echo htmlspecialchars($job['requirements'] ?? ''); ?></textarea>
                        </div>

                        <!-- Subject & Education level -->
                        <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="subject" class="form-label">Fag/område</label>
                            <input type="text" class="form-control" id="subject" name="subject"value="<?php echo htmlspecialchars($job['subject'] ?? ''); ?>"></div>
                            <div class="col-md-6 mb-3">
                            <label for="education_level" class="form-label">Utdanningsnivå</label>
                        <input type="text" class="form-control" id="education_level" name="education_level"
                        value="<?php echo htmlspecialchars($job['education_level'] ?? ''); ?>">
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="active" <?php echo (($job['status'] ?? 'active') === 'active') ? 'selected' : ''; ?>>Aktiv</option>
                                <option value="inactive" <?php echo (($job['status'] ?? '') === 'inactive') ? 'selected' : ''; ?>>Inaktiv</option>
                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="view.php?id=<?php echo $job_id; ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Avbryt
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Oppdater stilling
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>