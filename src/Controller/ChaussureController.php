<?php

namespace App\Controller;

use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ChaussureController extends AbstractController
{
    /**
     * @Route("/chaussures", name="chaussures")
     */
    public function index(PaginatorInterface $pagination, SessionInterface $session, Request $request)
    {
        $em= $this->getDoctrine()->getManager();
        $repo = $em->getRepository('App:Sneaker');
        $chaussures = $pagination->paginate($repo->findAll(), $request->query->getInt('page',1),6);
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
