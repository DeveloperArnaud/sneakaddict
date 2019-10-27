<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191027183347 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE quantity_order (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sneaker ADD quantity_order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sneaker ADD CONSTRAINT FK_4259B88AFD062D7E FOREIGN KEY (quantity_order_id) REFERENCES quantity_order (id)');
        $this->addSql('CREATE INDEX IDX_4259B88AFD062D7E ON sneaker (quantity_order_id)');
        $this->addSql('ALTER TABLE taille ADD quantity_order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE taille ADD CONSTRAINT FK_76508B38FD062D7E FOREIGN KEY (quantity_order_id) REFERENCES quantity_order (id)');
        $this->addSql('CREATE INDEX IDX_76508B38FD062D7E ON taille (quantity_order_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sneaker DROP FOREIGN KEY FK_4259B88AFD062D7E');
        $this->addSql('ALTER TABLE taille DROP FOREIGN KEY FK_76508B38FD062D7E');
        $this->addSql('DROP TABLE quantity_order');
        $this->addSql('DROP INDEX IDX_4259B88AFD062D7E ON sneaker');
        $this->addSql('ALTER TABLE sneaker DROP quantity_order_id');
        $this->addSql('DROP INDEX IDX_76508B38FD062D7E ON taille');
        $this->addSql('ALTER TABLE taille DROP quantity_order_id');
    }
}
