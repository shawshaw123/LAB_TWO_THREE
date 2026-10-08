# Technical Architecture & Design Document

## Student Information Management REST API

### 1. Architectural Overview

The application follows an enterprise layered architecture leveraging **PHP 8.5** and the **Laravel Framework**. It strictly separates HTTP delivery, validation, authorization, business domain logic, and data persistence layers.

```
       HTTP Client (Postman / Browser / Swagger UI)
                           │
                           ▼
          [Laravel Routing Layer (/api/v1)]
                           │
                           ▼
     [Middleware Pipeline (Sanctum Auth & Role Guard)]
                           │
                           ▼
      [FormRequest Layer (Server-Side Validation)]
                           │
                           ▼
     [Policy Layer (Object-Level Authorization)]
                           │
                           ▼
     [Resource Controllers & Domain Aggregators]
                           │
                           ▼
   [Eloquent ORM & Query Scopes (Prepared PDO)]
                           │
                           ▼
       [Relational Database (SQLite / MySQL)]
```

---

### 2. Major Design Decisions

1. **API Versioning**:
   - All RESTful endpoints are grouped under `/api/v1` to support future non-breaking updates.
   - Resource-oriented URLs using plural nouns (`/students`, `/course-offerings`, `/grades`) with standard HTTP verbs (`GET`, `POST`, `PUT`, `PATCH`, `DELETE`).

2. **Consistent Response Envelope (Section 9)**:
   - **Success Envelope**:
     ```json
     {
       "success": true,
       "message": "Human-readable status summary.",
       "data": { ... },
       "meta": { "current_page": 1, "per_page": 20, "total": 105, "last_page": 6 }
     }
     ```
   - **Error Envelope**:
     ```json
     {
       "success": false,
       "message": "Error description.",
       "errors": { "field": ["Specific error reason."] }
     }
     ```
   - Standardized via the `ApiResponse` trait and handled globally in `bootstrap/app.php` for unhandled exceptions (`ModelNotFoundException`, `ValidationException`, `AuthenticationException`, `AccessDeniedHttpException`).

3. **Data Integrity Safeguards (Section 7.2)**:
   - Composite unique constraint `['student_id', 'course_offering_id']` on `enrollments` table.
   - Unique constraints on `student_number`, `course_code`, `program_code`, and `user.email`.
   - Foreign key integrity cascading down on associated offerings, enrollments, and grades.

4. **Object-Level Authorization (Section 12.2)**:
   - Implemented in `StudentPolicy` and `GradePolicy`.
   - Prevents unauthorized cross-tenant data exfiltration: a student authenticated under ID 1 cannot inspect ID 2 merely by altering the URL (`/api/v1/students/2/academic-record` returns `403 Forbidden`).
   - Instructors can only view and update grades for course offerings specifically assigned to them.

---

### 3. Security Approach (Section 13)

- **Authentication**: Stateful and stateless token issuance using **Laravel Sanctum**. Personal access tokens are securely hashed in the database and revoked on logout.
- **Password Hashing**: Cryptographic password hashing using Bcrypt (`Hash::make()`) with configurable cost rounds.
- **SQL Injection Prevention**: All queries utilize Eloquent ORM or parameterized PDO bindings; raw untrusted SQL interpolation is prohibited.
- **Data Protection**: Sensitive attributes such as `password` and `remember_token` are hidden from serialization.
- **Information Leak Prevention**: Detailed stack traces and environment secrets are masked in API responses; standardized error structures are returned uniformly.

---

### 4. AI-Assisted Engineering Workflow & Accountability (Section 3 & 20)

In accordance with the laboratory guidelines:

1. **Requirements Decomposition**:
   - The PDF specifications were decomposed into 10 development phases, identifying all domain constraints, quota requirements, and acceptance criteria.
2. **Scaffolding & Code Generation**:
   - Model migrations, relationships, FormRequests, policies, and controllers were generated using structured Laravel conventions.
3. **Review & Human Verification**:
   - Every generated file was reviewed against framework standards (PHP 8 constructor promotion, strict typing, Pint styling).
   - Fixed schema compatibility (`decimal` vs `unsignedDecimal` migration compatibility).
4. **Automated Test Coverage**:
   - 30 comprehensive PHPUnit feature tests were designed and executed, ensuring 100% test passing rate across authentication, CRUD, validation, object-level authorization, duplicate handling, and collections.
