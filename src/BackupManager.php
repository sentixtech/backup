<?php
namespace Backup;

use Backup\FileBackup;
use Backup\DatabaseBackup;
use Backup\GoogleDriveUploader;

class BackupManager
{
    protected $backupPath;
    protected $googleUploader;

    public function __construct($backupPath, $googleUploader = null)
    {
        $this->backupPath = $backupPath;
        $this->googleUploader = $googleUploader;
    }

    public function createFullBackup($dbConfig)
    {
        $fileBackup = new FileBackup($this->backupPath);
        $dbBackup = new DatabaseBackup($this->backupPath);

        $zipBackup = $fileBackup->createBackup();
        $dbBackupFile = $dbBackup->createBackup($dbConfig);

        if ($this->googleUploader) {
            if ($zipBackup) {
                $this->googleUploader->uploadFile($zipBackup);
            }
            if ($dbBackupFile) {
                $this->googleUploader->uploadFile($dbBackupFile);
            }
        }

        return [$zipBackup, $dbBackupFile];
    }
}
