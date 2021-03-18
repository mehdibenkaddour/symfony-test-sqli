<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Article;
use App\Entity\Categorie;
use Gedmo\Mapping\Annotation as Gedmo;

class AppFixtures extends Fixture
{
    const NB_ARTICLES =10;
    const NB_CATEGORIES =2;
    const TEST_CATEGORIES = ["sport","business"];
    private $faker;

    public function __construct(){
        $this->faker = Factory::create();
    }

    /**
     * @param ObjectManager $manager
     * 
     */
    public function load(ObjectManager $manager)
    {
        $this->loadCategories($manager);
        $this->loadArticles($manager);
        $manager->flush();
    }

    public function loadArticles(ObjectManager $manager){
        for($i=1;$i<= self::NB_ARTICLES;$i++){
            $article = new Article();
            $article->setTitre($this->faker->sentence(5))
                    ->setContenu($this->faker->paragraph(3))
                    ->setIntroduction($this->faker->paragraph(3))
                    ->setCover('https://picsum.photos/200/300')
                    ->setDateDeCreation(new \DateTime())
                    ->setVisibilite(true)
                    ->setTrending(true)
                    ->setPopular(true)
                    ->setCategorie($this->getReference('article-categorie-'.rand(0,1)));
            if($i%2 == 0){
                $article->setVisibilite(false)
                        ->setTrending(false)
                        ->setPopular(false);
            }
            $manager->persist($article);
        }
    }


    public function loadCategories(ObjectManager $manager){
        for($i=0;$i< self::NB_CATEGORIES;$i++){
            $categorie = new Categorie();
            $categorie->setTitle(self::TEST_CATEGORIES[$i]);
            $manager->persist($categorie);
            $this->addReference('article-categorie-'.$i,$categorie);
        }
    }
}
