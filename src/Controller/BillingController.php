<?php

namespace App\Controller;

use App\Repository\SneakerRepository;
use App\Stripe\Stripe;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\LazyProxy\Instantiator\RealServiceInstantiator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class BillingController extends AbstractController
{
    /**
     * @Route("/billing", name="billing")
     */
    public function index( Request $request, SneakerRepository $sneakerRepository, Security $security)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('App:User');
        $user = $repo->findBy(array('id'=>$security->getUser()->getId()));

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

    /**
     * @Route("/billing/payment", name="billing_payment")
     */
    public function billing_payment(Request $request)
    {
        $token = $request->get('stripeToken');
        $email = $request->get('email');
        $name = $request->get('name');

        $stripe = new Stripe('sk_test_C0P5MK6d6wM1flxZagJp3Iog00faEMmcD7');
        $customer = $stripe->api('customers', [
            'source' => $token,
            'description' => $name,
            'email' => $email,
        ]);

        $charge = $stripe->api('charges', [
            'amount' => $request->get('amount')* 100,
            'currency' => 'eur',
            'customer'=> $customer->id
        ]);

        var_dump($charge);

        return new Response('Paiement réussi');





    }


}
