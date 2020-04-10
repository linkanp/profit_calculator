<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200410162446 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE buy DROP batch');
        $this->addSql('ALTER TABLE sale CHANGE buy_id buy_id INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E54BC0054AFB9379 ON sale (buy_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE buy ADD batch INT DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_E54BC0054AFB9379 ON sale');
        $this->addSql('ALTER TABLE sale CHANGE buy_id buy_id INT DEFAULT NULL');
    }
}
