<?php

namespace App\Controller;

use App\Repository\SneakerRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart_index")
     */
    public function index(SessionInterface $session , SneakerRepository $sneakerRepository)
    {
        $panier = $session->get('panier',[]);
        $panierData = [];

        foreach ($panier as $id => $quantity) {
            $panierData [] = [
                'sneaker' => $sneakerRepository->find($id),
                'quantity' =>$quantity
            ];
        }
        $total = 0;
        foreach($panierData as $item) {
            $totalItem= $item['sneaker']->getPrix() * $item['quantity'];
            $total = $total + $totalItem;
        }

        return $this->render('cart/index.html.twig', [
            'items' => $panierData,
            'total' => $total
        ]);
    }


    /**
     * @Route("/cart/add/{id}", name="cart_add")
     */
    public function add($id, SessionInterface $session) {

        $panier = $session->get('panier', []);

        if(!empty($panier[$id])) {
            $panier[$id] ++;
        } else {
            $panier[$id] = 1;
        }

        $session->set('panier',$panier);


        return $this->redirectToRoute("cart_index");

    }

    /**
     * @Route("/cart/remove/{id}", name="cart_remove")
     */
    public function remove($id, SessionInterface $session) {

        $panier = $session->get('panier', []);

        if(!empty($panier[$id])) {
            unset($panier[$id]);
        }

        $session->set('panier',$panier);
       return $this->redirectToRoute("cart_index");

    }


    /**
     * @Route("/cart_modal", name="cart_modal")
     */
    public function modal(SessionInterface $session , SneakerRepository $sneakerRepository)
    {
        $panier = $session->get('panier',[]);
        $panierData = [];

        foreach ($panier as $id => $quantity) {
            $panierData [] = [

                    'titre' => $sneakerRepository->find($id)->getTitre(),
                    'prix' => $sneakerRepository->find($id)->getPrix(),
                    'modele' => $sneakerRepository->find($id)->getModele(),
                    'url' => $sneakerRepository->find($id)->getPath(),
                    'quantity' =>$quantity
            ];
        }
        $total = 0;


        return new JsonResponse($panierData);
    }

}
