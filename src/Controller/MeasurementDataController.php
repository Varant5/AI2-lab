<?php

namespace App\Controller;

use App\Entity\MeasurementData;
use App\Form\MeasurementDataType;
use App\Repository\MeasurementDataRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/measurement/data')]
class MeasurementDataController extends AbstractController
{
    #[Route('/', name: 'app_measurement_data_index', methods: ['GET'])]
    #[IsGranted('ROLE_MEASUREMENTDATA_INDEX')]
    public function index(MeasurementDataRepository $measurementDataRepository): Response
    {
        return $this->render('measurement_data/index.html.twig', [
            'measurement_datas' => $measurementDataRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_measurement_data_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_MEASUREMENTDATA_NEW')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $measurementDatum = new MeasurementData();
        $form = $this->createForm(MeasurementDataType::class, $measurementDatum, [
            'validation_groups' => 'new',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($measurementDatum);
            $entityManager->flush();

            return $this->redirectToRoute('app_measurement_data_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('measurement_data/new.html.twig', [
            'measurement_datum' => $measurementDatum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_measurement_data_show', methods: ['GET'])]
    #[IsGranted('ROLE_MEASUREMENTDATA_SHOW')]
    public function show(MeasurementData $measurementDatum): Response
    {
        return $this->render('measurement_data/show.html.twig', [
            'measurement_datum' => $measurementDatum,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_measurement_data_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_MEASUREMENTDATA_EDIT')]
    public function edit(Request $request, MeasurementData $measurementDatum, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MeasurementDataType::class, $measurementDatum, [
            'validation_groups' => 'edit',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_measurement_data_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('measurement_data/edit.html.twig', [
            'measurement_datum' => $measurementDatum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_measurement_data_delete', methods: ['POST'])]
    #[IsGranted('ROLE_MEASUREMENTDATA_DELETE')]
    public function delete(Request $request, MeasurementData $measurementDatum, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$measurementDatum->getId(), $request->request->get('_token'))) {
            $entityManager->remove($measurementDatum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_measurement_data_index', [], Response::HTTP_SEE_OTHER);
    }
}
