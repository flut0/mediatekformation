<?php

namespace App\Repository;

use App\Entity\Playlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository des playlists
 * @extends ServiceEntityRepository<Playlist>
 */
class PlaylistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Playlist::class);
    }

    /**
     * Ajoute ou modifie une playlist
     * @param Playlist $entity
     */
    public function add(Playlist $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Supprime une playlist
     * @param Playlist $entity
     */
    public function remove(Playlist $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
    
    /**
     * Retourne toutes les playlists triées sur le nom de la playlist
     * @param string $ordre ordre de tri (ASC ou DESC)
     * @return Playlist[]
     */
    public function findAllOrderByName($ordre): array {
        return $this->createQueryBuilder('p')
                ->leftjoin('p.formations', 'f')
                ->groupBy('p.id')
                ->orderBy('p.name', $ordre)
                ->getQuery()
                ->getResult();       
    } 
	
    /**
     * Retourne les playlists dont un champ contient une valeur
     * ou toutes les playlists si la valeur est vide
     * @param string $champ nom du champ
     * @param string $valeur valeur recherchée
     * @param string $table table liée si le champ est dans une autre table
     * @return Playlist[]
     */
    public function findByContainValue($champ, $valeur, $table=""): array {
        if($valeur==""){
            return $this->findAllOrderByName('ASC');
        }    
        if($table==""){      
            return $this->createQueryBuilder('p')
                    ->leftjoin('p.formations', 'f')
                    ->where('p.'.$champ.' LIKE :valeur')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->groupBy('p.id')
                    ->orderBy('p.name', 'ASC')
                    ->getQuery()
                    ->getResult();              
        }else{   
            return $this->createQueryBuilder('p')
                    ->leftjoin('p.formations', 'f')
                    ->leftjoin('f.categories', 'c')
                    ->where('c.'.$champ.' LIKE :valeur')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->groupBy('p.id')
                    ->orderBy('p.name', 'ASC')
                    ->getQuery()
                    ->getResult();              
        }           
    }    

    /**
     * Retourne toutes les playlists triées sur le nombre de formations
     * @param string $ordre ordre de tri (ASC ou DESC)
     * @return Playlist[]
     */
    public function findAllOrderByNbFormations($ordre): array {
        return $this->createQueryBuilder('p')
                ->leftjoin('p.formations', 'f')
                ->groupBy('p.id')
                ->orderBy('COUNT(f.id)', $ordre)
                ->getQuery()
                ->getResult();
    }
}