<?php

namespace App\Model;


use App\Database\Database;
use App\Entity\Artist;
use App\Entity\Track;

/**
 * Class InfoModel
 * @package App\Model
 */
class InfoModel
{
    /**
     * @return mixed
     */
    public static function getArtists()
    {
        // find all artists in database
        $allArtistEntities = Database::getInstance()->getRepository(Artist::class)->findAll();

        // map into string array
        $data = [];
        $data['artists'] = [];
        foreach ($allArtistEntities as $e) {
            $data['artists'][] = $e->getName();
        }

        sort($data['artists']);

        // return artists
        return $data;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return mixed
     */
    public static function getTracks(int $limit, int $offset)
    {
        // find all tracks in database
        $allTrackEntities = Database::getInstance()->getRepository(Track::class)->findAll();
        $allTrackEntities = array_values($allTrackEntities); // map into 0..n key array

        // map into string array
        $data = [];
        $data['meta'] = [];
        $data['tracks'] = [];
        /**
         * @var $e Track
         */

        $iStart = $offset;
        $iLimit = ($offset + $limit) > count($allTrackEntities) ? count($allTrackEntities) : ($offset + $limit);

        for ($i = $iStart; $i < $iLimit; $i++) {
            $e = $allTrackEntities[$i];

            // map artists into string array
            $artists = [];
            $featuringArtists = [];
            foreach ($e->getArtists() as $a) {
                if ($a->getFeaturing()) {
                    $featuringArtists[] = $a->getArtist()->getName();
                } else {
                    $artists[] = $a->getArtist()->getName();
                }
            }

            // add to array
            $data['tracks'][] = [
                'trackId' => $e->getId(),
                'urlYtv' => $e->getYtv(),
                'artists' => $artists,
                'featuring' => $featuringArtists,
                'title' => $e->getTitle(),
                'album' => $e->getAlbum(),
                'urlCover' => $e->getCoverUrl(),
            ];
        }

        $data['meta'] = [
            'countOverall' => count($allTrackEntities),
            'countRequest' => count($data['tracks'])
        ];

        // return tracks
        return $data;
    }
}
