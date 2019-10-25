<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191025141739 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE favoris_sneaker (favoris_id INT NOT NULL, sneaker_id INT NOT NULL, INDEX IDX_F7B3118F51E8871B (favoris_id), INDEX IDX_F7B3118FB44896C4 (sneaker_id), PRIMARY KEY(favoris_id, sneaker_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favoris_user (favoris_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_3E144C2E51E8871B (favoris_id), INDEX IDX_3E144C2EA76ED395 (user_id), PRIMARY KEY(favoris_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favoris_sneaker ADD CONSTRAINT FK_F7B3118F51E8871B FOREIGN KEY (favoris_id) REFERENCES favoris (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favoris_sneaker ADD CONSTRAINT FK_F7B3118FB44896C4 FOREIGN KEY (sneaker_id) REFERENCES sneaker (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favoris_user ADD CONSTRAINT FK_3E144C2E51E8871B FOREIGN KEY (favoris_id) REFERENCES favoris (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favoris_user ADD CONSTRAINT FK_3E144C2EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE favoris_sneaker');
        $this->addSql('DROP TABLE favoris_user');
    }
}
