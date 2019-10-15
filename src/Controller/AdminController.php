<?php

namespace App\Controller;

use App\Entity\Sneaker;
use App\Form\SneakerType;
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
     */
    public function admin_add(Request $request)
    {
        $sneaker = new Sneaker();

        $form = $this->createForm(SneakerType::class,$sneaker);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $sneaker->setPrix($request->get('prix'));
            $sneaker->setDescription($request->get('description'));

        }

        return $this->render('admin/admin.add.html.twig', [
            'form'=> $form->createView()
        ]);


        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

}
