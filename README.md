# ScholarTrack

ScholarTrack is a Scholarship Eligibility and Application Portal developed using PHP, MySQL, HTML, CSS, and JavaScript. It helps students discover scholarships, submit applications, upload requirements, and monitor application status. Administrators can manage scholarships, review applications, manage student accounts, and generate reports.

## Features

### Student Module

- User registration and login
- Profile management
- Profile picture upload
- Scholarship browsing
- Scholarship application
- Document upload
- Application status tracking
- Notifications
- Change password
- Forgot password

### Administrator Module

- Administrator login
- Dashboard overview
- Manage scholarships
- Manage student accounts
- Review scholarship applications
- Approve, reject, or waitlist applications
- Generate reports
- Profile management
- Change password

## Technologies Used

- PHP 8.0+
- MySQL/MariaDB
- HTML5
- CSS3
- JavaScript
- XAMPP

## Project Structure

```text
ScholarTrack/
├── assets/
├── profile/
├── uploads/
├── index.php
├── login.php
├── register.php
├── student_dashboard.php
├── admin_dashboard.php
├── config.php
└── scholartrack.sql
```

## Requirements

- Windows 10 or Windows 11
- XAMPP
- PHP 8.0 or higher
- MySQL or MariaDB
- Modern web browser

## Installation Using XAMPP

1. Install XAMPP.

2. Copy the project folder inside the XAMPP `htdocs` directory.

   Example:

   ```text
   D:\Xampp\htdocs\GUELAS\ScholarTrack\main
   ```

3. Open the XAMPP Control Panel.

4. Start:

   - Apache
   - MySQL

5. Open phpMyAdmin:

   ```text
   http://localhost/phpmyadmin
   ```

6. Create a database named:

   ```text
   scholartrack
   ```

7. Select the `scholartrack` database, click **Import**, choose `scholartrack.sql`, and click **Go**.

8. Check the database settings in `config.php`:

   ```php
   $host = 'localhost';
   $db   = 'scholartrack';
   $user = 'root';
   $pass = '';
   ```

9. Open the website using:

   ```text
   http://localhost/GUELAS/ScholarTrack/main/
   ```

   Do not include `htdocs` in the URL.

## Running the Project Through the VS Code Terminal

If the project is located at:

```text
D:\Xampp\htdocs\GUELAS\ScholarTrack\main
```

open the project in VS Code and run:

```powershell
D:\Xampp\php\php.exe -S 127.0.0.1:8000 -t "D:\Xampp\htdocs\GUELAS\ScholarTrack\main"
```

Then open:

```text
http://127.0.0.1:8000
```

Keep the terminal open while using the website. MySQL must also be running if the system uses the database.

## Default Administrator Account

```text
Email: admin@scholartrack.com
Password: admin123
```

For security purposes, change the default administrator password after logging in.

Students may create an account through the registration page.

## Troubleshooting

### Database connection failed

- Make sure MySQL is running.
- Confirm that the database name is `scholartrack`.
- Import the correct `.sql` file.
- Check the database settings in `config.php`.

### Images are not displayed

- Make sure the `assets/`, `profile/`, and `uploads/` folders exist.
- Verify that uploaded images are stored in the correct folder.
- Refresh the browser using `Ctrl + F5`.

### Website cannot be reached

- Make sure the PHP development server is running.
- Keep the VS Code terminal open.
- Check that you are using the correct URL and port.
- Try opening:

  ```text
  http://127.0.0.1:8000
  ```

### PHP errors or blank page

- Make sure PHP 8.0 or higher is installed.
- Confirm that all project files were copied correctly.
- Restart Apache or the PHP development server.

## Developers

Developed as a course project for Applications Development.

## License

This project is intended for educational purposes only.