# Student Information Management System - Frontend Client

This is the standalone decoupled frontend client for **Laboratory Activity III: AI-Assisted Frontend Framework Integration with an Existing REST API**.

---

## Architecture & Features

- **Decoupled Client Architecture**: Pure frontend communicating exclusively over HTTP with the Laravel REST API at `/api/v1` using standard `fetch()` and Bearer token authentication.
- **Configurable API Base URL**: Dynamic base URL resolution configurable via UI modal, `localStorage.getItem('api_base_url')`, or `.env.example` templates.
- **Role-Aware Dashboards & Protected Routes**: Tailored experiences and dynamic navigation tabs for **Administrator**, **Registrar / Staff**, **Instructor**, and **Student**.
- **Complete Module Coverage**:
  - **Authentication**: Token-based Sanctum authentication, `/api/v1/auth/me`, logout token revocation, and automatic 401 session expiry handling.
  - **Students Registry**: Collection queries, debounced search, multi-field filters (program, status), backend pagination metadata (`meta`), create modal, edit modal, and delete with confirmation.
  - **Academic Programs**: Degree programs listing and "+ Add Program" modal form (`POST /api/v1/programs`).
  - **Course Catalog**: Course curriculum listing and "+ Add Course" modal form (`POST /api/v1/courses`).
  - **Academic Terms**: School year and semester terms listing and "+ Add Academic Term" modal form (`POST /api/v1/academic-terms`).
  - **Course Offerings & Section Schedules**: Sections listing, instructor assignment, capacity utilization progress bar, "+ Create Course Offering" modal form (`POST /api/v1/course-offerings`), and class roster inspection modal.
  - **Student Enrollment**: Section registration form with duplicate conflict detection (`409 Conflict / 422`).
  - **Grades & Transcripts**: Authorized grade submission form (`POST /api/v1/grades`), recent submissions table, and official aggregated student academic record lookup with Cumulative GWA calculation.
  - **Role-Specific Faculty & Student Workspaces**: Instructor-only assigned sections & grading; Student-only personal profile, enrolled classes, and verified transcript.
- **Negative & Error States**: Dedicated toast notifications for `401 Unauthorized`, `403 Forbidden`, `404 Not Found`, `409 Conflict`, `422 Unprocessable Content` (field validation dictionary mapping), `500+ Server Error`, and offline network failures.

---

## Quick Start & Running the Project

### 1. Start the Backend REST API
In the `backend/` directory:
```bash
cd ../backend
php artisan serve --port=8000
```
The API is available at `http://127.0.0.1:8000`.

### 2. Run the Frontend Client
You can run the frontend client using either of these methods:
- **Option A (Static Web Server)**:
  ```bash
  npx serve .
  ```
  Open `http://localhost:3000` (or the port indicated).
- **Option B (Direct Browser File)**:
  Open `index.html` directly in Google Chrome, Microsoft Edge, or Mozilla Firefox.
- **Option C (Integrated Laravel View)**:
  Navigate to `http://127.0.0.1:8000/`.

---

## Running the Automated Frontend Test Suite

Run the included automated test runner to execute **13 automated end-to-end and integration tests** against the REST API:

```bash
# Using npm
npm test

# Or using Node directly
node tests/run-tests.mjs
```

### Test Coverage Highlights:
1. `Auth Flow`: Successful Admin login, Bearer token extraction, and `/auth/me` identity.
2. `Auth Flow`: Invalid password rejection (`422 Unprocessable Content`).
3. `Students Registry`: Paginated collection retrieval with metadata verification.
4. `Students Registry`: Keyword search query (`?search=Crisostomo`).
5. `Form Validation`: Duplicate student number validation mapping (`422 Unprocessable Content`).
6. `Authorization Guard`: Student role forbidden from creating degree programs (`403 Forbidden`).
7. `Reference Data`: Retrieval of Academic Programs, Courses, and Academic Terms.
8. `Offerings Module`: Section roster inspection (`/course-offerings/{id}/students`).
9. `Enrollment Conflict`: Duplicate student enrollment rejection (`409 Conflict / 422`).
10. `Academic Record`: Aggregated student record and Cumulative GWA calculation.
11. `Error Handling`: Non-existent resource ID returns `404 Not Found`.
12. `Session Lifecycle`: Token invalidation on logout and subsequent `401` rejection.

See [`evidence/TEST_EVIDENCE.md`](../evidence/TEST_EVIDENCE.md) for complete documented outputs and logs.
