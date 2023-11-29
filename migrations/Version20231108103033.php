<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231108103033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE archived_member DROP lastname, DROP firstname, DROP birthdate, DROP phone_number, DROP email, DROP id_caf, DROP id_pole_emploi');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE archived_member ADD lastname VARCHAR(50) NOT NULL, ADD firstname VARCHAR(50) NOT NULL, ADD birthdate DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD phone_number VARCHAR(15) DEFAULT NULL, ADD email VARCHAR(50) DEFAULT NULL, ADD id_caf INT DEFAULT NULL, ADD id_pole_emploi INT DEFAULT NULL');
    }
}
