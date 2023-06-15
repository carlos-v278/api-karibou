<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230615140523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_4d7e68547e3c61f9');
        $this->addSql('CREATE INDEX IDX_4D7E68547E3C61F9 ON apartment (owner_id)');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT fk_8d93d649176dfe85');
        $this->addSql('DROP INDEX idx_8d93d649176dfe85');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN apartment_id TO location_id');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D64964D218E FOREIGN KEY (location_id) REFERENCES apartment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8D93D64964D218E ON "user" (location_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D64964D218E');
        $this->addSql('DROP INDEX IDX_8D93D64964D218E');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN location_id TO apartment_id');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT fk_8d93d649176dfe85 FOREIGN KEY (apartment_id) REFERENCES apartment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_8d93d649176dfe85 ON "user" (apartment_id)');
        $this->addSql('DROP INDEX IDX_4D7E68547E3C61F9');
        $this->addSql('CREATE UNIQUE INDEX uniq_4d7e68547e3c61f9 ON apartment (owner_id)');
    }
}
