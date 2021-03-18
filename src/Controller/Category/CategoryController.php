<?php

namespace App\Controller\Category;

use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{slug}", name="category.articles")
     */
    public function index(CategorieRepository $categorieRepository,string $slug): Response
    {
        $categorie = $categorieRepository->findOneBy(['slug'=>$slug]);
        if (!$categorie) {
            throw $this->createNotFoundException('The category does not exist');
        }
        $articles =$categorie->getArticles()->getValues();
        foreach($articles as $key => $article){
            if($article->getVisibilite() == false)
                unset($articles[$key]);
        }
        return $this->render('category/index.html.twig', [
            'categorie' => $categorie,
            'categories' => $categorieRepository->findAll(),
            'articles' => $articles,
        ]);
    }
}
