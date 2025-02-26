<?php

namespace Backup;

use Google\Client;
use Google\Service\Drive;
use Exception;

class GoogleDriveUploader
{
    protected $client;
    protected $driveService;
    protected $folderId;

    public function __construct($serviceAccountFile, $folderId)
    {
        try {
            // Google Client Setup
            $this->client = new Client();
            $this->client->setAuthConfig($serviceAccountFile);
            $this->client->addScope(Drive::DRIVE); 

            $this->driveService = new Drive($this->client);
            $this->folderId = $folderId;
        } catch (Exception $e) {
            die("Google Drive API Error: " . $e->getMessage());
        }
    }

    public function uploadFile($filePath)
    {
        try {
            if (!file_exists($filePath)) {
                throw new Exception("File not found: " . $filePath);
            }

            $file = new Drive\DriveFile();
            if ($this->folderId) {
                $file->setParents([$this->folderId]);
            }

            // Set File Name
            $file->setName(basename($filePath));

            // Detect MIME Type
            $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';

            $content = file_get_contents($filePath);

            // Upload File
            $result = $this->driveService->files->create($file, [
                'data' => $content,
                'mimeType' => $mimeType,
                'uploadType' => 'multipart',
            ]);

            return $result->id ?? null;
        } catch (Exception $e) {
            return "File Upload Error: " . $e->getMessage();
        }
    }
}
