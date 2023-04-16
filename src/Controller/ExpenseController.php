<?php

namespace App\Controller;

use App\Entity\Expense;
use App\Form\Type\ExpenseType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/expense', name: 'app_expense')]
class ExpenseController extends AbstractController
{
    #[Route(
        '/list',
        name: 'list',
        methods: ['GET'],
    )]
    public function listAction(): Response
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository(Expense::class)
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

        $entity = $em->getRepository(Expense::class)->findOneByIdentifier($identifier);
        if (!$entity) {
            return $this->json([
                'message' => 'Despesa não encontrada',
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
        $entity = new Expense;

        $form = $this->createForm(ExpenseType::class, $entity);
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

        $entity = $em->getRepository(Expense::class)->findOneByIdentifier($identifier);
        if (!$entity) {
            return $this->json([
                'message' => 'Despesa não encontrada',
                'success' => false,
            ], 404);
        }

        $form = $this->createForm(ExpenseType::class, $entity);
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

        $entity = $em->getRepository(Expense::class)->findOneByIdentifier($identifier);
        if (!$entity) {
            return $this->json([
                'message' => 'Despesa não encontrada',
                'success' => false,
            ], 404);
        }

        $entity->setIsActive(false)->setDeletedAt(new \DateTime());
        $em->flush();

        return $this->json([
            'message' => 'Despesa removida com sucesso',
        ], 200);
    }
}
