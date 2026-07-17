<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public routes
$routes->get('/', 'LandingController::index');
$routes->get('/health', 'HealthController::check');

// Auth routes
$routes->get('/login', 'Auth\AuthController::loginForm');
$routes->post('/login', 'Auth\AuthController::login');
$routes->get('/register', 'Auth\AuthController::registerForm');
$routes->post('/register', 'Auth\AuthController::register');
$routes->get('/logout', 'Auth\AuthController::logout');
$routes->get('/forgot-password', 'Auth\AuthController::forgotPasswordForm');
$routes->post('/forgot-password', 'Auth\AuthController::forgotPassword');
$routes->get('/reset-password/(:segment)', 'Auth\AuthController::resetPasswordForm/$1');
$routes->post('/reset-password', 'Auth\AuthController::resetPassword');
$routes->get('/verify-email/(:segment)', 'Auth\AuthController::verifyEmail/$1');

// Protected routes
$routes->group('', ['filter' => 'auth'], function ($routes) {
    // Dashboard
    $routes->get('/dashboard', 'DashboardController::index');
    
    // Patients
    $routes->resource('patients', ['controller' => 'Patient\PatientController']);
    $routes->get('/patients/(:segment)/visits', 'Patient\PatientController::visits/$1');
    $routes->get('/patients/(:segment)/allergies', 'Patient\PatientController::allergies/$1');
    $routes->post('/patients/(:segment)/allergies', 'Patient\PatientController::storeAllergy/$1');
    $routes->get('/patients/(:segment)/chronic-diseases', 'Patient\PatientController::chronicDiseases/$1');
    $routes->get('/patients/(:segment)/documents', 'Patient\PatientController::documents/$1');
    $routes->post('/patients/(:segment)/documents', 'Patient\PatientController::storeDocument/$1');
    $routes->get('/patients/export', 'Patient\PatientController::export');
    
    // Doctors
    $routes->resource('doctors', ['controller' => 'Doctor\DoctorController']);
    $routes->get('/doctors/(:segment)/schedules', 'Doctor\DoctorController::schedules/$1');
    
    // Nurses
    $routes->resource('nurses', ['controller' => 'Nurse\NurseController']);
    
    // Polyclinics
    $routes->resource('polyclinics', ['controller' => 'Polyclinic\PolyclinicController']);
    $routes->post('/polyclinics/(:segment)/doctors', 'Polyclinic\PolyclinicController::assignDoctor/$1');
    
    // Rooms
    $routes->resource('rooms', ['controller' => 'Room\RoomController']);
    $routes->get('/rooms/(:segment)/availability', 'Room\RoomController::availability/$1');
    
    // Schedules
    $routes->resource('schedules', ['controller' => 'ScheduleController']);
    
    // Queues
    $routes->resource('queues', ['controller' => 'QueueController']);
    $routes->post('/queues/(:segment)/call', 'QueueController::call/$1');
    $routes->post('/queues/(:segment)/skip', 'QueueController::skip/$1');
    $routes->post('/queues/(:segment)/recall', 'QueueController::recall/$1');
    $routes->post('/queues/(:segment)/complete', 'QueueController::complete/$1');
    $routes->get('/queues/display', 'QueueController::display');
    
    // Registration
    $routes->resource('registrations', ['controller' => 'RegistrationController']);
    $routes->post('/registrations/(:segment)/status', 'RegistrationController::updateStatus/$1');
    $routes->post('/registrations/(:segment)/discharge', 'RegistrationController::discharge/$1');
    
    // Medical Records
    $routes->resource('medical-records', ['controller' => 'Medical\MedicalRecordController']);
    $routes->post('/medical-records/(:segment)/diagnoses', 'Medical\MedicalRecordController::addDiagnosis/$1');
    $routes->post('/medical-records/(:segment)/actions', 'Medical\MedicalRecordController::addAction/$1');
    $routes->get('/icd10/search', 'Medical\MedicalRecordController::searchICD10');
    
    // Prescriptions
    $routes->resource('prescriptions', ['controller' => 'PrescriptionController']);
    $routes->post('/prescriptions/(:segment)/dispense', 'PrescriptionController::dispense/$1');
    $routes->post('/prescriptions/(:segment)/cancel', 'PrescriptionController::cancel/$1');
    
    // Pharmacy
    $routes->group('pharmacy', ['namespace' => 'Pharmacy'], function ($routes) {
        $routes->resource('drugs', ['controller' => 'PharmacyController']);
        $routes->get('/stocks', 'PharmacyController::stocks');
        $routes->post('/stocks/receive', 'PharmacyController::receiveStock');
        $routes->post('/stocks/distribute', 'PharmacyController::distributeStock');
        $routes->get('/alerts', 'PharmacyController::alerts');
    });
    
    // Lab
    $routes->group('lab', ['namespace' => 'Lab'], function ($routes) {
        $routes->resource('orders', ['controller' => 'LabController']);
        $routes->post('/orders/(:segment)/results', 'LabController::storeResults/$1');
    });
    
    // Radiology
    $routes->group('radiology', ['namespace' => 'Radiology'], function ($routes) {
        $routes->resource('orders', ['controller' => 'RadiologyController']);
        $routes->post('/orders/(:segment)/results', 'RadiologyController::storeResult/$1');
        $routes->post('/orders/(:segment)/images', 'RadiologyController::uploadImage/$1');
    });
    
    // Billing
    $routes->resource('billing', ['controller' => 'Billing\BillingController']);
    $routes->post('/billing/(:segment)/items', 'Billing\BillingController::addItem/$1');
    $routes->post('/billing/(:segment)/discount', 'Billing\BillingController::applyDiscount/$1');
    
    // Payments
    $routes->resource('payments', ['controller' => 'Payment\PaymentController']);
    $routes->post('/payments/process', 'Payment\PaymentController::process');
    
    // Warehouse
    $routes->group('warehouse', ['namespace' => 'Warehouse'], function ($routes) {
        $routes->resource('items', ['controller' => 'WarehouseController']);
        $routes->get('/stocks', 'WarehouseController::stocks');
        $routes->post('/stocks/receive', 'WarehouseController::receiveStock');
        $routes->post('/stocks/distribute', 'WarehouseController::distributeStock');
        $routes->resource('suppliers', ['controller' => 'SupplierController']);
    });
    
    // Inventory
    $routes->resource('inventory', ['controller' => 'Inventory\InventarisController']);
    
    // Reports
    $routes->group('reports', ['namespace' => 'Report'], function ($routes) {
        $routes->get('/', 'ReportController::index');
        $routes->get('/visits', 'ReportController::visits');
        $routes->get('/revenue', 'ReportController::revenue');
        $routes->get('/pharmacy', 'ReportController::pharmacy');
        $routes->get('/lab', 'ReportController::lab');
        $routes->get('/inventory', 'ReportController::inventory');
        $routes->get('/export/pdf', 'ReportController::exportPdf');
        $routes->get('/export/excel', 'ReportController::exportExcel');
    });
    
    // Statistics
    $routes->group('stats', ['namespace' => 'Stats'], function ($routes) {
        $routes->get('/', 'StatsController::index');
        $routes->get('/visits', 'StatsController::visits');
        $routes->get('/diseases', 'StatsController::diseases');
        $routes->get('/revenue', 'StatsController::revenue');
    });
    
    // Users
    $routes->group('users', ['namespace' => 'Admin'], function ($routes) {
        $routes->resource('/', ['controller' => 'UserController']);
        $routes->get('/(:segment)/activity', 'UserController::activity/$1');
        $routes->post('/(:segment)/activate', 'UserController::activate/$1');
        $routes->post('/(:segment)/deactivate', 'UserController::deactivate/$1');
        $routes->post('/(:segment)/assign-role', 'UserController::assignRole/$1');
        $routes->get('/export', 'UserController::export');
    });
    
    // Roles
    $routes->group('roles', ['namespace' => 'Admin'], function ($routes) {
        $routes->resource('/', ['controller' => 'RoleController']);
        $routes->get('/(:segment)/permissions', 'RoleController::permissions/$1');
        $routes->post('/(:segment)/permissions', 'RoleController::updatePermissions/$1');
    });
    
    // Audit
    $routes->get('/audit-logs', 'AuditController::index');
    $routes->get('/audit-logs/(:segment)', 'AuditController::show/$1');
    $routes->get('/audit-logs/export', 'AuditController::export');
    
    // Notifications
    $routes->get('/notifications', 'NotificationController::index');
    $routes->put('/notifications/(:segment)/read', 'NotificationController::markAsRead/$1');
    $routes->put('/notifications/read-all', 'NotificationController::markAllAsRead');
    $routes->get('/notifications/unread-count', 'NotificationController::unreadCount');
    
    // Settings
    $routes->get('/settings', 'SettingController::index');
    $routes->post('/settings/update', 'SettingController::update');
});