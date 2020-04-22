<?php

namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class AdminPropertyController extends Controller
{
    /**
     * @var PropertyRepository
     */
    protected $repository;

    /**
     * @var ObjectManager
     */
    protected $em;

    public function __construct(PropertyRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin", name="admin.property.index")
     *
     * @return Response
     */
    public function index():Response
    {
        return $this->render('admin/property/index.html.twig', [
            'properties' => $this->repository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/new", name="admin.property.new")
     *
     * @return Reponse
     */
    public function new(Request $request):Response
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->em->persist($property);
            $this->em->flush();
            $this->addFlash('success', 'Property Added');
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/property/new.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }
    /**
     *  @Route("admin/edit/{id}", name="admin.property.edit")
     *
     * @param Property $property
     * @param Request $request
     * @return Response
     */
    public function edit(Property $property, Request $request):Response
    {
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', 'Property updated');
            $this->em->flush();
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/property/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

    /**
     *  @Route("admin/delete/{id}", name="admin.property.delete")
     *
     * @param Property $property
     * @return Response
     */
    public function delete(Property $property, Request $request):Response
    {
        if ($this->isCsrfTokenValid('delete'.$property->getId(), $request->get('_token'))) {
            $this->em->remove($property);
            $this->em->flush();
            $this->addFlash('success', 'Property deleted');
        }
        return $this->redirectToRoute('admin.property.index');
    }
}