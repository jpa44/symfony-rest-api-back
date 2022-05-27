<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Document;
use Symfony\Component\HttpFoundation\Request;

class DocumentController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine) {}

    #[Route('/api/v1/document', name: 'get_document', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $documents = $entityManager->getRepository(Document::class)->findAll();

        $data = [];

        foreach ($documents as $document) {
            $data[] = [
                'id' => $document->getId(),
                'title' => $document->getTitle(),
                'description' => $document->getDescription(),
                'type' => $document->getDocumentType(),
                'medias' => $document->getMedia(),
                'createdAt' => $document->getCreatedAt()
            ];
        }

        return $this->json($data);
    }

    #[Route('/api/v1/document', name: 'post_document', methods: ['POST'])]
    public function new(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $document = new Document();
        $document->setTitle($request->request->get('title'));
        $document->setDescription($request->request->get('description'));
        $document->setCreatedAt(new \DateTime());
        //$document->setDocumentType(['ROLE_USER']);

        $entityManager->persist($document);

        $entityManager->flush();

        $data = [
            'id' => $document->getId(),
            'title' => $document->getTitle(),
            'description' => $document->getDescription(),
            'type' => $document->getDocumentType(),
            'medias' => $document->getMedia(),
            'createdAt' => $document->getCreatedAt()
        ];

        return $this->json($data);
    }

    #[Route('/api/v1/document/{id}', name: 'show_document', methods: ['GET'])]
    public function show(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $document = $entityManager
            ->getRepository(Document::class)
            ->find($id);

        if (!$document) {
            return $this->json('No document found for id' . $id, 404);
        }

        $data = [
            'id' => $document->getId(),
            'title' => $document->getTitle(),
            'description' => $document->getDescription(),
            'type' => $document->getDocumentType(),
            'medias' => $document->getMedia(),
            'createdAt' => $document->getCreatedAt()
        ];

        return $this->json($data);
    }

    #[Route('/api/v1/document/{id}', name: 'edit_document', methods: ['PATCH'])]
    public function edit(Request $request, int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $document = $entityManager->getRepository(Document::class)->find($id);

        if (!$document) {
            return $this->json('No document found for id' . $id, 404);
        }

        $document->setRoles(['ROLE_USER']);
        $document->setFirstName($request->request->get('firstName'));
        $entityManager->flush();

        $data = [
            'id' => $document->getId(),
            'title' => $document->getTitle(),
            'description' => $document->getDescription(),
            'type' => $document->getDocumentType(),
            'medias' => $document->getMedia(),
            'createdAt' => $document->getCreatedAt()
        ];

        return $this->json($data);
    }

    #[Route('/api/v1/document/{id}', name: 'delete_document', methods: ['DELETE'])]
    public function delete(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $document = $entityManager->getRepository(Document::class)->find($id);

        if (!$document) {
            return $this->json('No document found for id' . $id, 404);
        }

        $entityManager->remove($document);
        $entityManager->flush();

        return $this->json('Deleted a document successfully with id ' . $id);
    }
}
