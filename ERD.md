# Entity Relationship Diagram (ERD)

### Student Information Management System REST API

```mermaid
erDiagram
    USERS ||--o{ STUDENTS : "owns profile (user_id)"
    USERS ||--o{ COURSE_OFFERINGS : "teaches (instructor_id)"
    PROGRAMS ||--o{ STUDENTS : "enrolls (program_id)"
    COURSES ||--o{ COURSE_OFFERINGS : "offered in (course_id)"
    ACADEMIC_TERMS ||--o{ COURSE_OFFERINGS : "scheduled in (academic_term_id)"
    STUDENTS ||--o{ ENROLLMENTS : "has (student_id)"
    COURSE_OFFERINGS ||--o{ ENROLLMENTS : "contains (course_offering_id)"
    ENROLLMENTS ||--o| GRADES : "receives (enrollment_id)"

    USERS {
        bigint id PK
        string name
        string email UK
        string password
        string role "admin, registrar, instructor, student"
        string status "ACTIVE, INACTIVE"
        datetime email_verified_at
        string remember_token
        timestamp created_at
        timestamp updated_at
    }

    PROGRAMS {
        bigint id PK
        string code UK "e.g. BSIT, BSCS"
        string name
        text description
        string status "ACTIVE, INACTIVE"
        timestamp created_at
        timestamp updated_at
    }

    STUDENTS {
        bigint id PK
        bigint user_id FK "nullable"
        string student_number UK "Indexed, e.g. 2026-00001"
        string first_name
        string middle_name
        string last_name
        string suffix
        date birth_date
        string email UK "Indexed"
        string contact_number
        text address
        bigint program_id FK
        tinyint year_level "1 - 5"
        string status "ACTIVE, INACTIVE, GRADUATED, DROPPED"
        timestamp created_at
        timestamp updated_at
    }

    COURSES {
        bigint id PK
        string course_code UK "Indexed, e.g. CS201"
        string course_title
        text description
        decimal units "Default: 3.0"
        string status "ACTIVE, INACTIVE"
        timestamp created_at
        timestamp updated_at
    }

    ACADEMIC_TERMS {
        bigint id PK
        string academic_year "Indexed, e.g. 2025-2026"
        string term "Indexed, e.g. 1st Semester"
        date start_date
        date end_date
        string status "ACTIVE, INACTIVE"
        timestamp created_at
        timestamp updated_at
    }

    COURSE_OFFERINGS {
        bigint id PK
        bigint course_id FK
        bigint academic_term_id FK
        bigint instructor_id FK
        string section "e.g. BSIT-3A"
        string schedule "e.g. MWF 08:00-09:30"
        string room "e.g. Lab 301"
        smallint capacity "Default: 40"
        string status "OPEN, CLOSED, CANCELLED"
        timestamp created_at
        timestamp updated_at
    }

    ENROLLMENTS {
        bigint id PK
        bigint student_id FK
        bigint course_offering_id FK
        date enrollment_date
        string status "ENROLLED, DROPPED, COMPLETED"
        timestamp created_at
        timestamp updated_at
    }

    GRADES {
        bigint id PK
        bigint enrollment_id FK, UK
        decimal midterm_grade "1.00 - 5.00"
        decimal final_grade "1.00 - 5.00"
        string remarks "PASSED, FAILED, INCOMPLETE, DROPPED"
        timestamp created_at
        timestamp updated_at
    }
```

## Relational Constraints & Data Integrity

1. **Unique Constraints**:
   - `users.email` is unique.
   - `students.student_number` is unique.
   - `students.email` is unique.
   - `programs.code` is unique.
   - `courses.course_code` is unique.
   - `grades.enrollment_id` is unique (one-to-one relationship with enrollment).
   - **Composite Unique Index**: `enrollments(student_id, course_offering_id)` strictly prevents duplicate student enrollments in the same offering.

2. **Foreign Key Cascades**:
   - `students.program_id` cascades on delete.
   - `students.user_id` nulls on delete.
   - `course_offerings.course_id`, `academic_term_id`, `instructor_id` cascade on delete.
   - `enrollments.student_id`, `course_offering_id` cascade on delete.
   - `grades.enrollment_id` cascades on delete.
