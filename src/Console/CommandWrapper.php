<?php

namespace App\Console;


use App\Exception\CommandException;
use App\Helper\ConfigHelper;
use App\Helper\ImageHelper;
use App\Helper\LoggingHelper;

class CommandWrapper
{
    public static function wget(string $url, string $targetFilePath, bool $verbose = false): void
    {
        $output = [];
        $exit_code = 255;
        self::exec('wget ' . ($verbose ? '' : '-q') . ' --no-check-certificate "' . $url . '" -O "' . $targetFilePath . '"', $output, $exit_code); //-q for silent

        if ($exit_code !== 0) {
            LoggingHelper::getInstance()->error(implode(PHP_EOL, $output));
            throw new CommandException('wget(1) failed with code ' . $exit_code, 500);
        }
    }

    public static function youtubedl(string $url, string $targetFilePath, bool $verbose = false)
    {
        $output = [];
        $exit_code = 255;
        // -o mymd5hasherino.%(ext)s
        self::exec('youtube-dl ' . ($verbose ? '' : '-q') . ' --no-playlist -r 1.0M -f bestaudio -o "' . $targetFilePath . '" "' . $url . '"', $output, $exit_code);

        if ($exit_code !== 0) {
            LoggingHelper::getInstance()->error(implode(PHP_EOL, $output));
            throw new CommandException('youtube-dl(1) failed with code ' . $exit_code, 500);
        }
    }

    public static function ffmpeg(string $sourceFilePath, string $targetFilePath, string $title, string $artist, string $album, string $coverFilePath, bool $verbose = false): void
    {
        $tmpTargetFilePath = $targetFilePath . '.tmp.mp3';

        $output = [];
        $exit_code = 254;
        $ffmpeg_metadata_params = [
            '-metadata title="' . $title . '"',
            '-metadata artist="' . $artist . '"',
            '-metadata album="' . $album . '"',
            '-metadata encoded_by="0x20"',
            '-id3v2_version 3',
        ];
        $ffmpeg_metadata_params = implode(' ', $ffmpeg_metadata_params);

        $command = 'ffmpeg'
            . ' -y'
            . ($verbose ? '' : ' -loglevel panic')
            . ($verbose ? '' : ' -hide_banner')
            . ' -i "' . $sourceFilePath . '"'
            . ' ' . $ffmpeg_metadata_params
            . ' "' . $tmpTargetFilePath . '"'
        ;

        self::exec($command, $output, $exit_code);

        if ($exit_code !== 0) {
            LoggingHelper::getInstance()->error(implode(PHP_EOL, $output), ['command' => $command]);
            throw new CommandException('ffmpeg(1) failed with exit code ' . $exit_code, 500);
        }

        $output = [];
        $exit_code = 253;
        $ffmpeg_metadata_params = [
            '-map 0:0',
            '-map 1:0',
            '-c copy',
            '-id3v2_version 3',
            '-metadata:s:v comment="Cover (front)"'
        ];
        $ffmpeg_metadata_params = implode(' ', $ffmpeg_metadata_params);

        // Add filetype extension to cover, needed by ffmpeg
        $extension = ImageHelper::getFiletype(file_get_contents($coverFilePath));
        if ($extension === null) {
            throw new CommandException('failed to determine image type', 500);
        }
        CommandWrapper::mv($coverFilePath, $coverFilePath . '.' . $extension);
        $coverFilePath = $coverFilePath . '.' . $extension;

        $command = 'ffmpeg'
            . ' -y'
            . ($verbose ? '' : ' -loglevel panic')
            . ($verbose ? '' : ' -hide_banner')
            . ' -i "' . $tmpTargetFilePath . '"'
            . ' -i "' . $coverFilePath . '"'
            . ' ' . $ffmpeg_metadata_params
            . ' "' . $targetFilePath . '"'
        ;

        self::exec($command, $output, $exit_code);

        if ($exit_code !== 0) {
            LoggingHelper::getInstance()->error(implode(PHP_EOL, $output), ['command' => $command]);
            throw new CommandException('ffmpeg(2) failed with exit code ' . $exit_code, 500);
        }

        // Remove old tmp file
        CommandWrapper::rm($tmpTargetFilePath);
    }

    public static function rm($targetFilePath)
    {
        $output = [];
        $exit_code = 252;
        if (file_exists($targetFilePath)) {
            self::exec('rm ' . $targetFilePath, $output, $exit_code);
        }
    }

    public static function mv($sourceFilePath, $targetFilePath)
    {
        $output = [];
        $exit_code = 251;
        self::exec('mv ' . $sourceFilePath . ' ' . $targetFilePath, $output, $exit_code);
    }

    public static function mkdir($name)
    {
        $output = [];
        $exit_code = 250;
        self::exec('mkdir -p ' . $name,$output, $exit_code);
    }

    public static function psgrep($filter)
    {
        $output = [];
        $exit_code = 249;
        self::exec('ps -aux | grep "' . $filter .'"',$output, $exit_code);

        return $output;
    }

    public static function triggerAsyncWorker()
    {
        $appRoot = ConfigHelper::get('app_root');

        $output = [];
        $exit_code = 249;
        self::exec('php ' . $appRoot . '/bin/console app:worker 1>>' . $appRoot . '/var/command.log 2>>' . $appRoot . '/var/command.log &', $output, $exit_code);
    }

    public static function exec(string $command, array &$output, int& $return_var)
    {
        echo '[CMD] Executing: ' . $command . PHP_EOL;
        exec($command, $output, $return_var);
    }
}
