<?php
/**
 * Test bootstrap. Just turns off the logger and includes the regular app bootstrap
 */
require_once '../app/bootstrap.php';

\Acme\Util\Logger::disable();
