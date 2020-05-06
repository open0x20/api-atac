<?php

namespace App\Helper;


use App\Database\Database;
use App\Entity\Artist;
use App\Entity\Track;
use App\Entity\TrackArtist;

class ArtistHelper
{
    /**
     * @param Track $track
     * @param string[] $artists
     * @param string[] $featuringArtists
     * @return TrackArtist[]
     */
    public static function createTrackArtistsFromStringArrays(Track $track, $artists, $featuringArtists)
    {
        $artistRepo = Database::getInstance()->getRepository(Artist::class);

        // find artists in database and map artist names into string array
        $databaseArtists = array_merge(
            $artistRepo->findByNames($artists),
            $artistRepo->findByNames($featuringArtists)
        );
        $mappedDatabaseArtists = [];
        foreach ($databaseArtists as $da) {
            $mappedDatabaseArtists[] = $da->getName();
        }

        // calculate difference between database artists and request artists
        $requestArtists = array_merge(
            $artists,
            isset($featuringArtists) ? $featuringArtists : []
        );
        $diff = [];
        foreach ($requestArtists as $ra) {
            if (!in_array($ra, $mappedDatabaseArtists)) {
                $diff[] = $ra;
            }
        }

        // create the new artists (from $diff)
        $newArtists = [];
        foreach ($diff as $d) {
            $a = new Artist();
            $a->setName($d);
            $newArtists[] = $a;
        }
        $artistRepo->createNewArtists($newArtists);

        $trackArtists = [];

        // create trackArtist for each artist
        foreach ($artistRepo->findByNames($artists) as $a) {
            $ta = new TrackArtist();
            $ta->setTrack($track);
            $ta->setArtist($a);
            $ta->setFeaturing(false);
            $trackArtists[] = $ta;
        }

        // create trackArtist for each featuring artist
        foreach ($artistRepo->findByNames($featuringArtists) as $a) {
            $ta = new TrackArtist();
            $ta->setTrack($track);
            $ta->setArtist($a);
            $ta->setFeaturing(true);
            $trackArtists[] = $ta;
        }

        return $trackArtists;
    }
}
