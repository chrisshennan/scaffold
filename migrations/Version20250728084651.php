<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250728084651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE scaffold_author (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', title VARCHAR(255) NOT NULL, biography VARCHAR(1024) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE scaffold_blog ADD author_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE scaffold_blog ADD CONSTRAINT FK_B8F6225EF675F31B FOREIGN KEY (author_id) REFERENCES scaffold_author (id)');
        $this->addSql('CREATE INDEX IDX_B8F6225EF675F31B ON scaffold_blog (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE scaffold_blog DROP FOREIGN KEY FK_B8F6225EF675F31B');
        $this->addSql('DROP TABLE scaffold_author');
        $this->addSql('DROP INDEX IDX_B8F6225EF675F31B ON scaffold_blog');
        $this->addSql('ALTER TABLE scaffold_blog DROP author_id');
    }
}
