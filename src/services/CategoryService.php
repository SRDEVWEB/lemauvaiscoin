<?php

namespace App\services;

use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService
{
    private EntityManagerInterface $em;

    public function __construct(
        EntityManagerInterface $em
    )
    {
        $this->em = $em;
    }

    public function getAllCategories(): array
    {
        return $this->em->getRepository(Categorie::class)->findBy([], ['name' => 'ASC']);
    }
}