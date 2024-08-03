<?php
require_once __DIR__ . "/src/Logger.php";

$logFolderPt = sprintf("%s/logs", __DIR__);
$logFileName = "main-script.log";
$fileLogger = new Logger($logFolderPt, $logFileName);

$fileLogger->writeLog("=== PHP SIMPLE LOGGER ===");
$fileLogger->writeLog(sprintf("Checking log file name variable: %s", isset($logFileName) ? true : false));
$fileLogger->writeLog("Done!");