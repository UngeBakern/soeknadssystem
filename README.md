# 🎓 Hjelpelærer Søknadssystem

Et komplett søknadssystem for hjelpelærerstillinger som kobler kvalifiserte assistentlærere med utdanningsinstitusjoner.

## Funksjoner

- **For arbeidsgivere**: Publiser stillinger og administrer søknader
- **For jobbsøkere**: Søk på stillinger og administrer profil
- Brukerregistrering og innlogging
- Opprett, rediger og slett stillingsannonser
- Send søknader med søknadsbrev
- Dashboard for begge brukertyper
- Dokumentopplasting (CV, vitnemål, attester)

## Teknologi

- PHP 8.x med objektorientert programmering
- MySQL database via PDO
- Bootstrap 5 for design
- Apache webserver (XAMPP)

## Installasjon

### 1. Forutsetninger
- XAMPP installert
- Git

### 2. Klon prosjektet
```bash
cd C:\xampp\htdocs
git clone https://github.com/UngeBakern/soeknadssystem.git
cd soeknadssystem
```

### 3. Opprett database
1. Åpne phpMyAdmin: `http://localhost/phpmyadmin`
2. Opprett ny database: `soeknadssystem`
3. Importer: `database/schema.sql`

### 4. Konfigurer
Åpne `includes/config.php` og sjekk at database-innstillingene stemmer:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'soeknadssystem');
```

### 5. Start XAMPP
1. Åpne XAMPP Control Panel
2. Start **Apache**
3. Start **MySQL**

### 6. Åpne applikasjonen
Gå til: `http://localhost/soeknadssystem/`

## Demo-kontoer

Etter installasjon kan du registrere nye brukere, eller bruke testdata hvis du kjører `database/demo_users.sql`:

- **Arbeidsgiver**: employer@example.com
- **Søker**: applicant@example.com
- **Passord**: password

## Mappestruktur

```
soeknadssystem/
├── auth/           # Innlogging og registrering
├── classes/        # PHP-klasser (Auth, User, Job, Application, etc.)
├── dashboard/      # Bruker-dashboards
├── includes/       # Konfigurasjon og hjelpefunksjoner
├── jobs/           # Stillingsannonser
├── applications/   # Søknadshåndtering
├── profile/        # Brukerprofil
├── assets/         # CSS, JavaScript, bilder
├── uploads/        # Opplastede filer
└── database/       # SQL-scripts
```

## Lisens

MIT License - se [LICENSE](LICENSE)

## Prosjekt

Dette er et 2-personers kursprosjekt i PHP-utvikling ved Universitetet i Agder (UiA).
