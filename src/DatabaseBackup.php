<?php

namespace Backup;

class DatabaseBackup
{
    protected $backupPath;

    public function __construct($backupPath)
    {
        $this->backupPath = $backupPath;
    }

    public function createBackup($dbConfig)
    {
        $backupFile = $this->backupPath . '/db_backup_' . date('Y-m-d_H-i-s') . '.sql';
        $command = "mysqldump --user={$dbConfig['username']} --password={$dbConfig['password']} --host={$dbConfig['host']} {$dbConfig['database']} > $backupFile";
        system($command);

        return file_exists($backupFile) ? $backupFile : false;
    }
}
