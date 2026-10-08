<?php

$baseUrl = 'http://127.0.0.1:8000/api/v1';

$collection = [
    'info' => [
        'name' => 'Student Information Management REST API',
        'description' => 'Acceptance Demonstration and Complete REST API Test Suite for Student Information Management System (Laravel 12).',
        'schema' => 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json',
    ],
    'variable' => [
        ['key' => 'baseUrl', 'value' => 'http://127.0.0.1:8000/api/v1', 'type' => 'string'],
        ['key' => 'token', 'value' => '', 'type' => 'string'],
        ['key' => 'adminToken', 'value' => '', 'type' => 'string'],
        ['key' => 'studentToken', 'value' => '', 'type' => 'string'],
    ],
    'item' => [
        [
            'name' => '0. Mandatory Acceptance Demo (Section 22 Checklist)',
            'item' => [
                [
                    'name' => 'Demo 01 - Verify API Server & OpenAPI Spec (200 OK)',
                    'request' => [
                        'method' => 'GET',
                        'header' => [['key' => 'Accept', 'value' => 'application/json']],
                        'url' => ['raw' => 'http://127.0.0.1:8000/docs/openapi.json', 'protocol' => 'http', 'host' => ['127', '0', '0', '1'], 'port' => '8000', 'path' => ['docs', 'openapi.json']],
                    ],
                ],
                [
                    'name' => 'Demo 02 - Authenticate Successfully (Admin Login - 200 OK)',
                    'event' => [
                        [
                            'listen' => 'test',
                            'script' => [
                                'type' => 'text/javascript',
                                'exec' => [
                                    "pm.test('Status code is 200 OK', function () { pm.response.to.have.status(200); });",
                                    'var jsonData = pm.response.json();',
                                    'if (jsonData.data && jsonData.data.token) {',
                                    "    pm.collectionVariables.set('token', jsonData.data.token);",
                                    "    pm.collectionVariables.set('adminToken', jsonData.data.token);",
                                    '}',
                                ],
                            ],
                        ],
                    ],
                    'request' => [
                        'method' => 'POST',
                        'header' => [
                            ['key' => 'Accept', 'value' => 'application/json'],
                            ['key' => 'Content-Type', 'value' => 'application/json'],
                        ],
                        'body' => [
                            'mode' => 'raw',
                            'raw' => json_encode(['email' => 'admin@example.com', 'password' => 'Password123!'], JSON_PRETTY_PRINT),
                            'options' => ['raw' => ['language' => 'json']],
                        ],
                        'url' => ['raw' => '{{baseUrl}}/auth/login', 'host' => ['{{baseUrl}}'], 'path' => ['auth', 'login']],
                    ],
                ],
                [
                    'name' => 'Demo 02b - Authenticate as Student (Sets studentToken)',
                    'event' => [
                        [
                            'listen' => 'test',
                            'script' => [
                                'type' => 'text/javascript',
                                'exec' => [
                                    "pm.test('Status code is 200 OK', function () { pm.response.to.have.status(200); });",
                                    'var jsonData = pm.response.json();',
                                    'if (jsonData.data && jsonData.data.token) {',
                                    "    pm.collectionVariables.set('studentToken', jsonData.data.token);",
                                    '}',
                                ],
                            ],
                        ],
                    ],
                    'request' => [
                        'method' => 'POST',
                        'header' => [
                            ['key' => 'Accept', 'value' => 'application/json'],
                            ['key' => 'Content-Type', 'value' => 'application/json'],
                        ],
                        'body' => [
                            'mode' => 'raw',
                            'raw' => json_encode(['email' => 'student@example.com', 'password' => 'Password123!'], JSON_PRETTY_PRINT),
                            'options' => ['raw' => ['language' => 'json']],
                        ],
                        'url' => ['raw' => '{{baseUrl}}/auth/login', 'host' => ['{{baseUrl}}'], 'path' => ['auth', 'login']],
                    ],
                ],
                [
                    'name' => 'Demo 03 - Protected Endpoint Rejects Unauthenticated Request (401 Unauthorized)',
                    'request' => [
                        'method' => 'GET',
                        'header' => [['key' => 'Accept', 'value' => 'application/json']],
                        'url' => ['raw' => '{{baseUrl}}/students', 'host' => ['{{baseUrl}}'], 'path' => ['students']],
                    ],
                ],
                [
                    'name' => 'Demo 04 - Create a Program (201 Created)',
                    'request' => [
                        'auth' => ['type' => 'bearer', 'bearer' => [['key' => 'token', 'value' => '{{adminToken}}', 'type' => 'string']]],
                        'method' => 'POST',
                        'header' => [
                            ['key' => 'Accept', 'value' => 'application/json'],
                            ['key' => 'Content-Type', 'value' => 'application/json'],
                        ],
                        'body' => [
                            'mode' => 'raw',
                            'raw' => json_encode([
                                'code' => 'BSDS',
                                'name' => 'Bachelor of Science in Data Science',
                                'description' => 'Specialized curriculum in data analytics and intelligence.',
                            ], JSON_PRETTY_PRINT),
                            'options' => ['raw' => ['language' => 'json']],
                        ],
                        'url' => ['raw' => '{{baseUrl}}/programs', 'host' => ['{{baseUrl}}'], 'path' => ['programs']],
                    ],
                ],
                [
                    'name' => 'Demo 05 - Create a Valid Student (201 Created)',
                    'request' => [
                        'auth' => ['type' => 'bearer', 'bearer' => [['key' => 'token', 'value' => '{{adminToken}}', 'type' => 'string']]],
                        'method' => 'POST',
                        'header' => [
                            ['key' => 'Accept', 'value' => 'application/json'],
                            ['key' => 'Content-Type', 'value' => 'application/json'],
                        ],
                        'body' => [
                            'mode' => 'raw',
                            'raw' => json_encode([
                                'student_number' => '2026-99001',
                                'first_name' => 'Maria Clara',
                                'last_name' => 'De los Santos',
                                'middle_name' => 'Reyes',
                                'email' => 'maria.delossantos@university.edu.ph',
                                'program_id' => 1,
                                'year_level' => 1,
                                'gender' => 'female',
                                'status' => 'active',
                                'contact_number' => '09171234567',
                                'birth_date' => '2005-06-19',
                                'address' => '45 Sampaguita St., Quezon City',
                            ], JSON_PRETTY_PRINT),
                            'options' => ['raw' => ['language' => 'json']],
                        ],
                        'url' => ['raw' => '{{baseUrl}}/students', 'host' => ['{{baseUrl}}'], 'path' => ['students']],
                    ],
                ],
                [
                    'name' => 'Demo 06 - Reject Invalid or Duplicate Student (422 Unprocessable Content)',
                    'request' => [
                        'auth' => ['type' => 'bearer', 'bearer' => [['key' => 'token', 'value' => '{{adminToken}}', 'type' => 'string']]],
                        'method' => 'POST',
                        'header' => [
                            ['key' => 'Accept', 'value' => 'application/json'],
                            ['key' => 'Content-Type', 'value' => 'application/json'],
                        ],
                        'body' => [
                            'mode' => 'raw',
                            'raw' => json_encode([
                                'student_number' => '2026-00001', // Already exists in database
                                'first_name' => '',
                                'last_name' => 'Test',
                                'email' => 'not-an-email',
                            ], JSON_PRETTY_PRINT),
                            'options' => ['raw' => ['language' => 'json']],
                        ],
                        'url' => ['raw' => '{{baseUrl}}/students', 'host' => ['{{baseUrl}}'], 'path' => ['students']],
                    ],
                ],
                [
                    'name' => 'Demo 07 - Retrieve and Update a Student (200 OK)',
                    'request' => [
                        'auth' => ['type' => 'bearer', 'bearer' => [['key' => 'token', 'value' => '{{adminToken}}', 'type' => 'string']]],
                        'method' => 'PUT',
                        'header' => [
                            ['key' => 'Accept', 'value' => 'application/json'],
                            ['key' => 'Content-Type', 'value' => 'application/json'],
                        ],
                        'body' => [
                            'mode' => 'raw',
                            'raw' => json_encode([
                                'student_number' => '2026-00001',
                                'first_name' => 'Crisostomo',
                                'last_name' => 'Ibarra',
                                'middle_name' => 'Magsalin',
                                'email' => 'student@example.com',
                                'program_id' => 1,
                                'year_level' => 2,
                                'gender' => 'male',
                                'status' => 'active',
                                'contact_number' => '09181234567',
                                'birth_date' => '2004-03-15',
                                'address' => 'San Diego, Laguna',
                            ], JSON_PRETTY_PRINT),
                            'options' => ['raw' => ['language' => 'json']],
                        ],
                        'url' => ['raw' => '{{baseUrl}}/students/1', 'host' => ['{{baseUrl}}'], 'path' => ['students', '1']],
                    ],
                ],
                [
                    'name' => 'Demo 08 - Search and Filter Student Records (200 OK)',
                    'request' => [
                        'auth' => ['type' => 'bearer', 'bearer' => [['key' => 'token', 'value' => '{{adminToken}}', 'type' => 'string']]],
                        'method' => 'GET',
                        'header' => [['key' => 'Accept', 'value' => 'application/json']],
                        'url' => [
                            'raw' => '{{baseUrl}}/students?search=Dela&program_id=1&status=active',
                            'host' => ['{{baseUrl}}'],
                            'path' => ['students'],
                            'query' => [
                                ['key' => 'search', 'value' => 'Dela'],
                                ['key' => 'program_id', 'value' => '1'],
                                ['key' => 'status', 'value' => 'active'],
                            ],
                        ],
                    ],
                ],
                [
                    'name' => 'Demo 09 - Show Pagination and Sorting Metadata (200 OK)',
                    'request' => [
                        'auth' => ['type' => 'bearer', 'bearer' => [['key' => 'token', 'value' => '{{adminToken}}', 'type' => 'string']]],
                        'method' => 'GET',
                        'header' => [['key' => 'Accept', 'value' => 'application/json']],
                        'url' => [
                            'raw' => '{{baseUrl}}/students?page=2&per_page=10&sort_by=last_name&sort_order=asc',
                            'host' => ['{{baseUrl}}'],
                            'path' => ['students'],
                            'query' => [
                                ['key' => 'page', 'value' => '2'],
                                ['key' => 'per_page', 'value' => '10'],
                                ['key' => 'sort_by', 'value' => 'last_name'],
                                ['key' => 'sort_order', 'value' => 'asc'],
                            ],
                        ],
                    ],
                ],
                [
                    'name' => 'Demo 10 - Create Course and Academic Term (201 Created)',
                    'request' => [
                        'auth' => ['type' => 'bearer', 'bearer' => [['key' => 'token', 'value' => '{{adminToken}}', 'type' => 'string']]],
                        'method' => 'POST',
                        'header' => [
                            ['key' => 'Accept', 'value' => 'application/json'],
                            ['key' => 'Content-Type', 'value' => 'application/json'],
                        ],
                        'body' => [
                            'mode' => 'raw',
                            'raw' => json_encode([
                                'code' => 'IT499',
                                'title' => 'Capstone Project & Research',
                                'units' => 3,
                                'lecture_hours' => 3,
                                'lab_hours' => 0,
                                'program_id' => 1,
                                'description' => 'Culminating capstone project and research.',
                            ], JSON_PRETTY_PRINT),
                            'options' => ['raw' => ['language' => 'json']],
                        ],
                        'url' => ['raw' => '{{baseUrl}}/courses', 'host' => ['{{baseUrl}}'], 'path' => ['courses']],
                    ],
                ],
                [
                    'name' => 'Demo 11 - Create Course Offering (201 Created)',
                    'request' => [
                        'auth' => ['type' => 'bearer', 'bearer' => [['key' => 'token', 'value' => '{{adminToken}}', 'type' => 'string']]],
                        'method' => 'POST',
                        'header' => [
                            ['key' => 'Accept', 'value' => 'application/json'],
                            ['key' => 'Content-Type', 'value' => 'application/json'],
                        ],
                        'body' => [
                            'mode' => 'raw',
                            'raw' => json_encode([
                                'course_id' => 1,
                                'academic_term_id' => 1,
                                'instructor_id' => 3,
                                'section' => 'IT-4A',
                                'schedule' => 'MWF 09:00 - 10:30 AM',
                                'room' => 'Lab 305',
                                'max_capacity' => 40,
                                'status' => 'open',
                            ], JSON_PRETTY_PRINT),
                            'options' => ['raw' => ['language' => 'json']],
                        ],
                        'url' => ['raw' => '{{baseUrl}}/course-offerings', 'host' => ['{{baseUrl}}'], 'path' => ['course-offerings']],
                    ],
                ],
                [
                    'name' => 'Demo 12 - Enroll a Student (201 Created)',
                    'request' => [
                        'auth' => ['type' => 'bearer', 'bearer' => [['key' => 'token', 'value' => '{{adminToken}}', 'type' => 'string']]],
                        'method' => 'POST',
                        'header' => [
                            ['key' => 'Accept', 'value' => 'application/json'],
                            ['key' => 'Content-Type', 'value' => 'application/json'],
                        ],
                        'body' => [
                            'mode' => 'raw',
                            'raw' => json_encode([
                                'student_id' => 1,
                                'course_offering_id' => 15,
                            ], JSON_PRETTY_PRINT),
                            'options' => ['raw' => ['language' => 'json']],
                        ],
                        'url' => ['raw' => '{{baseUrl}}/enrollments', 'host' => ['{{baseUrl}}'], 'path' => ['enrollments']],
                    ],
                ],
                [
                    'name' => 'Demo 13 - Prevent Duplicate Enrollment (409 Conflict)',
                    'request' => [
                        'auth' => ['type' => 'bearer', 'bearer' => [['key' => 'token', 'value' => '{{adminToken}}', 'type' => 'string']]],
                        'method' => 'POST',
                        'header' => [
                            ['key' => 'Accept', 'value' => 'application/json'],
                            ['key' => 'Content-Type', 'value' => 'application/json'],
                        ],
                        'body' => [
                            'mode' => 'raw',
                            'raw' => json_encode([
                                'student_id' => 1,
                                'course_offering_id' => 15,
                            ], JSON_PRETTY_PRINT),
                            'options' => ['raw' => ['language' => 'json']],
                        ],
                        'url' => ['raw' => '{{baseUrl}}/enrollments', 'host' => ['{{baseUrl}}'], 'path' => ['enrollments']],
                    ],
                ],
                [
                    'name' => 'Demo 14 - Encode or Update Authorized Grade (201 Created)',
                    'request' => [
                        'auth' => ['type' => 'bearer', 'bearer' => [['key' => 'token', 'value' => '{{adminToken}}', 'type' => 'string']]],
                        'method' => 'POST',
                        'header' => [
                            ['key' => 'Accept', 'value' => 'application/json'],
                            ['key' => 'Content-Type', 'value' => 'application/json'],
                        ],
                        'body' => [
                            'mode' => 'raw',
                            'raw' => json_encode([
                                'enrollment_id' => 1,
                                'midterm_grade' => 1.25,
                                'final_grade' => 1.00,
                                'remarks' => 'Passed - Excellent Performance',
                            ], JSON_PRETTY_PRINT),
                            'options' => ['raw' => ['language' => 'json']],
                        ],
                        'url' => ['raw' => '{{baseUrl}}/grades', 'host' => ['{{baseUrl}}'], 'path' => ['grades']],
                    ],
                ],
                [
                    'name' => 'Demo 15 - Retrieve Student Academic Record (Aggregated GWA - 200 OK)',
                    'request' => [
                        'auth' => ['type' => 'bearer', 'bearer' => [['key' => 'token', 'value' => '{{adminToken}}', 'type' => 'string']]],
                        'method' => 'GET',
                        'header' => [['key' => 'Accept', 'value' => 'application/json']],
                        'url' => ['raw' => '{{baseUrl}}/students/1/academic-record', 'host' => ['{{baseUrl}}'], 'path' => ['students', '1', 'academic-record']],
                    ],
                ],
                [
                    'name' => 'Demo 16a - Student Role Forbidden from Creating Programs (403 Forbidden)',
                    'request' => [
                        'auth' => ['type' => 'bearer', 'bearer' => [['key' => 'token', 'value' => '{{studentToken}}', 'type' => 'string']]],
                        'method' => 'POST',
                        'header' => [
                            ['key' => 'Accept', 'value' => 'application/json'],
                            ['key' => 'Content-Type', 'value' => 'application/json'],
                        ],
                        'body' => [
                            'mode' => 'raw',
                            'raw' => json_encode(['code' => 'HACK', 'name' => 'Unauthorized Program'], JSON_PRETTY_PRINT),
                            'options' => ['raw' => ['language' => 'json']],
                        ],
                        'url' => ['raw' => '{{baseUrl}}/programs', 'host' => ['{{baseUrl}}'], 'path' => ['programs']],
                    ],
                ],
                [
                    'name' => 'Demo 16b - Object-Level Auth: Student Viewing Peer Record (403 Forbidden)',
                    'request' => [
                        'auth' => ['type' => 'bearer', 'bearer' => [['key' => 'token', 'value' => '{{studentToken}}', 'type' => 'string']]],
                        'method' => 'GET',
                        'header' => [['key' => 'Accept', 'value' => 'application/json']],
                        'url' => ['raw' => '{{baseUrl}}/students/2/academic-record', 'host' => ['{{baseUrl}}'], 'path' => ['students', '2', 'academic-record']],
                    ],
                ],
                [
                    'name' => 'Demo 17 - Show 404 Not Found Case',
                    'request' => [
                        'auth' => ['type' => 'bearer', 'bearer' => [['key' => 'token', 'value' => '{{adminToken}}', 'type' => 'string']]],
                        'method' => 'GET',
                        'header' => [['key' => 'Accept', 'value' => 'application/json']],
                        'url' => ['raw' => '{{baseUrl}}/students/99999', 'host' => ['{{baseUrl}}'], 'path' => ['students', '99999']],
                    ],
                ],
                [
                    'name' => 'Demo 18 - Display Interactive API Documentation (200 OK)',
                    'request' => [
                        'method' => 'GET',
                        'header' => [['key' => 'Accept', 'value' => 'text/html']],
                        'url' => ['raw' => 'http://127.0.0.1:8000/api/docs', 'protocol' => 'http', 'host' => ['127', '0', '0', '1'], 'port' => '8000', 'path' => ['api', 'docs']],
                    ],
                ],
            ],
        ],
    ],
];

$json = json_encode($collection, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
file_put_contents(__DIR__.'/../Student_Information_Management_API.postman_collection.json', $json);
file_put_contents(__DIR__.'/Student_Information_Management_API.postman_collection.json', $json);
echo "Postman collection generated successfully!\n";
