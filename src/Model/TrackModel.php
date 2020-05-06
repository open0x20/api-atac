<?php

namespace App\Model;


use App\Console\CommandWrapper;
use App\Database\Database;
use App\Dto\Request\AddDto;
use App\Dto\Request\IdDto;
use App\Dto\Request\UpdateDto;
use App\Entity\Artist;
use App\Entity\Track;
use App\Entity\TrackArtist;
use App\Exception\TrackException;
use App\File\FileManager;
use App\Helper\ArtistHelper;

/**
 * Class TrackModel
 * @package App\Model
 */
class TrackModel
{
    /**
     * @param AddDto $addDto
     */
    public static function create(AddDto $addDto)
    {
        $trackRepo = Database::getInstance()->getRepository(Track::class);
        $trackArtistRepo = Database::getInstance()->getRepository(TrackArtist::class);

        // check for duplicate track entries
        if (count($trackRepo->findBy(['ytv' => $addDto->urlYtv])) > 0) {
            throw new TrackException('A track with that urlYtv already exists.', 400);
        }

        // save track into database
        $track = new Track();
        $track->setYtv($addDto->urlYtv);
        $track->setTitle($addDto->title);
        $track->setCoverUrl($addDto->urlCover);
        $track->setAlbum($addDto->album);
        $track->setModified(true);
        $trackRepo->persistTrack($track);

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

        // remove from storage (needed in case the ytv url changes)
        FileManager::removeFromStorage($track);

        // update the database entity
        $track->setYtv($updateDto->urlYtv);
        $track->setTitle($updateDto->title);
        $track->setCoverUrl($updateDto->urlCover);
        $track->setAlbum($updateDto->album);
        $track->setModified(true);

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

        $trackRepo->persistTrack($track);

        // trigger async worker
        CommandWrapper::triggerAsyncWorker();

        return [
            'id' => $track->getId()
        ];
    }

    /**
     * @param IdDto $idDto
     */
    public static function delete(IdDto $idDto)
    {
        $artistRepo = Database::getInstance()->getRepository(Artist::class);
        $trackRepo = Database::getInstance()->getRepository(Track::class);
        $trackArtistRepo = Database::getInstance()->getRepository(TrackArtist::class);

        // find track in database
        $track = $trackRepo->find($idDto->trackId);
        if ($track === null) {
            throw new TrackException('No track found for given id.', 400);
        }
        $trackId = $track->getId();

        // remove ytv from local storage
        FileManager::removeFromStorage($track);

        // remove ytv from database
        $trackArtistRepo->removeTrackArtists($track->getArtists());
        $trackRepo->removeTrack($track);

        // return removed database id
        return [
            'id' => $trackId
        ];
    }
}
