<?php

namespace App\Model;

use App\Database\Database;
use App\Entity\Artist;
use App\Entity\Track;
use App\Helper\ImageHelper;
use App\Helper\LockHelper;
use App\Helper\ParserHelper;
use Symfony\Component\Console\Command\LockableTrait;

class InfoModel
{
    use LockableTrait;

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

        // sort alphabetically
        sort($data['artists']);

        // return artists
        return $data;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return mixed
     * TODO optimize pagination
     */
    public static function getTracks(int $limit, int $offset)
    {
        // find all tracks in database
        $allTrackEntities = Database::getInstance()->getRepository(Track::class)->findAll();
        $allTrackEntities = array_values($allTrackEntities); // map into 0..n key array

        // prepare resulting structure
        $data = [];
        $data['meta'] = [];
        $data['tracks'] = [];

        /* pagination calculations
         * Example: There are 100 Tracks (0-99).
         * For $limit = 0,  $offset = 0   iterates over: nothing (loop skipped)
         * For $limit = 10, $offset = 0   iterates over: 0-9
         * For $limit = 10, $offset = 10  iterates over: 10-19
         * For $limit = 10, $offset = 90  iterates over: 90-99
         * For $limit = 10, $offset = 95  iterates over: 95-99
         * For $limit = 10, $offset = 99  iterates over: 99
         * For $limit = 10, $offset = 100 iterates over: nothing (loop skipped)
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
                'modified' => $e->getModified(),
            ];
        }

        // add additional metadata
        $data['meta'] = [
            'countOverall' => count($allTrackEntities),
            'countRequest' => count($data['tracks'])
        ];

        // return tracks
        return $data;
    }

    /**
     * @param $url
     * @return string|null
     */
    public static function checkCover(?string $url)
    {
        if ($url === null) {
            return null;
        }

        $cover = file_get_contents($url);

        if ($cover === false) {
            return null;
        }

        if (strlen($cover) < 100) {
            return null;
        }

        return ImageHelper::getFiletype($cover);
    }

    /**
     * @param string|null $url
     * @return mixed|string|null
     */
    public static function getYtvInfo(?string $url)
    {
        if ($url === null) {
            return null;
        }

        $htmlYtv = file_get_contents($url);


        if ($htmlYtv === false) {
            return null;
        }

        $title = ParserHelper::getStringBetween($htmlYtv, 'title\":\"', '\",');
        $title2 = ParserHelper::getStringBetween($htmlYtv, '<title>', '</title>', 0, 0, 10);
        $title2 = html_entity_decode($title2);
        return [
            'title' => $title,
            'alternativeTitle' => $title2,
        ];
    }

    /**
     * @return array
     */
    public static function getApplicationStatus()
    {
        $trackRepo = Database::getInstance()->getRepository(Track::class);
        $stats = [
            'isRunning' => false,
            'tracks' => [
                'all' => 0,
                'queued' => 0,
                'done' => 0,
            ]
        ];

        $lh = new LockHelper();
        if (!$lh->getLock()) {
            $stats['isRunning'] = true;
        }
        $lh->releaseLock();

        $allUnfinishedConversions = $trackRepo->findBy([
            'modified' => true
        ]);
        $allDoneCoversions = $trackRepo->findBy([
            'modified' => false
        ]);

        $stats['tracks']['all'] = count($allDoneCoversions) + count($allUnfinishedConversions);
        $stats['tracks']['queued'] = count($allUnfinishedConversions);
        $stats['tracks']['done'] = count($allDoneCoversions);

        return $stats;
    }
}
