<?php

namespace App\Repository;

use App\Entity\Artist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Artist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Artist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Artist[]    findAll()
 * @method Artist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArtistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Artist::class);
    }

    /**
     * @param string[] $names
     * @return Artist[] Returns an array of Artist objects
     */
    public function findByNames($names)
    {
        $artistRepo = $this->getEntityManager()->getRepository(Artist::class);

        if ($names === null) {
            return [];
        }

        $artists = [];
        foreach ($names as $name) {
            $a = $artistRepo->findOneBy([
                'name' => $name
            ]);
            if ($a !== null) {
                $artists[] = $a;
            }
        }

        //$query = $this->getEntityManager()
        //    ->createQuery('SELECT a FROM App\\Entity\\Artist a WHERE a.name IN (:names)')
        //    ->setParameter('names', '"' . implode('", "', $names) . '"');

        //return $query->getResult();

        return $artists;
    }

    /**
     * @param Artist[] $artists
     */
    public function createNewArtists($artists)
    {
        foreach ($artists as $a) {
            $this->getEntityManager()->persist($a);
        }
        $this->getEntityManager()->flush();
    }
}
