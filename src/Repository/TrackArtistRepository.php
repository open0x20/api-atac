<?php

namespace App\Repository;

use App\Entity\TrackArtist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrackArtist|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrackArtist|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrackArtist[]    findAll()
 * @method TrackArtist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrackArtistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrackArtist::class);
    }

    // /**
    //  * @return TrackArtist[] Returns an array of TrackArtist objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /**
     * @param TrackArtist $trackArtist
     */
    private function persistTrackArtist(TrackArtist $trackArtist)
    {
        $this->getEntityManager()->persist($trackArtist);
    }

    /**
     * @param TrackArtist[] $trackArtists
     */
    public function persistTrackArtists($trackArtists)
    {
        foreach ($trackArtists as $ta) {
            $this->persistTrackArtist($ta);
        }
        $this->getEntityManager()->flush();
    }

    private function removeTrackArtist(TrackArtist $trackArtist)
    {
        $this->getEntityManager()->remove($trackArtist);
    }

    public function removeTrackArtists($trackArtists)
    {
        foreach ($trackArtists as $ta) {
            $this->removeTrackArtist($ta);
        }
        $this->getEntityManager()->flush();
    }
}
