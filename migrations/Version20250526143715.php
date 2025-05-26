<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250526143715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE shopping_list_contribution (id SERIAL NOT NULL, shopping_list_item_id INT NOT NULL, contributor_id INT NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B8AB347A1CAF1D95 ON shopping_list_contribution (shopping_list_item_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B8AB347A7A19A357 ON shopping_list_contribution (contributor_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE shopping_list_item (id SERIAL NOT NULL, party_id INT NOT NULL, name VARCHAR(255) NOT NULL, quantity INT NOT NULL, brought_quantity INT NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4FB1C224213C1059 ON shopping_list_item (party_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE shopping_list_contribution ADD CONSTRAINT FK_B8AB347A1CAF1D95 FOREIGN KEY (shopping_list_item_id) REFERENCES shopping_list_item (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE shopping_list_contribution ADD CONSTRAINT FK_B8AB347A7A19A357 FOREIGN KEY (contributor_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE shopping_list_item ADD CONSTRAINT FK_4FB1C224213C1059 FOREIGN KEY (party_id) REFERENCES party (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE shopping_list_contribution DROP CONSTRAINT FK_B8AB347A1CAF1D95
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE shopping_list_contribution DROP CONSTRAINT FK_B8AB347A7A19A357
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE shopping_list_item DROP CONSTRAINT FK_4FB1C224213C1059
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE shopping_list_contribution
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE shopping_list_item
        SQL);
    }
}
