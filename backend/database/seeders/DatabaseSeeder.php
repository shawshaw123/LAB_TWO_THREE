<?php

namespace Database\Seeders;

use App\Models\AcademicTerm;
use App\Models\Course;
use App\Models\CourseOffering;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Program;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $password = Hash::make('Password123!');

        // 1. Primary Users (Admin, Registrar, Instructors, Student)
        $admin = User::updateOrCreate(['email' => 'admin@example.com'], [
            'name' => 'Juan Dela Cruz (Administrator)',
            'password' => $password,
            'role' => 'admin',
            'status' => 'ACTIVE',
        ]);

        $registrar = User::updateOrCreate(['email' => 'registrar@example.com'], [
            'name' => 'Maria Clara Santos (Registrar)',
            'password' => $password,
            'role' => 'registrar',
            'status' => 'ACTIVE',
        ]);

        $instructor1 = User::updateOrCreate(['email' => 'instructor@example.com'], [
            'name' => 'Prof. Jose Rizal',
            'password' => $password,
            'role' => 'instructor',
            'status' => 'ACTIVE',
        ]);

        $instructor2 = User::updateOrCreate(['email' => 'instructor2@example.com'], [
            'name' => 'Prof. Andres Bonifacio',
            'password' => $password,
            'role' => 'instructor',
            'status' => 'ACTIVE',
        ]);

        $instructor3 = User::updateOrCreate(['email' => 'instructor3@example.com'], [
            'name' => 'Prof. Gabriela Silang',
            'password' => $password,
            'role' => 'instructor',
            'status' => 'ACTIVE',
        ]);

        $studentUser = User::updateOrCreate(['email' => 'student@example.com'], [
            'name' => 'Crisostomo Ibarra',
            'password' => $password,
            'role' => 'student',
            'status' => 'ACTIVE',
        ]);

        $instructors = collect([$instructor1, $instructor2, $instructor3]);

        // 2. Programs (>= 4 programs)
        $programs = [
            ['code' => 'BSIT', 'name' => 'Bachelor of Science in Information Technology', 'description' => 'Focuses on software engineering, system integration, and database management.'],
            ['code' => 'BSCS', 'name' => 'Bachelor of Science in Computer Science', 'description' => 'Emphasizes algorithms, computer architecture, and computing theory.'],
            ['code' => 'BSIS', 'name' => 'Bachelor of Science in Information Systems', 'description' => 'Combines business processes, data analytics, and enterprise systems.'],
            ['code' => 'BSEMC', 'name' => 'Bachelor of Science in Entertainment and Multimedia Computing', 'description' => 'Covers interactive game design, computer animation, and multimedia.'],
        ];

        $programModels = collect();
        foreach ($programs as $prog) {
            $programModels->push(Program::firstOrCreate(['code' => $prog['code']], $prog));
        }

        // 3. Courses (>= 22 courses)
        $coursesData = [
            ['course_code' => 'IT101', 'course_title' => 'Introduction to Computing', 'units' => 3.0, 'description' => 'Foundations of computer systems and computational thinking.'],
            ['course_code' => 'IT102', 'course_title' => 'Computer Programming 1', 'units' => 3.0, 'description' => 'Fundamentals of structured programming and algorithm design.'],
            ['course_code' => 'IT103', 'course_title' => 'Computer Programming 2', 'units' => 3.0, 'description' => 'Object-oriented programming concepts, classes, and inheritance.'],
            ['course_code' => 'CS201', 'course_title' => 'Data Structures and Algorithms', 'units' => 3.0, 'description' => 'Linear and non-linear data structures, searching, and sorting.'],
            ['course_code' => 'IT202', 'course_title' => 'Database Management Systems 1', 'units' => 3.0, 'description' => 'Relational database design, normalization, and SQL.'],
            ['course_code' => 'IT203', 'course_title' => 'Database Management Systems 2', 'units' => 3.0, 'description' => 'Advanced database administration, transactions, and indexing.'],
            ['course_code' => 'CS204', 'course_title' => 'Discrete Mathematics', 'units' => 3.0, 'description' => 'Sets, relations, propositional logic, and graph theory.'],
            ['course_code' => 'IT205', 'course_title' => 'Web Systems and Technologies 1', 'units' => 3.0, 'description' => 'Client-side web architecture, modern HTML, CSS, and JS.'],
            ['course_code' => 'IT206', 'course_title' => 'Web Systems and Technologies 2', 'units' => 3.0, 'description' => 'Server-side application development and RESTful Web APIs.'],
            ['course_code' => 'CS301', 'course_title' => 'Operating Systems', 'units' => 3.0, 'description' => 'Process management, virtual memory, scheduling, and concurrency.'],
            ['course_code' => 'CS302', 'course_title' => 'Computer Networks and Data Communication', 'units' => 3.0, 'description' => 'OSI model, TCP/IP, network routing, and socket programming.'],
            ['course_code' => 'IT303', 'course_title' => 'Information Assurance and Security 1', 'units' => 3.0, 'description' => 'Cryptography, threat models, network security, and defense.'],
            ['course_code' => 'IT304', 'course_title' => 'Information Assurance and Security 2', 'units' => 3.0, 'description' => 'Enterprise security policies, penetration testing, and compliance.'],
            ['course_code' => 'CS305', 'course_title' => 'Software Engineering', 'units' => 3.0, 'description' => 'Agile lifecycle, requirements engineering, CI/CD, and testing.'],
            ['course_code' => 'CS306', 'course_title' => 'Design and Analysis of Algorithms', 'units' => 3.0, 'description' => 'Algorithm complexity, dynamic programming, and greedy methods.'],
            ['course_code' => 'IT307', 'course_title' => 'Mobile Application Development', 'units' => 3.0, 'description' => 'Native and hybrid mobile app architecture and API integration.'],
            ['course_code' => 'IT401', 'course_title' => 'Cloud Computing and DevOps', 'units' => 3.0, 'description' => 'Containerization, cloud providers, orchestration, and infrastructure.'],
            ['course_code' => 'CS402', 'course_title' => 'Artificial Intelligence & Machine Learning', 'units' => 3.0, 'description' => 'Supervised learning, neural networks, and model deployment.'],
            ['course_code' => 'IT403', 'course_title' => 'Capstone Project 1', 'units' => 3.0, 'description' => 'Proposal, requirement specifications, and architectural design.'],
            ['course_code' => 'IT404', 'course_title' => 'Capstone Project 2', 'units' => 3.0, 'description' => 'System implementation, comprehensive testing, and defense.'],
            ['course_code' => 'GE101', 'course_title' => 'Understanding the Self', 'units' => 3.0, 'description' => 'Nature of identity and human factors in digital environments.'],
            ['course_code' => 'GE102', 'course_title' => 'Ethics in the Digital Age', 'units' => 3.0, 'description' => 'Ethical issues in software engineering, AI, and data privacy.'],
        ];

        $courseModels = collect();
        foreach ($coursesData as $c) {
            $courseModels->push(Course::firstOrCreate(['course_code' => $c['course_code']], $c));
        }

        // 4. Academic Terms (>= 3 terms)
        $term1 = AcademicTerm::firstOrCreate([
            'academic_year' => '2025-2026',
            'term' => '1st Semester',
        ], [
            'start_date' => '2025-08-15',
            'end_date' => '2025-12-20',
            'status' => 'ACTIVE',
        ]);

        $term2 = AcademicTerm::firstOrCreate([
            'academic_year' => '2025-2026',
            'term' => '2nd Semester',
        ], [
            'start_date' => '2026-01-15',
            'end_date' => '2026-05-30',
            'status' => 'ACTIVE',
        ]);

        $term3 = AcademicTerm::firstOrCreate([
            'academic_year' => '2024-2025',
            'term' => '2nd Semester',
        ], [
            'start_date' => '2025-01-15',
            'end_date' => '2025-05-30',
            'status' => 'INACTIVE',
        ]);

        $terms = collect([$term1, $term2, $term3]);

        // 5. Students (>= 100 students)
        // 5. Students (>= 100 students)
        $filipinoFirstNames = [
            'Juan', 'Jose', 'Andres', 'Emilio', 'Antonio', 'Apolinario', 'Francisco', 'Manuel', 'Ramon',
            'Diosdado', 'Ferdinand', 'Corazon', 'Benigno', 'Rodrigo', 'Danilo', 'Eduardo', 'Angelo',
            'Christian', 'Joshua', 'Mark', 'Paolo', 'Miguel', 'Rafael', 'Gabriel', 'Daniel', 'Carlo',
            'Jericho', 'Alden', 'Dingdong', 'Enchong', 'Rayver', 'Arjo', 'Renato', 'Salvador', 'Rogelio',
            'Jaime', 'Arturo', 'Edgardo', 'Rolando', 'Ernesto', 'Fernando', 'Ricardo', 'Roberto', 'Rodolfo',
            'Maria', 'Clara', 'Gabriela', 'Melchora', 'Teresa', 'Leonor', 'Juana', 'Angelica', 'Nicole',
            'Bea', 'Angel', 'Kathryn', 'Nadine', 'Liza', 'Julia', 'Janella', 'Marian', 'Heart', 'Patricia',
            'Christine', 'Camille', 'Kimberly', 'Stephanie', 'Alyssa', 'Andrea', 'Bianca', 'Clarisse',
            'Dianne', 'Katrina', 'Lorraine', 'Maricar', 'Rowena', 'Rochelle', 'Sunshine', 'Sheryl', 'Jennifer',
            'Maricel', 'Vilma', 'Nora', 'Rosario', 'Lourdes', 'Carmen', 'Milagros', 'Esperanza', 'Consuelo',
            'Remedios', 'Josefina', 'Florencia', 'Concepcion', 'Natividad', 'Leticia', 'Victoria', 'Amalia',
        ];

        $filipinoLastNames = [
            'Santos', 'Reyes', 'Cruz', 'Bautista', 'Ocampo', 'Garcia', 'Mendoza', 'Torres', 'Tomas',
            'Andrada', 'Del Rosario', 'Flores', 'Morales', 'Castillo', 'Villanueva', 'Ramos', 'Castro',
            'Rivera', 'Aquino', 'Navarro', 'Salazar', 'Mercado', 'Dela Cruz', 'De Leon', 'Gonzales',
            'Lopez', 'Hernandez', 'Perez', 'Sanchez', 'Ramirez', 'Alvarez', 'Gomez', 'Diaz', 'Fernandez',
            'Rodriguez', 'Tolentino', 'Soriano', 'Manalo', 'Dizon', 'Dimaculangan', 'Macaraeg', 'Ilagan',
            'Magbanua', 'Alcantara', 'Evangelista', 'Pascual', 'Concepcion', 'Valenzuela', 'Natividad',
            'Espiritu', 'Panganiban', 'Mabini', 'Luna', 'Del Pilar', 'Bonifacio', 'Silang', 'Aguinaldo',
            'Quezon', 'Magsaysay', 'Osmeña', 'Roxas', 'Macapagal', 'Laurel', 'Quirino', 'Estrada',
            'Duterte', 'Marcos', 'Cojuangco', 'Enrile', 'Binay', 'Lacson', 'Cayetano', 'Villafuerte',
            'Gatchalian', 'Villar', 'Sotto', 'Poe', 'Legarda', 'Hontiveros', 'Gordon', 'Zubiri', 'Pimentel',
        ];

        $filipinoAddresses = [
            '123 Rizal Avenue, Sampaloc, Manila',
            '456 Katipunan Avenue, Diliman, Quezon City',
            '789 Ayala Avenue, San Lorenzo, Makati City',
            '101 Shaw Boulevard, Kapasigan, Pasig City',
            '202 Bonifacio High Street, Fort Bonifacio, Taguig City',
            '303 Alabang-Zapote Road, Alabang, Muntinlupa City',
            '404 Ortigas Avenue, Greenhills, San Juan City',
            '505 EDSA, Monumento, Caloocan City',
            '606 Boni Avenue, Plainview, Mandaluyong City',
            '707 Marcos Highway, San Roque, Marikina City',
            '808 Alabang-Zapote Road, Almanza, Las Piñas City',
            '909 Dr. A. Santos Avenue, Sucat, Parañaque City',
            '111 McArthur Highway, Malolos, Bulacan',
            '222 Jose Abad Santos Avenue, San Fernando, Pampanga',
            '333 Aguinaldo Highway, Dasmariñas, Cavite',
            '444 Sumulong Highway, Antipolo, Rizal',
            '555 National Highway, Calamba, Laguna',
            '666 Ayala Highway, Lipa City, Batangas',
            '777 Session Road, Baguio City, Benguet',
            '888 Perez Boulevard, Dagupan City, Pangasinan',
            '999 Crisologo Street, Vigan City, Ilocos Sur',
            '124 Magsaysay Avenue, Naga City, Camarines Sur',
            '235 Rizal Street, Legazpi City, Albay',
            '346 Fuente Osmeña, Lahug, Cebu City',
            '457 M.H. Del Pilar Street, Iloilo City',
            '568 Lacson Street, Bacolod City, Negros Occidental',
            '679 Real Street, Tacloban City, Leyte',
            '780 J.P. Laurel Avenue, Bajada, Davao City',
            '891 Corrales Avenue, Cagayan de Oro City',
            '902 Mayor Jaldon Street, Zamboanga City',
            '113 Pioneer Avenue, General Santos City',
        ];

        // Primary demo student
        $firstStudent = Student::updateOrCreate(['student_number' => '2026-00001'], [
            'user_id' => $studentUser->id,
            'first_name' => 'Crisostomo',
            'middle_name' => 'Rizal',
            'last_name' => 'Ibarra',
            'suffix' => null,
            'birth_date' => '2004-05-14',
            'email' => 'student@example.com',
            'contact_number' => '+63 912 345 6789',
            'address' => '123 Calle Crisostomo, Sampaloc, Manila, Philippines',
            'program_id' => $programModels->first()->id,
            'year_level' => 3,
            'status' => 'ACTIVE',
        ]);

        $allStudents = collect([$firstStudent]);

        // Generate 104 additional students to reach 105 total with Filipino names
        for ($i = 2; $i <= 105; $i++) {
            $formattedNum = '2026-'.str_pad((string) $i, 5, '0', STR_PAD_LEFT);
            $chosenProg = $programModels->random();

            $fName = $filipinoFirstNames[($i * 7) % count($filipinoFirstNames)];
            $mName = $filipinoLastNames[($i * 11) % count($filipinoLastNames)];
            $lName = $filipinoLastNames[($i * 13) % count($filipinoLastNames)];
            $addr = $filipinoAddresses[$i % count($filipinoAddresses)];

            $student = Student::updateOrCreate(['student_number' => $formattedNum], [
                'user_id' => null,
                'first_name' => $fName,
                'middle_name' => $mName,
                'last_name' => $lName,
                'suffix' => ($i % 10 === 0) ? 'Jr.' : (($i % 25 === 0) ? 'III' : null),
                'birth_date' => fake()->dateTimeBetween('-24 years', '-18 years')->format('Y-m-d'),
                'email' => "student{$i}@example.com",
                'contact_number' => '+63 9'.str_pad((string) fake()->numberBetween(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'address' => $addr,
                'program_id' => $chosenProg->id,
                'year_level' => fake()->numberBetween(1, 4),
                'status' => 'ACTIVE',
            ]);
            $allStudents->push($student);
        }

        // 6. Course Offerings (>= 22 offerings across terms and instructors)
        $offerings = collect();
        $sections = ['A', 'B'];

        foreach ($courseModels->take(12) as $idx => $course) {
            foreach ($sections as $sectionLetter) {
                $offering = CourseOffering::firstOrCreate([
                    'course_id' => $course->id,
                    'academic_term_id' => ($idx % 2 === 0) ? $term1->id : $term2->id,
                    'section' => $course->course_code.'-'.$sectionLetter,
                ], [
                    'instructor_id' => $instructors->random()->id,
                    'schedule' => fake()->randomElement(['MWF 08:00-09:30', 'TTH 10:00-11:30', 'MWF 13:00-14:30', 'TTH 14:00-15:30']),
                    'room' => fake()->randomElement(['Room 101', 'Room 205', 'Lab 301', 'Lab 302', 'Online']),
                    'capacity' => 45,
                    'status' => 'OPEN',
                ]);
                $offerings->push($offering);
            }
        }

        // 7. Enrollments (>= 200 distinct enrollments)
        // Ensure student 1 has enrollments in both terms for realistic transcript testing
        $enrollmentCount = 0;
        $createdEnrollments = collect();

        // Give demo student 5 enrollments
        foreach ($offerings->take(5) as $demoOffering) {
            $enr = Enrollment::firstOrCreate([
                'student_id' => $firstStudent->id,
                'course_offering_id' => $demoOffering->id,
            ], [
                'enrollment_date' => '2025-08-20',
                'status' => 'COMPLETED',
            ]);
            $createdEnrollments->push($enr);
            $enrollmentCount++;
        }

        // Distribute enrollments across other students
        foreach ($allStudents->skip(1) as $student) {
            // Enroll each student in 2-3 offerings
            $assignedOfferings = $offerings->random(min(3, $offerings->count()));
            foreach ($assignedOfferings as $offering) {
                if ($enrollmentCount >= 220) {
                    break 2;
                }

                $enr = Enrollment::firstOrCreate([
                    'student_id' => $student->id,
                    'course_offering_id' => $offering->id,
                ], [
                    'enrollment_date' => '2025-08-20',
                    'status' => fake()->randomElement(['ENROLLED', 'COMPLETED', 'COMPLETED']),
                ]);
                $createdEnrollments->push($enr);
                $enrollmentCount++;
            }
        }

        // 8. Grades (>= 100 grades)
        $gradesCount = 0;
        foreach ($createdEnrollments as $enrollment) {
            if ($gradesCount >= 115) {
                break;
            }

            $midterm = fake()->randomElement([1.00, 1.25, 1.50, 1.75, 2.00, 2.25, 2.50, 2.75, 3.00, 5.00]);
            $final = fake()->randomElement([1.00, 1.25, 1.50, 1.75, 2.00, 2.25, 2.50, 2.75, 3.00, 5.00]);
            $remarks = ($final <= 3.00) ? 'PASSED' : 'FAILED';

            Grade::firstOrCreate(['enrollment_id' => $enrollment->id], [
                'midterm_grade' => $midterm,
                'final_grade' => $final,
                'remarks' => $remarks,
            ]);
            $gradesCount++;
        }
    }
}
