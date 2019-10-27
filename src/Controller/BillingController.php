<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\QuantityOrder;
use App\Entity\User;
use App\Repository\CommandeRepository;
use App\Repository\SneakerRepository;
use App\Repository\TailleRepository;
use App\Stripe\Stripe;
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

        $panierBilling = [];
        foreach ($panierData as $item => $value ) {
            foreach ($value['sneaker'] as $sneaker) {
                $panierBilling [] = [
                    'sneakers' => $sneakerRepository->find($sneaker->getId()),
                    'quantity' => $value['quantity']
                ];
            }
        }

        return $this->render('billing/index.html.twig', [
            'controller_name' => 'BillingController',
            'user' =>$user,
            'panier' => $panierBilling,
            'total' => $total
        ]);
    }

    /**
     * @Route("/billing/payment", name="billing_payment")
     */
    public function billing_payment(Request $request, Security $security,SneakerRepository $sneakerRepository, TailleRepository $tailleRepository,\Swift_Mailer $mailer, CommandeRepository $commandeRepository)
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

        if($charge) {
            $quantity_sneaker_size = new QuantityOrder();
            $commande = new Commande();
            $us = $security->getUser();
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(User::class)->findOneBy(array('id' => $us->getId()));
            $commande->setUser($user);
            $commande->setStatut("Envoyée");
            $date = new \DateTime('now');
            $commande->setDateCommande($date);
            $session = $request->getSession();
            $panier = $session->get('panier');
            $panierConfirm = [];
            foreach ($panier as $item => $value) {
                foreach ($value as $v => $quantity) {
                    $panierConfirm [] = [
                        'sneaker' => $sneakerRepository->getSneakerByTailleIdandSneakerId($v,$item),
                        'quantity' =>$quantity
                    ];
                }
            }


            foreach ($panierConfirm as $item => $value) {
                foreach ($value['sneaker'] as $sneaker) {
                    $commande->addSneaker($sneaker);
                    $quantity_sneaker_size->addChaussure($sneaker);
                }


                foreach ($value['sneaker'] as $sneaker) {
                    foreach ($sneaker->getTaille() as $taille) {
                        $commande->addTaille($tailleRepository->find($taille->getId()));
                        $quantity_sneaker_size->addTaille($taille);

                    }
                }
            }
            $total = 0;
            foreach ($panierConfirm as $item => $value ) {
                foreach ($value['sneaker'] as $sneaker) {
                    $totalItem =  $sneaker->getPrix() * $value['quantity'];
                    $quantity_sneaker_size->setQuantity($value['quantity']);
                    $total = $total + $totalItem;
                    $commande->setTotal($total);
                }
            }

            $quantity_sneaker_size->addOrderU($commande);
            $em->persist($commande);
            $em->persist($quantity_sneaker_size);
            $em->flush();
            $session->remove('panier');
            $message = (new \Swift_Message('Confirmation de commande'))
                ->setFrom('example@example.com')
                ->setTo($user->getEmail())
                ->setBody(
                    "".$commande->__toString(),
                    'text/html'
                );

            $mailer->send($message);
            $commandeUser = $commandeRepository->getCommandeByUserIdAndIdCommande($security->getUser()->getId(),$commande->getId());

            return $this->render('billing/billing_confirmation.page.html.twig',array('commandes'=> $commandeUser));

        }

        return new Response('Paiement réussi, mais commande pas prise en compte');





    }


}
