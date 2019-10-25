<?php

namespace App\Controller;

use App\Entity\User;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
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

    /**
     * @Route("/forgotten-password", name="forgotten_password")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param \Swift_Mailer $mailer
     * @param TokenGeneratorInterface $tokenGenerator
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function forgottenPassword(
        Request $request,
        UserPasswordEncoderInterface $encoder,
        \Swift_Mailer $mailer,
        TokenGeneratorInterface $tokenGenerator)
    {

        if ($request->isMethod('POST')) {

            $email = $request->request->get('email');

            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneBy(array('email'=>$email));
            /* @var $user User */

            if ($user === null) {
                $this->addFlash('danger', "Cette adresse e-mail n'existe pas");
                return $this->redirectToRoute('forgotten_password');
            }
            $token = $tokenGenerator->generateToken();

            try{
                $user->setResetToken($token);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('forgotten_password');
            }

            $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

            $message = (new \Swift_Message('Forgot Password'))
                ->setFrom('example@example.com')
                ->setTo($user->getEmail())
                ->setBody(
                    "Cliquez ici pour réinitialiser votre mot de passe : " . $url,
                    'text/html'
                );

            $mailer->send($message);

            $this->addFlash('notice', "L'email de réinitialisation a été envoyé");

            return $this->redirectToRoute('login');
        }

        return $this->render('security/forgotten-password.page.html.twig');
    }

    /**
     * @Route("/reset_password/{token}", name="app_reset_password")
     * @param Request $request
     * @param string $token
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {

        if ($request->isMethod('POST')) {
            $entityManager = $this->getDoctrine()->getManager();

            $user = $entityManager->getRepository(User::class)->findOneBy(array('resetToken'=>$token));
            /* @var $user User */

            if ($user === null) {
                $this->addFlash('danger', 'Token Inconnu');
                return $this->redirectToRoute('forgotten_password');
            }

                $user->setResetToken(null);
                $user->setPassword($request->get('password'));
                $entityManager->flush();


            $this->addFlash('notice', 'Votre mot de passe a bien été changé');

            return $this->redirectToRoute('login');
        }else {

            return $this->render('security/reset_password.html.twig', ['token' => $token]);
        }

    }


}
