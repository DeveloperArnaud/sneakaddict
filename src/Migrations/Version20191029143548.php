<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191029143548 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE quantity_order_commande DROP FOREIGN KEY FK_9A59338AFD062D7E');
        $this->addSql('ALTER TABLE quantity_order_sneaker DROP FOREIGN KEY FK_25791E9CFD062D7E');
        $this->addSql('ALTER TABLE quantity_order_taille DROP FOREIGN KEY FK_8BBC51A3FD062D7E');
        $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, avis VARCHAR(255) DEFAULT NULL, note DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE quantity_order');
        $this->addSql('DROP TABLE quantity_order_commande');
        $this->addSql('DROP TABLE quantity_order_sneaker');
        $this->addSql('DROP TABLE quantity_order_taille');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE quantity_order (id INT AUTO_INCREMENT NOT NULL, quantity LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE quantity_order_commande (quantity_order_id INT NOT NULL, commande_id INT NOT NULL, INDEX IDX_9A59338AFD062D7E (quantity_order_id), INDEX IDX_9A59338A82EA2E54 (commande_id), PRIMARY KEY(quantity_order_id, commande_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE quantity_order_sneaker (quantity_order_id INT NOT NULL, sneaker_id INT NOT NULL, INDEX IDX_25791E9CFD062D7E (quantity_order_id), INDEX IDX_25791E9CB44896C4 (sneaker_id), PRIMARY KEY(quantity_order_id, sneaker_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE quantity_order_taille (quantity_order_id INT NOT NULL, taille_id INT NOT NULL, INDEX IDX_8BBC51A3FD062D7E (quantity_order_id), INDEX IDX_8BBC51A3FF25611A (taille_id), PRIMARY KEY(quantity_order_id, taille_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE quantity_order_commande ADD CONSTRAINT FK_9A59338A82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quantity_order_commande ADD CONSTRAINT FK_9A59338AFD062D7E FOREIGN KEY (quantity_order_id) REFERENCES quantity_order (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quantity_order_sneaker ADD CONSTRAINT FK_25791E9CB44896C4 FOREIGN KEY (sneaker_id) REFERENCES sneaker (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quantity_order_sneaker ADD CONSTRAINT FK_25791E9CFD062D7E FOREIGN KEY (quantity_order_id) REFERENCES quantity_order (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quantity_order_taille ADD CONSTRAINT FK_8BBC51A3FD062D7E FOREIGN KEY (quantity_order_id) REFERENCES quantity_order (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quantity_order_taille ADD CONSTRAINT FK_8BBC51A3FF25611A FOREIGN KEY (taille_id) REFERENCES taille (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE avis');
    }
}
