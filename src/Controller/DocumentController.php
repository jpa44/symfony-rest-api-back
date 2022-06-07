<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Document;
use App\Entity\DocumentType;
use App\Entity\DocumentMedia;
use Symfony\Component\HttpFoundation\Request;
use App\Service\FileUploader;

class DocumentController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine)
    {
    }

    #[Route('/api/document', name: 'get_document', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $user = $this->getUser();
        $entityManager = $doctrine->getManager();

        if($user->getRoles() == 'ROLE_ADMIN') {
            $documents = $entityManager->getRepository(Document::class)->findAll();
        }else{
            //TODO: find by DocumentRule
            $documents = $entityManager->getRepository(Document::class)->findBy(['user' => $user]);
        }
      
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

        return $this->json($data, Response::HTTP_OK);
    }

    #[Route('/api/document', name: 'post_document', methods: ['POST'])]
    public function new(Request $request, ManagerRegistry $doctrine, FileUploader $fileUploader): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $documentType = $entityManager
            ->getRepository(DocumentType::class)
            ->find($request->request->get('documentType'));

        if (!$documentType) {
            return $this->json('No document type found for id' . $request->request->get('documentType'), Response::HTTP_NOT_FOUND);
        }

        $document = new Document();
        $document->setTitle($request->request->get('title'));
        $document->setDescription($request->request->get('description'));
        $document->setDocumentType($documentType);
        $document->setCreatedAt(new \DateTime());
        $document->setUser($this->getUser());

        $uploadedFile = $request->files->get('file');
        if ($uploadedFile) {
            $mediaObject = new DocumentMedia();
            $mediaObject->filePath = $fileUploader->upload($uploadedFile);
            $mediaObject->setDocument($document);
            $entityManager->persist($mediaObject);
        }

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

        return $this->json($data, Response::HTTP_CREATED);
    }

    #[Route('/api/document/{id}', name: 'show_document', methods: ['GET'])]
    public function show(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $document = $entityManager
            ->getRepository(Document::class)
            ->find($id);

        if (!$document) {
            return $this->json('No document found for id' . $id, Response::HTTP_NOT_FOUND);
        }

        $user = $this->getUser();

        if($user->getId() !== $document->getUser()->getId()){
            return $this->json('Access denied', Response::HTTP_UNAUTHORIZED);
        }


        $data = [
            'id' => $document->getId(),
            'title' => $document->getTitle(),
            'description' => $document->getDescription(),
            'type' => $document->getDocumentType(),
            'medias' => $document->getMedia(),
            'createdAt' => $document->getCreatedAt()
        ];

        return $this->json($data, Response::HTTP_OK);
    }

    #[Route('/api/document/{id}', name: 'edit_document', methods: ['PATCH'])]
    public function edit(Request $request, int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $document = $entityManager->getRepository(Document::class)->find($id);

        if (!$document) {
            return $this->json('No document found for id' . $id, Response::HTTP_NOT_FOUND);
        }

        $documentType = $entityManager
            ->getRepository(DocumentType::class)
            ->find($request->request->get('documentType'));

        if (!$documentType) {
            return $this->json('No document type found for id' . $request->request->get('documentType'), Response::HTTP_NOT_FOUND);
        }

        $user = $this->getUser();

        if($user->getId() !== $document->getUser()->getId()){
            return $this->json('Access denied', Response::HTTP_UNAUTHORIZED);
        }

        $document->setTitle($request->request->get('title'));
        $document->setDescription($request->request->get('description'));
        $document->setDocumentType($documentType);
        $entityManager->flush();

        $data = [
            'id' => $document->getId(),
            'title' => $document->getTitle(),
            'description' => $document->getDescription(),
            'type' => $document->getDocumentType(),
            'medias' => $document->getMedia(),
            'createdAt' => $document->getCreatedAt()
        ];

        return $this->json($data, Response::HTTP_OK);
    }

    #[Route('/api/document/{id}', name: 'delete_document', methods: ['DELETE'])]
    public function delete(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $document = $entityManager->getRepository(Document::class)->find($id);

        if (!$document) {
            return $this->json('No document found for id' . $id, Response::HTTP_NOT_FOUND);
        }

        $user = $this->getUser();

        if($user->getId() !== $document->getUser()->getId()){
            return $this->json('Access denied', Response::HTTP_UNAUTHORIZED);
        }

        $entityManager->remove($document);
        $entityManager->flush();

        return $this->json('Deleted a document successfully with id ' . $id, Response::HTTP_OK);
    }
}
