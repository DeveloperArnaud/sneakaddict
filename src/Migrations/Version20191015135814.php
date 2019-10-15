<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191015135814 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE quantity (id INT AUTO_INCREMENT NOT NULL, relation_id INT DEFAULT NULL, taille_id INT DEFAULT NULL, quantity INT DEFAULT NULL, INDEX IDX_9FF316363256915B (relation_id), INDEX IDX_9FF31636FF25611A (taille_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quantity ADD CONSTRAINT FK_9FF316363256915B FOREIGN KEY (relation_id) REFERENCES sneaker (id)');
        $this->addSql('ALTER TABLE quantity ADD CONSTRAINT FK_9FF31636FF25611A FOREIGN KEY (taille_id) REFERENCES taille (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE quantity');
    }
}
