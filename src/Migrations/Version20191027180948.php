<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191027180948 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE quantity_order (id INT AUTO_INCREMENT NOT NULL, chaussure_id INT DEFAULT NULL, taille_id INT DEFAULT NULL, order_u_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_200EBF12F8458E35 (chaussure_id), INDEX IDX_200EBF12FF25611A (taille_id), INDEX IDX_200EBF12A954A987 (order_u_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quantity_order ADD CONSTRAINT FK_200EBF12F8458E35 FOREIGN KEY (chaussure_id) REFERENCES sneaker (id)');
        $this->addSql('ALTER TABLE quantity_order ADD CONSTRAINT FK_200EBF12FF25611A FOREIGN KEY (taille_id) REFERENCES taille (id)');
        $this->addSql('ALTER TABLE quantity_order ADD CONSTRAINT FK_200EBF12A954A987 FOREIGN KEY (order_u_id) REFERENCES commande (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE quantity_order');
    }
}
