<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231030223457 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE difficulty (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE end_support (id INT AUTO_INCREMENT NOT NULL, release_reason_id INT DEFAULT NULL, release_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_15CBBA4C63554487 (release_reason_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE end_support_difficulty (end_support_id INT NOT NULL, difficulty_id INT NOT NULL, INDEX IDX_936FAD95D5DEC8B (end_support_id), INDEX IDX_936FAD95FCFA9DAE (difficulty_id), PRIMARY KEY(end_support_id, difficulty_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE external_tool (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE member (id INT AUTO_INCREMENT NOT NULL, gender VARCHAR(10) NOT NULL, lastname VARCHAR(50) NOT NULL, firstname VARCHAR(50) NOT NULL, birthdate DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', phone_number INT DEFAULT NULL, email VARCHAR(50) DEFAULT NULL, id_caf INT DEFAULT NULL, id_pole_emploi INT DEFAULT NULL, note LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE member_vulnerability (member_id INT NOT NULL, vulnerability_id INT NOT NULL, INDEX IDX_8F58F8CB7597D3FE (member_id), INDEX IDX_8F58F8CB72897D8B (vulnerability_id), PRIMARY KEY(member_id, vulnerability_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE member_difficulty (member_id INT NOT NULL, difficulty_id INT NOT NULL, INDEX IDX_502571067597D3FE (member_id), INDEX IDX_50257106FCFA9DAE (difficulty_id), PRIMARY KEY(member_id, difficulty_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE place (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE release_reason (id INT AUTO_INCREMENT NOT NULL, reason VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE support (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, place_id INT NOT NULL, targeted_axis_id INT DEFAULT NULL, end_support_id INT DEFAULT NULL, member_id INT DEFAULT NULL, entry_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ongoing_job LONGTEXT DEFAULT NULL, ongoing_formation LONGTEXT DEFAULT NULL, worksite_position TINYINT(1) DEFAULT NULL, formation_positioning TINYINT(1) DEFAULT NULL, note LONGTEXT DEFAULT NULL, INDEX IDX_8004EBA5A76ED395 (user_id), INDEX IDX_8004EBA5DA6A219 (place_id), INDEX IDX_8004EBA59EFD8B20 (targeted_axis_id), UNIQUE INDEX UNIQ_8004EBA5D5DEC8B (end_support_id), UNIQUE INDEX UNIQ_8004EBA57597D3FE (member_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE support_external_tool (support_id INT NOT NULL, external_tool_id INT NOT NULL, INDEX IDX_EDBFA98E315B405 (support_id), INDEX IDX_EDBFA98E56D9FB8F (external_tool_id), PRIMARY KEY(support_id, external_tool_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE targeted_axis (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, firstname VARCHAR(30) NOT NULL, lastname VARCHAR(30) NOT NULL, phone INT DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vulnerability (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE end_support ADD CONSTRAINT FK_15CBBA4C63554487 FOREIGN KEY (release_reason_id) REFERENCES release_reason (id)');
        $this->addSql('ALTER TABLE end_support_difficulty ADD CONSTRAINT FK_936FAD95D5DEC8B FOREIGN KEY (end_support_id) REFERENCES end_support (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE end_support_difficulty ADD CONSTRAINT FK_936FAD95FCFA9DAE FOREIGN KEY (difficulty_id) REFERENCES difficulty (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE member_vulnerability ADD CONSTRAINT FK_8F58F8CB7597D3FE FOREIGN KEY (member_id) REFERENCES member (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE member_vulnerability ADD CONSTRAINT FK_8F58F8CB72897D8B FOREIGN KEY (vulnerability_id) REFERENCES vulnerability (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE member_difficulty ADD CONSTRAINT FK_502571067597D3FE FOREIGN KEY (member_id) REFERENCES member (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE member_difficulty ADD CONSTRAINT FK_50257106FCFA9DAE FOREIGN KEY (difficulty_id) REFERENCES difficulty (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE support ADD CONSTRAINT FK_8004EBA5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE support ADD CONSTRAINT FK_8004EBA5DA6A219 FOREIGN KEY (place_id) REFERENCES place (id)');
        $this->addSql('ALTER TABLE support ADD CONSTRAINT FK_8004EBA59EFD8B20 FOREIGN KEY (targeted_axis_id) REFERENCES targeted_axis (id)');
        $this->addSql('ALTER TABLE support ADD CONSTRAINT FK_8004EBA5D5DEC8B FOREIGN KEY (end_support_id) REFERENCES end_support (id)');
        $this->addSql('ALTER TABLE support ADD CONSTRAINT FK_8004EBA57597D3FE FOREIGN KEY (member_id) REFERENCES member (id)');
        $this->addSql('ALTER TABLE support_external_tool ADD CONSTRAINT FK_EDBFA98E315B405 FOREIGN KEY (support_id) REFERENCES support (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE support_external_tool ADD CONSTRAINT FK_EDBFA98E56D9FB8F FOREIGN KEY (external_tool_id) REFERENCES external_tool (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE end_support DROP FOREIGN KEY FK_15CBBA4C63554487');
        $this->addSql('ALTER TABLE end_support_difficulty DROP FOREIGN KEY FK_936FAD95D5DEC8B');
        $this->addSql('ALTER TABLE end_support_difficulty DROP FOREIGN KEY FK_936FAD95FCFA9DAE');
        $this->addSql('ALTER TABLE member_vulnerability DROP FOREIGN KEY FK_8F58F8CB7597D3FE');
        $this->addSql('ALTER TABLE member_vulnerability DROP FOREIGN KEY FK_8F58F8CB72897D8B');
        $this->addSql('ALTER TABLE member_difficulty DROP FOREIGN KEY FK_502571067597D3FE');
        $this->addSql('ALTER TABLE member_difficulty DROP FOREIGN KEY FK_50257106FCFA9DAE');
        $this->addSql('ALTER TABLE support DROP FOREIGN KEY FK_8004EBA5A76ED395');
        $this->addSql('ALTER TABLE support DROP FOREIGN KEY FK_8004EBA5DA6A219');
        $this->addSql('ALTER TABLE support DROP FOREIGN KEY FK_8004EBA59EFD8B20');
        $this->addSql('ALTER TABLE support DROP FOREIGN KEY FK_8004EBA5D5DEC8B');
        $this->addSql('ALTER TABLE support DROP FOREIGN KEY FK_8004EBA57597D3FE');
        $this->addSql('ALTER TABLE support_external_tool DROP FOREIGN KEY FK_EDBFA98E315B405');
        $this->addSql('ALTER TABLE support_external_tool DROP FOREIGN KEY FK_EDBFA98E56D9FB8F');
        $this->addSql('DROP TABLE difficulty');
        $this->addSql('DROP TABLE end_support');
        $this->addSql('DROP TABLE end_support_difficulty');
        $this->addSql('DROP TABLE external_tool');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP TABLE member_vulnerability');
        $this->addSql('DROP TABLE member_difficulty');
        $this->addSql('DROP TABLE place');
        $this->addSql('DROP TABLE release_reason');
        $this->addSql('DROP TABLE support');
        $this->addSql('DROP TABLE support_external_tool');
        $this->addSql('DROP TABLE targeted_axis');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE vulnerability');
    }
}
