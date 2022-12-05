<?php

namespace App\services;

class AddsService
{

    /**
     * return a random seller
     *
     * @return string
     * @throws \Exception
     */
    public function getAdds(): array
    {
        $adds = [
            [
                'marque' => 'Findus',
                'produit' => 'Poissons panés',
                'categorie' => 'alimentaire', //Mon chere Watson
                'img' => '',
                'titre' =>'',
                'text'=>'Une petite faim? go to le congélateur!',

            ],
            [
                'marque' => 'Interval',
                'produit' => 'Pot Feuille Blonde 90g',
                'categorie' => 'tabac', //Mon chere Watson
                'img' => '',
                'titre' =>'',
                'text'=>'Pour les toxicos, le meilleur du pas bon!',
            ],
            [
                'marque' => 'Puma',
                'produit' => 'Undersky/Black',
                'categorie' => 'vêtement', //Mon chere Watson
                'img' => '1.jpg',
                'titre' =>'',
                'text'=>'',
            ],
            [
                'marque' => 'Marie',
                'produit' => 'Papillote de Cabillaud et Riz aux petits légumes 300g',
                'categorie' => 'alimentaire', //Mon chere Watson
                'img' => '2.jpg',
                'titre' =>'',
                'text'=>'',
            ],
            [
                'marque' => 'Rizla',
                'produit' => '150 filtres diam.6mm',
                'categorie' => 'bimboloterie', //Mon chere Watson
                'img' => '',
                'titre' =>'',
                'text'=>'Pas de fumée sans feu, pas de roulée sans filtre!',
            ],
        ];
        $add = $adds[random_int(0, count($adds) - 1)];





        return $add;
    }
}