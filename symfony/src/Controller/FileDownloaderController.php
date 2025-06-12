<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

use App\Repository\FichierRepository;
use App\Entity\Fichier;

use Nelmio\ApiDocBundle\Attribute\Model;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;

final class FileDownloaderController extends AbstractController
{

    public function __construct(
        private readonly FichierRepository $fichierRepository,
    ) {}
    #[Route('/api/file/{id}', name: 'download_file', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Streamed file content (binary)',
        content: new OA\MediaType(
            mediaType: 'application/octet-stream',
            schema: new OA\Schema(type: 'string', format: 'binary')
        )
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        description: 'File ID',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    public function downloadFile(int $id): StreamedResponse
    {
        // Retrieve the file entity by ID
        $file = $this->fichierRepository->find($id);

        if (!$file) {
            throw $this->createNotFoundException('File not found');
        }

        // Create the streamed response
        $response = new StreamedResponse(function () use ($file) {
            // Open the file in binary mode
            $handle = fopen("../defis_assets/".$file->getNom(), 'rb');
            if (!$handle) {
                throw new \RuntimeException('Failed to open file for reading');
            }

            // Stream the file in chunks
            while (!feof($handle)) {
                echo fread($handle, 8192); // 8KB chunks
            }
            fclose($handle);
        });

        // Set headers for file download
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $file->getNom()
        );

        #$response->headers->set('Content-Type', $file->getMimeType());
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }
}

