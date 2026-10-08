# Laboratory Activity III: Frontend Framework Integration Report

### Student Information Management System — Frontend Client
**Laboratory Activity III: AI-Assisted Frontend Framework Integration with an Existing REST API**

---

## 1. Executive Summary & Verification

| Requirement | Implementation Details | Status |
| :--- | :--- | :---: |
| **Development Mode** | Frontend client communicating exclusively over HTTP with `/api/v1` REST API | **Complete** |
| **Backend Integration** | Connects to authenticated endpoints; zero direct database queries from frontend | **Complete** |
| **API Base URL** | Configurable via environment; clearly displayed on login interface (`/api/v1`) | **Complete** |
| **Role-Aware UI** | Distinct dashboards, navigation tabs, and actions for Admin, Registrar, Instructor, Student | **Complete** |
| **Core Modules** | Auth, Students CRUD, Programs, Courses, Terms, Offerings, Enrollments, Grades, Transcripts | **Complete** |
| **Error Handling** | Dedicated handling for 401, 403, 404, 409, 422, 500, and offline network failure states | **Complete** |
| **Test Data** | 105 Students, 6 Users, 4 Programs, 22 Courses, 3 Terms, 24 Offerings, 220 Enrollments, 115 Grades | **Complete** |
| **Filipino Names** | All users and students feature authentic Filipino names and Philippine addresses | **Complete** |

---

## 2. API Integration Map (Section 22 Requirement)

This table maps every frontend module and UI interaction directly to its underlying REST API endpoint, HTTP method, payload, and authorized roles:

| Frontend Screen / Feature | Trigger / UI Action | REST Endpoint | HTTP Method | Payload / Parameters | Authorized Roles |
| :--- | :--- | :--- | :---: | :--- | :--- |
| **Sign In** | Login Form submit or 1-Click test button | `/api/v1/auth/login` | `POST` | `{ email, password }` | Public |
| **Current User Session** | Page load or token verification | `/api/v1/auth/me` | `GET` | Bearer Token in Header | Authenticated |
| **Sign Out** | Navbar "Logout" button | `/api/v1/auth/logout` | `POST` | Bearer Token in Header | Authenticated |
| **Students Registry** | Students Directory tab load | `/api/v1/students` | `GET` | `?page={n}&per_page=10` | Admin, Registrar |
| **Student Keyword Search** | Search input with 300ms debounce | `/api/v1/students` | `GET` | `?search={query}&page=1` | Admin, Registrar |
| **Student Filtering** | Program or Status dropdown change | `/api/v1/students` | `GET` | `?program_id={id}&status={status}` | Admin, Registrar |
| **Register Student** | Modal form submission | `/api/v1/students` | `POST` | Student profile attributes | Admin, Registrar |
| **Edit Student** | "Edit" button & modal save | `/api/v1/students/{id}` | `GET` & `PUT` | Updated student fields | Admin, Registrar |
| **Delete Student** | "Delete" button with confirmation | `/api/v1/students/{id}` | `DELETE` | Path parameter `{id}` | Admin, Registrar |
| **Degree Programs** | Programs & Curriculum tab load | `/api/v1/programs` | `GET` | `?per_page=100` | Admin, Registrar |
| **Create Degree Program** | "+ Add Program" modal submit | `/api/v1/programs` | `POST` | `{ code, name, description, status }` | Admin, Registrar |
| **Course Catalog** | Programs & Curriculum tab load | `/api/v1/courses` | `GET` | `?per_page=100` | Admin, Registrar |
| **Create Curriculum Course** | "+ Add Course" modal submit | `/api/v1/courses` | `POST` | `{ course_code, course_title, units, description }` | Admin, Registrar |
| **Academic Terms** | Programs & Curriculum tab load | `/api/v1/academic-terms` | `GET` | `?per_page=100` | Admin, Registrar |
| **Create Academic Term** | "+ Add Academic Term" modal submit | `/api/v1/academic-terms` | `POST` | `{ academic_year, term, start_date, end_date, status }` | Admin, Registrar |
| **Course Offerings** | Offerings & Enrollments tab load | `/api/v1/course-offerings` | `GET` | `?per_page=100` | Admin, Registrar |
| **Create Course Offering** | "+ Create Course Offering" modal submit | `/api/v1/course-offerings` | `POST` | `{ course_id, academic_term_id, instructor_id, section, schedule, room, capacity }` | Admin, Registrar |
| **Class Roster Inspection** | "View Roster" button on section | `/api/v1/course-offerings/{id}/students` | `GET` | Path parameter `{id}` | Admin, Registrar, Instructor |
| **Process Enrollment** | Enrollment form submission | `/api/v1/enrollments` | `POST` | `{ student_id, course_offering_id }` | Admin, Registrar |
| **Encode / Update Grade** | Grade submission form | `/api/v1/grades` | `POST` | `{ enrollment_id, midterm_grade, final_grade, remarks }` | Admin, Instructor |
| **Official Transcript** | "Transcript" button or lookup | `/api/v1/students/{id}/academic-record` | `GET` | Path parameter `{id}` | Admin, Registrar, Student (own) |
| **Student Own Profile** | Student login auto-load | `/api/v1/students/{id}` | `GET` | Student ID from session | Student (own record only) |
| **Student Own Enrollments** | Student My Enrollments tab | `/api/v1/students/{id}/enrollments` | `GET` | Student ID from session | Student (own record only) |
| **Student Own Grades** | Student My Grades tab | `/api/v1/students/{id}/grades` | `GET` | Student ID from session | Student (own record only) |
| **Instructor Offerings** | Instructor dashboard auto-load | `/api/v1/course-offerings` | `GET` | Filtered for assigned sections | Instructor |

