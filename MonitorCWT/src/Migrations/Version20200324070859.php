<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200324070859 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE interface_device ADD device_id INT NOT NULL');
        $this->addSql('ALTER TABLE interface_device ADD CONSTRAINT FK_9973711A94A4C7D4 FOREIGN KEY (device_id) REFERENCES device (id)');
        $this->addSql('CREATE INDEX IDX_9973711A94A4C7D4 ON interface_device (device_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE interface_device DROP FOREIGN KEY FK_9973711A94A4C7D4');
        $this->addSql('DROP INDEX IDX_9973711A94A4C7D4 ON interface_device');
        $this->addSql('ALTER TABLE interface_device DROP device_id');
    }
}
