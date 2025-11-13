<?php
require_once '../includes/autoload.php';

/**
 * Lar søkere søke på stillinger med CV og søknadsbrev.
 */

// Sjekk innlogging
if (!is_logged_in()) {
    redirect('../auth/login.php', 'Du må logge inn for å søke på stillinger', 'danger');
}

// Sjekk rolle
if (!has_role('applicant')) {
    redirect('list.php', 'Kun søkere kan søke på stillinger', 'danger');
}

// Hent stilling
$job_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$job_id) {
    redirect('list.php', 'Ugyldig stilling', 'danger');
}

$job = Job::findById($job_id);

if (!$job) {
    redirect('list.php', 'Stillingen finnes ikke', 'danger');
}

if ($job['status'] !== 'active') {
    redirect('view.php?id=' . $job_id, 'Denne stillingen er ikke lenger aktiv', 'danger');
}

// Sjekk om søknadsfrist har passert
if if (!empty($job['deadline']) && strtotime($job['deadline']) < time()) {
    redirect('view.php?id=' . $job_id, 'Søknadsfristen for denne stillingen har passert', 'danger');
}

// Sjekk om bruker allerede har søkt
if (Application::hasApplied($job_id, $_SESSION['user_id'])) {
    redirect('view.php?id=' . $job_id, 'Du har allerede søkt på denne stillingen', 'danger');
}

// Håndter søknadsinnsending
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cover_letter = sanitize_input($_POST['cover_letter'] ?? '');
    
    // Validering
    if (empty($cover_letter)) {

        show_error('Søknadsbrev er påkrevd.');

    } elseif (strlen($cover_letter) < 100) {

        show_error('Søknadsbrevet må være minst 100 tegn.');

    } else {

            // Last opp CV
            $cv_path = 'test_cv.pdf';

            // Opprett søknad
            $application_data = [
                'job_id'        => $job_id,
                'applicant_id'  => $_SESSION['user_id'],
                'cover_letter'  => $cover_letter,
                'cv_path'       => $cv_path
            ];
            
            $application_id = Application::create($application_data);
            
            if ($application_id) {
                // TODO: Send e-post til arbeidsgiver
                redirect('../dashboard/applicant.php', 'Din søknad er sendt!', 'success');
            } else {
                show_error('Kunne ikke opprette søknad. Prøv igjen.');
            }
        }
    }

$page_title = 'Søk på stilling';
$body_class = 'bg-light'
include_once '../includes/header.php';
?>

