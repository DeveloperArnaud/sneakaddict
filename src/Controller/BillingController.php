<?php

namespace App\Controller;

use App\Repository\SneakerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class BillingController extends AbstractController
{
    /**
     * @Route("/billing/{id_user}", name="billing")
     */
    public function index($id_user, Request $request, SneakerRepository $sneakerRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('App:User');
        $user = $repo->findBy(array('id'=>$id_user));

        $session = $request->getSession();
        $panier = $session->get('panier');
        $panierUser = [];

        foreach ($panier as $id => $quantity) {
            $panierUser[] = [
                'sneakers' => $sneakerRepository->find($id),
                'quantity' =>$quantity
            ];
        }

        $total =  0;
        foreach($panierUser as $item) {
            $totalItem= $item['sneakers']->getPrix() * $item['quantity'];
            $total = $total + $totalItem;
        }
        return $this->render('billing/index.html.twig', [
            'controller_name' => 'BillingController',
            'user' =>$user,
            'panier' => $panierUser,
            'total' => $total
        ]);
    }
}
