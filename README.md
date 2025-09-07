

# 📚 Student Academic Progress Monitoring System (SAPMS)

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red?logo=laravel)](https://laravel.com/)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.x-06B6D4?logo=tailwindcss)](https://tailwindcss.com/)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?logo=javascript)](https://alpinejs.dev/)
[![Chart.js](https://img.shields.io/badge/Chart.js-4.x-FF6384?logo=chartdotjs)](https://www.chartjs.org/)
[![License](https://img.shields.io/badge/license-TBD-lightgrey)](#)

SAPMS is a **Laravel-based GPA tracking and academic monitoring system** designed for students of Njala University (and adaptable for other institutions).  
It helps students **track GPA**, **set academic goals**, **analyze trends**, and **project future performance** with an interactive and responsive web UI.  

---

## 🚀 Features

- **Authentication & Profiles** – Secure login and user management with Laravel Breeze.
- **Course Management** – Add, edit, and delete courses with dropdowns for grades, credit hours, semesters, and academic years.
- **GPA Calculation** – Automatic GPA calculation based on Njala University’s grading scale (A=5 → F=0).
- **Cumulative GPA** – Tracks GPA across semesters and academic years.
- **Analytics** – Interactive GPA trend lines and grade distribution charts (powered by Chart.js).
- **Projection Tool** – Simulate "what-if" scenarios with hypothetical courses to see projected GPA.
- **Goal Tracking** – Set semester-specific and cumulative GPA goals, track progress in real-time.
- **Dark Mode** – Smooth animated dark/light theme toggle.
- **Responsive UI** – TailwindCSS floating card layout, optimized for desktop & mobile.
- **Export** – PDF download for academic summaries and reports.

---

## 🛠️ Tech Stack

- **Backend:** Laravel 11, PHP 8.x  
- **Frontend:** Blade, TailwindCSS, Alpine.js  
- **Charts:** Chart.js (line, doughnut)  
- **Database:** SQLite (dev) / MySQL (prod-ready)  
- **Auth:** Laravel Breeze (login, registration, password reset)  
- **Build Tools:** Composer, NPM, Vite  

---

## 📂 Project Structure

SAPMS-app/ ├── app/ │ ├── Http/Controllers/ # CourseController, GoalController │ ├── Models/ # Course.php, Goal.php, User.php │ └── Helpers/ # GPAHelper.php ├── database/migrations/ # Courses, Goals, Users ├── resources/views/ │ ├── courses/ │ │ ├── index.blade.php # Main shell (tabbed layout) │ │ └── tabs/ # dashboard, courses, analytics, goals, projections, settings ├── public/ # Assets └── routes/web.php # Routes
---

## ⚙️ Installation

Clone the repo and install dependencies:

```bash
git clone https://github.com/<your-username>/SAPMS.git
cd SAPMS-app
composer install
npm install && npm run dev
cp .env.example .env
php artisan key:generate
Configure your .env file with database settings (SQLite or MySQL).
Run migrations:
php artisan migrate
Start the development server:
php artisan serve
Visit http://127.0.0.1:8000 in your browser.

🔑 Default Credentials
If you seeded users during setup, log in with:
Email: test@example.com
Password: password
Otherwise, register a new account via the signup form.

📊 Usage
* Dashboard Tab: Overview of GPA, courses, and quick stats.
* Courses Tab: Manage course entries grouped by academic year & semester.
* Analytics Tab: View GPA trend chart & grade distribution.
* Projections Tab: Add hypothetical courses to see projected GPA.
* Goals Tab: Set semester and cumulative GPA goals, track progress.
* Settings Tab: Manage profile and theme preferences.

🧪 Testing
Run tests with:
php artisan test
Unit tests include:
* GPAHelper calculations
* Course CRUD operations
* Goal tracking logic

📈 Roadmap / Future Work
* AI Assistant – Personalized study recommendations and reminders.
* Export Options – CSV/Excel exports in addition to PDF.
* Collaboration – Advisor/lecturer dashboards to monitor groups of students.
* Notifications – Email/SMS reminders for goals and progress alerts.
* Improved Mobile UX – Swipe-friendly navigation.

🤝 Contributing
Contributions are welcome! To contribute:
1. Fork the repo
2. Create a feature branch (git checkout -b feature/your-feature)
3. Commit your changes (git commit -m "Add new feature")
4. Push to your fork (git push origin feature/your-feature)
5. Submit a pull request

📜 License
This project’s license will be finalized later. For now, all rights reserved by the author.

🙌 Acknowledgments
* Njala University Students for survey responses and testing.
* Laravel, Tailwind, Alpine.js, Chart.js communities for tools and documentation.
* Project contributors: Mamadu B. Barrie.
---

