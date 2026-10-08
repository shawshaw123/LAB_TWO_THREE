# Student Information Management REST API

An enterprise-grade, fully secured, documented, and tested RESTful API backend for a **Student Information Management System**, developed using **PHP Laravel** under the *AI-Assisted Framework-Based REST API Development* laboratory activity.

---

## 1. Technology Stack

- **Framework**: PHP Laravel 12 (running on PHP 8.5)
- **Database**: Relational Database (SQLite default for zero-configuration local reproducibility; MySQL/MariaDB and PostgreSQL supported)
- **Authentication**: Laravel Sanctum (Bearer Token)
- **Authorization**: Laravel Gates, Policies, and Custom Role Middleware
- **Testing**: PHPUnit 12 Test Suite (30 Feature Tests with 92 Assertions)
- **API Documentation**: OpenAPI 3.0 Specification & Interactive Swagger UI at `/api/docs`
- **API Client**: Postman Collection (`Student_Information_Management_API.postman_collection.json`)

---

## 2. Prerequisites

Ensure the following tools are installed on your machine:
- **PHP** >= 8.2 (tested on PHP 8.5.1) with extensions: `pdo_sqlite`, `pdo_mysql`, `curl`, `mbstring`, `openssl`, `tokenizer`
- **Composer** >= 2.2
- **Git**

---

## 3. Installation & Setup

1. **Clone the repository**:
   ```bash
   git clone <repository-url>
   cd labTwoToThree
   ```

2. **Install PHP dependencies**:
   ```bash
   composer install
   ```

3. **Configure Environment Variables**:
   Copy the provided `.env.example` file to `.env`:
   ```bash
   cp .env.example .env
   ```
   Generate the application encryption key:
   ```bash
   php artisan key:generate
   ```

4. **Initialize Database & Seed Demonstration Data**:
   By default, the application is configured to use SQLite located at `database/database.sqlite`.
   Run the fresh migrations and master seeder:
   ```bash
   php artisan migrate:fresh --seed
   ```
   *This seeds all Section 15 required records:*
   - **Users**: 6 (Admin, Registrar, 3 Instructors, Student)
   - **Programs**: 4 (BSIT, BSCS, BSIS, BSEMC)
   - **Students**: 105 records across programs and year levels
   - **Courses**: 22 university courses
   - **Academic Terms**: 3 terms
   - **Course Offerings**: 24 offerings
   - **Enrollments**: 220 unique enrollments
   - **Grades**: 115 grades with midterm/final marks and remarks

---

## 4. Running the Application

Start the local Laravel development server:
```bash
php artisan serve --port=8000
```
The API is now running at `http://127.0.0.1:8000`.

---

## 5. API Documentation & Interactive Swagger UI

Open your browser and navigate to:
```
http://127.0.0.1:8000/api/docs
```
This serves an interactive **Swagger UI** loaded from `/docs/openapi.json`. You can inspect all endpoints, schemas, parameters, response codes, and test endpoints directly with Bearer authentication.

---

## 6. Pre-Configured Test Accounts

All accounts share the default password: `Password123!`

| Role | Email | Permissions / Scope |
| :--- | :--- | :--- |
| **Administrator** | `admin@example.com` | Full system access to all resources, CRUD operations, and grade management. |
| **Registrar** | `registrar@example.com` | Manages students, programs, courses, academic terms, offerings, and enrollments. |
| **Instructor 1** | `instructor@example.com` | Teaches assigned offerings; view enrolled students and encode/update grades. |
| **Instructor 2** | `instructor2@example.com` | Teaches alternative offerings; restricted from accessing Instructor 1's classes. |
| **Student** | `student@example.com` | Restricted to own student profile (`2026-00001`), own enrollments, grades, and academic record. |

---

## 7. Running Automated Tests

Run the complete automated PHPUnit test suite:
```bash
php artisan test
```

