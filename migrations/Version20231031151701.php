<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231031151701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE member CHANGE phone_number phone_number VARCHAR(15) DEFAULT NULL');
        $this->addSql('ALTER TABLE support CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE phone phone VARCHAR(15) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE member CHANGE phone_number phone_number INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE phone phone INT DEFAULT NULL');
        $this->addSql('ALTER TABLE support CHANGE user_id user_id INT NOT NULL');
    }
}
