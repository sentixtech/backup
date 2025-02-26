<?php

namespace Backup;

use Google\Client;
use Google\Service\Drive;

class GoogleDriveUploader
{
    protected $client;
    protected $driveService;
    protected $folderId;

    public function __construct($serviceAccountFile, $folderId)
    {
        $this->client = new Client();
        $this->client->setAuthConfig($serviceAccountFile);
        $this->client->addScope(Drive::DRIVE_FILE);

        $this->driveService = new Drive($this->client);
        $this->folderId = $folderId;
    }

    public function uploadFile($filePath)
    {
        $file = new Drive\DriveFile();
        if ($this->folderId) {
            $file->setParents([$this->folderId]);
        }

        $file->setName(basename($filePath));
        $content = file_get_contents($filePath);

        $result = $this->driveService->files->create($file, [
            'data' => $content,
            'mimeType' => 'application/zip',
            'uploadType' => 'multipart',
        ]);

        return $result->id;
    }
}
