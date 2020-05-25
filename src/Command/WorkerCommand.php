<?php

namespace App\Command;

use App\Console\CommandWrapper;
use App\Database\Database;
use App\Entity\Track;
use App\File\FileManager;
use App\Helper\ConfigHelper;
use App\Helper\LoggingHelper;
use App\Validator\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class WorkerCommand extends Command
{
    use LockableTrait;

    protected static $defaultName = 'app:worker';

    public function __construct(ParameterBagInterface $parameterBag, ValidatorInterface $validator, LoggerInterface $logger, EntityManagerInterface $entityManager, string $name = null)
    {
        parent::__construct($name);
        ConfigHelper::initialize($parameterBag);
        Validator::initialize($validator);
        LoggingHelper::initialize($logger);
        Database::initialize($entityManager);
    }

    protected function configure()
    {
        $this
            ->setDescription('Converts all ytv queued for processing.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->lock()) {
            echo 'Already running. Terminating...' . PHP_EOL;
            return 0;
        }

        // Create data/store directories if they don't exist yet
        if (!file_exists(ConfigHelper::get('data_dir'))) {
            CommandWrapper::mkdir(ConfigHelper::get('data_dir'));
        }
        if (!file_exists(ConfigHelper::get('store_dir'))) {
            CommandWrapper::mkdir(ConfigHelper::get('store_dir'));
        }

        $trackRepository = Database::getInstance()->getRepository(Track::class);

        $tracks = $trackRepository->findBy([
            'modified' => true
        ]);

        echo 'Video(s) queued for processing: ' . count($tracks) . PHP_EOL;

        foreach ($tracks as $track) {
            $filepath = ConfigHelper::get('data_dir') . '/' . FileManager::computeResultingFilename($track->getYtv());

            FileManager::downloadCoverFile($track);
            FileManager::downloadVideoFile($track);
            /**
             * @var $track Track
             */
            CommandWrapper::ffmpeg(
                $filepath,
                $filepath . '.mp3',
                $track->getTitle(),
                $track->getArtists()[0]->getArtist()->getName(),
                $track->getAlbum() !== null ? $track->getAlbum() : '',
                $filepath . '.cover'
            );

            // cleanup
            FileManager::cleanupWorkingFiles($track);
            FileManager::moveToStorage($track);

            $track->setModified(false);
            $trackRepository->persistTrack($track);
        }

        $this->release();

        return 0;
    }
}
