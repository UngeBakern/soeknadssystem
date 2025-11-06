<?php
require_once '../includes/autoload.php';
require_once '../includes/header.php';

/*
 * 
 *
 *
 */

?>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="mb-4 text-center">
                    <h1 class="h2 mb-2">Opprett ny stilling</h1>
                    <p class="text-muted">Fyll ut informasjonen under for å publisere din stillingsutlysning</p>
                </div>
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form>
                            <!-- Basic Information -->
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <label for="title" class="form-label" style="font-size:0.95rem;">
                                        <i class="fas fa-tag me-1"></i>
                                        Stillingstittel *
                                    </label>
                                    <input type="text" class="form-control form-control-sm" id="title" name="title" 
                                           placeholder="F.eks. Hjelpelærer i matematikk" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label for="location" class="form-label">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        Lokasjon *
                                    </label>
                                    <input type="text" class="form-control" id="location" name="location" 
                                           placeholder="F.eks. Oslo" required>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="employment_type" class="form-label">
                                        <i class="fas fa-clock me-1"></i>
                                        Stillingstype *
                                    </label>
                                    <select class="form-select" id="employment_type" name="employment_type" required>
                                        <option value="">Velg stillingstype</option>
                                        <option value="Heltid">Heltid</option>
                                        <option value="Deltid">Deltid</option>
                                        <option value="Ekstrahjelp">Ekstrahjelp</option>
                                        <option value="Vikariat">Vikariat</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label for="hours_per_week" class="form-label">
                                        <i class="fas fa-hourglass-half me-1"></i>
                                        Timer per uke
                                    </label>
                                    <input type="number" class="form-control" id="hours_per_week" name="hours_per_week" 
                                           min="1" max="40" placeholder="F.eks. 20">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="salary" class="form-label">
                                        <i class="fas fa-money-bill-wave me-1"></i>
                                        Lønn
                                    </label>
                                    <input type="text" class="form-control" id="salary" name="salary" 
                                           placeholder="F.eks. 200-250 kr/time eller Etter avtale">
                                </div>
                            </div>

                            <div class="mb-2">
                                <label for="deadline" class="form-label">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    Søknadsfrist *
                                </label>
                                <input type="date" class="form-control" id="deadline" name="deadline" required>
                            </div>

                            <!-- Job Description -->
                            <div class="mb-2">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left me-1"></i>
                                    Stillingsbeskrivelse *
                                </label>
                                <textarea class="form-control" id="description" name="description" rows="5" 
                                          placeholder="Beskriv stillingen, arbeidsoppgaver og hva dere ser etter..." required></textarea>
                                <div class="form-text">Minst 50 tegn</div>
                            </div>

                            <!-- Requirements -->
                            <div class="mb-2">
                                <label for="requirements" class="form-label">
                                    <i class="fas fa-list-check me-1"></i>
                                    Krav og kvalifikasjoner *
                                </label>
                                <textarea class="form-control" id="requirements" name="requirements" rows="4" 
                                          placeholder="Liste opp krav til utdanning, erfaring og andre kvalifikasjoner..." required></textarea>
                                <div class="form-text">F.eks. utdanningsnivå, relevant erfaring, språkkrav</div>
                            </div>

                            <!-- Additional Information -->
                            <div class="card bg-light border-0 mb-2">
                                <div class="card-body p-2">
                                    <h6 class="card-title mb-2" style="font-size:1rem;">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Tilleggsinformasjon
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label for="subject" class="form-label">Fag/område</label>
                                            <select class="form-select" id="subject" name="subject">
                                                <option value="">Velg fag</option>
                                                <option value="Matematikk">Matematikk</option>
                                                <option value="Norsk">Norsk</option>
                                                <option value="Engelsk">Engelsk</option>
                                                <option value="Naturfag">Naturfag</option>
                                                <option value="Samfunnsfag">Samfunnsfag</option>
                                                <option value="Historie">Historie</option>
                                                <option value="Annet">Annet</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="level" class="form-label">Utdanningsnivå</label>
                                            <select class="form-select" id="level" name="level">
                                                <option value="">Velg nivå</option>
                                                <option value="Barneskole">Barneskole</option>
                                                <option value="Ungdomsskole">Ungdomsskole</option>
                                                <option value="Videregående">Videregående</option>
                                                <option value="Høyere utdanning">Høyere utdanning</option>
                                                <option value="Alle nivåer">Alle nivåer</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="text-muted" style="font-size:0.95rem;">
                                    <i class="fas fa-asterisk me-1" style="font-size: 0.7rem;"></i>
                                    Obligatoriske felt
                                </small>
                                <div class="d-flex align-items-center justify-content-end">
                                    <a href="list.php" class="btn btn-outline-secondary me-2">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        Tilbake
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        Publiser stilling
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Preview Card (skjult for nå) -->
                <!-- ...existing code... -->
            </div>
        </div>
    </div>

 <?php include_once '../includes/footer.php'; ?>

