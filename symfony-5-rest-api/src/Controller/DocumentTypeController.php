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

    #[Route('/api/v1/document/type', name: 'get_document_type', methods: ['GET'])]
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

    #[Route('/api/v1/document/type', name: 'post_document_type', methods: ['POST'])]
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

    #[Route('/api/v1/document/type/{id}', name: 'show_document_type', methods: ['GET'])]
    public function show(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $documentType = $entityManager
            ->getRepository(DocumentType::class)
            ->find($id);

        if (!$documentType) {
            return $this->json('No document type found for id' . $id, 404);
        }

        $data = [
            'id' => $documentType->getId(),
            'name' => $documentType->getName()
        ];

        return $this->json($data);
    }

    #[Route('/api/v1/document/type/{id}', name: 'edit_document_type', methods: ['PATCH'])]
    public function edit(Request $request, int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $documentType = $entityManager->getRepository(DocumentType::class)->find($id);

        if (!$documentType) {
            return $this->json('No document found for id' . $id, 404);
        }

        $documentType->setName($request->request->get('name'));
        $entityManager->flush();

        $data = [
            'id' => $documentType->getId(),
            'name' => $documentType->getName()
        ];

        return $this->json($data);
    }

    #[Route('/api/v1/document/type/{id}', name: 'delete_document_type', methods: ['DELETE'])]
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
