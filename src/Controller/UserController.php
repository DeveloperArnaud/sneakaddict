<?php

namespace App\Controller;

use App\Entity\Favoris;
use App\Repository\FavorisRepository;
use App\Repository\SneakerRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/user/add/favoris/{idSneaker}/{idFavoris}", name="user_remove_favoris")
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







}
