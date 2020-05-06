<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200505114323 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE track (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ytv VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, cover_url VARCHAR(1000) NOT NULL, album VARCHAR(255) DEFAULT NULL, modified BOOLEAN NOT NULL)');
        $this->addSql('CREATE TABLE track_artist (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, artist_id INTEGER NOT NULL, track_id INTEGER NOT NULL, featuring BOOLEAN NOT NULL)');
        $this->addSql('CREATE INDEX IDX_499B576EB7970CF8 ON track_artist (artist_id)');
        $this->addSql('CREATE INDEX IDX_499B576E5ED23C43 ON track_artist (track_id)');
        $this->addSql('CREATE TABLE artist (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE track');
        $this->addSql('DROP TABLE track_artist');
        $this->addSql('DROP TABLE artist');
    }
}
