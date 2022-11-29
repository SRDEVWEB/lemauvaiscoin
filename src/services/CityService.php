<?php

namespace App\services;

//use App\services\Contracts\HttpClient;


// API postal to INSEE : https://api.gouv.fr/documentation/api_carto_codes_postaux
// API INSEE to data : https://api.gouv.fr/documentation/api-geo
// EP =================== /commune/{code}

// HTPP CLIENT
// https://symfony.com/doc/current/http_client.html


use Symfony\Contracts\HttpClient\HttpClientInterface;

class CityService
{

    /**
     * @var HttpClientInterface
     * App\services\Contracts\HttpClient;
     */
    private HttpClientInterface $client;


    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;

    }

    public function normalizer(string $input): string
    {
        // todo : enlever les accent, passer tout en strtolower, remove [a-z0-9] => ' '

        $txt = array(
            '/[áàâãªäÁÀÂÃÄ]/u' => 'a',
            '/[ÍÌÎÏíìîï]/u' => 'i',
            '/[éèêëÉÈÊË]/u' => 'e',
            '/[óòôõºöÓÒÔÕÖ]/u' => 'o',
            '/[úùûüÚÙÛÜ]/u' => 'u',
            '/[çÇ]/' => 'c',
            '/[ñÑ]/' => 'n',
            '/--/' => '-'
            );

//dump( "normalizedCity: " . preg_replace(array_keys($txt), array_values($txt), preg_replace("'[0-9]'",'',strtolower($input))));
        return preg_replace(array_keys($txt), array_values($txt), preg_replace("'[0-9]'",'',strtolower($input)));

    }

    public function getCity(string $postalCode, string $city): array
    {
        // on check si le nom de la ville correspond a un des element de la reponse
        // de la premiére API, si non on prend le premier element du tableau
        // $resp[0]
        $response = $this->client->request(
            'GET',
            'https://apicarto.ign.fr/api/codes-postaux/communes/' . $postalCode
        );


        $contentData = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        if(!is_array($contentData)){
            throw new \RuntimeException('bad format data');
        }

        $cityFound = null;
        $normalizedCity = $this->normalizer($city);
        // todo un foreach et check si on a la ville, si on trouve alors on stock dans $cityFound

        foreach ($contentData as $data){
            //dump("citysFound: ".strtolower($Data['nomCommune']));
            if  (strtolower($data['nomCommune'])===$normalizedCity) {
                //$cityFound = $Data['nomCommune'];
                //dump($Data['codeCommune']);
                //$cityFound=$Data['nomCommune'];
                $cityFound['codeCommune'] = $data['codeCommune'];
                dump("Code commune: " . $cityFound['codeCommune']);
                break;
            }
        }

        if($cityFound === null){
            $cityFound = $contentData[0] ?? null;
        }

        if($cityFound === null){
            throw new \RuntimeException('no data found');
        }

        $codeInsee = $cityFound['codeCommune'];
dump("codeInsee:".$codeInsee);
        /// todo 2eme call API

        $response = $this->client->request(
            'GET',
            'https://geo.api.gouv.fr/communes?code=' . $codeInsee . '&fields=nom,codesPostaux,departement,region,population&format=json&geometry=centre'
        );

        //dump("INSEE:" .$response['departement']);

        $contentData = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        //$urlApiCommune = 'https://geo.api.gouv.fr/communes/' . $codeInsee
          //  . '?fields=nom,code,codesPostaux,siren,codeEpci,codeDepartement,codeRegion,population,departement&format=json&geometry=centre';



        // todo si jamais la reponse est bonne alors on return la reponse de l'api
        //dump($contentData['0']['codesPostaux']);

        // todo si non alors on return UKN
        if(!is_array($contentData)){
            throw new \RuntimeException('bad format data');
        }else{

            return  [
                'city' => $contentData[0]['nom'],
                'cp' => $contentData[0]['codesPostaux']['0'],
                'population' => $contentData[0]['population'],
                'region' => $contentData[0]['region']['nom'],
                'departement' =>$contentData[0]['departement']['code'],
            ];

        }


    }


}