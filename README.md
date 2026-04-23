  # Library Management System

A modern, full-featured **Library Management System** built with **Laravel** and **MySQL**. This web application automates book inventory management, student book requests, admin approvals, and overdue tracking with automatic penalty calculation.

![Status](https://img.shields.io/badge/Status-Active-brightgreen)
![Laravel](https://img.shields.io/badge/Laravel-11.x-red?logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue)
![License](https://img.shields.io/badge/License-MIT-green)

---

## 🎯 Features

### Admin Features
- 📚 **Book Management** - Add, edit, delete, and track book inventory
- 👥 **User Management** - View all registered students
- 📋 **Issue Management** - Issue books directly to students
- ✅ **Request Approval** - Approve or reject student book requests
- 🔄 **Return Tracking** - Mark books as returned and update inventory
- ⏰ **Overdue Monitoring** - View overdue books and calculate automatic penalties
- 📊 **Dashboard** - Summary cards showing total books, students, issued books, and overdue count

### Student Features
- 🔐 **User Registration & Login** - Create account and log in securely
- 🔍 **Book Search** - Search books by title, author, or category
- 📤 **Request Books** - Request available books for approval
- 📖 **View Borrowed Books** - See all borrowed, pending, and overdue books
- 💰 **View Penalties** - Check outstanding penalties for overdue books
- 📊 **Personal Dashboard** - Summary of borrowed books and borrowing capacity

### System Features
- ✨ **Role-Based Access Control** - Separate admin and student dashboards
- 🔒 **Secure Authentication** - Password hashing and session management
- 📅 **Fixed 15-Day Expiry** - All issued books have fixed 15-day due date
- 💳 **Auto Penalty System** - ₹20/day penalty for overdue books
- 🚫 **Borrow Limit** - Maximum 3 active books/requests per student
- 🔁 **Duplicate Prevention** - No duplicate active requests for same book
- 📊 **Real-Time Inventory** - Automatic availability updates

---

## 📋 Prerequisites

Before you begin, ensure you have the following installed:
- **PHP** >= 8.2
- **Composer** (PHP dependency manager)
- **MySQL** >= 5.7
- **Node.js** (for frontend assets)
- **Git** (for cloning the repository)

---

## 🚀 Installation & Setup

### 1. Clone the Repository
```bash
git clone https://github.com/PrincePatel-dev/library-management-system.git
cd library-management-system
```

### 2. Install Dependencies
```bash
# Install PHP packages
composer install

# Install Node.js packages
npm install
```

### 3. Environment Setup
```bash
# Copy the example environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration
Edit the `.env` file and configure your MySQL credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=library_management
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Create Database
```bash
# Create the database
mysql -u root -p -e "CREATE DATABASE library_management;"

# Run migrations
php artisan migrate
```

### 6. Build Frontend Assets
```bash
npm run build
```

### 7. Start Development Server
```bash
php artisan serve
```

The application will be available at **http://localhost:8000**

---

## 💾 Database Schema

The system uses three main tables:

### Users Table
Stores admin and student accounts with roles.

| Field | Type | Description |
|-------|------|-------------|
| id | BIGINT | Primary key |
| name | VARCHAR | User's full name |
| email | VARCHAR | User's email (unique) |
| password | VARCHAR | Hashed password |
| role | VARCHAR | `admin` or `student` |
| created_at | TIMESTAMP | Account creation time |
| updated_at | TIMESTAMP | Last update time |

### Books Table
Stores book catalog information and availability.

| Field | Type | Description |
|-------|------|-------------|
| id | BIGINT | Primary key |
| title | VARCHAR | Book title |
| author | VARCHAR | Book author |
| category | VARCHAR | Book category |
| isbn | VARCHAR | ISBN (unique) |
| quantity | INT | Total copies |
| available_quantity | INT | Available copies |
| description | TEXT | Book description |
| created_at | TIMESTAMP | Creation time |
| updated_at | TIMESTAMP | Last update time |

### Book Issues Table
Stores issue transactions and return history.

| Field | Type | Description |
|-------|------|-------------|
| id | BIGINT | Primary key |
| book_id | BIGINT | Foreign key to Books |
| user_id | BIGINT | Foreign key to Users |
| issue_date | DATE | Date book was issued |
| due_date | DATE | Due date (issue_date + 15 days) |
| return_date | DATE | Date book was returned |
| status | ENUM | `pending` / `issued` / `returned` / `overdue` / `rejected` |
| created_at | TIMESTAMP | Creation time |
| updated_at | TIMESTAMP | Last update time |

---

## 📁 Project Structure

```
library-management-system/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── AdminController.php
│   │   │   ├── BookController.php
│   │   │   ├── IssueController.php
│   │   │   └── StudentController.php
│   │   ├── Middleware/
│   │   └── Kernel.php
│   └── Models/
│       ├── User.php
│       ├── Book.php
│       └── BookIssue.php
├── database/
│   ├── migrations/
│   │   ├── create_users_table.php
│   │   ├── create_books_table.php
│   │   ├── create_book_issues_table.php
│   │   └── update_book_issues_for_approval_flow.php
│   └── seeders/
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   └── app.js
│   └── views/
│       ├── auth/
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       ├── admin/
│       │   ├── dashboard.blade.php
│       │   ├── books/
│       │   ├── issues/
│       │   └── ...
│       ├── student/
│       │   ├── dashboard.blade.php
│       │   ├── books.blade.php
│       │   ├── my-books.blade.php
│       │   └── ...
│       └── layouts/
├── routes/
│   ├── web.php
│   └── api.php
├── .env.example
├── composer.json
├── package.json
└── README.md
```

---

## 🔑 Key Concepts

### Due Date & Expiry
- All issued books have a **fixed 15-day due date** (from issue date)
- Admins cannot customize the expiry date
- System automatically tracks overdue books after this period

### Penalty System
- **Overdue Penalty:** ₹20 per day after the due date
- Penalties accumulate until the book is returned
- Both admin and student can see calculated penalties
- Example: Book overdue by 5 days = 5 × ₹20 = ₹100

### Borrow Limit
- Each student can have **maximum 3 active books/requests** at a time
- Active includes: `pending`, `issued`, `overdue` statuses
- Cannot request the same book multiple times if already active

### Request & Approval Workflow
1. Student requests a book (status = `pending`)
2. Admin reviews and approves (status = `issued`, due_date set)
3. Or admin rejects (status = `rejected`)
4. Student returns book (status = `returned`)

---

## 🛠 Technologies Used

| Technology | Version | Purpose |
|-----------|---------|---------|
| Laravel | 11.x | PHP Framework |
| PHP | 8.2+ | Server-side language |
| MySQL | 5.7+ | Database |
| Bootstrap | 5.x | Frontend framework |
| Blade | Latest | Template engine |
| Eloquent ORM | Latest | Database ORM |
| Carbon | Latest | Date handling |

---

## 📖 Usage Guide

### First Login
1. Register as the **first user** (automatically becomes `admin`)
2. Register additional users (automatically become `student`)

### Admin Workflow
1. Go to **Admin Dashboard** after login
2. Add books via **Manage Books** → Add New Book
3. Issue books directly or approve student requests
4. View overdue books and penalties
5. Mark books as returned to update inventory

### Student Workflow
1. Go to **Student Dashboard** after login
2. Browse books via **Browse Books**
3. Request books (if available and within limit)
4. Check **My Borrowed Books** for status and penalties
5. Admins approve/reject requests

---

## 🐛 Troubleshooting

### Database Connection Error
```bash
# Check .env file for correct credentials
php artisan migrate:reset  # Reset if needed
php artisan migrate         # Re-run migrations
```

### Asset Issues
```bash
# Rebuild frontend assets
npm run dev
npm run build
```

### Cache Issues
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Permission Issues (Linux/Mac)
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

---

## 📊 Sample Data

To populate the database with sample data, you can create seeders:

```bash
php artisan make:seeder BookSeeder
php artisan make:seeder UserSeeder
```

Then run:
```bash
php artisan db:seed
```

---

## 🔐 Security Features

- Password hashing using bcrypt
- CSRF protection on all forms
- SQL injection prevention via Eloquent ORM
- Role-based middleware protecting admin routes
- Session management and authentication guards

---

## 📝 API Routes (Future)

The system currently uses web routes. Future versions can include:
- REST API endpoints for mobile app integration
- JWT authentication for API access
- Advanced reporting endpoints

---

## 🚀 Future Enhancements

- 📧 Email notifications for due dates and approvals
- 💳 Online fine payment system
- 📱 Mobile app integration via REST API
- 🎫 QR code-based book scanning
- 📊 Advanced analytics and reporting dashboard
- 📋 Reservation and waitlist system
- 🏪 Multi-library support

---

## 📄 License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

---

## 🤝 Contributing

Contributions are welcome! Here's how you can help:

1. **Fork** the repository
2. **Create** a feature branch (`git checkout -b feature/amazing-feature`)
3. **Commit** your changes (`git commit -m 'Add amazing feature'`)
4. **Push** to the branch (`git push origin feature/amazing-feature`)
5. **Open** a Pull Request

---

## 👨‍💻 Author

**Your Name**  
Email: [your.email@example.com](mailto:your.email@example.com)  
GitHub: [@yourusername](https://github.com/yourusername)

---

## 📞 Support & Contact

If you encounter any issues or have questions:
- Open an **Issue** on GitHub
- Contact via email: [your.email@example.com](mailto:your.email@example.com)
- Check **Discussions** for Q&A

---

## 🙏 Acknowledgments

- Laravel Framework Documentation
- Bootstrap Framework for UI
- Carbon for date handling
- PHP Community

---

**Last Updated:** April 23, 2026  
**Current Version:** 1.0.0  
**Status:** ✅ Production Ready

---

## 📸 Screenshots

See [PROJECT_REPORT.html](PROJECT_REPORT.html) for detailed screenshots and project documentation.

---

## 📚 Further Reading

- [Laravel Documentation](https://laravel.com/docs)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [Bootstrap Documentation](https://getbootstrap.com/docs)

---

Made with ❤️ by Library Management Team
