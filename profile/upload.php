<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Sjekk om bruker er innlogget
// if (!is_logged_in()) {
//     redirect('../auth/login.php', 'Du må være innlogget for å laste opp dokumenter.', 'danger');
// }

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        $filename = basename($_FILES['document']['name']);
        $target_path = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['document']['tmp_name'], $target_path)) {
            $success = 'Dokumentet er lastet opp!';
        } else {
            $error = 'Kunne ikke laste opp dokumentet.';
        }
    } else {
        $error = 'Ingen fil valgt eller feil ved opplasting.';
    }
}
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Last opp dokumenter - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php"></a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="../dashboard/applicant.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="../profile/view.php">Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="../auth/logout.php">Logg ut</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="row justify-content-center w-100">
            <div class="col-md-5 col-lg-4 col-xl-4 my-5">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h1 class="h4 mb-3 fw-normal">Last opp dokumenter</h1>
                            <p class="text-muted">Her kan du laste opp relevante dokumenter, som CV, vitnemål eller attester.</p>
                        </div>
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo htmlspecialchars($success); ?>
                            </div>
                        <?php endif; ?>
                        <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="document" class="form-label">Velg dokument</label>
                                <input type="file" class="form-control" id="document" name="document" required>
                                <div class="invalid-feedback">Vennligst velg et dokument.</div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Last opp</button>
                        </form>
                        <hr>
                        <div class="mt-3 text-center">
                            <a href="../dashboard/applicant.php" class="btn btn-outline-secondary">Tilbake til dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
