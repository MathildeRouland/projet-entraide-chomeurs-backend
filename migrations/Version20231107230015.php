<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231107230015 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE archived_member ADD archived_support_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE archived_member ADD CONSTRAINT FK_3C50B32FD5475356 FOREIGN KEY (archived_support_id) REFERENCES archived_support (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3C50B32FD5475356 ON archived_member (archived_support_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE archived_member DROP FOREIGN KEY FK_3C50B32FD5475356');
        $this->addSql('DROP INDEX UNIQ_3C50B32FD5475356 ON archived_member');
        $this->addSql('ALTER TABLE archived_member DROP archived_support_id');
    }
}
