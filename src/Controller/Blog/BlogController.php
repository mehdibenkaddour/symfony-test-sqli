<?php

namespace App\Controller\Blog;

use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @param ArticleRepository $articleRepository
     * @param CategorieRepository $category
     * @param string $slug
     * 
     * @return Response
     * 
     * * @Route("/blog/{slug}", name="blog.index")
     */
    public function index(ArticleRepository $articleRepository,CategorieRepository $category,string $slug): Response
    {   
        $article = $articleRepository->findOneBy(['slug' => $slug, 'visibilite' => 'true']);
        if(!$article){
            throw $this->createNotFoundException('The article does not exist');
        }
        $related_articles = $article->getCategorie()->getArticles()->getValues();
        return $this->render('blog/index.html.twig', [
            'article' => $article,
            'related_articles' => $related_articles,
            'categories' => $category->findAll()
        ]);
    }
}
