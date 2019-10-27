<?php

namespace App\Controller;

use App\Entity\Quantity;
use App\Entity\Sneaker;
use App\Entity\Taille;
use App\Form\SneakerType;
use App\Form\StockType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
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
        return $this->render('admin/admin.products.html.twig', [
            'controller_name' => 'AdminController',
            'chaussures' => $chaussures ,
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
     * @Route("/admin/edit/{id}", name="admin_edit_sneaker")
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
    public function admin_stock(Request $request)
    {
        $stock = new Quantity();

        $form = $this->createForm(StockType::class,$stock);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stock = $form->getData();
            $em = $this->getDoctrine()->getManager();
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
