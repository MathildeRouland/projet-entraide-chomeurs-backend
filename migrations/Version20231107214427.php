<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231107214427 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE archived_member (id INT AUTO_INCREMENT NOT NULL, gender VARCHAR(10) NOT NULL, lastname VARCHAR(50) NOT NULL, firstname VARCHAR(50) NOT NULL, birthdate DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', phone_number VARCHAR(15) DEFAULT NULL, email VARCHAR(50) DEFAULT NULL, id_caf INT DEFAULT NULL, id_pole_emploi INT DEFAULT NULL, note LONGTEXT DEFAULT NULL, vulnerabilities LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', difficulties LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE archived_support (id INT AUTO_INCREMENT NOT NULL, entry_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ongoing_job LONGTEXT DEFAULT NULL, ongoing_formation LONGTEXT DEFAULT NULL, worksite_position TINYINT(1) DEFAULT NULL, formation_positioning TINYINT(1) DEFAULT NULL, note LONGTEXT DEFAULT NULL, user VARCHAR(255) NOT NULL, place VARCHAR(255) NOT NULL, targeted_axis VARCHAR(255) NOT NULL, external_tool LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', end_support VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE end_support CHANGE release_date release_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE archived_member');
        $this->addSql('DROP TABLE archived_support');
        $this->addSql('ALTER TABLE end_support CHANGE release_date release_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
