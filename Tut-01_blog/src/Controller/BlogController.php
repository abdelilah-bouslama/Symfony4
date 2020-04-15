<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        //return new Response('<h1>Page d\'accueil</h1>');
        return $this->render('blog/index.html.twig',[
            'controller_name' => 'BlogController'
        ]);
    }

    public function add()
    {
    	return $this->render('blog/add.html.twig');
    }

    public function show($url)
    {
    	return $this->render('blog/show.html.twig', [
            'slug' => $url,
            'controller_name' => 'BlogController'
        ]);
    }

    public function edit($id)
    {
    	return $this->render('blog/edit.html.twig',[
            'slug' => $id
        ]);
    }

    public function remove($id)
    {
    	return new Response('<h1>Supprimer l\'article ' .$id. '</h1>');
    }
}
