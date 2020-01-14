<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191213124947 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sneaker_color (id INT AUTO_INCREMENT NOT NULL, sneaker_id INT DEFAULT NULL, colors_id INT DEFAULT NULL, colorsneaker_path VARCHAR(255) NOT NULL, INDEX IDX_B6E9C998B44896C4 (sneaker_id), INDEX IDX_B6E9C9985C002039 (colors_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sneaker_color ADD CONSTRAINT FK_B6E9C998B44896C4 FOREIGN KEY (sneaker_id) REFERENCES sneaker (id)');
        $this->addSql('ALTER TABLE sneaker_color ADD CONSTRAINT FK_B6E9C9985C002039 FOREIGN KEY (colors_id) REFERENCES color (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE sneaker_color');
    }
}
