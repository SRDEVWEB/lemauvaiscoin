<?php

namespace App\services;

use App\Entity\Annonce;
use App\Entity\Owner;
use App\Repository\OwnerRepository;
use ContainerF3cJm2L\getOwnerRepositoryService;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Curl\User;
use function PHPUnit\Framework\isEmpty;


class AnnonceService
{
    private EntityManagerInterface $em;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em = $em;
    }
    /**
     * @param array $filters
     * @param array $order
     * @param int $user
     * @param int $page
     * @param int
     * @return array
     */
    public function getAnnonces(
        array $filters,
        array $order,
        int $user=0,
        int $page = 1,
        int $limit = 10
    ): array {
        if ($page < 1) {
            $page = 1;
        }

        $qb = $this ->em->createQueryBuilder();
        $qb
            ->select('a')
            ->from(Annonce::class, 'a')
            ->where('1 = 1');


        if (isset($filters['query'])) {
            $qb->andWhere('a.commentaires LIKE :query')
                ->orWhere('a.description LIKE :query')
                ->setParameter('query', '%'.$filters['query'].'%');
        }

        if (isset($filters['in_categorie'])) {
            $qb->andWhere('a.categorie IN (:categorie)')
                ->setParameter('categorie', $filters['in_categorie']);
//            dump('filter',$filters['in_categorie']);die;
        }

        if (isset($filters['price_sup'])) {
            $qb->andWhere('a.prix >= :pricesup')
                ->setParameter('pricesup', $filters['price_sup']);
        }

        if (isset($filters['price_inf'])) {
            $qb->andWhere('a.prix < :priceinf')
                ->setParameter('priceinf', $filters['price_inf']);
        }

        if (isset($filters['imgcheck'])) {
            $qb->andWhere('a.imageFile IS NOT NULL');
        }

        if (isset($filters['yourcheck'])) {
            $qb->andWhere('a.owner = :lapinou ')
            ->setParameter('lapinou', $user);
        }

//        dump($filters['imgcheck']);die;

        if (isset($order['prix'])) {
            $qb->orderBy('a.prix', $order['prix']);
        }

        if (isset($order['date_depot'])) {
            $qb->orderBy('a.dateDepot', $order['date_depot']);
        }

        if (isset($order['produit'])) {
            $qb->orderBy('a.produit', $order['produit']);
        }

        $qbCount = clone $qb;

        $qb->setMaxResults($limit)
            ->setFirstResult(($page - 1) * $limit);
        // count all result
        $qbCount->select('count(a.id)');
        $total = (int)$qbCount->getQuery()->getSingleScalarResult();
        $totalPages = (int)ceil($total / $limit);

        if ($total === 0) {
            throw new \RuntimeException('No results !');
        } else {
            if ($page > $totalPages) {
                throw new \RuntimeException('This page does not exist !');
            }
        }

        if ($total === 0) {
            throw new \RuntimeException('No results !', 0);
        } else {
            if ($page > $totalPages) {
                throw new \RuntimeException('This page does not exist !', 10);
            }
        }

        $res = $qb->getQuery()->getResult();

        return [
            'results' => $res,
            'count' => $total,
            'page' => $page,
            'limit' => $limit,
            'sql' => $qb->getQuery()->getSQL(),
            'totalPages' => $totalPages,
        ];
    }


}