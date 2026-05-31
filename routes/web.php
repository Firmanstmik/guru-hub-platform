<?php

use App\Http\Controllers\CategoriController;
use App\Http\Controllers\AksesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ClassScheduleController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseMaterialController;
use App\Http\Controllers\CourseStudentController;
use App\Http\Controllers\CourseVideoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TeacherEarningController;
use App\Http\Controllers\TeacherProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserManajemenController;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Role
    Route::resource('roles', RoleController::class);

    Route::controller(RoleController::class)->group(function () {
        Route::get('roles/{id}/permissions', 'editPermissions')->name('roles.permissions');
        Route::post('roles/{id}/permissions', 'updatePermissions')->name('roles.permissions.update');
        Route::get('/view-edit-roles/{id}', 'viewEditRoles')->name('roles.edit');
        Route::post('/post-edit-roles/{id}', 'PostUpdateRoles')->name('roles.post.edit');
    });

    // Permission
    Route::resource('permissions', PermissionController::class);

    // Assign Role ke User
    Route::get('users-manage', [UserManajemenController::class, 'index'])->name('users-manage.index');
    Route::get('users-manage/{id}/roles', [UserManajemenController::class, 'editRoles'])->name('users-manage.roles');
    Route::post('users-manage/{id}/roles', [UserManajemenController::class, 'updateRoles'])->name('users-manage.roles.update');

    Route::get('/akses', [AksesController::class, 'index'])->name('akses.index');
});


// ==========================================
// 1. Courses (Index, Store, Update, Destroy)
// ==========================================
// Route::get('courses', [CourseController::class, 'index']);
// Route::post('courses', [CourseController::class, 'store']);
// Route::put('courses/{course}', [CourseController::class, 'update']);
// Route::delete('courses/{course}', [CourseController::class, 'destroy']);

// ==========================================
// 2. Categories (Index, Store, Update, Destroy)
// ==========================================
// Route::get('categories', [CategoriController::class, 'index']);
// Route::post('categories', [CategoriController::class, 'store']);
// Route::put('categories/{category}', [CategoriController::class, 'update']);
// Route::delete('categories/{category}', [CategoriController::class, 'destroy']);

// ==========================================
// 3. Users (Index, Store, Update, Destroy)
// ==========================================
// Route::get('users', [UserController::class, 'index']);
// Route::post('users', [UserController::class, 'store']);
// Route::put('users/{user}', [UserController::class, 'update']);
// Route::delete('users/{user}', [UserController::class, 'destroy']);

// ==========================================
//4. teacher profile
// ==========================================
// Route::get('teachers', [TeacherProfileController::class, 'index']);
// Route::patch('teachers/{profile}/verify', [TeacherProfileController::class, 'verify']);

// ==========================================
// 5. Materials (Index, Store, Update, Destroy)
// ==========================================
// Route::get('materials', [CourseMaterialController::class, 'index']);
// Route::post('materials', [CourseMaterialController::class, 'store']);
// Route::put('materials/{material}', [CourseMaterialController::class, 'update']);
// Route::delete('materials/{material}', [CourseMaterialController::class, 'destroy']);

// ==========================================
// 6. Videos (Index, Store, Update, Destroy)
// ==========================================
// Route::get('videos', [CourseVideoController::class, 'index']);
// Route::post('videos', [CourseVideoController::class, 'store']);
// Route::put('videos/{video}', [CourseVideoController::class, 'update']);
// Route::delete('videos/{video}', [CourseVideoController::class, 'destroy']);

// ==========================================
// 7. Course Students (Index, Store, Update, Destroy)
// ==========================================
// Route::get('course-students', [CourseStudentController::class, 'index']);
// Route::post('course-students', [CourseStudentController::class, 'store']);
// Route::put('course-students/{courseStudent}', [CourseStudentController::class, 'update']);
// Route::delete('course-students/{courseStudent}', [CourseStudentController::class, 'destroy']);

// ==========================================
// 8. Schedules (Index, Store, Update, Destroy)
// ==========================================
// Route::get('schedules', [ClassScheduleController::class, 'index']);
// Route::post('schedules', [ClassScheduleController::class, 'store']);
// Route::put('schedules/{schedule}', [ClassScheduleController::class, 'update']);
// Route::delete('schedules/{schedule}', [ClassScheduleController::class, 'destroy']);

// ==========================================
// 9. Bookings (Index, Update, Destroy)
// ==========================================
// Route::get('bookings', [BookingController::class, 'index']);
// Route::put('bookings/{booking}', [BookingController::class, 'update']);
// Route::delete('bookings/{booking}', [BookingController::class, 'destroy']);

// ==========================================
// 10. Certificates (Index, Store, Destroy)
// ==========================================
// Route::get('certificates', [CertificateController::class, 'index']);
// Route::post('certificates', [CertificateController::class, 'store']);
// Route::delete('certificates/{certificate}', [CertificateController::class, 'destroy']);

// ==========================================
// 11. Reviews (Index, Destroy)
// ==========================================
// Route::get('reviews', [ReviewController::class, 'index']);
// Route::delete('reviews/{review}', [ReviewController::class, 'destroy']);

// ==========================================
// 12. Payments (Index, Approve, Reject)
// ==========================================
// Route::get('payments', [PaymentController::class, 'index']);
// Route::patch('payments/{payment}/approve', [PaymentController::class, 'approve']);
// Route::patch('payments/{payment}/reject', [PaymentController::class, 'reject']);

// ==========================================
// 13. Teacher Earnings (Index, Update Status)
// ==========================================
// Route::get('earnings', [TeacherEarningController::class, 'index']);
// Route::patch('earnings/{earning}/status', [TeacherEarningController::class, 'updateStatus']);



Route::get('/login', [AuthController::class, 'viewLogin']);
Route::post('/login', [AuthController::class, 'Login'])->name('login');
Route::get('/logout', [AuthController::class, 'Logout'])->middleware('auth')->name('logout');
