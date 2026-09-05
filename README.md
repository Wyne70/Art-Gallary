# Erwyne ArtSpace

A contemporary, futuristic art gallery web application and administrative management system built with modern PHP, MySQL/MariaDB, and custom CSS. The platform enables creators and curators to showcase digital and traditional artworks with an interactive 3D visual identity, clean dark-mode aesthetics, responsive layouts, and an administrative control panel for content management.

---

## Features

* **Futuristic Dark-Mode Aesthetics:** Custom CSS properties, glowing radial accents, interactive particle canvas elements, and smooth scroll transitions.
* **3D Visual Branding:** Floating perspective-styled profile badge and animated celestial orbits integrated into the hero showcase.
* **Interactive Art Catalog:** Dynamic artwork gallery with search/filtering capabilities, category indicators, and Philippine Peso (`₱`) price formatting.
* **Artist Roster & Profiles:** Dedicated creator portfolios linking directly to their respective catalog entries.
* **Secure Admin Control Panel:** Complete CRUD (Create, Read, Update, Delete) management for artworks, artist profiles, and incoming contact inquiries.
* **Authentication & Hardening:** Protected session handling, CSRF token verification on state modifications, prepared statements via PDO, and bcrypt password hashing.

---

## Tech Stack

* **Backend:** PHP 8.2+
* **Database:** MySQL / MariaDB (via PDO)
* **Frontend:** HTML5, Modern Vanilla CSS (Custom properties, Grid, Flexbox), Vanilla JavaScript (Canvas animations)
* **Local Development Environment:** XAMPP for Linux (Apache/MariaDB)

---

## Project Structure

```text
ART-GALLARY/
├── admin/                  # Administrative management panel
│   ├── index.php           # Admin dashboard & overview statistics
│   ├── artworks.php        # Artwork catalog CRUD operations
│   ├── artists.php         # Artist directory management
│   └── messages.php        # Inquiry review and message handling
├── assets/
│   ├── css/
│   │   └── style.css       # Unified gallery stylesheet
│   ├── images/
│   │   ├── artworks/       # Uploaded art assets
│   │   ├── artists/        # Uploaded artist avatars
│   │   └── erwyne-hero.png # Hero profile asset
│   └── js/                 # Particle canvas and interaction scripts
├── config/
│   └── database.php        # PDO database connection configuration
├── includes/
│   ├── functions.php       # Sanitization, CSRF, and helper utilities
│   ├── header.php          # Shared navigation and meta headers
│   └── footer.php          # Shared layout footer and status indicators
├── artwork.php             # Individual artwork detail view
├── artworks.php            # Searchable and filterable art gallery
├── artists.php             # Creator directory
├── contact.php             # Contact and inquiry form
├── login.php               # Administrative authentication gateway
├── index.php               # Public homepage and featured gallery showcase
├── README.md               # Documentation
└── .gitignore              # Ignored local files
