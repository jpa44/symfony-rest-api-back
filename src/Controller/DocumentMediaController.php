<?php

namespace App\Controller;

use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\DocumentMedia;
use Symfony\Component\HttpFoundation\Response;

class DocumentMediaController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine)
    {
    }

    #[Route('/api/document_media/{id}', name: 'get_document_media', methods: ['GET'])]
    public function index(int $id, ManagerRegistry $doctrine, FileUploader $fileUploader): BinaryFileResponse
    {
        //TODO : check if user can download the document
        $entityManager = $doctrine->getManager();
        $documentMedia = $entityManager->getRepository(DocumentMedia::class)->find($id);

        if (!$documentMedia) {
            return $this->json(['message' => 'DocumentMedia not found'], Response::HTTP_NOT_FOUND);
        }

        return $fileUploader->download($documentMedia->filePath);
    }

    #[Route('/api/document_media/{id}', name: 'delete_document_type', methods: ['DELETE'])]
    public function delete(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        //TODO : check if user can download the document
        $entityManager = $doctrine->getManager();
        $documentMedia = $entityManager->getRepository(DocumentMedia::class)->find($id);

        if (!$documentMedia) {
            return $this->json('No document media found for id' . $id, Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($documentMedia);
        $entityManager->flush();

        return $this->json('Deleted a document media successfully with id ' . $id);
    }
}
