<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Color;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Cache\CacheInterface;

class ChaussureController extends AbstractController
{

    /**
     * @Route("/chaussures", name="chaussures")
     */
    public function index(CacheInterface $cache, PaginatorInterface $pagination, SessionInterface $session, Request $request)
    {
        $em= $this->getDoctrine()->getManager();
        $repo = $em->getRepository('App:Sneaker');
        $chaussures = $pagination->paginate($repo->findAll(), $request->query->getInt('page',1),12);
        $repo = $em->getRepository('App:Taille');
        $tailles = $repo->findAll();

        $couleurChaussure = $em->getRepository(Color::class)->GroupByColor();

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
    public function chaussure_detail(Security $security, $id) {

        $em= $this->getDoctrine()->getManager();
        $repo = $em->getRepository('App:Sneaker');
        $chaussure = $repo->findBy(array('id'=>$id));
        $avis = $repo = $em->getRepository('App:Avis')-> findBySneakerId($id);

        return $this->render('chaussure/chaussure_detail.html.twig', [
            'controller_name' => 'ChaussureController',
            'chaussures' => $chaussure,
            'avis' => $avis
        ]);

    }

    /**
     * @Route(name="chaussure_post_avis")
     */
    public function chaussure_post_avis(Security $security,Request $request)
    {
        $avis = new Avis();

        if ($request->isMethod("POST")) {
            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository('App:Sneaker');
            $chaussure = $repo->find( $request->request->get('idSneaker'));
            $user = $em->getRepository('App:User')->find($security->getUser()->getId());
            $avis->setSneaker($chaussure);
            $avis->setUser($user);
            if (empty($request->request->get('avis-user'))) {
                $this->addFlash('notice', 'Veuillez écrire quelque chose');
                return $this->redirectToRoute('chaussure_detail', array('id' => $chaussure->getId(), 'avis' => $avis));
            }
            $avis->setAvis($request->request->get('avis-user'));
            $avis->setNote(3.5);
            $em->persist($avis);
            $em->flush();
            $this->addFlash('success', 'Votre avis a bien été ajouté !');
            return $this->redirectToRoute('chaussure_detail', array('id' => $chaussure->getId(), 'avis' => $avis));

        }
        return $this->redirectToRoute('chaussure_detail',[ 'avis' => $avis]);

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



}
