<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251012090913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_unit (product_id INT NOT NULL, unit_id INT NOT NULL, INDEX IDX_51532EF24584665A (product_id), INDEX IDX_51532EF2F8BD700D (unit_id), PRIMARY KEY(product_id, unit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unit (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, short_name VARCHAR(20) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_unit ADD CONSTRAINT FK_51532EF24584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_unit ADD CONSTRAINT FK_51532EF2F8BD700D FOREIGN KEY (unit_id) REFERENCES unit (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_unit DROP FOREIGN KEY FK_51532EF24584665A');
        $this->addSql('ALTER TABLE product_unit DROP FOREIGN KEY FK_51532EF2F8BD700D');
        $this->addSql('DROP TABLE product_unit');
        $this->addSql('DROP TABLE unit');
    }
}
