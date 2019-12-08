<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Avis;
use App\Entity\Color;
use App\Entity\Sneaker;
use App\Form\SearchForm;
use App\Repository\SneakerRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function index(SneakerRepository $repository,PaginatorInterface $pagination, Request $request)
    {

        $searchData = new SearchData();
        $searchData->page = $request->get('page',1);
        $form = $this->createForm(SearchForm::class,$searchData);
        $form->handleRequest($request);
        [$min,$max] = $repository->findMinMax($searchData);
        $chaussures = $repository->findSearch($searchData);
        if($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('chaussure/_chaussures.html.twig', ['chaussures' => $chaussures]),
                'sorting' => $this->renderView('chaussure/_sorting.html.twig', ['chaussures' => $chaussures]),
                'pagination' => $this->renderView('chaussure/_pagination.html.twig', ['chaussures' => $chaussures]),
                'pages' => ceil($chaussures->getTotalItemCount() / $chaussures->getItemNumberPerPage()),
                'min' => $min,
                'max' => $max


            ]);
        }


        return $this->render('chaussure/index.html.twig', [
            'chaussures' => $chaussures,
            'form' => $form->createView(),
            'min' => $min,
            'max' =>$max
        ]);
    }

    /**
     * @Route("/chaussure/{id}", name="chaussure_detail")
     */
    public function chaussure_detail(Security $security, $id) {

        $em= $this->getDoctrine()->getManager();
        $repo = $em->getRepository('App:Sneaker');
        $chaussure = $repo->find($id);
        $avis = $repo = $em->getRepository('App:Avis')-> findBySneakerId($id);
        return $this->render('chaussure/chaussure_detail.html.twig', [
            'controller_name' => 'ChaussureController',
            'chaussure' => $chaussure,
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
     * @Route("/orders/chaussures", name="chaussure_order")
     */
    public function chaussure_order(Request $request,PaginatorInterface $pagination) {

        $em= $this->getDoctrine()->getManager();
        $repo = $em->getRepository('App:Sneaker');
        $chaussures = $repo->orderBy($request->get('query'),$request->get('offset'),$request->get('limit'));
        $repo = $em->getRepository('App:Taille');
        $tailles = $repo->findAll();
        $couleurChaussure = $em->getRepository(Color::class)->GroupByColor();
        return $this->render('chaussure/chaussure.filter.html.twig', [
            'controller_name' => 'ChaussureController',
            'chaussures' => $chaussures,
            'tailles' => $tailles,
            'couleurs' => $couleurChaussure
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
