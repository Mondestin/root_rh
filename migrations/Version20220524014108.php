<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220524014108 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE employee_employee_category');
        $this->addSql('ALTER TABLE employees ADD kpa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE employees ADD CONSTRAINT FK_BA82C30030B932B1 FOREIGN KEY (kpa_id) REFERENCES employee_category (id)');
        $this->addSql('CREATE INDEX IDX_BA82C30030B932B1 ON employees (kpa_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employee_employee_category (employee_id INT NOT NULL, employee_category_id INT NOT NULL, INDEX IDX_9014162793605C9F (employee_category_id), INDEX IDX_901416278C03F15C (employee_id), PRIMARY KEY(employee_id, employee_category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE employee_employee_category ADD CONSTRAINT FK_901416278C03F15C FOREIGN KEY (employee_id) REFERENCES employees (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employee_employee_category ADD CONSTRAINT FK_9014162793605C9F FOREIGN KEY (employee_category_id) REFERENCES employee_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employees DROP FOREIGN KEY FK_BA82C30030B932B1');
        $this->addSql('DROP INDEX IDX_BA82C30030B932B1 ON employees');
        $this->addSql('ALTER TABLE employees DROP kpa_id');
    }
}