<div class="container my-5">
    <!-- Stillingsinfo øverst -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="mb-3"><?php echo htmlspecialchars($job['title']); ?></h3>
                    
                    <p class="text-muted mb-4">
                        <i class="fas fa-building me-2"></i>
                        <?php echo htmlspecialchars($job['company']); ?>
                    </p>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-map-marker-alt text-primary me-2 mt-1"></i>
                                <div>
                                    <small class="text-muted d-block">Lokasjon</small>
                                    <strong><?php echo htmlspecialchars($job['location']); ?></strong>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-briefcase text-primary me-2 mt-1"></i>
                                <div>
                                    <small class="text-muted d-block">Type</small>
                                    <strong><?php echo htmlspecialchars($job['job_type']); ?></strong>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-calendar text-primary me-2 mt-1"></i>
                                <div>
                                    <small class="text-muted d-block">Søknadsfrist</small>
                                    <strong><?php echo date('d.m.Y', strtotime($job['deadline'])); ?></strong>
                                </div>
                            </div>
                        </div>
                        
                        <?php if (!empty($job['salary'])): ?>
                            <div class="col-md-3">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-money-bill-wave text-primary me-2 mt-1"></i>
                                    <div>
                                        <small class="text-muted d-block">Lønn</small>
                                        <strong><?php echo htmlspecialchars($job['salary']); ?></strong>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Stillingsbeskrivelse -->
                    <?php if (!empty($job['description'])): ?>
                        <hr class="my-4">
                        <h5 class="mb-3">Om stillingen</h5>
                        <div class="job-description">
                            <?php echo nl2br(htmlspecialchars($job['description'])); ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Krav -->
                    <?php if (!empty($job['requirements'])): ?>
                        <hr class="my-4">
                        <h5 class="mb-3">Krav og kvalifikasjoner</h5>
                        <div class="job-requirements">
                            <?php echo nl2br(htmlspecialchars($job['requirements'])); ?>
                        </div>
                    <?php endif; ?>
                    
                    <a href="view.php?id=<?php echo $job_id; ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>
                        Se full stillingsannonse
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Søknadsskjema under -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 text-primary">
                        <i class="fas fa-paper-plane me-2"></i>
                        Send søknad
                    </h5>
                </div>
                
                <div class="card-body p-4">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        
                        <!-- Søknadsbrev -->
                        <div class="mb-4">
                            <label for="cover_letter" class="form-label fw-bold">
                                Søknadsbrev <span class="text-danger">*</span>
                            </label>
                            <textarea 
                                class="form-control" 
                                id="cover_letter" 
                                name="cover_letter" 
                                rows="12"
                                minlength="100"
                                placeholder="Skriv ditt søknadsbrev her... (minimum 100 tegn)"
                                required><?php echo htmlspecialchars($_POST['cover_letter'] ?? ''); ?></textarea>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Fortell hvorfor du er den rette kandidaten for stillingen.
                            </div>
                            <div class="invalid-feedback">
                                Søknadsbrevet må være minst 100 tegn.
                            </div>
                            <div id="charCount" class="form-text text-end mt-1">
                                <span id="currentCount">0</span> / 100 tegn
                            </div>
                        </div>
                        
                        <!-- CV-opplasting -->
                        <!--  <div class="mb-4">
                            <label for="cv" class="form-label fw-bold">
                                Last opp CV <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="file" 
                                class="form-control" 
                                id="cv" 
                                name="cv"
                                accept=".pdf,.doc,.docx"
                                required>
                            <div class="form-text">
                                <i class="fas fa-file-pdf me-1"></i>
                                Tillatte formater: PDF, DOC, DOCX. Maksimal størrelse: 5MB.
                            </div>
                            <div class="invalid-feedback">
                                Du må laste opp din CV.
                            </div>
                        </div>-->
                        
                        <!-- Info-boks -->
                        <div class="alert alert-info border-info bg-light">
                            <h6 class="alert-heading">
                                <i class="fas fa-info-circle me-2"></i>
                                Viktig informasjon
                            </h6>
                            <ul class="mb-0 ps-4">
                                <li>Søknaden kan ikke endres etter innsending</li>
                                <li>Du vil motta e-post når søknaden er mottatt</li>
                                <li>Du kan følge statusen i ditt dashboard</li>
                            </ul>
                        </div>
                        
                        <!-- Knapper -->
                        <div class="d-flex gap-2 justify-content-end mt-4">
                            <a href="view.php?id=<?php echo $job_id; ?>" class="btn btn-light border">
                                <i class="fas fa-times me-1"></i>
                                Avbryt
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-paper-plane me-1"></i>
                                Send søknad
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Bootstrap form validation
(function() {
    'use strict';
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();

// Character counter for cover letter
const coverLetter = document.getElementById('cover_letter');
const currentCount = document.getElementById('currentCount');

function updateCharCount() {
    const length = coverLetter.value.length;
    currentCount.textContent = length;
    
    const min = 100;
    if (length < min) {
        coverLetter.setCustomValidity(`Minimum ${min} tegn`);
        currentCount.classList.add('text-danger');
        currentCount.classList.remove('text-success');
    } else {
        coverLetter.setCustomValidity('');
        currentCount.classList.add('text-success');
        currentCount.classList.remove('text-danger');
    }
}

coverLetter.addEventListener('input', updateCharCount);
updateCharCount(); // Initial count
</script>

<?php include_once '../includes/footer.php'; ?>