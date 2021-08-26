<?php

namespace App\Model;

use App\Console\CommandWrapper;
use App\Database\Database;
use App\Dto\Request\AddDto;
use App\Dto\Request\IdDto;
use App\Dto\Request\UpdateDto;
use App\Entity\Track;
use App\Entity\TrackArtist;
use App\Exception\ApiException;
use App\Exception\TrackException;
use App\File\FileManager;
use App\Helper\ArtistHelper;

class TrackModel
{
    /**
     * @param AddDto $addDto
     * @return array
     * @throws TrackException
     */
    public static function create(AddDto $addDto)
    {
        $trackRepo = Database::getInstance()->getRepository(Track::class);
        $trackArtistRepo = Database::getInstance()->getRepository(TrackArtist::class);

        // check for duplicate track entries
        if (count($trackRepo->findBy(['url' => $addDto->url])) > 0) {
            throw new TrackException('A track with that url already exists.', 400);
        }

        // save track into database
        $track = new Track();
        $track->setUrl($addDto->url);
        $track->setTitle($addDto->title);
        $track->setCoverUrl($addDto->urlCover);
        $track->setAlbum($addDto->album);
        $track->setModified(true);
        $trackRepo->persistTrack($track);

        // save trackArtists into database
        $trackArtists = ArtistHelper::createTrackArtistsFromStringArrays($track, $addDto->artists, $addDto->featuring);
        $trackArtistRepo->persistTrackArtists($trackArtists);

        // trigger async worker
        CommandWrapper::triggerAsyncWorker();

        // return database track id
        return [
            'id' => $track->getId()
        ];
    }

    /**
     * @param UpdateDto $updateDto
     * @return array
     * @throws TrackException
     */
    public static function update(UpdateDto $updateDto)
    {
        $trackRepo = Database::getInstance()->getRepository(Track::class);
        $trackArtistRepo = Database::getInstance()->getRepository(TrackArtist::class);

        // find track in database
        $track = $trackRepo->find($updateDto->trackId);
        if ($track === null) {
            throw new TrackException('No track found for given id.', 400);
        }

        // remove from storage (needed in case the url changes)
        FileManager::removeFromStorage($track);

        // update the database entity
        $track->setUrl($updateDto->url);
        $track->setTitle($updateDto->title);
        $track->setCoverUrl($updateDto->urlCover);
        $track->setAlbum($updateDto->album);
        $track->setModified(true);
        $trackRepo->persistTrack($track);

        // remove old trackArtists
        $trackArtistRepo->removeTrackArtists($track->getArtists());

        // add new trackArtists
        $trackArtistRepo->persistTrackArtists(
            ArtistHelper::createTrackArtistsFromStringArrays(
                $track,
                $updateDto->artists,
                $updateDto->featuring
            )
        );

        // trigger async worker
        CommandWrapper::triggerAsyncWorker();

        // return database track id
        return [
            'id' => $track->getId()
        ];
    }

    /**
     * @param IdDto $idDto
     * @return array
     * @throws TrackException
     */
    public static function delete(IdDto $idDto)
    {
        $trackRepo = Database::getInstance()->getRepository(Track::class);
        $trackArtistRepo = Database::getInstance()->getRepository(TrackArtist::class);

        // find track in database
        $track = $trackRepo->find($idDto->trackId);
        if ($track === null) {
            throw new TrackException('No track found for given id.', 400);
        }
        $removedTrackId = $track->getId();

        // remove track from local storage
        FileManager::removeFromStorage($track);

        // remove track from database
        $trackArtistRepo->removeTrackArtists($track->getArtists());
        $trackRepo->removeTrack($track);

        // return removed database id
        return [
            'id' => $removedTrackId
        ];
    }

    /**
     * @param int $trackId
     * @return false|string
     * @throws TrackException
     */
    public static function stream(int $trackId)
    {
        $trackRepo = Database::getInstance()->getRepository(Track::class);

        // find track in database
        $track = $trackRepo->find($trackId);
        if ($track === null) {
            throw new TrackException('No track found for given id.', 400);
        }

        // return raw file
        return FileManager::streamFile($track);
    }

    /**
     * @param String $filename
     * @return false|string
     * @throws ApiException
     */
    public static function download($filename)
    {
        return FileManager::downloadFile($filename);
    }
}
