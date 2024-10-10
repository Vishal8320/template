<?php

// Include your database connection and configuration files
require_once __DIR__ . '/include/config.php';
require_once __DIR__ . '/include/database.php';

// Define colors
define('COLOR_YELLOW', "\033[1;33m");
define('COLOR_RED', "\033[1;31m");
define('COLOR_RESET', "\033[0m");

// Backup Function
function backup()
{
    global $CONF;
    
    // Fetch database name from connection details
    $databaseName = $CONF['name'] ?? 'default_db_name';
    $backupFilePath = __DIR__ . '/backup/database/backup.sql';

    // Create backup directory if it doesn't exist
    if (!is_dir(__DIR__ . '/backup/database')) {
        mkdir(__DIR__ . '/backup/database', 0777, true);
    }
    
    echo "Starting database backup for '$databaseName'...\n";

    // Full path to mysqldump executable
    $mysqldumpPath = 'C:\xampp\mysql\bin\mysqldump.exe';
    $command = "\"$mysqldumpPath\" -u {$CONF['user']} -p{$CONF['pass']} {$databaseName} > \"$backupFilePath\"";

    // Execute the backup command
    $output = shell_exec($command);
    
    if ($output === null) {
        echo COLOR_YELLOW . "Database backup completed and stored at $backupFilePath!\n" . COLOR_RESET;
    } else {
        echo COLOR_RED . "Error during backup: $output\n" . COLOR_RESET;
    }
}

// Restore Function
function restore()
{
    global $CONF;

    // Fetch database name from connection details
    $databaseName = $CONF['name'] ?? 'default_db_name';
    $backupFilePath = __DIR__ . '/backup/database/backup.sql';
    
    echo "Starting database restore for '$databaseName'...\n";

    // Full path to mysql executable
    $mysqlPath = 'C:\xampp\mysql\bin\mysql.exe';
    $dropCommand = "\"$mysqlPath\" -u {$CONF['user']} -p{$CONF['pass']} -e 'DROP DATABASE IF EXISTS {$databaseName}; CREATE DATABASE {$databaseName};'";
    
    // Execute the drop and create command
    shell_exec($dropCommand);

    // Command to restore the database
    $restoreCommand = "\"$mysqlPath\" -u {$CONF['user']} -p{$CONF['pass']} {$databaseName} < \"$backupFilePath\"";

    // Execute the restore command
    $output = shell_exec($restoreCommand);

    if ($output === null) {
        echo COLOR_YELLOW . "Database restore completed from $backupFilePath!\n" . COLOR_RESET;
    } else {
        echo COLOR_RED . "Error during restore: $output\n" . COLOR_RESET;
    }
}

// Command Parsing Logic
$command = $argv[1] ?? null; // First argument passed to the script

if ($command) {
    switch ($command) {
        case 'backup':
            backup();
            break;
        case 'restore':
            restore();
            break;
        default:
            echo COLOR_RED . "Unknown command: $command\n" . COLOR_RESET;
            break;
    }
} else {
    echo COLOR_RED . "No command provided.\n" . COLOR_RESET;
    echo "Usage: php make.php <command>\n";
    echo "Available commands: backup, restore\n";
}
