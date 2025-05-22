<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250520135000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE "user" (id SERIAL NOT NULL, phone_number VARCHAR(180) NOT NULL, roles JSON NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_IDENTIFIER_PHONE_NUMBER ON "user" (phone_number)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_party (user_id INT NOT NULL, party_id INT NOT NULL, PRIMARY KEY(user_id, party_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6B57B5B8A76ED395 ON user_party (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6B57B5B8213C1059 ON user_party (party_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_party ADD CONSTRAINT FK_6B57B5B8A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_party ADD CONSTRAINT FK_6B57B5B8213C1059 FOREIGN KEY (party_id) REFERENCES party (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_party DROP CONSTRAINT FK_6B57B5B8A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_party DROP CONSTRAINT FK_6B57B5B8213C1059
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "user"
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_party
        SQL);
    }
}
