<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default route - redirect to login if not authenticated
$routes->get('/', function() {
    if (session()->get('is_logged_in')) {
        return redirect()->to(base_url('chat'));
    }
    return redirect()->to(base_url('login'));
});

// Authentication routes
$routes->group('', ['filter' => 'guest'], function($routes) {
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::login');
    $routes->get('register', 'AuthController::register');
    $routes->post('register', 'AuthController::register');
});

// Public routes for logout/offline (accessible without auth filter)
$routes->post('auth/logout', 'AuthController::logout');
$routes->post('chat/set-offline', 'ChatController::setOffline');

// Authenticated routes
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('logout', 'AuthController::logout');
    $routes->post('logout', 'AuthController::logout'); // Add POST method for auto-logout
    $routes->get('profile', 'AuthController::profile');
    $routes->post('profile', 'AuthController::profile');
    
    // Chat routes
    $routes->get('chat', 'ChatController::index');
    $routes->get('chat/users', 'ChatController::getUsers');
    $routes->get('chat/conversation/(:num)', 'ChatController::conversation/$1');
    $routes->post('chat/send', 'ChatController::sendMessage');
    $routes->get('chat/check-messages', 'ChatController::checkNewMessages');
    $routes->post('chat/online-status', 'ChatController::updateOnlineStatus');
    $routes->post('chat/set-offline', 'ChatController::setOffline'); // Add offline status route
    $routes->get('chat/recent', 'ChatController::getRecentMessages');
    $routes->get('chat/search', 'ChatController::search');
});
