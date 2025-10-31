<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Mockup: Hent id fra URL
$job_id = $_GET['id'] ?? 1;

// Mockup: Stillingsdata
$jobs = [
    1 => [
        'title' => 'Hjelpelærer i matematikk',
        'employer_name' => 'Universitetet i Agder',
        'location' => 'Kristiansand',
        'description' => 'Undervisning og veiledning i matematikk for studenter. Arbeidsoppgaver inkluderer gruppearbeid, oppgaveløsning og støtte til hovedlærer.',
        'requirements' => 'Relevant utdanning innen matematikk. Gode samarbeidsevner. Erfaring med undervisning er en fordel.',
        'deadline' => '2024-11-15',
        'type' => 'Deltid',
        'salary' => 'Etter avtale',
        'subject' => 'Matematikk',
        'level' => 'Høyere utdanning'
    ],
    2 => [
        'title' => 'Norsk hjelpelærer',
        'employer_name' => 'Universitetet i Agder',
        'location' => 'Grimstad',
        'description' => 'Støtte til norskundervisning for elever på videregående. Fokus på skriftlig og muntlig norsk.',
        'requirements' => 'Utdanning i norsk. Erfaring med ungdom er en fordel.',
        'deadline' => '2024-11-20',
        'type' => 'Ekstrahjelp',
        'salary' => '200 kr/time',
        'subject' => 'Norsk',
        'level' => 'Videregående'
    ],
    3 => [
        'title' => 'IT-hjelpelærer',
        'employer_name' => 'Universitetet i Agder',
        'location' => 'Kristiansand',
        'description' => 'Veiledning i IT-fag, programmering og systemutvikling. Praktisk hjelp i lab og prosjektarbeid.',
        'requirements' => 'IT-utdanning. Erfaring med programmering og systemutvikling.',
        'deadline' => '2024-11-10',
        'type' => 'Vikariat',
        'salary' => '250 kr/time',
        'subject' => 'IT',
        'level' => 'Høyere utdanning'
    ]
];

$job = $jobs[$job_id] ?? $jobs[1];
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($job['title']); ?> - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <i class="fas fa-graduation-cap me-2"></i>
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="list.php">Alle stillinger</a>
                <a class="nav-link" href="../dashboard/applicant.php">Dashboard</a>
                <a class="nav-link" href="../profile/view.php">Profil</a>
                <a class="nav-link" href="../auth/logout.php">Logg ut</a>
            </div>
        </div>
    </nav>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-5">
                        <div class="d-flex align-items-center mb-4">
                            <img src="../uialogo.jpeg" alt="UiA logo" style="max-width:70px; max-height:70px;" class="me-3">
                            <div>
                                <h1 class="h3 mb-1 fw-bold"><?php echo htmlspecialchars($job['title']); ?></h1>
                                <div class="text-muted mb-1">
                                    <strong><?php echo htmlspecialchars($job['employer_name']); ?></strong> &bull; <?php echo htmlspecialchars($job['location']); ?>
                                </div>
                                <div class="small text-muted">
                                    Søknadsfrist: <?php echo date('d.m.Y', strtotime($job['deadline'])); ?>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="mb-4">
                            <h5 class="mb-2">Stillingsbeskrivelse</h5>
                            <p><?php echo htmlspecialchars($job['description']); ?></p>
                        </div>
                        <div class="mb-4">
                            <h5 class="mb-2">Krav og kvalifikasjoner</h5>
                            <p><?php echo htmlspecialchars($job['requirements']); ?></p>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-4 mb-2">
                                <span class="text-muted">Stillingstype:</span><br>
                                <strong><?php echo htmlspecialchars($job['type']); ?></strong>
                            </div>
                            <div class="col-md-4 mb-2">
                                <span class="text-muted">Lønn:</span><br>
                                <strong><?php echo htmlspecialchars($job['salary']); ?></strong>
                            </div>
                            <div class="col-md-4 mb-2">
                                <span class="text-muted">Fag/område:</span><br>
                                <strong><?php echo htmlspecialchars($job['subject']); ?></strong>
                            </div>
                        </div>
                        <div class="mb-4">
                            <span class="text-muted">Utdanningsnivå:</span>
                            <strong><?php echo htmlspecialchars($job['level']); ?></strong>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="apply.php?id=<?php echo $job_id; ?>" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>
                                Søk på stillingen
                            </a>
                            <a href="list.php" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Tilbake til stillinger
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="bg-white border-top py-4 mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted">Hjelpelærer Søknadssystem</h6>
                    <p class="text-muted small mb-0">Kobler sammen hjelpelærere og utdanningsinstitusjoner.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted small mb-0">
                        <a href="#" class="text-muted me-3 text-decoration-none">Om oss</a>
                        <a href="#" class="text-muted me-3 text-decoration-none">Kontakt</a>
                        <a href="#" class="text-muted text-decoration-none">Support</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>
