<?php
require_once '../includes/autoload.php';

// Må være arbeidsgiver eller admin
auth_check(['employer', 'admin']);

// Kun POST-requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../dashboard/employer.php', 'Ugyldig forespørsel.', 'danger');
}

// CSRF-sjekk
csrf_check('../dashboard/employer.php');

// Hent job_id
$job_id = filter_input(INPUT_POST, 'job_id', FILTER_VALIDATE_INT);

if (!$job_id) {
    redirect('list.php', 'Ugyldig stillings-ID.', 'danger');
}

// Hent stillingen
$job = Job::findById($job_id);

if (!$job) {
    redirect('list.php', 'Stillingen finnes ikke.', 'danger');
}

// Sjekk at bruker eier stillingen (eller er admin)
if ($job['employer_id'] != Auth::id() && !has_role('admin')) {
    redirect('view.php?id=' . $job_id, 'Du har ikke tilgang til å slette denne stillingen.', 'danger');
}

// Slett stillingen
if (Job::delete($job_id)) {
    $message = 'Stillingen er slettet!';
    
    // Gi bedre tilbakemelding hvis det var søknader
    if ($applications_count > 0) {
        $message .= " ({$applications_count} søknad" . ($applications_count > 1 ? 'er' : '') . " ble også slettet)";
    }
    
    redirect('../dashboard/employer.php', $message, 'success');
} else {
    redirect('view.php?id=' . $job_id, 'Kunne ikke slette stillingen. Prøv igjen senere.', 'danger');
}