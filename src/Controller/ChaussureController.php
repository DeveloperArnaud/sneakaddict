<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ChaussureController extends AbstractController
{
    /**
     * @Route("/chaussures", name="chaussures")
     */
    public function index(SessionInterface $session)
    {
        $em= $this->getDoctrine()->getManager();
        $repo = $em->getRepository('App:Sneaker');
        $chaussures = $repo->findAll();
        $repo = $em->getRepository('App:Taille');
        $tailles = $repo->findAll();


        return $this->render('chaussure/index.html.twig', [
            'controller_name' => 'ChaussureController',
            'chaussures' => $chaussures,
            'tailles' => $tailles
        ]);
    }

    /**
     * @Route("/chaussure/{id}", name="chaussure_detail")
     */
    public function chaussure_detail($id) {

        $em= $this->getDoctrine()->getManager();
        $repo = $em->getRepository('App:Sneaker');
        $chaussure = $repo->findBy(array('id'=>$id));
        return $this->render('chaussure/chaussure_detail.html.twig', [
            'controller_name' => 'ChaussureController',
            'chaussures' => $chaussure
        ]);

    }
}
