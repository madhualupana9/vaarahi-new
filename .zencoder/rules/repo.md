---
description: Repository Information Overview
alwaysApply: true
---

# Vaarahi Diagnostics Website Information

## Summary
The project is a PHP-based web application for **Vaarahi Diagnostics**, a diagnostic center in Hyderabad. It provides a landing page for users to view services (X-Ray, CT Scan, Mammography, etc.) and book tests. It integrates with **Twilio** for SMS notifications and **Google Apps Script** for form submission processing.

## Structure
- **Root**: Contains main entry points (`index.php`), form handlers (`submit_form.php`), and configuration (`config.php`).
- **css/**: Stylesheets including Bootstrap and custom styles.
- **js/**: JavaScript libraries (jQuery, Bootstrap) and custom logic (`main.js`, `app.js`).
- **images/**: Project assets, including icons, banners, and service-related images.
- **fonts/**: Web fonts and Bootstrap icons.
- **clinical-team/**: Contains information about the medical staff.
- **lp/**: Landing page related scripts.
- **ajax/**: Local copies of common libraries (FontAwesome, jQuery).

## Language & Runtime
**Language**: PHP  
**Version**: Not specified (Standard PHP 7/8)  
**Build System**: None (Direct deployment)  
**Package Manager**: None (Dependencies are included locally)

## Dependencies
**Main Dependencies**:
- **jQuery** (3.7.1): DOM manipulation and AJAX.
- **Bootstrap** (5.x): CSS framework for layout and components.
- **Swiper**: For image sliders and carousels.
- **AOS** (Animate on Scroll): For scroll animations.
- **FontAwesome**: For iconography.

**Integrations**:
- **Twilio API**: For sending automated SMS notifications on form submission.
- **Google Apps Script**: For processing and storing form data.
- **Google Tag Manager**: For analytics and tracking.

## Build & Installation
This is a direct-deployment PHP project. To set it up:
1. Copy `config.sample.php` to `config.php`.
2. Configure `TWILIO_ACCOUNT_SID`, `TWILIO_AUTH_TOKEN`, `TWILIO_NUMBER`, and `GOOGLE_SCRIPT_URL` in `config.php`.
3. Serve the project root using a PHP-capable web server (e.g., Apache with `mod_php`, Nginx with `PHP-FPM`).

## Main Files & Resources
- **index.php**: The main application entry point and landing page.
- **submit_form.php**: The backend handler for test booking and inquiry forms.
- **config.php**: Stores sensitive credentials for Twilio and Google integrations.
- **main.js**: Contains frontend logic for menu toggling, search, and voice recognition.
- **css/stylesheet.css**: The primary stylesheet for the application's look and feel.
- **clinical-team/index.htm**: Secondary entry point for team information.

## Usage & Operations
The application is a static-like PHP site where the primary interaction is via forms. When a user submits a "Book a Test" form:
1. `submit_form.php` validates the input (`name`, `phone`).
2. It sends an SMS notification via the Twilio API to the user.
3. It forwards the submission data to a Google Apps Script URL for record-keeping.
4. Returns a JSON success or error response to the frontend.

## Testing & Validation
No automated testing framework (like PHPUnit) is present in the repository. Validation is performed manually or via basic input checks in `submit_form.php`.
