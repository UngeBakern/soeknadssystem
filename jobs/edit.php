<?php
require_once '../includes/autoload.php';

// Sjekk om bruker er innlogget og har riktig rolle
// if (!is_logged_in() || !has_role('employer')) {
//     redirect('../auth/login.php', 'Du må være innlogget som arbeidsgiver for å redigere stillinger.', 'danger');
// }

// Dummy: Hent jobbinformasjon (erstatt med database senere)
$job_id = $_GET['id'] ?? null;
$job = null;
if ($job_id) {
    require_once '../data/jobs.php';
    foreach ($jobs as $j) {
        if ($j['id'] == $job_id) {
            $job = $j;
            break;
        }
    }
}

$error = '';
$success = '';

if ($_POST) {
    $title = sanitize_input($_POST['title'] ?? '');
    $description = sanitize_input($_POST['description'] ?? '');
    $location = sanitize_input($_POST['location'] ?? '');
    $deadline = sanitize_input($_POST['deadline'] ?? '');

    if (empty($title) || empty($description) || empty($location) || empty($deadline)) {
        $error = 'Alle felt må fylles ut';
    } else {
        // Oppdater jobben (dummy)
        $success = 'Stillingsannonsen er oppdatert.';
        // Her kan du lagre til database senere
    }
}
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rediger stilling - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php"><?php echo APP_NAME; ?></a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="../dashboard/employer.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="../jobs/list.php">Stillinger</a></li>
                    <li class="nav-item"><a class="nav-link" href="../profile/view.php">Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="../auth/logout.php">Logg ut</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="row justify-content-center w-100">
            <div class="col-md-8 col-lg-7 col-xl-6 my-5">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <form method="POST" class="needs-validation" novalidate>
                            <div class="d-flex align-items-center mb-3">
                                <?php
                                $logo_path = '../assets/images/uialogo.jpeg';
                                if (!file_exists($logo_path)) {
                                    $logo_path = 'https://via.placeholder.com/32x32?text=UiA';
                                }
                                ?>
                                <img src="<?php echo $logo_path; ?>" alt="UiA logo" style="height:32px;width:32px;object-fit:contain;margin-right:16px;">
                                <div class="flex-grow-1">
                                    <input type="text" class="form-control form-control-lg fw-bold mb-2" id="title" name="title" value="<?php echo htmlspecialchars($job['title'] ?? 'IT-hjelpelærer'); ?>" required>
                                    <div class="invalid-feedback">Vennligst oppgi en tittel.</div>
                                    <div class="d-flex gap-2 text-muted">
                                        <input type="text" class="form-control form-control-sm" id="employer" name="employer" value="<?php echo htmlspecialchars($job['employer'] ?? 'Universitetet i Agder'); ?>" style="max-width:220px;">
                                        <span>·</span>
                                        <input type="text" class="form-control form-control-sm" id="location" name="location" value="<?php echo htmlspecialchars($job['location'] ?? 'Kristiansand'); ?>" style="max-width:140px;">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-2 text-muted">Søknadsfrist:
                                <input type="date" class="form-control form-control-sm d-inline w-auto" id="deadline" name="deadline" value="<?php echo htmlspecialchars($job['deadline'] ?? ''); ?>" required style="max-width:150px;">
                                <input type="date" class="form-control form-control-sm d-inline w-auto" id="deadline" name="deadline" value="<?php echo htmlspecialchars($job['deadline'] ?? '2025-10-31'); ?>" required style="max-width:150px;">
                            </div>
                            <hr>
                            <div class="mb-4">
                                <h5 class="fw-bold mb-2">Stillingsbeskrivelse</h5>
                                <label class="form-label mb-1" for="description">Beskrivelse</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars(isset($job['description']) ? $job['description'] : 'Veiledning i IT-fag, programmering og systemutvikling. Praktisk hjelp i lab og prosjektarbeid.'); ?></textarea>
                                <div class="invalid-feedback">Vennligst oppgi en beskrivelse.</div>
                            </div>
                            <div class="mb-4">
                                <label class="fw-bold mb-1" for="requirements">Krav og kvalifikasjoner</label>
                                <textarea class="form-control" id="requirements" name="requirements" rows="2" required><?php echo htmlspecialchars($job['requirements'] ?? 'IT-utdanning. Erfaring med programmering og systemutvikling.'); ?></textarea>
                                <div class="invalid-feedback">Vennligst oppgi krav og kvalifikasjoner.</div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label class="form-label">Stillingstype</label>
                                    <input type="text" class="form-control" id="type" name="type" value="<?php echo htmlspecialchars($job['type'] ?? 'Vikariat'); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Lønn</label>
                                    <input type="text" class="form-control" id="salary" name="salary" value="<?php echo htmlspecialchars($job['salary'] ?? '250 kr/time'); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Fag/område</label>
                                    <input type="text" class="form-control" id="field" name="field" value="<?php echo htmlspecialchars($job['field'] ?? 'IT'); ?>">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Utdanningsnivå</label>
                                <input type="text" class="form-control" id="education" name="education" value="<?php echo htmlspecialchars($job['education'] ?? 'Høyere utdanning'); ?>">
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
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Oppdater stilling</button>
                                <a href="list.php" class="btn btn-outline-secondary">Tilbake til stillinger</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
