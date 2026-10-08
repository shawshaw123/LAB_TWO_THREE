/**
 * Laboratory Activity III: Frontend Integration Automated Test Suite
 * Validates authentication flows, API list queries, pagination, 422 validation,
 * 403 role authorization guards, conflict rejection, and academic records.
 */

const BASE_URL = process.env.API_BASE_URL || 'http://127.0.0.1:8000/api/v1';

let testsPassed = 0;
let testsFailed = 0;
const results = [];

function assert(condition, message) {
    if (!condition) {
        throw new Error(message);
    }
}

async function test(name, fn) {
    process.stdout.write(`  Testing: ${name}... `);
    try {
        await fn();
        testsPassed++;
        results.push({ name, status: 'PASSED' });
        console.log(`[PASS]`);
    } catch (err) {
        testsFailed++;
        results.push({ name, status: 'FAILED', error: err.message });
        console.log(`[FAIL] -> ${err.message}`);
    }
}

async function api(endpoint, method = 'GET', body = null, token = null) {
    const headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    };
    if (token) headers['Authorization'] = `Bearer ${token}`;

    const res = await fetch(`${BASE_URL}${endpoint}`, {
        method,
        headers,
        body: body ? JSON.stringify(body) : null
    });

    const json = await res.json().catch(() => ({}));
    return {
        status: res.status,
        ok: res.ok,
        raw: json,
        data: json.data !== undefined ? json.data : json,
        meta: json.meta,
        errors: json.errors,
        message: json.message
    };
}

console.log('='.repeat(70));
console.log(' LABORATORY ACTIVITY III: FRONTEND API AUTOMATED TEST SUITE');
console.log(` Target API: ${BASE_URL}`);
console.log('='.repeat(70));

