# 🎓 Helper Teacher Job Application System

[![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-blue.svg)](https://php.net/)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

> A complete job application system for helper teacher positions that connects qualified assistant teachers with educational institutions.

## Features

### User Types
- **Employers**: Can post jobs and manage applications
- **Job Seekers**: Can apply for positions and manage their profile

### Main Functions
- User registration and login
- Job management (create, edit, view)
- Application system with status tracking
- User profiles and CV management
- Dashboard for both user types

## Technology Stack
- **Backend**: PHP 8.x
- **Database**: MySQL (via XAMPP) - Array-based storage initially
- **Frontend**: HTML5, CSS3, Bootstrap 5
- **Server**: Apache (XAMPP)
- **Session Management**: PHP Sessions
- **Version Control**: Git with GitHub integration

## 🚀 Complete Setup Guide

### Prerequisites
Before starting, make sure you have:
- **XAMPP** installed (includes Apache, MySQL, PHP)
- **Git** for version control
- A modern web browser
- Text editor or IDE (VS Code recommended)

### Step 1: Install XAMPP
1. Download XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Install XAMPP to your preferred location (usually `C:\xampp\`)
3. Start the XAMPP Control Panel

### Step 2: Clone the Project
Open your terminal/command prompt and run:

```bash
# Navigate to XAMPP htdocs directory
cd C:\xampp\htdocs

# Clone the repository
git clone https://github.com/UngeBakern/soeknadssystem.git

# Navigate to project directory
cd soeknadssystem
```

### Step 3: Start XAMPP Services
1. Open **XAMPP Control Panel**
2. Start **Apache** service (click "Start" button)
3. Start **MySQL** service (click "Start" button)
4. Verify both services show "Running" status

### Step 4: Verify Installation
1. Open your web browser
2. Navigate to: `http://localhost/soeknadssystem/`
3. You should see the application homepage

### Step 5: Test with Demo Accounts

The system comes with pre-configured demo accounts for testing:

#### Employer Account
- **Email**: `employer@example.com`
- **Password**: `password`
- **Access**: Can create job postings and manage applications

#### Job Seeker Account
- **Email**: `applicant@example.com`
- **Password**: `password`
- **Access**: Can browse jobs and submit applications

### Project Structure

```
soeknadssystem/
├── index.php              # Main homepage
├── auth/                  # Authentication
│   ├── login.php          # User login
│   ├── register.php       # User registration (planned)
│   └── logout.php         # Logout functionality (planned)
├── dashboard/             # User dashboards
│   ├── employer.php       # Employer dashboard (planned)
│   └── applicant.php      # Applicant dashboard (planned)
├── jobs/                  # Job management
│   ├── list.php           # Job listings (planned)
│   ├── create.php         # Create job posting (planned)
│   ├── edit.php           # Edit job posting (planned)
│   └── view.php           # View job details (planned)
├── applications/          # Application handling
│   ├── apply.php          # Submit application (planned)
│   ├── manage.php         # Manage applications (planned)
│   └── status.php         # Application status (planned)
├── profile/              # User profiles
│   ├── view.php          # View profile (planned)
│   └── edit.php          # Edit profile (planned)
├── includes/             # Core functionality
│   ├── config.php        # Configuration & session management
│   ├── functions.php     # Helper functions
│   └── auth.php          # Authentication functions (planned)
├── assets/               # Static files
│   ├── css/              # Custom stylesheets
│   │   └── style.css     # Main stylesheet
│   ├── js/               # JavaScript files
│   │   └── main.js       # Main JavaScript
│   └── images/           # Image assets
├── data/                 # Data storage (temporary)
│   ├── users.php         # User accounts (array-based)
│   ├── jobs.php          # Job postings (array-based)
│   └── applications.php  # Applications (array-based)
├── uploads/              # File uploads
│   └── README.md         # Upload directory info
├── .github/              # GitHub configuration
│   └── workflows/        # CI/CD workflows
│       └── php.yml       # Automated PHP testing
├── .gitignore            # Git ignore patterns
├── LICENSE               # MIT License
└── README.md             # This file
```

## Development Phases

### Phase 1: Basic Structure ✅
- [x] Project setup and Git repository
- [x] User authentication with roles
- [x] Responsive design with Bootstrap
- [x] Basic navigation and structure

### Phase 2: Core Functionality (In Progress)
- [ ] Job management (create, edit, view)
- [ ] Application system with file handling
- [ ] User profiles and dashboard
- [ ] Search and filtering of jobs

### Phase 3: Database and Completion
- [ ] MySQL database integration
- [ ] Testing and bug fixes
- [ ] Documentation and delivery

## 👥 Team

This is a 2-person course project for PHP development at the University of Agder (UiA).

## 📋 Project Resources

- 🔧 **[GitHub Repository](https://github.com/UngeBakern/soeknadssystem)** - Source code and version control
- 📄 **[MIT License](LICENSE)** - Project license

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Testing

Open `http://localhost/soeknadssystem/` in your browser after XAMPP is started.

Use the demo credentials provided above to test different user roles and functionality.