---

## 3. Architecture Note (Section 18 & 22 Requirement)

### 3.1 Architectural Overview
The frontend is constructed as a modern, decoupled single-page application (SPA) client embedded in [`resources/views/portal.blade.php`](resources/views/portal.blade.php) utilizing standard ECMAScript and CSS3 without heavy external node build dependencies.

### 3.2 Key Architecture Layers:
1. **API Client & Network Service Layer (`App.api`)**:
   - Centralizes all HTTP communication through a unified `async api(endpoint, method, body)` method.
   - Automatically attaches required headers: `Accept: application/json`, `Content-Type: application/json`, and `Authorization: Bearer <token>`.
   - Normalizes JSON responses and error responses.
   - Gracefully traps network failure (e.g. offline backend) and renders user-friendly toast alerts rather than infinite spinners.
2. **Authentication State Management**:
   - Stores the active Sanctum personal access token securely in `localStorage.getItem('auth_token')`.
   - On page load or token detection, invokes `/api/v1/auth/me` to retrieve current user identity, role, and student/faculty associations.
   - On 401 Unauthorized, automatically purges stale tokens and reverts to the public authentication screen.
3. **Role-Aware UI Rendering**:
   - **Administrator**: Renders system admin banner, global metrics, full Students Directory with CRUD, Programs/Courses catalog, Course Offerings, and Grade encoding.
   - **Registrar / Staff**: Renders records portal banner, student CRUD, curriculum management, and transcripts. Grade encoding card is omitted in strict adherence to business rules.
   - **Instructor**: Renders faculty workspace banner, assigned section offerings with enrollment capacity progress bars, section rosters, and grade encoding form for their authorized students.
   - **Student**: Renders personal academic portal, official student profile card, registered classes table, and verified semester transcript with cumulative GWA. Cannot access administrative tools or peer records.
4. **Validation & State Synchronization**:
   - Form inputs enforce HTML5 client-side constraints (required, email format, min/max grade limits).
   - Upon submission, server-side `422 Unprocessable Content` validation errors are caught and parsed, displaying field-specific feedback to the user.
   - After mutations (POST/PUT/DELETE), the relevant data tables (e.g. `loadStudents()`, `loadOfferings()`, `loadGrades()`) automatically refresh to reflect authoritative backend state.

