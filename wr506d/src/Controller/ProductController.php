<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'product_list')]
    public function listProducts(): Response
    {
        return $this->render('product/list.html.twig', [
            'title' => '(Total) Liste des produits',
        ]);
    }

    #[Route('/product/{id}', name: 'product_view')]
    public function viewProduct(string $id): Response
    {
        return $this->render('product/view.html.twig', [
            'title' => "Affichage du produit - $id",
        ]);
    }
}