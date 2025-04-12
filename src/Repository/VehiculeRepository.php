<?php

namespace App\Repository;

use App\Entity\Vehicule\Port\VehiculeRepositoryInterface;
use App\Entity\Vehicule\Vehicule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class VehiculeRepository extends ServiceEntityRepository implements VehiculeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicule::class);
    }

    public function save(Vehicule $vehicule): void
    {
        $this->_em->persist($vehicule);
        $this->_em->flush();
    }

    public function findVehiculeById(int $id): ?Vehicule
    {
        return parent::find($id);
    }

    public function remove(Vehicule $vehicule): void
    {
        $this->_em->remove($vehicule);
        $this->_em->flush();
    }
}