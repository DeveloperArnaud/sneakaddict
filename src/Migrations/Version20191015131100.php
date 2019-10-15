<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191015131100 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sneaker_taille (sneaker_id INT NOT NULL, taille_id INT NOT NULL, INDEX IDX_51827513B44896C4 (sneaker_id), INDEX IDX_51827513FF25611A (taille_id), PRIMARY KEY(sneaker_id, taille_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sneaker_taille ADD CONSTRAINT FK_51827513B44896C4 FOREIGN KEY (sneaker_id) REFERENCES sneaker (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sneaker_taille ADD CONSTRAINT FK_51827513FF25611A FOREIGN KEY (taille_id) REFERENCES taille (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE sneaker_taille');
    }
}
