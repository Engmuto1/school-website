# School Website Setup Guide

## 🎓 Welcome to Your School Website!

This is a comprehensive PHP-based school website with a public site, admin panel, and basic portal system built using:
- **PHP 8+** with MVC architecture
- **MySQL/MariaDB** database  
- **Apache** web server (XAMPP)
- **Responsive design** with modern CSS
- **Security features** (CSRF protection, validation, etc.)

## 🚀 Quick Setup

### 1. Prerequisites
- XAMPP (or similar) with PHP 8.0+ and MySQL
- Web browser
- Text editor (optional, for customization)

### 2. Installation Steps

1. **Copy the project** to your XAMPP htdocs folder:
   ```
   C:\xampp\htdocs\school\
   ```

2. **Start XAMPP** services:
   - Apache
   - MySQL

3. **Create the database**:
   - Open phpMyAdmin: http://localhost/phpmyadmin
   - Or run the migration script:
   ```bash
   cd C:\xampp\htdocs\school
   php migrate.php
   ```

4. **Configure settings** (optional):
   - Copy `.env.example` to `.env`
   - Update database credentials if needed
   - Add email settings for contact forms

5. **Access your website**:
   - Public site: http://localhost/school
   - Admin panel: http://localhost/school/admin/login

### 3. Default Login Credentials
- **Username**: admin
- **Password**: admin123

## 📁 Project Structure

```
school/
├── app/
│   ├── Controllers/     # Application controllers
│   ├── Models/         # Database models
│   ├── Views/          # Template files
│   ├── Core/           # Framework core files
│   ├── Middleware/     # Authentication & security
│   └── Helpers/        # Utility classes
├── config/             # Configuration files
├── database/           # Database schema & migrations
├── public/             # Public assets (CSS, JS, images)
├── storage/            # File uploads & logs
├── index.php           # Application entry point
└── migrate.php         # Database setup script
```

## 🌟 Features Implemented

### ✅ Core Foundation
- [x] MVC architecture with clean URLs
- [x] Database schema with 15+ tables
- [x] User authentication & RBAC
- [x] Responsive public layout
- [x] Security features (CSRF, validation)

### ✅ Public Website
- [x] Homepage with hero section
- [x] About, Academics, Admissions pages
- [x] News & Events system
- [x] Contact form
- [x] Gallery & Downloads

### ✅ Admin Panel
- [x] Dashboard with statistics
- [x] Content management (CMS)
- [x] News & Events management
- [x] Media manager
- [x] User management
- [x] Settings panel

### 🚧 Coming Next
- [ ] Complete admin CRUD operations
- [ ] Student portal
- [ ] Email notifications
- [ ] Advanced search
- [ ] More admin features

## 🛠️ Customization

### Update School Information
1. Login to admin panel
2. Go to Settings → School Information
3. Update name, logo, contact details
4. Save changes

### Add Content
1. **Pages**: Admin → Pages → Edit content
2. **News**: Admin → News → Create new articles
3. **Events**: Admin → Events → Add upcoming events
4. **Media**: Admin → Media → Upload images/documents

### Styling
- Main CSS file: `public/assets/css/style.css`
- Custom styles can be added per page
- Responsive design with mobile-first approach

## 🔒 Security Features

- CSRF token protection on all forms
- Password hashing with PHP's password_hash()
- SQL injection prevention with PDO prepared statements
- File upload validation (type, size, MIME)
- Role-based access control (Admin, Staff, Teacher)
- Session security settings

## 📝 Database Schema

Key tables created:
- `users` - Admin/staff accounts
- `pages` - CMS content (About, Academics, etc.)
- `news` - News articles
- `events` - School events
- `media` - File uploads
- `admissions` - Application forms
- `messages` - Contact form submissions
- `students` - Student records (portal)
- `settings` - System configuration

## 🔧 Configuration

### Database Settings
Update in `config/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'school_website');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### Email Settings
For contact forms and notifications:
```php
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'your-email@gmail.com');
define('MAIL_PASSWORD', 'your-app-password');
```

## 🆘 Troubleshooting

### Common Issues

1. **404 Error**: 
   - Check if mod_rewrite is enabled in Apache
   - Verify .htaccess file is present

2. **Database Connection Error**:
   - Verify MySQL is running
   - Check database credentials in config.php

3. **File Upload Issues**:
   - Check folder permissions (storage/uploads)
   - Verify PHP upload limits

4. **Login Issues**:
   - Use default credentials: admin/admin123
   - Clear browser cache and cookies

### Need Help?
- Check error logs in Apache error.log
- Enable PHP error reporting in development
- Verify file permissions (755 for folders, 644 for files)

## 🚀 Next Steps

1. **Customize your school's information**
2. **Add your content and images**
3. **Create additional user accounts**
4. **Test all functionality**
5. **Deploy to production server** (optional)

## 📋 Development Roadmap

The current implementation provides a solid foundation. Future enhancements can include:
- Email notifications system
- Advanced student portal features
- Online exam system
- Fee management
- Timetable generator
- Mobile app integration

---

**Congratulations!** 🎉 Your school website is ready to use. Start by logging into the admin panel and customizing your school's information!