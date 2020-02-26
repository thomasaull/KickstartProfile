<?php namespace ProcessWire;

/**
 * Admin template just loads the admin application controller, 
 * and admin is just an application built on top of ProcessWire. 
 *
 * This demonstrates how you can use ProcessWire as a front-end 
 * to another application. 
 *
 * Feel free to hook admin-specific functionality from this file, 
 * but remember to leave the require() statement below at the end.
 * 
 */

 // check if the user is logged in and if they are not a super user
if ($user->isLoggedIn() && $config->maintenanceBackend === true && !$user->isSuperuser()) {
  // logout the user
  $session->logout();
  // spit out an error message via session, so it still appears after the redirect
  $session->error('Database currently in maintenance - logged out');
  // redirect to the login page
  $session->redirect($config->urls->admin);
}

require($config->paths->adminTemplates . 'controller.php'); 
