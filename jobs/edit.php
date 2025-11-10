<?php
require_once '../includes/autoload.php';


/*
 * 
 * 
 * 
 *
 */

// Sjekk at bruker er innlogget
if (!is_logged_in()) {
    redirect('../auth/login.php', 'Du må være innlogget for å redigere stillinger.', 'error');
}

// Sjekk at bruker er arbeidsgiver eller admin
if (!has_role('employer') && !has_role('admin')) {
    redirect('../dashboard/applicant.php', 'Kun arbeidsgivere kan redigere stillinger.', 'error');
}

$error = '';
$success = '';

// Hent jobb-ID fra URL
$job_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$job_id) {
    redirect('list.php', 'Ingen stilling valgt.', 'error');
}

// Hent stillingen fra database
$job = Job::findById($job_id);

if (!$job) {
    redirect('list.php', 'Stillingen finnes ikke.', 'error');
}

// Sjekk at bruker eier stillingen (eller er admin)
if ($job['employer_id'] != $_SESSION['user_id'] && !has_role('admin')) {
    redirect('view.php?id=' . $job_id, 'Du har ikke tilgang til å redigere denne stillingen.', 'error');
}

// HÅNDTER POST-REQUEST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize_input($_POST['title'] ?? '');
    $company = sanitize_input($_POST['company'] ?? '');
    $location = sanitize_input($_POST['location'] ?? '');
    $job_type = sanitize_input($_POST['job_type'] ?? '');
    $description = sanitize_input($_POST['description'] ?? '');
    $requirements = sanitize_input($_POST['requirements'] ?? '');
    $salary = sanitize_input($_POST['salary'] ?? '');
    $deadline = sanitize_input($_POST['deadline'] ?? '');
    $status = sanitize_input($_POST['status'] ?? 'active');

    // Validering
    if (!validate_required($title) || 
        !validate_required($location) || 
        !validate_required($job_type) || 
        !validate_required($description) || 
        !validate_required($requirements) || 
        !validate_required($deadline)) {
        $error = 'Vennligst fyll ut alle obligatoriske felt.';
    } elseif (strlen($description) < 50) {
        $error = 'Stillingsbeskrivelsen må være minst 50 tegn.';
    } elseif (!empty($deadline) && strtotime($deadline) < time()) {
        $error = 'Søknadsfristen må være en fremtidig dato.';
    } else {
        // Oppdater stilling
        $updated_job = [
            'title' => $title,
            'company' => $company,
            'location' => $location,
            'job_type' => $job_type,
            'description' => $description,
            'requirements' => $requirements,
            'salary' => $salary,
            'deadline' => $deadline,
            'status' => $status
        ];

        if (Job::update($job_id, $updated_job)) {
            redirect('view.php?id=' . $job_id, 'Stillingen er oppdatert!', 'success');
        } else {
            $error = 'Det oppstod en feil under oppdateringen. Vennligst prøv igjen.';
        }
    }
}

$page_title = 'Rediger stilling';
require_once '../includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mb-4">
                <h1 class="h2 mb-2">Rediger stilling</h1>
                <p class="text-muted">Oppdater informasjonen for stillingen</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="">
                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Stillingstittel *</label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   value="<?php echo htmlspecialchars($job['title'] ?? ''); ?>" required>
                        </div>

                        <!-- Company -->
                        <div class="mb-3">
                            <label for="company" class="form-label">Bedrift/Organisasjon *</label>
                            <input type="text" class="form-control" id="company" name="company" 
                                   value="<?php echo htmlspecialchars($job['company'] ?? ''); ?>" required>
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