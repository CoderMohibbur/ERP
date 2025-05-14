<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/page', function () {
    return view('page');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});



// Admin Panel Routes Group
Route::prefix('admin')->middleware(['auth'])->group(function () {

    // Employee Management
    Route::get('/employmanagement', function () {
        return 'admin/employmanagement';
    })->name('admin.employmanagement.index');

    // Attendance System
    Route::get('/attendancesystem', function () {
        return 'admin/attendancesystem';
    })->name('admin.attendancesystem.index');

    // Project Management
    Route::get('/projectmanagement', function () {
        return 'admin/projectmanagement';
    })->name('admin.projectmanagement.index');

    // Client Management
    Route::get('/clientmanagement', function () {
        return 'admin/clientmanagement';
    })->name('admin.clientmanagement.index');

    // Billing & Invoicing
    Route::get('/billinginvoicing', function () {
        return 'admin/billinginvoicing';
    })->name('admin.billinginvoicing.index');
});

