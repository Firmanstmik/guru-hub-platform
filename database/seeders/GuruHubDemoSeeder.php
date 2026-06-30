<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class GuruHubDemoSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = ['admin', 'guru', 'siswa'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        $permissions = [
            ['name' => 'tampil-users', 'controller' => 'UserController', 'uri' => '/users', 'method' => 'get', 'action' => 'index', 'roles' => ['admin', 'guru', 'siswa']],
            ['name' => 'view-tambah-user', 'controller' => 'UserController', 'uri' => '/users/create', 'method' => 'get', 'action' => 'create', 'roles' => ['admin']],
            ['name' => 'post-tambah-user', 'controller' => 'UserController', 'uri' => '/users', 'method' => 'post', 'action' => 'store', 'roles' => ['admin']],
            ['name' => 'view-edit-user', 'controller' => 'UserController', 'uri' => '/users/{user}/edit', 'method' => 'get', 'action' => 'edit', 'roles' => ['admin', 'guru', 'siswa']],
            ['name' => 'post-update-user', 'controller' => 'UserController', 'uri' => '/users/{user}', 'method' => 'put', 'action' => 'update', 'roles' => ['admin', 'guru', 'siswa']],
            ['name' => 'hapus-user', 'controller' => 'UserController', 'uri' => '/users/{user}', 'method' => 'delete', 'action' => 'destroy', 'roles' => ['admin']],
            ['name' => 'update-status-akun', 'controller' => 'UserController', 'uri' => '/users/toggle/{user}', 'method' => 'post', 'action' => 'toggleStatus', 'roles' => ['admin']],
            ['name' => 'user-update-role', 'controller' => 'UserController', 'uri' => '/users/{user}/update-role', 'method' => 'post', 'action' => 'updateRole', 'roles' => ['admin']],
            ['name' => 'categories', 'controller' => 'CategoriController', 'uri' => '/categories', 'method' => 'get', 'action' => 'index', 'roles' => ['admin', 'guru']],
            ['name' => 'categories-add', 'controller' => 'CategoriController', 'uri' => '/categories', 'method' => 'post', 'action' => 'store', 'roles' => ['admin']],
            ['name' => 'categories-edit', 'controller' => 'CategoriController', 'uri' => '/categories/{category}', 'method' => 'put', 'action' => 'update', 'roles' => ['admin']],
            ['name' => 'categories-hapus', 'controller' => 'CategoriController', 'uri' => '/categories/{category}', 'method' => 'delete', 'action' => 'destroy', 'roles' => ['admin']],
            ['name' => 'bookings', 'controller' => 'BookingController', 'uri' => '/bookings', 'method' => 'get', 'action' => 'index', 'roles' => ['admin']],
            ['name' => 'bookings-edit', 'controller' => 'BookingController', 'uri' => '/bookings/{booking}', 'method' => 'put', 'action' => 'update', 'roles' => ['admin']],
            ['name' => 'bookings-hapus', 'controller' => 'BookingController', 'uri' => '/bookings/{booking}', 'method' => 'delete', 'action' => 'destroy', 'roles' => ['admin']],
            ['name' => 'certificates', 'controller' => 'CertificateController', 'uri' => '/certificates', 'method' => 'get', 'action' => 'index', 'roles' => ['admin', 'guru']],
            ['name' => 'certificates-add', 'controller' => 'CertificateController', 'uri' => '/certificates', 'method' => 'post', 'action' => 'store', 'roles' => ['admin', 'guru']],
            ['name' => 'certificates-hapus', 'controller' => 'CertificateController', 'uri' => '/certificates/{certificate}', 'method' => 'delete', 'action' => 'destroy', 'roles' => ['admin']],
            ['name' => 'schedules', 'controller' => 'ClassScheduleController', 'uri' => '/schedules', 'method' => 'get', 'action' => 'index', 'roles' => ['admin', 'guru']],
            ['name' => 'schedules-add', 'controller' => 'ClassScheduleController', 'uri' => '/schedules', 'method' => 'post', 'action' => 'store', 'roles' => ['admin', 'guru']],
            ['name' => 'schedules-edit', 'controller' => 'ClassScheduleController', 'uri' => '/schedules/{schedule}', 'method' => 'put', 'action' => 'update', 'roles' => ['admin', 'guru']],
            ['name' => 'schedules-hapus', 'controller' => 'ClassScheduleController', 'uri' => '/schedules/{schedule}', 'method' => 'delete', 'action' => 'destroy', 'roles' => ['admin']],
            ['name' => 'courses', 'controller' => 'CourseController', 'uri' => '/courses', 'method' => 'get', 'action' => 'index', 'roles' => ['admin', 'guru']],
            ['name' => 'courses-add', 'controller' => 'CourseController', 'uri' => '/courses', 'method' => 'post', 'action' => 'store', 'roles' => ['admin', 'guru']],
            ['name' => 'courses-edit', 'controller' => 'CourseController', 'uri' => '/courses/{course}', 'method' => 'put', 'action' => 'update', 'roles' => ['admin', 'guru']],
            ['name' => 'courses-hapus', 'controller' => 'CourseController', 'uri' => '/courses/{course}', 'method' => 'delete', 'action' => 'destroy', 'roles' => ['admin', 'guru']],
            ['name' => 'materials', 'controller' => 'CourseMaterialController', 'uri' => '/materials', 'method' => 'get', 'action' => 'index', 'roles' => ['admin', 'guru']],
            ['name' => 'materials-add', 'controller' => 'CourseMaterialController', 'uri' => '/materials', 'method' => 'post', 'action' => 'store', 'roles' => ['admin', 'guru']],
            ['name' => 'materials-edit', 'controller' => 'CourseMaterialController', 'uri' => '/materials/{material}', 'method' => 'put', 'action' => 'update', 'roles' => ['admin', 'guru']],
            ['name' => 'materials-hapus', 'controller' => 'CourseMaterialController', 'uri' => '/materials/{material}', 'method' => 'delete', 'action' => 'destroy', 'roles' => ['admin']],
            ['name' => 'videos', 'controller' => 'CourseVideoController', 'uri' => '/videos', 'method' => 'get', 'action' => 'index', 'roles' => ['admin', 'guru']],
            ['name' => 'videos-add', 'controller' => 'CourseVideoController', 'uri' => '/videos', 'method' => 'post', 'action' => 'store', 'roles' => ['admin', 'guru']],
            ['name' => 'videos-edit', 'controller' => 'CourseVideoController', 'uri' => '/videos/{video}', 'method' => 'put', 'action' => 'update', 'roles' => ['admin', 'guru']],
            ['name' => 'videos-hapus', 'controller' => 'CourseVideoController', 'uri' => '/videos/{video}', 'method' => 'delete', 'action' => 'destroy', 'roles' => ['admin']],
            ['name' => 'course-students', 'controller' => 'CourseStudentController', 'uri' => '/course-students', 'method' => 'get', 'action' => 'index', 'roles' => ['admin']],
            ['name' => 'course-students-add', 'controller' => 'CourseStudentController', 'uri' => '/course-students', 'method' => 'post', 'action' => 'store', 'roles' => []],
            ['name' => 'course-students-edit', 'controller' => 'CourseStudentController', 'uri' => '/course-students/{courseStudent}', 'method' => 'post', 'action' => 'update', 'roles' => ['admin']],
            ['name' => 'course-students-hapus', 'controller' => 'CourseStudentController', 'uri' => '/course-students/{courseStudent}', 'method' => 'delete', 'action' => 'destroy', 'roles' => ['admin']],
            ['name' => 'payments', 'controller' => 'PaymentController', 'uri' => '/payments', 'method' => 'get', 'action' => 'index', 'roles' => ['admin']],
            ['name' => 'payments-approve', 'controller' => 'PaymentController', 'uri' => '/payments/{payment}/approve', 'method' => 'patch', 'action' => 'approve', 'roles' => ['admin']],
            ['name' => 'payments-reject', 'controller' => 'PaymentController', 'uri' => '/payments/{payment}/reject', 'method' => 'patch', 'action' => 'reject', 'roles' => ['admin']],
            ['name' => 'reviews', 'controller' => 'ReviewController', 'uri' => '/reviews', 'method' => 'get', 'action' => 'index', 'roles' => ['admin']],
            ['name' => 'review-hapus', 'controller' => 'ReviewController', 'uri' => '/reviews/{review}', 'method' => 'delete', 'action' => 'destroy', 'roles' => ['admin']],
            ['name' => 'earnings', 'controller' => 'TeacherEarningController', 'uri' => '/earnings', 'method' => 'get', 'action' => 'index', 'roles' => ['admin', 'guru']],
            ['name' => 'earnings-edit-status', 'controller' => 'TeacherEarningController', 'uri' => '/earnings/{id}/status', 'method' => 'patch', 'action' => 'updateStatus', 'roles' => ['admin', 'guru']],
            ['name' => 'teachers', 'controller' => 'TeacherProfileController', 'uri' => '/teachers', 'method' => 'get', 'action' => 'index', 'roles' => ['admin', 'guru']],
            ['name' => 'teachers-verify', 'controller' => 'TeacherProfileController', 'uri' => '/teachers/{profile}/verify', 'method' => 'put', 'action' => 'verify', 'roles' => ['admin']],
            ['name' => 'teachers-add', 'controller' => 'TeacherProfileController', 'uri' => '/teachers', 'method' => 'post', 'action' => 'store', 'roles' => ['guru']],
            ['name' => 'teachers-edit', 'controller' => 'TeacherProfileController', 'uri' => '/teachers/{profile?}', 'method' => 'put', 'action' => 'update', 'roles' => ['guru']],
            ['name' => 'teachers-hapus', 'controller' => 'TeacherProfileController', 'uri' => '/teachers/{profile}', 'method' => 'delete', 'action' => 'destroy', 'roles' => ['admin']],
            ['name' => 'teachers-by-id', 'controller' => 'TeacherProfileController', 'uri' => '/teachers/{profile}', 'method' => 'get', 'action' => 'show', 'roles' => ['admin']],
            ['name' => 'tampil-kursus', 'controller' => 'StudentCourseController', 'uri' => '/tampil-kursus', 'method' => 'get', 'action' => 'index', 'roles' => ['siswa']],
            ['name' => 'siswa-booking', 'controller' => 'BookingController', 'uri' => '/bookings', 'method' => 'post', 'action' => 'store', 'roles' => ['siswa']],
            ['name' => 'booking-form', 'controller' => 'BookingController', 'uri' => '/bookings/create', 'method' => 'get', 'action' => 'create', 'roles' => ['siswa']],
            ['name' => 'history-bookings', 'controller' => 'BookingController', 'uri' => '/history-bookings', 'method' => 'get', 'action' => 'showHistory', 'roles' => ['siswa']],
            ['name' => 'my-courses', 'controller' => 'StudentCourseController', 'uri' => '/my-courses', 'method' => 'get', 'action' => 'myCourse', 'roles' => ['siswa']],
            ['name' => 'form-reviews', 'controller' => 'ReviewController', 'uri' => '/student/courses/{course_id}/review', 'method' => 'get', 'action' => 'create', 'roles' => ['siswa']],
            ['name' => 'store-reviews', 'controller' => 'ReviewController', 'uri' => '/student/reviews', 'method' => 'post', 'action' => 'store', 'roles' => ['siswa']],
            ['name' => 'ruang-belajar', 'controller' => 'StudentCourseController', 'uri' => 'student/courses/{course_id}/learn', 'method' => 'get', 'action' => 'roomLearn', 'roles' => ['siswa']],
            ['name' => 'admin-dashboard', 'controller' => 'DashboardController', 'uri' => '/admin-dashboard', 'method' => 'get', 'action' => 'dashboardAdmin', 'roles' => ['admin']],
            ['name' => 'guru-dashboard', 'controller' => 'DashboardController', 'uri' => '/guru-dashboard', 'method' => 'get', 'action' => 'dashboardGuru', 'roles' => ['guru']],
            ['name' => 'siswa-dashboard', 'controller' => 'DashboardController', 'uri' => '/siswa-dashboard', 'method' => 'get', 'action' => 'dashboardSiswa', 'roles' => ['siswa']],
            ['name' => 'form-payment-class', 'controller' => 'PaymentController', 'uri' => '/payments-class/{transaction_code}', 'method' => 'get', 'action' => 'showPaymentForm', 'roles' => ['siswa']],
            ['name' => 'simpan-payment-class', 'controller' => 'PaymentController', 'uri' => '/payments-class/{transaction_code}', 'method' => 'post', 'action' => 'storeStudentPayment', 'roles' => ['siswa']],
            ['name' => 'company-accounts', 'controller' => 'CompanyAccountController', 'uri' => '/company-accounts', 'method' => 'get', 'action' => 'index', 'roles' => ['admin']],
            ['name' => 'add-company-accounts', 'controller' => 'CompanyAccountController', 'uri' => '/company-accounts', 'method' => 'post', 'action' => 'store', 'roles' => ['admin']],
            ['name' => 'edit-company-accounts', 'controller' => 'CompanyAccountController', 'uri' => '/company-accounts/{account}', 'method' => 'put', 'action' => 'update', 'roles' => ['admin']],
            ['name' => 'hapus-company-accounts', 'controller' => 'CompanyAccountController', 'uri' => '/company-accounts/{account}', 'method' => 'delete', 'action' => 'destroy', 'roles' => ['admin']],
            ['name' => 'siswa-biodata-form', 'controller' => 'StudentBiodataController', 'uri' => '/biodata', 'method' => 'get', 'action' => 'siswaForm', 'roles' => ['siswa']],
            ['name' => 'siswa-biodata-store', 'controller' => 'StudentBiodataController', 'uri' => '/biodata', 'method' => 'post', 'action' => 'store', 'roles' => ['siswa']],
            ['name' => 'biodata-siswa', 'controller' => 'StudentBiodataController', 'uri' => '/student-biodata', 'method' => 'get', 'action' => 'index', 'roles' => ['admin']],
            ['name' => 'biodata-siswa-hapus', 'controller' => 'StudentBiodataController', 'uri' => '/student-biodata/{id}', 'method' => 'delete', 'action' => 'destroy', 'roles' => ['admin']],
            ['name' => 'course-progress-toggle', 'controller' => 'CourseProgressController', 'uri' => '/course-progress/toggle', 'method' => 'post', 'action' => 'toggleProgress', 'roles' => ['siswa']],
            ['name' => 'guru-schedules-create', 'controller' => 'GuruScheduleController', 'uri' => '/guru/schedules/create', 'method' => 'get', 'action' => 'create', 'roles' => ['guru']],
            ['name' => 'guru-schedules-store', 'controller' => 'GuruScheduleController', 'uri' => '/guru/schedules/store', 'method' => 'post', 'action' => 'store', 'roles' => ['guru']],
            ['name' => 'store-quiz', 'controller' => 'QuizManagementController', 'uri' => '/quiz/store', 'method' => 'post', 'action' => 'storeQuiz', 'roles' => ['guru']],
            ['name' => 'build-quiz', 'controller' => 'QuizManagementController', 'uri' => '/quiz/{quizId}/build', 'method' => 'get', 'action' => 'buildQuiz', 'roles' => ['guru']],
            ['name' => 'store-question', 'controller' => 'QuizManagementController', 'uri' => '/quiz/{quizId}/questions', 'method' => 'post', 'action' => 'storeQuestion', 'roles' => ['guru']],
            ['name' => 'review-students', 'controller' => 'QuizManagementController', 'uri' => '/quiz/{quizId}/review', 'method' => 'get', 'action' => 'reviewStudents', 'roles' => ['guru']],
            ['name' => 'review-single-student', 'controller' => 'QuizManagementController', 'uri' => '/quiz/{quizId}/review/{studentId}', 'method' => 'get', 'action' => 'reviewSingleStudent', 'roles' => ['guru']],
            ['name' => 'submit-grade', 'controller' => 'QuizManagementController', 'uri' => '/quiz/answer/{answerId}/grade', 'method' => 'post', 'action' => 'submitGrade', 'roles' => ['guru']],
            ['name' => 'show-detail-material', 'controller' => 'CourseMaterialController', 'uri' => '/materials/{id}', 'method' => 'get', 'action' => 'show', 'roles' => ['guru']],
            ['name' => 'quiz-introduce', 'controller' => 'StudentQuizController', 'uri' => '/materials/{materialId}/quiz', 'method' => 'get', 'action' => 'introduce', 'roles' => ['siswa']],
            ['name' => 'quiz-take', 'controller' => 'StudentQuizController', 'uri' => '/quiz/{quizId}/take', 'method' => 'get', 'action' => 'takeQuiz', 'roles' => ['siswa']],
            ['name' => 'quiz-submit', 'controller' => 'StudentQuizController', 'uri' => '/quiz/{quizId}/submit', 'method' => 'post', 'action' => 'submitQuiz', 'roles' => ['siswa']],
            ['name' => 'quiz-history', 'controller' => 'StudentQuizController', 'uri' => '/quiz/history', 'method' => 'get', 'action' => 'history', 'roles' => ['siswa']],
            ['name' => 'student-verify', 'controller' => 'StudentBiodataController', 'uri' => '/student-biodata/{id}/verify', 'method' => 'patch', 'action' => 'verify', 'roles' => ['admin']],
        ];

        foreach ($permissions as $perm) {
            $permission = Permission::firstOrCreate(
                ['name' => $perm['name'], 'guard_name' => 'web'],
                [
                    'controller' => $perm['controller'],
                    'uri' => $perm['uri'],
                    'method' => $perm['method'],
                    'action' => $perm['action'],
                ]
            );

            foreach ($perm['roles'] as $roleName) {
                Role::findByName($roleName)->givePermissionTo($permission);
            }
        }

        $demoPassword = Hash::make('password123');

        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Admin Guru Hub', 'password' => $demoPassword, 'is_active' => true]
        );
        $admin->syncRoles(['admin']);

        $guru = User::firstOrCreate(
            ['email' => 'guru@gmail.com'],
            ['name' => 'Budi Santoso, S.Pd.', 'password' => $demoPassword, 'is_active' => true]
        );
        $guru->syncRoles(['guru']);

        $siswa = User::firstOrCreate(
            ['email' => 'siswa@gmail.com'],
            ['name' => 'Bayu Nigroho', 'password' => $demoPassword, 'phone_number' => '082345678901', 'is_active' => true]
        );
        $siswa->syncRoles(['siswa']);

        $this->call(CategorySeeder::class);
    }
}
