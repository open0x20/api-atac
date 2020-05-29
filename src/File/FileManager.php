<?php

namespace App\File;

use App\Apis\DirectLinkExtrator;
use App\Console\CommandWrapper;
use App\Entity\Track;
use App\Helper\ConfigHelper;

class FileManager
{
    /**
     * @param $input
     * @return string
     */
    public static function computeResultingFilename($input)
    {
        return md5($input);
    }

    /**
     * @param Track $track
     */
    public static function cleanupWorkingFiles(Track $track)
    {
        CommandWrapper::rm(ConfigHelper::get('data_dir') . '/' . FileManager::computeResultingFilename($track->getYtv()) . '.cover.jpg');
        CommandWrapper::rm(ConfigHelper::get('data_dir') . '/' . FileManager::computeResultingFilename($track->getYtv()) . '.cover.png');
        CommandWrapper::rm(ConfigHelper::get('data_dir') . '/' . FileManager::computeResultingFilename($track->getYtv()));
    }

    /**
     * @param Track $track
     */
    public static function removeFromStorage(Track $track)
    {
        CommandWrapper::rm(
            ConfigHelper::get('store_dir') . '/' . FileManager::computeResultingFilename($track->getYtv()) . '.mp3'
        );
    }

    /**
     * @param Track $track
     */
    public static function moveToStorage(Track $track)
    {
        // remove first if already exists
        FileManager::removeFromStorage($track);

        // move to storage directory
        CommandWrapper::mv(
            ConfigHelper::get('data_dir') . '/' . FileManager::computeResultingFilename($track->getYtv()) . '.mp3',
            ConfigHelper::get('store_dir') . '/' . FileManager::computeResultingFilename($track->getYtv()) . '.mp3'
        );
    }

    /**
     * @param Track $track
     * @throws \App\Exception\ApiException
     * @throws \App\Exception\CommandException
     */
    public static function downloadVideoFile(Track $track, bool $verbose)
    {
        // Get the direct download link
        //$link = DirectLinkExtrator::getLinkAction($track->getYtv());

        // Download the file
        //CommandWrapper::wget($link, ConfigHelper::get('data_dir') . '/' . FileManager::computeResultingFilename($track->getYtv()));
        CommandWrapper::youtubedl($track->getYtv(), ConfigHelper::get('data_dir') . '/' . FileManager::computeResultingFilename($track->getYtv()), $verbose);
    }

    /**
     * @param Track $track
     * @throws \App\Exception\CommandException
     */
    public static function downloadCoverFile(Track $track, bool $verbose)
    {
        $filepath = ConfigHelper::get('data_dir') . '/' . FileManager::computeResultingFilename($track->getYtv());
        CommandWrapper::wget($track->getCoverUrl(), $filepath . '.cover', $verbose);
    }

    /**
     * @param Track $track
     * @return false|string
     */
    public static function streamFile(Track $track)
    {
        $filepath = ConfigHelper::get('store_dir') . '/' . FileManager::computeResultingFilename($track->getYtv()) . '.mp3';

        return file_get_contents($filepath);
    }
}
