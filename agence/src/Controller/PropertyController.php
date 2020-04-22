<?php

namespace App\Controller;

use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Repository\PropertyRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class PropertyController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    protected $repository;

    public function __construct(PropertyRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/properties", name="properties")
     */
    public function index(PaginatorInterface $paginatorInterface, Request $request):Response
    {
        $propertySearch = new PropertySearch();
        $searchForm = $this->createForm(PropertySearchType::class, $propertySearch);
        $searchForm->handleRequest($request);
        $properties = $this->repository->findAllVisible($paginatorInterface, $request->query->getInt('page', 1), $propertySearch); 
        return $this->render('property/index.html.twig', [
            'properties' => $properties,
            'form' => $searchForm->createView()
        ]);
    }

    /**
     * @Route("/show/{slug}-{id}", name="show", requirements={"slug": "[a-z0-9\-]*"})
     * 
     * @return Response
     */
    public function show(string $slug, Property $property):Response 
    {
        if ($property->getSlug() != $slug) {
            return $this->redirectToRoute('show',[
                'slug' => $property->getSlug(),
                'id' => $property->getId()
            ], 301);
        }
        return $this->render('property/show.html.twig', [
            'property' => $property
        ]);
    }
}
