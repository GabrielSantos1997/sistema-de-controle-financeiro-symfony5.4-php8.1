<?php

namespace App\Controller;

use App\Entity\Resource;
use App\Form\Type\ResourceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/resource', name: 'app_resource')]
class ResourceController extends AbstractController
{
    #[Route(
        '/list',
        name: 'list',
        methods: ['GET'],
    )]
    public function listAction(): Response
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository(Resource::class)
            ->createQueryBuilder('e')
            ->getQuery()
            ->getResult()
        ;

        return $this->json($qb, 200);
    }

    #[Route(
        '/{identifier}/show',
        name: 'show',
        methods: ['GET'],
        requirements: ['identifier' => '[\w\-\_]{15}']
    )]
    public function showAction(
        string $identifier = ''
    ): Response {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository(Resource::class)->findOneByIdentifier($identifier);
        if (!$entity) {
            return $this->json([
                'message' => 'Recurso não encontrado',
                'success' => false,
            ], 404);
        }

        return $this->json($entity, 200);
    }

    #[Route(
        '/new',
        name: 'new',
        methods: ['POST'],
    )]
    public function newAction(
        Request $request,
    ): Response {
        $entity = new Resource;

        $form = $this->createForm(ResourceType::class, $entity);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return $this->json([
                'message' => 'Dados do formulário inválidos',
                'success' => false,
            ], 400);
        }

        if (!$form->isValid()) {
            return $this->json([
                'errors' => $form->getErrors(true, false),
                'success' => false,
            ], 400);
        }

        $em = $this->getDoctrine()->getManager();

        $em->persist($entity->setUpdatedAt(new \DateTime()));
        $em->flush();

        return $this->json($entity, 200);
    }

    #[Route(
        '/{identifier}/edit',
        name: 'edit',
        methods: ['PUT', 'POST'],
        requirements: ['identifier' => '[\w\-\_]{15}']
    )]
    public function editAction(
        Request $request,
        string $identifier = '',
    ): Response
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository(Resource::class)->findOneByIdentifier($identifier);
        if (!$entity) {
            return $this->json([
                'message' => 'Recurso não encontrado',
                'success' => false,
            ], 404);
        }

        $form = $this->createForm(ResourceType::class, $entity);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return $this->json([
                'message' => 'Dados do formulário inválidos',
                'success' => false,
            ], 400);
        }

        if (!$form->isValid()) {
            return $this->json([
                'errors' => $form->getErrors(true, false),
                'success' => false,
            ], 400);
        }

        $em->persist($entity->setUpdatedAt(new \DateTime()));
        $em->flush();

        return $this->json($entity, 200);
    }

    #[Route(
        '/{identifier}/remove',
        name: 'remove',
        methods: ['DELETE'],
        requirements: ['identifier' => '[\w\-\_]{15}']
    )]
    public function deleteAction(
        string $identifier = ''
    ) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository(Resource::class)->findOneByIdentifier($identifier);
        if (!$entity) {
            return $this->json([
                'message' => 'Recurso não encontrado',
                'success' => false,
            ], 404);
        }

        $entity->setIsActive(false)->setDeletedAt(new \DateTime());
        $em->flush();

        return $this->json([
            'message' => 'Recurso removido com sucesso',
        ], 200);
    }
}
