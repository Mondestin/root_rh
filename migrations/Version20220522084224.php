<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220522084224 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mails ADD is_read TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE messages DROP is_read');
        $this->addSql('ALTER TABLE pointages CHANGE end_time end_time TIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mails DROP is_read');
        $this->addSql('ALTER TABLE messages ADD is_read TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE pointages CHANGE end_time end_time TIME DEFAULT NULL');
    }
}
