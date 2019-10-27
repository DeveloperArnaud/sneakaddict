<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191027184522 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE quantity_order ADD order_u_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE quantity_order ADD CONSTRAINT FK_200EBF12A954A987 FOREIGN KEY (order_u_id) REFERENCES commande (id)');
        $this->addSql('CREATE INDEX IDX_200EBF12A954A987 ON quantity_order (order_u_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE quantity_order DROP FOREIGN KEY FK_200EBF12A954A987');
        $this->addSql('DROP INDEX IDX_200EBF12A954A987 ON quantity_order');
        $this->addSql('ALTER TABLE quantity_order DROP order_u_id');
    }
}
