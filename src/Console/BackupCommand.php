<?php
namespace Backup\Console;

use Illuminate\Console\Command;
use Backup\BackupManager;
use Backup\GoogleDriveUploader;

class BackupCommand extends Command
{
    protected $signature = 'backup:run';
    protected $description = 'Create a backup and upload to Google Drive';

    public function handle()
    {
        $backupPath = config('backup.backup_path');
        $googleUploader = new GoogleDriveUploader(config('backup.service_account_json'), config('backup.google_drive_folder_id'));

        $backupManager = new BackupManager($backupPath, $googleUploader);
        $dbConfig = [
            'host' => env('DB_HOST'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'database' => env('DB_DATABASE'),
        ];

        $backupManager->createFullBackup($dbConfig);

        $this->info('Backup completed successfully!');
    }
}
