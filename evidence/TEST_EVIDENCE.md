# Laboratory Activity III: Automated Test Suite & Verification Evidence

This document contains automated testing evidence, test execution logs, and verification outputs across both the **Frontend Single-Page Client** and the **Backend Laravel 12 REST API**, satisfying **Section 20, 21, 22, and Required Output 7**.

---

## 1. Frontend Client Automated Test Suite (`frontend/tests/run-tests.mjs`)

- **Execution Command**: `npm test` or `node tests/run-tests.mjs`
- **Target REST API**: `http://127.0.0.1:8000/api/v1`
- **Test Runner**: Node.js ES Module Automated Test Suite
- **Result**: **13 PASSED, 0 FAILED (100% Success Rate)**

### Test Execution Log Output:
```text
======================================================================
 LABORATORY ACTIVITY III: FRONTEND API AUTOMATED TEST SUITE
 Target API: http://127.0.0.1:8000/api/v1
======================================================================
  Testing: Auth Flow - Successful Admin Login (200 OK & Token issued)... [PASS]
  Testing: Auth Flow - Invalid Password Rejected (422 Unprocessable Content)... [PASS]
  Testing: Auth Flow - Retrieve Current Session Profile via /auth/me (200 OK)... [PASS]
  Testing: Students Module - Paginated Collection with Metadata (200 OK)... [PASS]
  Testing: Students Module - Filter by Search Keyword (200 OK)... [PASS]
  Testing: Form Validation - Reject Duplicate Student Number (422 Unprocessable)... [PASS]
  Testing: Authorization Guard - Student Forbidden from Creating Degree Programs (403)... [PASS]
  Testing: Reference Data - Load Academic Terms, Programs, & Courses (200 OK)... [PASS]
  Testing: Offerings Module - Inspect Class Section Roster (200 OK)... [PASS]
  Testing: Enrollments Module - Reject Duplicate Enrollment Conflict (409/422)... [PASS]
  Testing: Academic Record - Retrieve Aggregated Student Transcript (200 OK)... [PASS]
  Testing: Error Handling - Nonexistent Resource Returns 404 Not Found... [PASS]
  Testing: Auth Flow - Logout and Revoke Personal Access Token (200 OK)... [PASS]
======================================================================
 SUMMARY: 13 PASSED, 0 FAILED out of 13 tests.
======================================================================
```

### Frontend Coverage Details:
| # | Test Scenario | Verified Behavior | HTTP Status |
| :-: | :--- | :--- | :-: |
| 1 | Valid Admin Login | Validates email/password against backend, extracts Sanctum Bearer token and user role | `200 OK` |
| 2 | Invalid Password Rejection | Validates server-side credential rejection with error feedback message | `422 Unprocessable` |
| 3 | Session Profile `/auth/me` | Validates session token transmission via HTTP Bearer header | `200 OK` |
| 4 | Students Pagination | Validates metadata envelope (`current_page`, `per_page`, `total`, `last_page`) | `200 OK` |
| 5 | Debounced Search Query | Validates filtering by student name/number with query parameter `?search=...` | `200 OK` |
| 6 | Server-Side 422 Error Mapping | Verifies validation dictionary mapping (`errors.student_number`) on duplicate attempt | `422 Unprocessable` |
| 7 | Role Authorization Guard (403) | Verifies Student role is rejected when attempting administrative mutations | `403 Forbidden` |
| 8 | Reference Data Loading | Confirms retrieval of Academic Programs, Catalog Courses, and Academic Terms | `200 OK` |
| 9 | Class Roster Inspection | Verifies retrieval of enrolled students list for a course offering section | `200 OK` |
| 10 | Duplicate Enrollment Conflict | Confirms conflict detection (`409/422`) when enrolling same student in same section | `409 Conflict` |
| 11 | Official Academic Record | Verifies aggregated semester groupings, earned units, and Cumulative GWA | `200 OK` |
| 12 | 404 Not Found Handling | Verifies graceful error response on non-existent resource IDs | `404 Not Found` |
| 13 | Session Invalidation on Logout | Verifies token revocation on server and subsequent `401` rejection of revoked token | `200 OK` / `401` |

---

## 2. Backend Automated Test Suite (`backend/tests/Feature/`)

- **Execution Command**: `php artisan test`
- **Framework**: PHPUnit 12 on PHP 8.3+ / Laravel 12
- **Result**: **30 Feature Tests with 92 Assertions PASSED**

### Test Suite Structure:
- `AuthenticationTest`: Valid login, invalid credentials, token generation, 401 unauthenticated guard, logout revocation.
- `StudentTest`: Creation, retrieval, update, duplicate student number rejection (422), email validation, 404 handling.
- `AuthorizationTest`: Admin system access, student restricted access (403), object-level authorization (student unable to view peer records), instructor roster isolation.
- `EnrollmentTest`: Successful enrollment, duplicate enrollment prevention (409 Conflict), invalid foreign keys.
- `GradeTest`: Authorized instructor grade submission, unauthorized instructor rejection (403), grade range validation (1.00 - 5.00).
- `CollectionTest`: Keyword search, multi-field filtering (program, year level), sorting, pagination metadata verification.
