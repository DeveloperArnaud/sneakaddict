<?php

namespace App\Controller;

use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

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

        $couleurChaussure = $em->getRepository('App:Sneaker')->GroupByColor();
        $test = $em->getRepository('App:Sneaker')->getSneakerByTailleIdandSneakerId(1,2);

        return $this->render('chaussure/index.html.twig', [
            'controller_name' => 'ChaussureController',
            'chaussures' => $chaussures,
            'tailles' => $tailles,
            'couleurs' => $couleurChaussure
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

    /**
     * @Route("/chaussures/{color}", name="chaussure_color_search")
     */
    public function chaussure_color_search($color) {

        $em= $this->getDoctrine()->getManager();
        $repo = $em->getRepository('App:Sneaker');
        $chaussures = $repo->findByCouleur($color);

        $repo = $em->getRepository('App:Taille');
        $tailles = $repo->findAll();
        $couleurChaussure = $em->getRepository('App:Sneaker')->GroupByColor();

        return $this->render('chaussure/chaussure.color.search.html.twig', [
            'controller_name' => 'ChaussureController',
            'chaussures' => $chaussures,
            'tailles' => $tailles,
            'couleurs' => $couleurChaussure
        ]);

    }

    /**
     * @Route("/search/chaussures", name="chaussure_search")
     */
    public function chaussure_search(Request $request) {

        $em= $this->getDoctrine()->getManager();
        $repo = $em->getRepository('App:Sneaker');
        $chaussures = $repo->findByTitre($request->get('query'));
        return $this->render('chaussure/chaussures.search.html.twig', [
            'chaussures' => $chaussures,
        ]);

    }

    /**
     * @Route("/chaussures/taille/{taille}", name="chaussures_search_taille")
     */
    public function chaussure_search_taille($taille) {

        $em= $this->getDoctrine()->getManager();
        $repo = $em->getRepository('App:Sneaker');
        $chaussures = $repo->SearchByTaille($taille);
        $repo = $em->getRepository('App:Taille');
        $tailles = $repo->findAll();
        $couleurChaussure = $em->getRepository('App:Sneaker')->GroupByColor();

        return $this->render('chaussure/chaussures.search.taille.html.twig', [
            'chaussures' => $chaussures,
            'tailles' => $tailles,
            'couleurs' => $couleurChaussure
        ]);

    }


    /**
     * @Route("/chaussure/test/html", name="chaussures_test")
     */
    public function chaussure_test() {

        return $this->render('chaussure/indexbis.html.twig');

    }
}