### Test Coverage Highlights:
- `AuthenticationTest`: Valid login, invalid password rejection, token generation, 401 unauthenticated access, logout invalidation.
- `StudentTest`: Creation, retrieval, update, 422 duplicate student number rejection, 422 email validation, 404 not found handling.
- `AuthorizationTest`: Admin access, student forbidden actions (403), object-level authorization (student unable to view another student's record), instructor roster isolation.
- `EnrollmentTest`: Valid enrollment, 409 duplicate enrollment prevention, invalid foreign key validation.
- `GradeTest`: Authorized instructor encoding, unauthorized instructor rejection (403), grade range validation (1.00 - 5.00).
- `CollectionTest`: Keyword search, multi-field filtering, sorting ascending/descending, pagination metadata verification.

---

## 8. Postman API Collection

A complete Postman Collection is included at the project root:
```
Student_Information_Management_API.postman_collection.json
```
Import this file into **Postman**, **Insomnia**, or **Bruno**.
It includes organized folders:
1. `Authentication` (Login, Logout, Current User)
2. `Students` (CRUD, Search, Filter, Sort, Pagination)
3. `Programs` (CRUD)
4. `Courses` (CRUD)
5. `Academic Terms` (CRUD)
6. `Course Offerings` (CRUD & Class Roster)
7. `Enrollments` (Enroll, Duplicate Prevention)
8. `Grades` (Encode Grade, Update Grade)
9. `Academic Records` (Aggregated Student Record with GWA)
10. `Security & Authorization Checks` (401, 403, 404, 409, 422)

---

## 9. Mandatory Acceptance Demonstration Checklist (Section 22)

| # | Demonstration Action | Endpoint / Method | Expected Status |
| :-: | :--- | :--- | :-: |
| 1 | Start REST API and connect to DB | `GET /api/docs` | `200 OK` |
| 2 | Authenticate successfully | `POST /api/v1/auth/login` | `200 OK` (token issued) |
| 3 | Reject unauthenticated request | `GET /api/v1/students` (no token) | `401 Unauthorized` |
| 4 | Create a program | `POST /api/v1/programs` | `201 Created` |
| 5 | Create a valid student | `POST /api/v1/students` | `201 Created` |
| 6 | Reject invalid or duplicate student | `POST /api/v1/students` | `422 Unprocessable` |
| 7 | Retrieve and update a student | `PUT /api/v1/students/{id}` | `200 OK` |
| 8 | Search and filter student records | `GET /api/v1/students?search=John` | `200 OK` |
| 9 | Show pagination and sorting | `GET /api/v1/students?page=2&per_page=10` | `200 OK` with `meta` |
| 10 | Create course & academic term | `POST /api/v1/courses` | `201 Created` |
| 11 | Create course offering | `POST /api/v1/course-offerings` | `201 Created` |
| 12 | Enroll a student | `POST /api/v1/enrollments` | `201 Created` |
| 13 | Prevent duplicate enrollment | `POST /api/v1/enrollments` (same student/offering) | `409 Conflict / 422` |
| 14 | Encode / update authorized grade | `POST /api/v1/grades` | `201 Created` |
| 15 | Retrieve student academic record | `GET /api/v1/students/{id}/academic-record` | `200 OK` (grouped by term) |
| 16 | Show forbidden request (unauthorized role) | Student calling `POST /api/v1/programs` | `403 Forbidden` |
| 17 | Show 404 not found case | `GET /api/v1/students/99999` | `404 Not Found` |
| 18 | Display API documentation | `GET /api/docs` | `200 OK` (Swagger UI) |
| 19 | Run automated tests | `php artisan test` | `30 passed` |
| 20 | Explain AI-assisted contribution | See [ARCHITECTURE.md](ARCHITECTURE.md) | Verified & Tested |

---

## 10. Laboratory Activity III: Frontend Framework Integration

A modern, responsive, decoupled frontend client is implemented in [`resources/views/portal.blade.php`](resources/views/portal.blade.php), communicating with the `/api/v1` REST API over HTTP.

- **Frontend Client URL**: `http://127.0.0.1:8000/`
- **Frontend Integration Report**: See [FRONTEND_REPORT.md](FRONTEND_REPORT.md) for:
  1. **API Integration Map**: Comprehensive mapping of all frontend screens/triggers to REST endpoints.
  2. **AI Development Log**: Record of AI-assisted tasks and human verification.
  3. **Architecture Note**: Explaining network service layer, auth state, and state management.
  4. **20-Step Live Demonstration Guide**: Detailed instructions to execute during defense.
  5. **Technical Defense Q&A Cheat Sheet**: Complete answers to the Section 29 oral defense questions.

---

## 11. AI-Assisted Development Summary

AI assistance was utilized systematically in accordance with Section 3 of the laboratory guidelines:
- **Planning & Architecture**: Structured schema design, object-level authorization modeling, and response envelope standardization.
- **Scaffolding & Boilerplate**: Generated Eloquent migrations, FormRequests, policies, controllers, and factories according to standard Laravel 12 conventions.
- **Code Review & Refactoring**: Corrected schema type definitions, enforced strict typing, promoted constructor injection, and formatted all code with Laravel Pint.
- **Test Generation**: Produced a complete 30-case PHPUnit test suite validating business rules, boundary conditions, and security barriers.
