# CodeNepal - PHP Version

Interactive programming learning platform built with vanilla PHP, MySQL, and Apache.

## Features

- 🔐 **User Authentication** - Secure registration and login with session management
- 📚 **Course Catalog** - Browse courses by language (Python, JavaScript, C++) and difficulty
- 💻 **Interactive Code Editor** - Write and execute code directly in the browser with CodeMirror
- 🎯 **Progressive Learning** - Unlock lessons as you complete exercises
- 📊 **Progress Tracking** - Track completed lessons, exercises, and earned points
- 🏆 **Achievements** - Earn badges and certificates
- 👨‍💼 **Admin Dashboard** - Manage courses, lessons, and exercises (coming soon)

## Tech Stack

- **Frontend**: HTML, CSS (Tailwind CSS), JavaScript, CodeMirror
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Server**: Apache 2.4+

## Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache web server with mod_rewrite enabled
- Python 3.x (for Python code execution)
- Node.js (for JavaScript code execution)
- GCC/G++ (for C++ code execution)

## Installation

### 1. Clone or Download

Place the project files in your Apache web root directory (e.g., `htdocs`, `www`, or `public_html`).

### 2. Configure Database

Edit `config/config.php` and update the database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_mysql_username');
define('DB_PASS', 'your_mysql_password');
define('DB_NAME', 'codenepal');
```

### 3. Create Database

Open phpMyAdmin or MySQL command line and run:

```bash
mysql -u root -p < database/schema.sql
mysql -u root -p codenepal < database/seed.sql
```

Or manually:
1. Create a database named `codenepal`
2. Import `database/schema.sql`
3. Import `database/seed.sql` for sample data

### 4. Set Permissions

Ensure these directories are writable:

```bash
chmod 755 uploads/
chmod 755 temp/
chmod 755 logs/
```

### 5. Configure Apache

Make sure `.htaccess` is enabled. In your Apache configuration:

```apache
<Directory "/path/to/codenepal">
    AllowOverride All
    Require all granted
</Directory>
```

Restart Apache after changes.

### 6. Test Code Execution

Verify that Python, Node.js, and G++ are accessible:

```bash
python --version
node --version
g++ --version
```

Update paths in `config/config.php` if needed:

```php
define('PYTHON_PATH', 'python');  // or 'python3'
define('NODE_PATH', 'node');
define('GCC_PATH', 'g++');
```

## Usage

### Access the Application

Open your browser and navigate to:
```
http://localhost/codenepal/
```

### Demo Accounts

**Admin Account:**
- Email: `admin@codenepal.com`
- Password: `admin123`

**Regular User:**
- Email: `user@codenepal.com`
- Password: `user123`

### Create New Account

Click "Get Started" or "Register" to create a new account.

## Project Structure

```
codenepal-php/
├── api/                    # API endpoints
│   ├── execute.php        # Code execution
│   └── progress.php       # Progress tracking
├── assets/                # Static assets
│   ├── css/              # Stylesheets
│   ├── js/               # JavaScript files
│   └── images/           # Images
├── config/               # Configuration files
│   ├── config.php        # App configuration
│   └── database.php      # Database functions
├── database/             # Database files
│   ├── schema.sql        # Database schema
│   └── seed.sql          # Sample data
├── includes/             # Common includes
│   ├── auth.php          # Authentication functions
│   ├── functions.php     # Utility functions
│   ├── header.php        # Common header
│   └── footer.php        # Common footer
├── pages/                # Application pages
│   ├── login.php         # Login page
│   ├── register.php      # Registration page
│   ├── dashboard.php     # User dashboard
│   ├── courses.php       # Course catalog
│   ├── course.php        # Single course view
│   ├── lesson.php        # Lesson with exercises
│   ├── accomplishments.php # Achievements
│   ├── settings.php      # Account settings
│   └── logout.php        # Logout handler
├── admin/                # Admin panel (coming soon)
├── uploads/              # Uploaded files
├── temp/                 # Temporary files for code execution
├── logs/                 # Application logs
├── index.php             # Landing page
├── .htaccess             # Apache configuration
└── README.md             # This file
```

## Features Implemented

✅ User registration and login
✅ Session-based authentication
✅ Course catalog with filtering
✅ Course enrollment
✅ Lesson viewing with markdown-like content
✅ Interactive code editor (CodeMirror)
✅ Code execution (Python, JavaScript, C++)
✅ Test case validation
✅ Progress tracking
✅ Exercise completion
✅ Achievements and badges
✅ User dashboard
✅ Account settings
✅ Responsive design

## Security Features

- Password hashing with bcrypt
- CSRF protection on forms
- SQL injection prevention (prepared statements)
- XSS prevention (input sanitization)
- Session security (httponly, secure flags)
- Code execution validation (dangerous pattern detection)
- File upload validation
- Session timeout

## Troubleshooting

### Database Connection Error

- Check MySQL is running
- Verify database credentials in `config/config.php`
- Ensure database `codenepal` exists

### Code Execution Not Working

- Verify Python/Node/G++ are installed and in PATH
- Check file permissions on `temp/` directory
- Review error logs in `logs/` directory

### .htaccess Not Working

- Enable mod_rewrite in Apache
- Check `AllowOverride All` is set
- Restart Apache

### Permission Denied Errors

```bash
chmod -R 755 uploads/ temp/ logs/
```

## Development

### Debug Mode

Enable debug mode in `config/config.php`:

```php
define('DEBUG_MODE', true);
```

This will display PHP errors on screen. **Disable in production!**

### Adding New Courses

1. Login as admin
2. Navigate to Admin Dashboard (coming soon)
3. Or manually insert into database via phpMyAdmin

## Roadmap

- [ ] Admin dashboard for course management
- [ ] Admin lesson management
- [ ] Admin exercise management
- [ ] User management for admins
- [ ] Certificate generation and download
- [ ] Email notifications
- [ ] Social authentication (GitHub, Google)
- [ ] Discussion forums
- [ ] Code review system
- [ ] Leaderboards

## License

MIT License - Free to use for learning and development

## Support

For issues and questions, please check:
1. This README file
2. Error logs in `logs/` directory
3. Browser console for JavaScript errors

## Credits

Built with ❤️ for aspiring programmers

---

**Note**: This is a learning platform. Always validate and sanitize user input in production environments.
