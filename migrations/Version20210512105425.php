<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210512105425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE nurschool_user (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', email_email VARCHAR(255) NOT NULL, password_password LONGTEXT DEFAULT NULL, full_name_firstname VARCHAR(255) DEFAULT NULL, full_name_lastname VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_5E6BCBDA7ADF3DFB (email_email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE nurschool_user');
    }
}
