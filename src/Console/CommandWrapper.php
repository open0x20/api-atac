<?php

namespace App\Console;


use App\Exception\CommandException;
use App\Helper\ConfigHelper;
use App\Helper\ImageHelper;
use App\Helper\LoggingHelper;

class CommandWrapper
{
    public static function wget(string $url, string $targetFilePath): void
    {
        $output = [];
        $exit_code = 255;
        exec('wget "' . $url . '" -O "' . $targetFilePath . '"', $output, $exit_code); //-q for silent

        if ($exit_code !== 0) {
            LoggingHelper::getInstance()->error(implode(PHP_EOL, $output));
            throw new CommandException('wget(1) failed with code ' . $exit_code, 500);
        }
    }

    public static function youtubedl(string $url, string $targetFilePath)
    {
        $output = [];
        $exit_code = 255;
        // -o mymd5hasherino.%(ext)s
        exec('youtube-dl -r 1.0M -f bestaudio -o "' . $targetFilePath . '" "' . $url . '"', $output, $exit_code);

        if ($exit_code !== 0) {
            LoggingHelper::getInstance()->error(implode(PHP_EOL, $output));
            throw new CommandException('youtube-dl(1) failed with code ' . $exit_code, 500);
        }
    }

    public static function ffmpeg(string $sourceFilePath, string $targetFilePath, string $title, string $artist, string $album, string $coverFilePath): void
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
            #. ' -loglevel panic'
            #. ' -hide_banner'
            . ' -i "' . $sourceFilePath . '"'
            . ' ' . $ffmpeg_metadata_params
            . ' "' . $tmpTargetFilePath . '"'
        ;

        exec($command, $output, $exit_code);

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
            #. ' -loglevel panic'
            #. ' -hide_banner'
            . ' -i "' . $tmpTargetFilePath . '"'
            . ' -i "' . $coverFilePath . '"'
            . ' ' . $ffmpeg_metadata_params
            . ' "' . $targetFilePath . '"'
        ;

        exec($command, $output, $exit_code);

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
            exec('rm ' . $targetFilePath, $output, $exit_code);
        }
    }

    public static function mv($sourceFilePath, $targetFilePath)
    {
        $output = [];
        $exit_code = 251;
        exec('mv ' . $sourceFilePath . ' ' . $targetFilePath, $output, $exit_code);
    }

    public static function mkdir($name)
    {
        $output = [];
        $exit_code = 250;
        exec('mkdir -p ' . $name,$output, $exit_code);
    }

    public static function psgrep($filter)
    {
        $output = [];
        $exit_code = 249;
        exec('ps -aux | grep "' . $filter .'"',$output, $exit_code);

        return $output;
    }

    public static function triggerAsyncWorker()
    {
        $appRoot = ConfigHelper::get('app_root');
        exec('php ' . $appRoot . '/bin/console app:worker 1>/dev/null 2>/dev/null &');
    }
}
