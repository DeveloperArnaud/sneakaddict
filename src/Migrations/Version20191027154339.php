<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191027154339 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE quantity (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quantity_sneaker (quantity_id INT NOT NULL, sneaker_id INT NOT NULL, INDEX IDX_B1ED718F7E8B4AFC (quantity_id), INDEX IDX_B1ED718FB44896C4 (sneaker_id), PRIMARY KEY(quantity_id, sneaker_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quantity_taille (quantity_id INT NOT NULL, taille_id INT NOT NULL, INDEX IDX_7BE8C62A7E8B4AFC (quantity_id), INDEX IDX_7BE8C62AFF25611A (taille_id), PRIMARY KEY(quantity_id, taille_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quantity_sneaker ADD CONSTRAINT FK_B1ED718F7E8B4AFC FOREIGN KEY (quantity_id) REFERENCES quantity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quantity_sneaker ADD CONSTRAINT FK_B1ED718FB44896C4 FOREIGN KEY (sneaker_id) REFERENCES sneaker (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quantity_taille ADD CONSTRAINT FK_7BE8C62A7E8B4AFC FOREIGN KEY (quantity_id) REFERENCES quantity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quantity_taille ADD CONSTRAINT FK_7BE8C62AFF25611A FOREIGN KEY (taille_id) REFERENCES taille (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE quantity_sneaker DROP FOREIGN KEY FK_B1ED718F7E8B4AFC');
        $this->addSql('ALTER TABLE quantity_taille DROP FOREIGN KEY FK_7BE8C62A7E8B4AFC');
        $this->addSql('DROP TABLE quantity');
        $this->addSql('DROP TABLE quantity_sneaker');
        $this->addSql('DROP TABLE quantity_taille');
    }
}
