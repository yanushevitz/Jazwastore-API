<?php

namespace App\Services;
use Ramsey\Uuid\Uuid;
use Slim\Psr7\UploadedFile;

class FileService{
        public function moveFile(UploadedFile $file){
            $clientFilename = $file->getClientFilename();
            $slicedFileName = explode(".", $clientFilename);
            $slicedFileName[2] = $slicedFileName[1];
            $slicedFileName[1] = Uuid::uuid4();
            $filename = $slicedFileName[0].$slicedFileName[1].".".$slicedFileName[2];
            $filePath = "/var/www/public/uploaded/".$filename;
            $file->moveTo($filePath);
            return $filename;
        }
}