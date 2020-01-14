<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191213123615 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sneaker_color (sneaker_id INT NOT NULL, color_id INT NOT NULL, INDEX IDX_B6E9C998B44896C4 (sneaker_id), INDEX IDX_B6E9C9987ADA1FB5 (color_id), PRIMARY KEY(sneaker_id, color_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sneaker_color ADD CONSTRAINT FK_B6E9C998B44896C4 FOREIGN KEY (sneaker_id) REFERENCES sneaker (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sneaker_color ADD CONSTRAINT FK_B6E9C9987ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE sneaker_color');
    }
}
