<?php
/**
 * Kenny Tang 2017
 */
//Sign out of the application
session_start();
session_unset();
session_destroy();
header("Location: index.php");