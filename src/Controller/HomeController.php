<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @param ArticleRepository $article
     * 
     * @return Response
     * 
     * @Route("/",name="home.index")
     * 
     */
    public function index(ArticleRepository $article,CategorieRepository $categorie): Response
    {   
        $trendingArticles = $this->getTrendingArticles($article);
        $popularArticles = $this->getPopularArticles($article);
        $recentArticles = $this->getRecentArticles($article);
        $sportArticles = $categorie->findOneBy(['slug'=>'sport'])->getArticles()->getValues();
        // Recuperer juste les produit visible
        foreach($sportArticles as $key => $sportArticle){
            if($sportArticle->getVisibilite() == false)
                unset($sportArticles[$key]);
        }

        $businessArticles = $categorie->findOneBy(['slug'=>'business'])->getArticles()->getValues();
        foreach($businessArticles as $key => $businessArticle){
            if($businessArticle->getVisibilite() == false)
                unset($businessArticles[$key]);
        }
        return $this->render('home/index.html.twig', [
            'trending_articles' => $trendingArticles,
            'popular_articles' => $popularArticles,
            'recent_articles' => $recentArticles,
            'sport_articles' => $sportArticles,
            'business_articles' =>$businessArticles,
            'categories' => $categorie->findAll()
        ]);
    }

    /**
     * Fonction permettre de recuperer les articles dont le trending evaluer à true
     * 
     * @param ArticleRepository $article
     * 
     * @return array
     */
    public function getTrendingArticles(ArticleRepository $article):array{
        return $article->findBy(['trending'=>'true','visibilite' => 'true']);
    }

    /**
     * Fonction Permettre de recuperer les articles populaire c-à-d les
     * Artcles ayant l'attribut popular est true
     * 
     * @param ArticleRepository $article
     * 
     * @return array
     */
    public function getPopularArticles(ArticleRepository $article):array{
        return $article->findBy(['popular'=>'true','visibilite' => 'true']);
    }

    /**
     * Fonction permettre de recuperer les 6 dernier articles ajouter
     * @param ArticleRepository $article
     * 
     * @return array
     */
    public function getRecentArticles(ArticleRepository $article):array{
        return $article->findBy(['visibilite' => 'true'],['id' => 'DESC'],6);
    }
}
