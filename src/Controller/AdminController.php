<?php

namespace App\Controller;

use App\Entity\Quantity;
use App\Entity\Sneaker;
use App\Entity\Taille;
use App\Form\SneakerType;
use App\Form\StockType;
use App\Repository\QuantityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/fr", name="accueil")
     * @param CacheInterface $cache
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function accueil_fr(CacheInterface $cache) {

        $function  = $cache->get('affichage',function() {
            return $this->test();
        });
        return $this->render('chaussure/accueil.fr.html.twig',['sneakers' => $function ]);

    }

    private function test() {
        $em= $this->getDoctrine()->getManager();
        $repo = $em->getRepository('App:Sneaker');
        $sneakers = $repo->findAllLimited();
        return $sneakers;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {


        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/products", name="admin_products")
     */
    public function admin_products()
    {
        $em= $this->getDoctrine()->getManager();
        $repo = $em->getRepository('App:Sneaker');
        $chaussures = $repo->findAll();
        $repoQ = $em->getRepository('App:Quantity');

        return $this->render('admin/admin.products.html.twig', [
            'controller_name' => 'AdminController',
            'chaussures' => $chaussures ,
        ]);
    }

    /**
     * @Route("/admin/sneaker-taille-stock", name="admin_sneaker-taille-stock")
     */
    public function admin_sneaker_taille_stock()
    {
        $em= $this->getDoctrine()->getManager();
        $repo = $em->getRepository('App:Sneaker');
        $chaussures = $repo->findAll();
        $repoQ = $em->getRepository('App:Quantity');

        return $this->render('admin/sneaker-taille-stock', [
            'controller_name' => 'AdminController',
            'stocks' => $stock ,
        ]);
    }


    /**
     * @Route("/admin/customers", name="admin_customers")
     */
    public function admin_customers()
    {
        $em= $this->getDoctrine()->getManager();
        $repo = $em->getRepository('App:User');
        $users = $repo->findAll();
        return $this->render('admin/admin.customers.html.twig', [
            'controller_name' => 'AdminController',
            'users' => $users ,
        ]);
    }

    /**
     * @Route("/admin/orders", name="admin_orders")
     */
    public function admin_orders()
    {
        $em= $this->getDoctrine()->getManager();
        $repo = $em->getRepository('App:Commande');
        $orders = $repo->findAll();
        $chaussures = $em->getRepository('App:Sneaker')->findAll();
        
        return $this->render('admin/admin.orders.html.twig', [
            'controller_name' => 'AdminController',
            'commandes' => $orders ,
            'chaussures' =>$chaussures
        ]);
    }

    /**
     * @Route("/admin/customers/detail/{id}", name="admin_customers_detail")
     */
    public function admin_customers_detail($id)
    {
        $em= $this->getDoctrine()->getManager();
        $repo = $em->getRepository('App:User');
        $user = $repo->findOneBy(array('id'=>$id));
        return $this->render('admin/admin.customers.html.twig', [
            'detail_user' => $user ,
        ]);
    }


    /**
     * @Route("/admin/product/detail/{id}", name="admin_product_detail")
     */
    public function admin_product_detail($id)
    {
        $em= $this->getDoctrine()->getManager();
        $repo = $em->getRepository('App:Sneaker');
        $chaussure = $repo->findBy(array('id'=> $id));
        return $this->render('admin/admin.products.detail.html.twig', [
            'controller_name' => 'AdminController',
            'chaussure' => $chaussure ,
        ]);
    }

    /**
     * @Route("/admin/product/detail/quantity/{idTaille}/{idSneaker}", name="admin_product_taille_sneaker_quantities")
     */
    public function admin_product_detail_quantities($idTaille , $idSneaker)
    {
        $em= $this->getDoctrine()->getManager();
        $repo = $em->getRepository('App:Quantity');
        $chaussure = $repo->findByIdTailleAndIdSneaker($idTaille,$idSneaker);
        return $this->render('admin/admin.products.detail.quantities.html.twig', [
            'controller_name' => 'AdminController',
            'chaussure' => $chaussure ,
        ]);
    }


    /**
     * @Route("/admin/add", name="admin_add")
     * @param Request $request
     * @return string
     */
    public function admin_add(Request $request)
    {
        $sneaker = new Sneaker();

        $form = $this->createForm(SneakerType::class,$sneaker);

         $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $sneaker = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $sneaker->setAddedAt(new \DateTime('now'));
                $sneaker->addTaille($form->getData()->getTaille());
                $em->persist($sneaker);
                $em->flush();
                return $this->redirectToRoute('admin_products');
            }
        return $this->render('admin/admin.add.html.twig', [
            'form'=> $form->createView()
        ]);
    }

    /**
     * @Route("/admin/remove/{id}", name="admin_remove")
     * @param Request $request
     * @return string
     */
    public function admin_remove($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('App:Sneaker');
        $sneaker = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($sneaker);
        $em->flush();

        $message= "Le produit a correctement été supprimé ! ";

        return $this->redirectToRoute('admin_products', [
            'message' => $message
        ]);
    }


    /**
     * @Route("/admin/edit", name="admin_edit_sneaker")
     * @param Request $request
     * @return string
     */
    public function admin_edit_sneaker(Sneaker $sneaker, Request $request)
    {
        $form = $this->createForm(SneakerType::class,$sneaker);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($sneaker);
                $em->flush();

            return $this->redirectToRoute('admin_products');
        }
        return $this->render('admin/admin_edit_sneaker.html.twig', array(
            'sneaker'=> $sneaker,
            'form'=>$form->createView()));

    }

    /**
     * @Route("/admin/stock", name="admin_stock")
     * @param Request $request
     * @return string
     */
    public function admin_stock(Request $request, QuantityRepository $quantityRepository)
    {
        $stock = new Quantity();

        $form = $this->createForm(StockType::class,$stock);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stock = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $checkStock = $quantityRepository->findBySneakerIdAndTailleId($stock->getChaussure()->getId(),$stock->getTailles()->getId());
            foreach($checkStock as $quantity) {
                if ($quantity->getChaussure()->getId() == $stock->getChaussure()->getId() && $quantity->getTailles()->getId() == $stock->getTailles()->getId()) {
                    $this->addFlash('notice', "Le stock de l'article n° " ." ". $stock->getChaussure()->getId() ." ". $stock->getChaussure()->getTitre() . "    à la taille " . $stock->getTailles()->getTaille() . " a déjà été initialisé");
                    return $this->redirectToRoute('admin_stock');
                }
            }
            $em->persist($stock);
            $em->flush();

            $this->addFlash('notice', "Le stock pour l'article n°" . $stock->getChaussure()->getId() ." ". $stock->getChaussure()->getTitre() . " (Taille " . $stock->getTailles()->getTaille().")" . " est de ". $stock->getQuantity() ." pièces");
            return $this->redirectToRoute('admin_products');
        }
        return $this->render('admin/admin.manage.stock.html.twig', [
            'form'=> $form->createView()
        ]);
    }



}
