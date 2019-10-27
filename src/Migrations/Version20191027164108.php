<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191027164108 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE quantity (id INT AUTO_INCREMENT NOT NULL, chaussure_id INT DEFAULT NULL, tailles_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_9FF31636F8458E35 (chaussure_id), INDEX IDX_9FF316361AEC613E (tailles_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quantity ADD CONSTRAINT FK_9FF31636F8458E35 FOREIGN KEY (chaussure_id) REFERENCES sneaker (id)');
        $this->addSql('ALTER TABLE quantity ADD CONSTRAINT FK_9FF316361AEC613E FOREIGN KEY (tailles_id) REFERENCES taille (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE quantity');
    }
}
