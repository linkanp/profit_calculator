<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200413072936 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE buy (id INT AUTO_INCREMENT NOT NULL, item VARCHAR(255) NOT NULL, quantity INT NOT NULL, price INT NOT NULL, stock INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sale (id INT AUTO_INCREMENT NOT NULL, item VARCHAR(255) NOT NULL, quantity INT NOT NULL, price INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sale_batch (id INT AUTO_INCREMENT NOT NULL, buy_id INT NOT NULL, sale_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_28D70A574AFB9379 (buy_id), INDEX IDX_28D70A574A7E4868 (sale_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sale_batch ADD CONSTRAINT FK_28D70A574AFB9379 FOREIGN KEY (buy_id) REFERENCES buy (id)');
        $this->addSql('ALTER TABLE sale_batch ADD CONSTRAINT FK_28D70A574A7E4868 FOREIGN KEY (sale_id) REFERENCES sale (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sale_batch DROP FOREIGN KEY FK_28D70A574AFB9379');
        $this->addSql('ALTER TABLE sale_batch DROP FOREIGN KEY FK_28D70A574A7E4868');
        $this->addSql('DROP TABLE buy');
        $this->addSql('DROP TABLE sale');
        $this->addSql('DROP TABLE sale_batch');
    }
}
