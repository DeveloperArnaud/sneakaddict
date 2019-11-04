<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\QuantityOrder;
use App\Entity\QuantitySneakerOrdered;
use App\Entity\QuantityTestOrder;
use App\Entity\User;
use App\Repository\AdressesRepository;
use App\Repository\CommandeRepository;
use App\Repository\SneakerRepository;
use App\Repository\TailleRepository;
use App\Repository\UserRepository;
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
     * @Route("/billing/adresses", name="billing_adresses")
     */
    public function billing_user_adress() {

        return $this->render('billing/billing.adress.user.html.twig');

    }


    /**
     * @Route("/billing", name="billing")
     */
    public function index(Request $request, SneakerRepository $sneakerRepository, Security $security,AdressesRepository $adressesRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('App:User');
        $user = $repo->findBy(array('id'=>$security->getUser()->getId()));
        $adresse = $adressesRepository->findBy(array('id'=>$request->get('adresse-billing')));
        $session = $request->getSession();
        $panier = $session->get('panier');
        $panierData = [];
        foreach ($panier as $item => $value) {
            foreach ($value as $v => $quantity) {
                $panierData [] = [
                    'sneaker' => $sneakerRepository->getSneakerByTailleIdandSneakerId($v,$item),
                    'quantity' =>$quantity,
                ];
            }
        }

            $total = 0;
        foreach ($panierData as $item => $value ) {
            foreach ($value['sneaker'] as $sneaker) {
                $totalItem =  $sneaker->getPrix() * $value['quantity'];
            }
        }
        $total = $total + $totalItem;

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
            'total' => $total,
            'adresse' => $adresse
        ]);
    }

    /**
     * @Route("/billing/payment", name="billing_payment")
     */
    public function billing_payment(Request $request, AdressesRepository $adressesRepository,Security $security,SneakerRepository $sneakerRepository, TailleRepository $tailleRepository,\Swift_Mailer $mailer, CommandeRepository $commandeRepository)
    {
        $token = $request->get('stripeToken');
        $email = $request->get('email');
        $name = $request->get('name');
        $adresse_id = $request->get('id-adresse');

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

            $commande = new Commande();
            $us = $security->getUser();
            $em = $this->getDoctrine()->getManager();
            $adresse = $adressesRepository->findOneBy(array('id'=> $adresse_id));
            $user = $em->getRepository(User::class)->findOneBy(array('id' => $us->getId()));
            $commande->setUser($user);
            $commande->setStatut("En cours de traitement");
            $date = new \DateTime('now');
            $commande->setDateCommande($date);
            $commande->setAdresse($adresse);
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
                }


                foreach ($value['sneaker'] as $sneaker) {
                    foreach ($sneaker->getTaille() as $taille) {
                        $commande->addTaille($tailleRepository->find($taille->getId()));

                    }
                }

            }
            $total = 0;
            foreach ($panierConfirm as $item => $value ) {
                foreach ($value['sneaker'] as $sneaker) {
                    $totalItem =  $sneaker->getPrix() * $value['quantity'];
                }
            }

            $total = $total + $totalItem;
            $commande->setTotal($total);


            $em->persist($commande);
            $em->flush();
            $session->remove('panier');

            $commandeUser = $commandeRepository->getCommandeByUserIdAndIdCommande($security->getUser()->getId(),$commande->getId());

            $message = (new \Swift_Message('Confirmation de commande'))
                ->setFrom('example@example.fr')
                ->setTo($user->getEmail());

            $img = "";
            foreach($commande->getSneakers() as $imgSneaker) {
                $img = $message->embed(\Swift_Image::fromPath($imgSneaker->getPath()));

            }
            $message->setBody(
                $this->render('billing/email.confirmation.order.html.twig', [
                    'commandes' => $commandeUser, 'img' => $img]),
                'text/html'
            );
            $mailer->send($message);
            return $this->render('billing/billing_confirmation.page.html.twig',array('commandes'=> $commandeUser));

        }

        return new Response('Paiement réussi, mais commande pas prise en compte');





    }



}
