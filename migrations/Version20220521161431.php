<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220521161431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('CREATE TABLE v_dashboard (id INT AUTO_INCREMENT NOT NULL, nb_employee INT DEFAULT NULL, nb_department INT DEFAULT NULL, nb_customer INT DEFAULT NULL, salary_avg NUMERIC(6, 2) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('DROP TABLE employee_departments');
        // $this->addSql('ALTER TABLE employees ADD departement_id INT DEFAULT NULL');
        // $this->addSql('ALTER TABLE employees ADD CONSTRAINT FK_BA82C300CCF9E01E FOREIGN KEY (departement_id) REFERENCES departments (id)');
        // $this->addSql('CREATE UNIQUE INDEX UNIQ_BA82C300CCF9E01E ON employees (departement_id)');
        // $this->addSql('ALTER TABLE pointages CHANGE end_time end_time TIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employee_departments (employee_id INT NOT NULL, departments_id INT NOT NULL, INDEX IDX_978879A98C03F15C (employee_id), INDEX IDX_978879A9F1B3F295 (departments_id), PRIMARY KEY(employee_id, departments_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE v_dashboard');
        $this->addSql('ALTER TABLE employees DROP FOREIGN KEY FK_BA82C300CCF9E01E');
        $this->addSql('DROP INDEX UNIQ_BA82C300CCF9E01E ON employees');
        $this->addSql('ALTER TABLE employees DROP departement_id');
        $this->addSql('ALTER TABLE pointages CHANGE end_time end_time TIME DEFAULT NULL');
    }
}
