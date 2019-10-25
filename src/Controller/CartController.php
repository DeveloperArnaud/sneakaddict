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
        foreach ($panier as $item => $value) {
            foreach ($value as $v => $quantity) {
                $panierData [] = [
                    'sneaker' => $sneakerRepository->getSneakerByTailleIdandSneakerId($v,$item),
                    'quantity' =>$quantity
                ];
            }
        }
        $total = 0;
        foreach ($panierData as $item => $value ) {
            foreach ($value['sneaker'] as $sneaker) {
                $totalItem =  $sneaker->getPrix() * $value['quantity'];
                $total = $total + $totalItem;
            }
        }

        $convertObject = [];
        foreach ($panierData as $item => $value ) {
            foreach ($value['sneaker'] as $sneaker) {
                $convertObject [] = [
                    'sneaker' => $sneakerRepository->find($sneaker->getId()),
                    'quantity' => $value['quantity']
                ];
            }
        }



        return $this->render('cart/index.html.twig', [
            'items' => $convertObject,
            'total' => $total,
        ]);
    }


    /**
     * @Route("/cart/add/{idSneaker}/{idTaille}", name="cart_add")
     */
    public function add($idSneaker, $idTaille,SessionInterface $session) {

        $panier = $session->get('panier', []);

        if(!empty($panier[$idSneaker][$idTaille])) {
            $panier[$idSneaker][$idTaille] ++;

        } else {
            $panier[$idSneaker][$idTaille] = 1;
        }

        $session->set('panier',$panier);

        return $this->redirectToRoute("cart_index");

    }

    /**
     * @Route("/cart/remove/{idSneaker}/{idTaille}", name="cart_remove")
     */
    public function remove($idSneaker,$idTaille,SessionInterface $session) {

        $panier = $session->get('panier', []);

        if(!empty($panier[$idSneaker][$idTaille])) {
            unset($panier[$idSneaker][$idTaille]);
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
