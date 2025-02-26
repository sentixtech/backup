<?php

namespace Backup;

use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class FileBackup
{
    protected $backupPath;

    public function __construct($backupPath)
    {
        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0777, true);
        }
        $this->backupPath = realpath($backupPath);
    }

    public function createBackup()
    {
        $zipFileName = $this->backupPath . '/backup_' . date('Y-m-d_H-i-s') . '.zip';
        $zip = new ZipArchive();

        if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {
            $rootPath = realpath(__DIR__ . '/../../../');
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath), RecursiveIteratorIterator::LEAVES_ONLY);

            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($rootPath) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }

            $zip->close();
            return $zipFileName;
        }

        return false;
    }
}
