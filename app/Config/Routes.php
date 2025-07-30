<?php


namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

define("CI_ENVIRONMENT","development");
// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Login');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Login::index');

$routes->group('marriage', function($routes) {
    $routes->get('/', 'Marriage::index');
    $routes->get('calendar', 'Marriage::calendar');
    $routes->get('create', 'Marriage::create');
    $routes->post('store', 'Marriage::store');
    $routes->get('view/(:num)', 'Marriage::view/$1');
    $routes->get('edit/(:num)', 'Marriage::edit/$1');
    $routes->post('update/(:num)', 'Marriage::update/$1');
    $routes->delete('delete/(:num)', 'Marriage::delete/$1');
    $routes->get('payment/(:num)', 'Marriage::payment/$1');
    $routes->post('process_payment', 'Marriage::process_payment');
    $routes->post('check_availability', 'Marriage::check_availability');
    $routes->get('settings', 'Marriage::settings');
    $routes->get('reports', 'Marriage::reports');
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
$routes->group('due_report', function($routes) {
    $routes->get('/', 'Due_report::index');
    $routes->get('exportExcel', 'Due_report::exportExcel');
    $routes->get('printReport', 'Due_report::printReport');
});
$routes->get('getReceiptPaymentHistory/(:num)', 'DueReport::getReceiptPaymentHistory/$1');
$routes->get('getReceiptPaymentHistoryAjax/(:num)', 'DueReport::getReceiptPaymentHistoryAjax/$1');
$routes->group('api/inventory', function($routes) {
    $routes->get('dashboard-stats', 'Inventory\InventoryController::dashboardStats');
    $routes->get('low-stock', 'Inventory\InventoryController::lowStock');
    $routes->get('expiring-items', 'Inventory\InventoryController::expiringItems');
    $routes->get('recent-transactions', 'Inventory\InventoryController::recentTransactions');
});