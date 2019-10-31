<?php

namespace App\Controller;

use App\Entity\Favoris;
use App\Entity\User;
use App\Form\UserType;
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
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController']);
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
     * @Route("/user/update", name="user_update")
     * @param Request $request
     * @param Security $security
     * @param UserRepository $userRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function user_update(Request $request,Security $security, UserRepository $userRepository)
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

        return $this->render('user/user.update.html.twig', [
            'controller_name' => 'UserController',
            'form'=>$form->createView()]);
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
           $favoris->addSneaker($sneakerRepository->find($idSneaker));
           $favoris->addUser($userRepository->find($security->getUser()->getId()));
           $em->persist($favoris);
           $em->flush();
           $this->addFlash('success', "L'article a été ajouté dans vos favoris !");

           return $this->redirect($this->generateUrl('chaussure_detail', array('id' => $idSneaker)));
       }
    }

    /**
     * @Route("/user/remove/favoris/{idSneaker}/{idFavoris}", name="user_remove_favoris")
     */
    public function remove_favoris($idFavoris,$idSneaker,Security $security, SneakerRepository $sneakerRepository,UserRepository $userRepository,FavorisRepository $favorisRepository)
    {


            $em = $this->getDoctrine()->getManager();
            $favoris =$favorisRepository->findByIdandUserIdAndSneakerId($idFavoris,$security->getUser()->getId(),$idSneaker);

            foreach ($favoris as $favori) {
                $favori->removeUser($userRepository->find($security->getUser()->getId()));
                $favori->removeSneaker($sneakerRepository->find($idSneaker));
                $em->remove($favori);
                $em->flush();
                $this->addFlash('notice', "L'article a été supprimé dans vos favoris !");
                return $this->redirectToRoute('user_favoris');

            }



            return $this->redirect($this->generateUrl('chaussure_detail', array('id' => $idSneaker)));
        }


    /**
     * @Route("/user/favoris", name="user_favoris")
     */
    public function favoris(Security $security)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('App:Favoris');
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









}
