<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191027160026 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sneaker ADD quantity_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sneaker ADD CONSTRAINT FK_4259B88A7E8B4AFC FOREIGN KEY (quantity_id) REFERENCES quantity (id)');
        $this->addSql('CREATE INDEX IDX_4259B88A7E8B4AFC ON sneaker (quantity_id)');
        $this->addSql('ALTER TABLE taille ADD quantity_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE taille ADD CONSTRAINT FK_76508B387E8B4AFC FOREIGN KEY (quantity_id) REFERENCES quantity (id)');
        $this->addSql('CREATE INDEX IDX_76508B387E8B4AFC ON taille (quantity_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sneaker DROP FOREIGN KEY FK_4259B88A7E8B4AFC');
        $this->addSql('DROP INDEX IDX_4259B88A7E8B4AFC ON sneaker');
        $this->addSql('ALTER TABLE sneaker DROP quantity_id');
        $this->addSql('ALTER TABLE taille DROP FOREIGN KEY FK_76508B387E8B4AFC');
        $this->addSql('DROP INDEX IDX_76508B387E8B4AFC ON taille');
        $this->addSql('ALTER TABLE taille DROP quantity_id');
    }
}
