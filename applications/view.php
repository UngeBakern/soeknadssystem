<?php
require_once '../includes/autoload.php';

// Sjekk innlogging
auth_check(['applicant', 'employer']);

// Hent søknads-ID
$application_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$application_id) {
    redirect('../dashboard/applicant.php', 'Ugyldig søknads-ID.', 'danger');
}

// Hent søknad med full info
$application = Application::findById($application_id);

if (!$application) {
    redirect('../dashboard/applicant.php', 'Søknaden finnes ikke.', 'danger');
}

// Sikkerhet: Sjekk at bruker har tilgang
$is_applicant = (has_role('applicant') && $application['applicant_id'] == Auth::id());
$is_employer = (has_role('employer') && $application['employer_id'] == Auth::id());

if (!$is_applicant && !$is_employer && !has_role('admin')) {
    redirect('../dashboard/applicant.php', 'Du har ikke tilgang til denne søknaden.', 'danger');
}

$page_title = 'Min søknad - ' . $application['job_title'];
$body_class = 'bg-light';

require_once '../includes/header.php';
?>


<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Back button -->
            <div class="mb-3">
                <a href="<?php echo $is_employer ? '../dashboard/employer.php' : '../dashboard/applicant.php'; ?>" 
                   class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Tilbake
                </a>
            </div>

            <?php render_flash_messages(); ?>

            <!-- Application Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-file-alt text-primary me-2"></i>
                            Søknadsdetaljer
                        </h4>
                        <?php 
                        $status_badges = [
                            'Mottatt'   => 'info',
                            'Vurderes'  => 'warning',
                            'Tilbud'    => 'success',
                            'Avslått'   => 'danger'
                        ];
                        $badge_color = $status_badges[$application['status']] ?? 'secondary';
                        ?>
                        <span class="badge bg-<?php echo $badge_color; ?> fs-6">
                            <?php echo htmlspecialchars($application['status']); ?>
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Job Info -->
                    <div class="mb-4 pb-4 border-bottom">
                        <h5 class="mb-3">
                            <i class="fas fa-briefcase text-primary me-2"></i>
                            Stilling
                        </h5>
                        <h6>
                            <a href="../jobs/view.php?id=<?php echo $application['job_id']; ?>" 
                               class="text-decoration-none">
                                <?php echo htmlspecialchars($application['job_title']); ?>
                            </a>
                        </h6>
                        <p class="text-muted mb-1">
                            <i class="fas fa-building me-2"></i>
                            <?php echo htmlspecialchars($application['company']); ?>
                        </p>
                        <?php if (!empty($application['location'])): ?>
                        <p class="text-muted mb-1">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <?php echo htmlspecialchars($application['location']); ?>
                        </p>
                        <?php endif; ?>
                        <p class="text-muted small mb-0">
                            <i class="fas fa-calendar me-2"></i>
                            Søkt: <?php echo date('d.m.Y \k\l. H:i', strtotime($application['created_at'])); ?>
                        </p>
                    </div>

                    <!-- Applicant Info (for employer) -->
                    <?php if ($is_employer): ?>
                    <div class="mb-4 pb-4 border-bottom">
                        <h5 class="mb-3">
                            <i class="fas fa-user text-primary me-2"></i>
                            Søker
                        </h5>
                        <p class="mb-1">
                            <strong>Navn:</strong> <?php echo htmlspecialchars($application['applicant_name']); ?>
                        </p>
                        <p class="mb-1">
                            <strong>E-post:</strong> 
                            <a href="mailto:<?php echo htmlspecialchars($application['applicant_email']); ?>">
                                <?php echo htmlspecialchars($application['applicant_email']); ?>
                            </a>
                        </p>
                    </div>
                    <?php endif; ?>

                    <!-- Cover Letter -->
                    <div class="mb-4 pb-4 border-bottom">
                        <h5 class="mb-3">
                            <i class="fas fa-envelope-open-text text-primary me-2"></i>
                            Søknadsbrev
                        </h5>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0" style="white-space: pre-wrap;"><?php echo htmlspecialchars($application['cover_letter']); ?></p>
                        </div>
                    </div>

                    <!-- CV -->
                    <div class="mb-4">
                        <h5 class="mb-3">
                            <i class="fas fa-file-pdf text-primary me-2"></i>
                            CV / Vedlegg
                        </h5>
                        <?php if (!empty($application['cv_path'])): ?>
                            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded">
                                <div>
                                    <i class="fas fa-file-pdf text-danger me-2 fs-4"></i>
                                    <span><?php echo basename($application['cv_path']); ?></span>
                                </div>
                                <a href="<?php echo htmlspecialchars($application['cv_path']); ?>" 
                                   target="_blank"
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-download me-1"></i>
                                    Last ned
                                </a>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">Ingen CV lastet opp.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Actions for employer -->
                    <?php if ($is_employer && $application['status'] !== 'Avslått' && $application['status'] !== 'Tilbud'): ?>
                    <div class="mt-4 pt-4 border-top">
                        <h5 class="mb-3">Handlinger</h5>
                        <div class="d-flex gap-2">
                            <form method="POST" action="update_status.php" class="d-inline">
                                <input type="hidden" name="application_id" value="<?php echo $application['id']; ?>">
                                <input type="hidden" name="status" value="Tilbud">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check me-1"></i>
                                    Gi tilbud
                                </button>
                            </form>
                            <form method="POST" action="update_status.php" class="d-inline">
                                <input type="hidden" name="application_id" value="<?php echo $application['id']; ?>">
                                <input type="hidden" name="status" value="Avslått">
                                <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Er du sikker på at du vil avslå denne søknaden?');">
                                    <i class="fas fa-times me-1"></i>
                                    Avslå
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>