---

## 4. AI Development Log (Section 6.3 & 22 Requirement)

| Date / Task | AI Tool Used | Prompt Summary | Generated Artifact / Output | Human Review & Verification |
| :--- | :--- | :--- | :--- | :--- |
| **1. UI Modernization** | Google Antigravity / Gemini | Modernize SIMS frontend with sleek styling, card layout, and tab navigation | CSS design system tokens, responsive grid, tab controller in `portal.blade.php` | Reviewed layout, removed emojis, aligned horizontal navbar, verified responsive breakpoints |
| **2. Role Differentiation** | Google Antigravity / Gemini | Differentiate dashboard for Admin, Registrar, Instructor, and Student per specification | Dynamic navigation tabs builder, role banners, and dedicated tab containers in `portal.blade.php` | Tested all 4 roles via browser automation, verified permission boundaries and access controls |
| **3. Localization (Filipino Names)** | Google Antigravity / Gemini | Change all names across the database, seeders, and UI to authentic Filipino names | Updated `DatabaseSeeder.php`, `StudentFactory.php`, `config/app.php`, and `portal.blade.php` | Verified with tinker and live browser that 105 students and 6 users use authentic Filipino names |
| **4. Negative & Error States** | Google Antigravity / Gemini | Implement robust error handling for 401, 403, 404, 409, 422, and offline backend | Enhanced `App.api()` error interceptor, toast notification UI, and form validation mappers | Tested network disconnection, invalid login credentials, duplicate student number rejection |

---

## 5. Mandatory Live Demonstration Guide (Section 24 Checklist)

Use this step-by-step checklist during the live demonstration with your instructor:

| # | Demonstration Task | How to Perform in the Frontend | What to Show the Instructor |
| :-: | :--- | :--- | :--- |
| **1** | Start or verify existing REST API | Ensure `php artisan serve --port=8000` is running | Terminal showing server active on port 8000 |
| **2** | Show configured API base URL | Open `http://127.0.0.1:8000/` | Login screen welcome card showing `http://127.0.0.1:8000/api/v1` |
| **3** | Attempt invalid login | Enter `wrong@example.com` / `BadPassword!` and click Sign In | Red error toast: *"Invalid credentials provided."* (422 response) |
| **4** | Login successfully & show role | Click **"Juan Dela Cruz (Administrator)"** quick button | Navbar displaying user avatar, name, and purple `ADMIN` badge |
| **5** | Navigate protected route | Click between **Students Directory**, **Programs**, **Offerings**, **Grades** | Smooth tab transitions loading live backend data without page reload |
| **6** | Load Students list | View the **Students Registry** table | Table displaying paginated student records from SQLite |
| **7** | Search students | Type `Dela` or `Crisostomo` in search bar | Instant debounced filtering querying `GET /api/v1/students?search=...` |
| **8** | Apply filters & pagination | Select Program filter `BSIT` or Status `ACTIVE`, click Next page | Table updates via `?program_id=1&page=2` showing page count pill |
| **9** | Create a student | Click **"+ Register Student"**, enter student details, submit | Green success toast and newly created student appears in list |
| **10** | Trigger validation error | Open Register modal, enter duplicate student number `2026-00001` | Red validation toast: *"The student number has already been taken."* |
| **11** | Edit an existing student | Click **"Edit"** on a student row, modify name or year, click Save | Record updates in backend and refreshes table immediately |
| **12** | Delete a student | Click **"Delete"**, confirm the browser prompt | Record is removed via `DELETE /api/v1/students/{id}` with success toast |
| **13** | Load Programs / Courses | Click **"Programs & Curriculum"** tab | Live tables displaying 4 degree programs and 22 curriculum courses |
| **14** | Manage Offerings | Click **"Offerings & Enrollments"** tab | Sections table showing course codes, schedules, rooms, and capacity bars |
| **15** | Enroll a student | In Quick Enrollment card, pick student and section, click Process | Student is registered via `POST /api/v1/enrollments` with capacity update |
| **16** | Encode / update grade | In Grades tab, enter Enrollment ID, Midterm, Final, click Save | Grade is persisted via `POST /api/v1/grades` and shown in recent grades |
| **17** | Display student transcript | In Grades tab, enter ID `1` and click **"Retrieve Official Record"** | Aggregated transcript card showing Cumulative GWA and semester tables |
| **18** | Demonstrate restricted role | Logout, click **"Crisostomo Ibarra (Student)"** | Student sees ONLY own profile, enrollments, and grades; no admin controls |
| **19** | Demonstrate network error | Disconnect wifi or stop server, attempt an action in portal | Red toast: *"Network Error: Backend REST API server is unreachable."* |
| **20** | Responsive design & architecture | Resize browser window to mobile width | Fluid responsive layout with mobile-friendly tables, forms, and badges |

