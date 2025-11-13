<?php
require_once '../includes/autoload.php';

/* 
 * Visning av jobber
 */

// Håndterer sletting av jobb (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_job'])) {

    // Sjekk innlogging
    if (!is_logged_in()) {
        redirect('../auth/login.php', 'Du må være logget inn for å slette en stilling.', 'danger');
    }

    $delete_job_id = filter_input(INPUT_POST, 'delete_job', FILTER_VALIDATE_INT);

    if(!$delete_job_id) {
        redirect('list.php', 'Ugyldig jobb ID.', 'danger');
    }

    $job_to_delete = Job::findById($delete_job_id);

    if(!$job_to_delete){
        redirect('list.php', 'Jobben finnes ikke.', 'danger');
    }

    // Sjekk: Eier jobben eller er admin 
    if ($job_to_delete['employer_id'] != $_SESSION['user_id'] && !has_role('admin')) {
        redirect('view.php?id=' . $delete_job_id, 'Du har ikke tilgang til å slette denne jobben.', 'danger');
    } 

    // Forsøk å slette 
    if (Job::delete($delete_job_id)) {

        redirect('../dashboard/employer.php', 'Stillingen er slettet!', 'success');
    } else {

        redirect('view.php?id=' . $delete_job_id, 'Kunne ikke slette stilling.', 'danger');
    }
}

    // Hent jobb-ID fra URL (GET)
    $job_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if (!$job_id) {
    redirect('list.php', 'Ugyldig jobb ID.', 'danger');
    }

    $job = Job::findById($job_id); 

    if(!$job) {
    redirect('list.php', 'Jobben finnes ikke.', 'danger');
    }


    // Sjekk om brukeren har søkt 
    $has_applied = false;
    if (is_logged_in() && has_role('applicant')) {
        //TODO : Iplementer funksjon for å sjekke om brukeren har søkt
    }

// Sett sidevariabler 
$page_title = $job['title'];
$body_class = 'bg-light';

require_once '../includes/header.php';

?>
<div class="container py-5">
    <?php render_flash_messages(); ?>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Back button -->
            <div class="mb-3">
                <a href="list.php" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Tilbake til stillinger
                </a>
            </div>

            <!-- Job Details Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-5">
                    <!-- Header -->
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-3">
                            <img src="../assets/images/uialogo.jpeg" 
                                 alt="Logo" 
                                 style="width: 70px; height: 70px; object-fit: contain;"
                                 onerror="this.src='https://via.placeholder.com/70'">
                        </div>
                        <div>
                            <h1 class="h3 mb-1 fw-bold"><?php echo htmlspecialchars($job['title']); ?></h1>
                            <div class="text-muted mb-1">
                                <i class="fas fa-building me-1"></i>
                                <strong><?php echo htmlspecialchars($job['employer_name']); ?></strong>
                                <?php if (!empty($job['location'])): ?>
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    <?php echo htmlspecialchars($job['location']); ?>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($job['deadline'])): ?>
                            <div class="small text-muted">
                                <i class="fas fa-calendar-alt me-1"></i>
                                Søknadsfrist: <?php echo date('d.m.Y', strtotime($job['deadline'])); ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <hr>

                    <!-- Description -->
                    <div class="mb-4">
                        <h5 class="mb-3">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Stillingsbeskrivelse
                        </h5>
                        <p class="text-muted"><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
                    </div>

                    <!-- Requirements -->
                    <?php if (!empty($job['requirements'])): ?>
                    <div class="mb-4">
                        <h5 class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Krav og kvalifikasjoner
                        </h5>
                        <p class="text-muted"><?php echo nl2br(htmlspecialchars($job['requirements'])); ?></p>
                    </div>
                    <?php endif; ?>

                    <!-- Job Details -->
                    <div class="row mb-4">
                        <?php if (!empty($job['job_type'])): ?>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block mb-1">Stillingstype</small>
                                <strong><?php echo htmlspecialchars($job['job_type']); ?></strong>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($job['salary'])): ?>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block mb-1">Lønn</small>
                                <strong><?php echo htmlspecialchars($job['salary']); ?></strong>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($job['subject'])): ?>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block mb-1">Fag/område</small>
                                <strong><?php echo htmlspecialchars($job['subject']); ?></strong>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($job['education_level'])): ?>
                    <div class="mb-4">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted d-block mb-1">Utdanningsnivå</small>
                            <strong><?php echo htmlspecialchars($job['education_level']); ?></strong>
                        </div>
                    </div>
                    <?php endif; ?>

                    <hr>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2 flex-wrap">
                        <?php if (is_logged_in() && has_role('applicant')): ?>
                            <?php if ($has_applied): ?>
                                <button class="btn btn-secondary" disabled>
                                    <i class="fas fa-check me-2"></i>
                                    Du har allerede søkt
                                </button>
                            <?php else: ?>
                                <a href="apply.php?id=<?php echo $job['id']; ?>" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Søk på stillingen
                                </a>
                            <?php endif; ?>
                        <?php elseif (!is_logged_in()): ?>
                            <a href="../auth/login.php" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Logg inn for å søke
                            </a>
                        <?php endif; ?>

                        <a href="list.php" class="btn btn-outline-secondary">
                            <i class="fas fa-list me-2"></i>
                            Se alle stillinger
                        </a>

                        <?php if (is_logged_in() && ((has_role('employer') && $job['employer_id'] == $_SESSION['user_id']) || has_role('admin'))): ?>
                            <a href="edit.php?id=<?php echo $job['id']; ?>" class="btn btn-outline-primary">
                                <i class="fas fa-edit me-2"></i>
                                Rediger stilling
                            </a>

                            <form method="POST" style="display: inline;"
                                  onsubmit="return confirm('Er du sikker på at du vil slette denne stillingen?');">
                                <input type="hidden" name="delete_job" value="<?php echo $job['id']; ?>">
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fas fa-trash me-2"></i>
                                    Slett stilling
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h6 class="mb-3">
                        <i class="fas fa-building text-primary me-2"></i>
                        Om arbeidsgiver
                    </h6>
                    <p class="text-muted mb-2">
                        <?php echo htmlspecialchars($job['employer_name']); ?>
                    </p>
                    <p class="text-muted small mb-0">
                        <i class="fas fa-clock me-1"></i>
                        Publisert: <?php echo date('d.m.Y', strtotime($job['created_at'])); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>