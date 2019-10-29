<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191029113033 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE quantity_test_order (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quantity_test_order_quantity_sneaker_ordered (quantity_test_order_id INT NOT NULL, quantity_sneaker_ordered_id INT NOT NULL, INDEX IDX_7F7562489A66933 (quantity_test_order_id), INDEX IDX_7F75624E59C5658 (quantity_sneaker_ordered_id), PRIMARY KEY(quantity_test_order_id, quantity_sneaker_ordered_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quantity_test_order_quantity_sneaker_ordered ADD CONSTRAINT FK_7F7562489A66933 FOREIGN KEY (quantity_test_order_id) REFERENCES quantity_test_order (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quantity_test_order_quantity_sneaker_ordered ADD CONSTRAINT FK_7F75624E59C5658 FOREIGN KEY (quantity_sneaker_ordered_id) REFERENCES quantity_sneaker_ordered (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE quantity_test_order_quantity_sneaker_ordered DROP FOREIGN KEY FK_7F7562489A66933');
        $this->addSql('DROP TABLE quantity_test_order');
        $this->addSql('DROP TABLE quantity_test_order_quantity_sneaker_ordered');
    }
}