async function run() {
    let adminToken = '';
    let studentToken = '';

    // 1. Authentication Flow: Valid Login
    await test('Auth Flow - Successful Admin Login (200 OK & Token issued)', async () => {
        const res = await api('/auth/login', 'POST', {
            email: 'admin@example.com',
            password: 'Password123!'
        });
        assert(res.status === 200, `Expected 200, got ${res.status}`);
        assert(res.data?.token, 'Token not received in login response');
        assert(res.data?.user?.role === 'admin', 'Expected user role to be admin');
        adminToken = res.data.token;
    });

    // 2. Authentication Flow: Invalid Credentials Rejection
    await test('Auth Flow - Invalid Password Rejected (422 Unprocessable Content)', async () => {
        const res = await api('/auth/login', 'POST', {
            email: 'admin@example.com',
            password: 'WrongPassword999!'
        });
        assert(res.status === 422, `Expected 422 validation failure, got ${res.status}`);
        assert(res.message, 'Expected error message in response');
    });

    // 3. Current User Profile (/auth/me)
    await test('Auth Flow - Retrieve Current Session Profile via /auth/me (200 OK)', async () => {
        const res = await api('/auth/me', 'GET', null, adminToken);
        assert(res.status === 200, `Expected 200, got ${res.status}`);
        assert(res.data?.email === 'admin@example.com', 'Mismatched authenticated user email');
    });

    // 4. Students Registry: Collection & Pagination Metadata
    await test('Students Module - Paginated Collection with Metadata (200 OK)', async () => {
        const res = await api('/students?page=1&per_page=10', 'GET', null, adminToken);
        assert(res.status === 200, `Expected 200, got ${res.status}`);
        assert(Array.isArray(res.data), 'Expected data array of students');
        assert(res.meta, 'Expected pagination meta in response');
        assert(res.meta.current_page === 1, 'Expected current_page == 1');
        assert(res.meta.per_page === 10, 'Expected per_page == 10');
        assert(res.meta.total > 0, 'Expected non-zero total students');
    });

    // 5. Students Registry: Search Query Parameter
    await test('Students Module - Filter by Search Keyword (200 OK)', async () => {
        const res = await api('/students?search=Crisostomo', 'GET', null, adminToken);
        assert(res.status === 200, `Expected 200, got ${res.status}`);
        assert(Array.isArray(res.data) && res.data.length >= 1, 'Expected at least 1 matching search result');
        const match = res.data[0];
        assert(
            match.first_name.includes('Crisostomo') || match.full_name.includes('Crisostomo'),
            'Search result did not match keyword'
        );
    });

    // 6. Form Validation Error Mapping (422)
    await test('Form Validation - Reject Duplicate Student Number (422 Unprocessable)', async () => {
        const res = await api('/students', 'POST', {
            student_number: '2026-00001', // Pre-existing student number in seed data
            first_name: 'Test',
            last_name: 'Duplicate',
            email: 'duplicate.test@example.com',
            program_id: 1,
            year_level: 1,
            birth_date: '2004-01-01'
        }, adminToken);
        assert(res.status === 422, `Expected 422, got ${res.status}`);
        assert(res.errors && res.errors.student_number, 'Expected errors.student_number field error');
    });

    // 7. Role-Based Authorization Guard (403 Forbidden)
    await test('Authorization Guard - Student Forbidden from Creating Degree Programs (403)', async () => {
        // First log in as Student
        const loginRes = await api('/auth/login', 'POST', {
            email: 'student@example.com',
            password: 'Password123!'
        });
        assert(loginRes.status === 200, 'Student login failed');
        studentToken = loginRes.data.token;

        // Attempt restricted admin action with student token
        const res = await api('/programs', 'POST', {
            code: 'HACK',
            name: 'Unauthorized Program'
        }, studentToken);
        assert(res.status === 403, `Expected 403 Forbidden, got ${res.status}`);
    });

    // 8. Academic Reference Data (Programs, Courses, Terms)
    await test('Reference Data - Load Academic Terms, Programs, & Courses (200 OK)', async () => {
        const [pRes, cRes, tRes] = await Promise.all([
            api('/programs?per_page=10', 'GET', null, adminToken),
            api('/courses?per_page=10', 'GET', null, adminToken),
            api('/academic-terms?per_page=10', 'GET', null, adminToken)
        ]);
        assert(pRes.status === 200 && pRes.data.length > 0, 'Failed to fetch programs');
        assert(cRes.status === 200 && cRes.data.length > 0, 'Failed to fetch courses');
        assert(tRes.status === 200 && tRes.data.length > 0, 'Failed to fetch academic terms');
    });

    // 9. Course Offerings & Roster Inspection
    await test('Offerings Module - Inspect Class Section Roster (200 OK)', async () => {
        const res = await api('/course-offerings/1/students', 'GET', null, adminToken);
        assert(res.status === 200, `Expected 200, got ${res.status}`);
        assert(Array.isArray(res.data), 'Expected array of enrolled students');
    });

    // 10. Enrollments Conflict Prevention
    await test('Enrollments Module - Reject Duplicate Enrollment Conflict (409/422)', async () => {
        // In seed data, Student 1 is already enrolled in Offering 1
        const res = await api('/enrollments', 'POST', {
            student_id: 1,
            course_offering_id: 1
        }, adminToken);
        assert(
            res.status === 409 || res.status === 422,
            `Expected 409 Conflict or 422 Validation Error, got ${res.status}`
        );
    });

    // 11. Official Student Academic Record & GWA
    await test('Academic Record - Retrieve Aggregated Student Transcript (200 OK)', async () => {
        const res = await api('/students/1/academic-record', 'GET', null, adminToken);
        assert(res.status === 200, `Expected 200, got ${res.status}`);
        assert(res.data?.student, 'Expected student profile in academic record');
        assert(res.data?.summary, 'Expected summary statistics in academic record');
        assert(res.data?.summary?.cumulative_gwa !== undefined, 'Expected cumulative_gwa calculation in summary');
        assert(Array.isArray(res.data?.academic_terms), 'Expected academic_terms array in transcript');
    });

    // 12. Resource Not Found Handling (404)
    await test('Error Handling - Nonexistent Resource Returns 404 Not Found', async () => {
        const res = await api('/students/999999', 'GET', null, adminToken);
        assert(res.status === 404, `Expected 404 Not Found, got ${res.status}`);
    });

    // 13. Student Self-Registration (POST /auth/register)
    const uniqueEmail = `newstudent_${Date.now()}@example.com`;
    let newStudentToken = '';
    await test('Auth Flow - Student Self-Registration creates User & Student record (201 Created)', async () => {
        const res = await api('/auth/register', 'POST', {
            name: 'New Registered Student',
            email: uniqueEmail,
            password: 'Password123!',
            password_confirmation: 'Password123!',
            program_id: 1,
            year_level: 1,
            birth_date: '2005-08-20'
        });
        assert(res.status === 201, `Expected 201 Created, got ${res.status}`);
        assert(res.data?.token, 'Token not received in registration response');
        assert(res.data?.user?.role === 'student', 'Expected registered user role to be student');
        assert(res.data?.student?.student_number, 'Expected student record with student_number');
        newStudentToken = res.data.token;
    });

    // 14. Instructor Student Visibility Scope (/students)
    let instructorToken = '';
    await test('Instructor Scope - Instructor views only students enrolled in courses they teach', async () => {
        const loginRes = await api('/auth/login', 'POST', {
            email: 'instructor@example.com',
            password: 'Password123!'
        });
        assert(loginRes.status === 200, 'Instructor login failed');
        instructorToken = loginRes.data.token;

        const res = await api('/students?per_page=100', 'GET', null, instructorToken);
        assert(res.status === 200, `Expected 200, got ${res.status}`);
        assert(Array.isArray(res.data), 'Expected array of students');
        assert(res.data.length > 0, 'Expected at least 1 student enrolled in instructor offerings');
    });

    // 15. Instructor Scope Guard - 403 Forbidden when viewing student not taught by instructor
    await test('Instructor Scope Guard - Forbidden (403) from accessing students outside taught courses', async () => {
        const instStudentsRes = await api('/students?per_page=100', 'GET', null, instructorToken);
        const instStudentIds = new Set(instStudentsRes.data.map(s => s.id));

        const allStudentsRes = await api('/students?per_page=100', 'GET', null, adminToken);
        const outsideStudent = allStudentsRes.data.find(s => !instStudentIds.has(s.id));

        if (outsideStudent) {
            const forbiddenRes = await api(`/students/${outsideStudent.id}`, 'GET', null, instructorToken);
            assert(forbiddenRes.status === 403, `Expected 403 Forbidden for outside student, got ${forbiddenRes.status}`);

            const forbiddenRecRes = await api(`/students/${outsideStudent.id}/academic-record`, 'GET', null, instructorToken);
            assert(forbiddenRecRes.status === 403, `Expected 403 Forbidden for outside student transcript, got ${forbiddenRecRes.status}`);
        }
    });

    // 16. Authentication Flow: Logout Token Invalidation
    await test('Auth Flow - Logout and Revoke Personal Access Token (200 OK)', async () => {
        const res = await api('/auth/logout', 'POST', null, adminToken);
        assert(res.status === 200, `Expected 200, got ${res.status}`);

        // Confirm token is revoked
        const testRevoked = await api('/auth/me', 'GET', null, adminToken);
        assert(testRevoked.status === 401, `Expected 401 Unauthenticated after logout, got ${testRevoked.status}`);
    });

    console.log('='.repeat(70));
    console.log(` SUMMARY: ${testsPassed} PASSED, ${testsFailed} FAILED out of ${testsPassed + testsFailed} tests.`);
    console.log('='.repeat(70));

    if (testsFailed > 0) {
        process.exit(1);
    }
}

run().catch((err) => {
    console.error('Fatal Test Runner Error:', err);
    process.exit(1);
});
