<?php

namespace App\Controller;

use App\Entity\User;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function index(AuthenticationUtils $authenticationUtils)
    {
        $email = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('security/login-page.html.twig', [
            'last_username' => $email,
            'error' => $error
        ]);
    }

    /**
     * @Route("/sign-up", name="sign-up")
     */
    public function signUp(Request $request)
    {
        $email = $request->request->get('email');
        $prenom = $request->request->get('prenom');
        $nomFamille = $request->request->get('nomFamille');
        $mdp = $request->request->get('password');
        $dateNaiss = $request->request->get('dateNaiss');
        $homme = $request->request->get('homme');
        $femme = $request->request->get('femme');


        if(!empty($request->request->get('valider'))) {
            if(!filter_var($email,FILTER_VALIDATE_EMAIL)) {
                $this->addFlash('error', "Votre e-mail n'est pas valide !");
                return $this->redirectToRoute('sign-up');
            } else if(strlen($mdp) < 5 ) {
                $this->addFlash('warning', "Votre mot de passe est trop court ! ");
                return $this->redirectToRoute('sign-up');
            } else if($prenom == "" || $nomFamille == "") {
                $this->addFlash('error', "Veuillez indiquer votre nom et prénom ! ");
                return $this->redirectToRoute('sign-up');
            } else if (empty($homme) && empty($femme)) {
                $this->addFlash('error', "Indiquez votre sexe !");
                return $this->redirectToRoute('sign-up');
            } else {
                $user = new User();
                $user->setEmail($email);
                $user->setPrenom($prenom);
                $user->setNom($nomFamille);
                if($homme) {
                    $user->setSexe($homme);
                } else {
                    $user->setSexe($femme);
                }
                $user->setPassword($mdp);

                $user->setRoles('ROLE_USER');
                $dateNa = new \DateTime($dateNaiss);
                $user->setDateNaissance($dateNa);
                $user->setAdresse(" ");
                $user->setVille(" ");
                $user->setPays(" ");
                $user->setCodePostal(" ");

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $this->addFlash('success', "Bienvenue sur Sneak'Addict");
                return $this->redirectToRoute('user');

            }

        }


        return $this->render('security/sign-up.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }
}
