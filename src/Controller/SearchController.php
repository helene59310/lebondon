<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Form\SearchAnnonceType;
use App\Repository\ImageRepository;
use App\Repository\AnnonceRepository;
use App\Repository\SousCategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    #[Route('/recherche', name: 'app_search')]//on crée la route
    public function index(AnnonceRepository $annonceRepo, Request $request, ImageRepository $imageRepo,SousCategorieRepository $sousCatRepo): Response//request permet de recupérer les info saisi dans le formulaire
    {
        
        $annonces = $annonceRepo-> findAll();
        //dd($annonces);
        //$informationAnnonce = $annonceRepo->infoAnnonceRecherche();//permet de recuperer un tableau avec toutes les infos des annonces (cf annonceRepository)
        //dd($informationAnnonce);
        $formRecherche = $this->createForm (SearchAnnonceType::class); //création du formulaire
        $search = $formRecherche->handleRequest($request);
        
        if ($formRecherche->isSubmitted() && $formRecherche->isValid()) {
            //on recherche les annonces correspondant aux mots clés
            
            $annonces = $annonceRepo->rechercheAnnonce( 
                $search->get('mots')->getData(), 
                $search->get('categorie')->getData(),
                $search->get('idEtat')->getData()  
                
            );
            $imag = $imageRepo -> obtenirImageParAnnonce();
            //dd($imag);
        }
        $imag = $imageRepo -> obtenirImageParAnnonce();
        //$image = $imageRepo-> findByIdAnnonce($annonces) ;
        
        //dd($image);
        /*if ( $formRecherche->isSubmitted()) {
        $nomCategorie = $search['idCategorie']->getData();
        $catSelectionnee=$nomCategorie->getIdCategorie();
        //dd($catSelectionnee);
        $sousCats=$sousCatRepo->findByIdCategorie($catSelectionnee);
        dd($sousCats);
        }*/
        /*foreach ($sousCats as $clef => $sousCat){
            dd($sousCats);
            foreach($sousCat as $caracteristique => $valeur){
                dd($caracteristique);
                return $nomSousCatSelectionnee= $valeur->getData("nomSousCategorie");
            }
            echo '<br>';
        }*/
        //dd($nomSousCatSelectionnee);

        return $this->render('searchAnnonce/index.html.twig', [ //on lie le controller à la vue
            'controller_name' => 'SearchController',
            //'infoAnnonce' => $informationAnnonce,
            'annonces' => $annonces,
            'formRecherche' => $formRecherche->createView(),// on envoie à la vue
            //'sousCategorie'=>$sousCats
            'image' => $imag
        ]);
    }
}