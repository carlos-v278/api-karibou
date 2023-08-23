<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230822003226 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE advertisement_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE advertisement_picture_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE apartment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE api_token_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE building_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE rent_receipt_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE syndicate_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE advertisement (id INT NOT NULL, building_id INT NOT NULL, owner_id INT NOT NULL, title VARCHAR(255) NOT NULL, published_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, update_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, description TEXT DEFAULT NULL, price INT DEFAULT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C95F6AEE4D2A7E12 ON advertisement (building_id)');
        $this->addSql('CREATE INDEX IDX_C95F6AEE7E3C61F9 ON advertisement (owner_id)');
        $this->addSql('COMMENT ON COLUMN advertisement.published_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN advertisement.update_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE advertisement_picture (id INT NOT NULL, advertisement_id INT NOT NULL, file VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, update_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C2D2BA84A1FBF71B ON advertisement_picture (advertisement_id)');
        $this->addSql('COMMENT ON COLUMN advertisement_picture.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN advertisement_picture.update_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE apartment (id INT NOT NULL, building_id INT NOT NULL, owner_id INT DEFAULT NULL, number INT DEFAULT NULL, floor INT NOT NULL, rent INT DEFAULT NULL, extra_charge INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4D7E68544D2A7E12 ON apartment (building_id)');
        $this->addSql('CREATE INDEX IDX_4D7E68547E3C61F9 ON apartment (owner_id)');
        $this->addSql('CREATE TABLE api_token (id INT NOT NULL, owned_by_id INT NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, token VARCHAR(68) NOT NULL, scopes JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7BA2F5EB5E70BCD7 ON api_token (owned_by_id)');
        $this->addSql('COMMENT ON COLUMN api_token.expires_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE building (id INT NOT NULL, syndicate_id INT NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, zipcode INT NOT NULL, street VARCHAR(255) NOT NULL, number INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E16F61D44C37717D ON building (syndicate_id)');
        $this->addSql('CREATE TABLE rent_receipt (id INT NOT NULL, apartment_id INT NOT NULL, month VARCHAR(255) NOT NULL, file VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B2B5CD35176DFE85 ON rent_receipt (apartment_id)');
        $this->addSql('COMMENT ON COLUMN rent_receipt.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE syndicate (id INT NOT NULL, street VARCHAR(255) NOT NULL, street_number INT NOT NULL, siret VARCHAR(255) DEFAULT NULL, siren VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, location_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) DEFAULT NULL, username VARCHAR(255) DEFAULT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, update_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('CREATE INDEX IDX_8D93D64964D218E ON "user" (location_id)');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".update_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE user_syndicate (user_id INT NOT NULL, syndicate_id INT NOT NULL, PRIMARY KEY(user_id, syndicate_id))');
        $this->addSql('CREATE INDEX IDX_561B53F6A76ED395 ON user_syndicate (user_id)');
        $this->addSql('CREATE INDEX IDX_561B53F64C37717D ON user_syndicate (syndicate_id)');
        $this->addSql('ALTER TABLE advertisement ADD CONSTRAINT FK_C95F6AEE4D2A7E12 FOREIGN KEY (building_id) REFERENCES building (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE advertisement ADD CONSTRAINT FK_C95F6AEE7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE advertisement_picture ADD CONSTRAINT FK_C2D2BA84A1FBF71B FOREIGN KEY (advertisement_id) REFERENCES advertisement (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE apartment ADD CONSTRAINT FK_4D7E68544D2A7E12 FOREIGN KEY (building_id) REFERENCES building (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE apartment ADD CONSTRAINT FK_4D7E68547E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE api_token ADD CONSTRAINT FK_7BA2F5EB5E70BCD7 FOREIGN KEY (owned_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE building ADD CONSTRAINT FK_E16F61D44C37717D FOREIGN KEY (syndicate_id) REFERENCES syndicate (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rent_receipt ADD CONSTRAINT FK_B2B5CD35176DFE85 FOREIGN KEY (apartment_id) REFERENCES apartment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D64964D218E FOREIGN KEY (location_id) REFERENCES apartment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_syndicate ADD CONSTRAINT FK_561B53F6A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_syndicate ADD CONSTRAINT FK_561B53F64C37717D FOREIGN KEY (syndicate_id) REFERENCES syndicate (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE advertisement_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE advertisement_picture_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE apartment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE api_token_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE building_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE rent_receipt_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE syndicate_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE advertisement DROP CONSTRAINT FK_C95F6AEE4D2A7E12');
        $this->addSql('ALTER TABLE advertisement DROP CONSTRAINT FK_C95F6AEE7E3C61F9');
        $this->addSql('ALTER TABLE advertisement_picture DROP CONSTRAINT FK_C2D2BA84A1FBF71B');
        $this->addSql('ALTER TABLE apartment DROP CONSTRAINT FK_4D7E68544D2A7E12');
        $this->addSql('ALTER TABLE apartment DROP CONSTRAINT FK_4D7E68547E3C61F9');
        $this->addSql('ALTER TABLE api_token DROP CONSTRAINT FK_7BA2F5EB5E70BCD7');
        $this->addSql('ALTER TABLE building DROP CONSTRAINT FK_E16F61D44C37717D');
        $this->addSql('ALTER TABLE rent_receipt DROP CONSTRAINT FK_B2B5CD35176DFE85');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D64964D218E');
        $this->addSql('ALTER TABLE user_syndicate DROP CONSTRAINT FK_561B53F6A76ED395');
        $this->addSql('ALTER TABLE user_syndicate DROP CONSTRAINT FK_561B53F64C37717D');
        $this->addSql('DROP TABLE advertisement');
        $this->addSql('DROP TABLE advertisement_picture');
        $this->addSql('DROP TABLE apartment');
        $this->addSql('DROP TABLE api_token');
        $this->addSql('DROP TABLE building');
        $this->addSql('DROP TABLE rent_receipt');
        $this->addSql('DROP TABLE syndicate');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_syndicate');
    }
}
