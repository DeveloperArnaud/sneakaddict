<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{id}", name="user")
     */
    public function index($id, UserRepository $userRepository)
    {

        $user = $userRepository->findBy(array('id'=>$id));

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'user' => $user
        ]);
    }
}