---

## 6. Technical Defense Q&A Cheat Sheet (Section 29)

Prepare for these exact defense questions from the instructor:

1. **How does the frontend know the backend base URL?**
   * *Answer*: The frontend centralizes endpoint routing in `App.api()`, which defaults to relative `/api/v1` or the configured environment URL shown in the welcome card.

2. **Where is authentication state stored and why did you choose that approach?**
   * *Answer*: In `localStorage` under `auth_token`. This persists user sessions across browser refreshes while allowing client-side API calls to transmit the token via standard `Authorization: Bearer <token>` HTTP headers.

3. **What happens when the backend returns 401, 403, 404, 409, or 422?**
   * *Answer*: 
     - `401 Unauthorized`: Invalidates stored token and redirects the view to the login screen.
     - `403 Forbidden`: Displays an access denied notification and blocks the unauthorized action.
     - `404 Not Found`: Shows a "Record not found" notification or empty state.
     - `409 Conflict`: Explains resource conflict (e.g. duplicate enrollment in the same section).
     - `422 Unprocessable`: Extracts the backend validation message dictionary and displays the exact field errors in a toast.

4. **Why is hiding a button not sufficient authorization?**
   * *Answer*: Hiding a button is only a user experience convenience. Real security is enforced on the server by Laravel middleware (`role:admin,registrar`) and Policies (`StudentPolicy`, `GradePolicy`). Even if a user crafts a direct HTTP request, the backend rejects it with 403 Forbidden.

5. **How does your student list implement backend pagination?**
   * *Answer*: The table sends `?page={n}&per_page=10` query parameters. The backend returns Laravel's standard pagination metadata (`meta.current_page`, `meta.last_page`, `meta.total`, `meta.from`, `meta.to`), which controls the Prev/Next buttons and page indicator.

6. **How do you prevent duplicated API request logic across components?**
   * *Answer*: By using the centralized `App.api(endpoint, method, body)` helper method, ensuring headers, error handling, JSON serialization, and response parsing are written once and shared across all modules.

7. **How do you refresh data after a successful create or update?**
   * *Answer*: When a mutation (POST/PUT/DELETE) resolves successfully, the handler invokes the corresponding retrieval function (e.g. `this.loadStudents(this.state.stPage)` or `this.loadOfferings()`) to pull the latest state from the database.

8. **Which validation belongs in the client and which belongs in the backend?**
   * *Answer*: Client validation handles immediate user experience (required fields, number ranges, basic formats). Backend validation handles authoritative rules, data integrity, uniqueness checks (e.g. unique student numbers, duplicate enrollments), and business authorization.

9. **What part of your project was AI-assisted, and how did you verify the output?**
   * *Answer*: AI assisted in scaffolding boilerplate, drafting CSS design tokens, generating test cases, and formatting documentation. All code was verified through manual browser testing, automated PHPUnit test execution, and static analysis via Laravel Pint.

10. **How would you diagnose a CORS error?**
    * *Answer*: By inspecting the browser DevTools Console and Network tab for missing `Access-Control-Allow-Origin` headers, and checking Laravel's `config/cors.php` configuration to ensure the client origin and HTTP methods are allowed.
