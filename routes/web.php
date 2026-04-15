<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('Loading');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', function () {
        return inertia('Dashboard');
    })->name('dashboard');

    Route::apiResource('users', \App\Http\Controllers\UserController::class);
    Route::apiResource('companies', \App\Http\Controllers\CompanyController::class);
    Route::apiResource('departments', \App\Http\Controllers\DepartmentController::class);
    Route::apiResource('categories', \App\Http\Controllers\CategoryController::class);
    Route::apiResource('designations', \App\Http\Controllers\DesignationController::class);

    Route::apiResource('employees', \App\Http\Controllers\EmployeeController::class);

    Route::apiResource('shifts', \App\Http\Controllers\ShiftController::class);
    Route::delete('rosters/bulk-delete', [\App\Http\Controllers\RosterController::class, 'bulkDelete'])->name('rosters.bulkDelete');
    Route::apiResource('rosters', \App\Http\Controllers\RosterController::class);
    Route::delete('attendance/bulk-delete', [\App\Http\Controllers\AttendanceController::class, 'bulkDelete'])->name('attendance.bulkDelete');
    Route::apiResource('attendance', \App\Http\Controllers\AttendanceController::class);
    Route::apiResource('defaults', \App\Http\Controllers\DefaultController::class);
    Route::post('deductions/generate-salary', [\App\Http\Controllers\DeductionController::class, 'generateSalary'])->name('deductions.generateSalary');
    Route::apiResource('deductions', \App\Http\Controllers\DeductionController::class);
    Route::get('reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');

    //pdf routes
    Route::get('/bio-data/{employee}', [\App\Http\Controllers\PdfController::class, 'generateBioData'])->name('pdf.bio-data');
    Route::get('/t-and-c/{employee}', [\App\Http\Controllers\PdfController::class, 'generateTermsAndConditions'])->name('pdf.t-and-c');
    Route::get('/appointment-order/{employee}', [\App\Http\Controllers\PdfController::class, 'generateAppointmentOrder'])->name('pdf.appointment-order');
    Route::get('/induction-training/{employee}', [\App\Http\Controllers\PdfController::class, 'generateInductionTraining'])->name('pdf.induction-training');
    Route::get('/form-v/{employee}', [\App\Http\Controllers\PdfController::class, 'generateFormV'])->name('pdf.form-v');
    Route::get('/form-f/{employee}', [\App\Http\Controllers\PdfController::class, 'generateFormF'])->name('pdf.form-f');
    Route::get('/form-2/{employee}', [\App\Http\Controllers\PdfController::class, 'generateForm2'])->name('pdf.form-2');
    Route::get('/esic/{employee}', [\App\Http\Controllers\PdfController::class, 'generateEsic'])->name('pdf.esic');
    Route::get('/form-34/{employee}', [\App\Http\Controllers\PdfController::class, 'generateForm34'])->name('pdf.form-34');

    //payslip
    Route::get('/form-25b/{employee}', [\App\Http\Controllers\PdfController::class, 'generateForm25B'])->name('pdf.form-25b');

    //timing report
    Route::get('/timing-report/{employee}', [\App\Http\Controllers\PdfController::class, 'generateTimingReport'])->name('pdf.timing-report');

    //Attendance report
    Route::get('/attendance-report/{company}', [\App\Http\Controllers\PdfController::class, 'generateAttendanceReport'])->name('pdf.attendance-report');

    //PDF documents page for all details routes
    Route::get('/employee-pdf-documents/{employee}', [\App\Http\Controllers\PdfController::class, 'employeePdfDocuments'])->name('pdf.documents');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', function () {
        return inertia('Login');
    })->name('login');

    Route::post('/login', [AuthController::class, 'login']);
});


