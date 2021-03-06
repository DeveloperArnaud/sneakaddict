<?php

namespace App\Controller;

use App\Entity\Adresses;
use App\Entity\Favoris;
use App\Entity\User;
use App\Form\AdressesType;
use App\Form\UserType;
use App\Repository\AdressesRepository;
use App\Repository\AvisRepository;
use App\Repository\FavorisRepository;
use App\Repository\SneakerRepository;
use App\Repository\UserRepository;
use Shippo;
use Shippo_Address;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(UserRepository $userRepository, Request $request, Security $security)
    {
        $user = $userRepository->find($security->getUser()->getId()) ;
        $form = $this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('notice', "Vos coordonnées ont bien étés modifiées");
            return $this->redirectToRoute('user');
        }
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'form' =>  $form->createView() ]);
    }

    /**
     * @Route("/user/modes_paiement", name="user_modes_paiement")
     */
    public function user_modes_paiement()
    {
        return $this->render('user/user.modes.paiement.html.twig', [
            'controller_name' => 'UserController']);
    }


    /**
     * @Route("/user/adresses", name="user_adresses")
     */
    public function user_adresses()
    {
        return $this->render('user/user.adresses.html.twig', [
            'controller_name' => 'UserController']);
    }

    /**
     * @Route("/user/adresse/{idAdresse}", name="user_update_adress")
     */
    public function user_adresse_update($idAdresse,AdressesRepository $adressesRepository,Request $request)
    {
        $adresse = $adressesRepository->find($idAdresse);
        $form = $this->createForm(AdressesType::class,$adresse);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $adresse = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($adresse);
            $em->flush();
            return $this->redirectToRoute('user_adresses');
        }

        return $this->render('user/user.update.adresse.html.twig', [
            'controller_name' => 'UserController',
            'adresse' => $adresse,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/user/adresse/remove/{idAdresse}", name="user_remove_adress")
     */
    public function user_adresse_remove($idAdresse,AdressesRepository $adressesRepository,Request $request)
    {
        $adresse = $adressesRepository->find($idAdresse);
            $em = $this->getDoctrine()->getManager();
            $em->remove($adresse);
            $em->flush();
            $this->addFlash('notice',"L'adresse a bien été supprimée");
        return $this->redirectToRoute('user_adresses');
    }


    /**
     * @Route("/user/new_adresse", name="user_new_adress")
     * @param AdressesRepository $adressesRepository
     * @param UserRepository $userRepository
     * @param Security $security
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function user_new_adress(AdressesRepository $adressesRepository,UserRepository $userRepository,Security $security,Request $request)
    {
        $adress = new Adresses();
        $user = $userRepository->find($security->getUser()->getId());
        $form = $this->createForm(AdressesType::class,$adress);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $adress = $form->getData();
            $adress->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($adress);
            $em->flush();
            $this->addFlash('notice', "Votre adresse a correctement été ajoutée !");
            return $this->redirectToRoute('user_adresses');
        }

        return $this->render('user/user.add.adress.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView()]);
    }

    /**
     * @Route("/user/shippo", name="user_shippo")
     */
    public function index_shippo()
    {
        Shippo::setApiKey("shippo_test_38942c5dd3fd9dc6d7cb1fc77baf184cb585d3dc");
        $address = Shippo_Address::
        create(
            array(
                'object_purpose' => 'QUOTE',
                'name' => 'John Smith',
                'company' => 'Initech',
                'street1' => '6512 Greene Rd.',
                'city' => 'Woodridge',
                'state' => 'IL',
                'zip' => '60517',
                'country' => 'US',
                'phone' => '773 353 2345',
                'email' => 'jmercouris@iit.com',
                'metadata' => 'Customer ID 23424'
            ));

        var_dump($address);

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController']);
    }


    /**
     * @Route("/user/orders", name="user_orders")
     */
    public function orders(Security $security)
    {
        $em= $this->getDoctrine()->getManager();
        $repo = $em->getRepository('App:Commande');
        $orderUser = $repo->getCommandesByUserId($security->getUser()->getId());

        return $this->render('user/user.orders.html.twig', [
            'controller_name' => 'UserController',
            'user_orders' => $orderUser ]);
    }

    /**
     * @Route("/user/add/favoris/{idSneaker}", name="user_add_favoris")
     */
    public function add_favoris($idSneaker,Security $security, SneakerRepository $sneakerRepository,UserRepository $userRepository,FavorisRepository $favorisRepository)
    {

       if($favorisRepository->findByUserIdAndSneakerId($security->getUser()->getId(),$idSneaker) != null ) {
           $this->addFlash('notice','Cet article est déjà dans vos favoris');
           return $this->redirect($this->generateUrl('chaussure_detail',array('id' => $idSneaker)));
       } else {

           $em = $this->getDoctrine()->getManager();
           $favoris = new Favoris();
           $favoris->setSneaker($sneakerRepository->find($idSneaker));
           $favoris->setUser($userRepository->find($security->getUser()->getId()));
           $em->persist($favoris);
           $em->flush();
           $this->addFlash('success', "L'article a été ajouté dans vos favoris !");

           return $this->redirect($this->generateUrl('chaussure_detail', array('id' => $idSneaker)));
       }
    }

    /**
     * @Route("/user/remove/favoris/{idFavoris}", name="user_remove_favoris")
     */
    public function remove_favoris($idFavoris,FavorisRepository $favorisRepository)
    {

            $em = $this->getDoctrine()->getManager();
            $favoris =$favorisRepository->findOneBy(array('id' => $idFavoris));


            $em->remove($favoris);
            $em->flush();
            $this->addFlash('notice', "L'article a été supprimé dans vos favoris !");
            return $this->redirectToRoute('user_favoris');



        }


    /**
     * @Route("/user/favoris", name="user_favoris")
     */
    public function favoris(Security $security)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Favoris::class);
        $favoris = $repo->findByUserId($security->getUser()->getId());
        return $this->render('user/user.favoris.html.twig', [
            'favoris' => $favoris
        ]);
    }


    /**
     * @Route("/user/avis", name="user_avis")
     */
    public function user_avis(Security $security)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('App:Avis');
        $avis = $repo->findByUserId($security->getUser()->getId());
        return $this->render('user/user.avis.html.twig', [
            'avis' => $avis
        ]);
    }

    /**
     * @Route("/user/remove/avis/{idAvis}", name="user_remove_avis")
     */
    public function remove_avis($idAvis,AvisRepository $avisRepository)
    {


        $em = $this->getDoctrine()->getManager();
        $avis =$avisRepository->find($idAvis);

            $em->remove($avis);
            $em->flush();
            $this->addFlash('notice', "L'avis a été supprimé !");
            return $this->redirectToRoute('user_avis');



    }

    /**
     * Test
     * @Route("/user/tabs", name="user_tabs")
     */

    public function testTabs(Security $security, UserRepository $userRepository, Request $request) {
        $user = $userRepository->find($security->getUser()->getId()) ;
        $form = $this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('notice', "Vos coordonnées ont bien étés modifiées");
            return $this->redirectToRoute('user');
        }
        return $this->render('user/tabs.html.twig', ['form' => $form->createView()]);
    }









}
