<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    #[Route('/products', name: 'app_products')]
    public function index(ProductsRepository $productsRepository, CategoriesRepository $categoriesRepository): Response
    {

        if(isset($_GET["filter"])){
            if($_GET["filter"] == "all"){
                $products = $productsRepository->findAll();
            }else{
                $products = $productsRepository->findBy(array('category' => $_GET["filter"]),array('name' => 'ASC'));
            }
        }else{
            $products = $productsRepository->findAll();
        }


        return $this->render('products/index.html.twig', [
            'controller_name' => 'ProductsController',
            'products' => $products,
            'categories' => $categoriesRepository->findAll()
        ]);
    }
}
