<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\DocumentType;
use Symfony\Component\HttpFoundation\Request;

class DocumentTypeController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine) {}

    #[Route('/api/document_type', name: 'get_document_type', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $documentTypes = $entityManager->getRepository(DocumentType::class)->findAll();

        $data = [];

        foreach ($documentTypes as $documentType) {
            $data[] = [
                'id' => $documentType->getId(),
                'name' => $documentType->getName()
            ];
        }

        return $this->json($data);
    }

    #[Route('/api/document_type', name: 'post_document_type', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $documentType = new DocumentType();
        $documentType->setName($request->request->get('name'));

        $entityManager->persist($documentType);

        $entityManager->flush();

        $data = [
            'id' => $documentType->getId(),
            'name' => $documentType->getName(),
        ];

        return $this->json($data);
    }

    #[Route('/api/document_type/{id}', name: 'delete_document_type', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $documentType = $entityManager->getRepository(DocumentType::class)->find($id);

        if (!$documentType) {
            return $this->json('No document type found for id' . $id, 404);
        }

        $entityManager->remove($documentType);
        $entityManager->flush();

        return $this->json('Deleted a document type successfully with id ' . $id);
    }
}
