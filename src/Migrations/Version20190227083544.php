<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190227083544 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE log_requests (log_id INT AUTO_INCREMENT NOT NULL, route VARCHAR(30) NOT NULL, ip VARCHAR(20) NOT NULL, header LONGTEXT NOT NULL, status VARCHAR(20) NOT NULL, params VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(log_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pages (page_id INT AUTO_INCREMENT NOT NULL, page_slug VARCHAR(255) NOT NULL, page_title VARCHAR(255) DEFAULT NULL, content LONGTEXT DEFAULT NULL, page_params LONGTEXT DEFAULT NULL, page_type VARCHAR(255) NOT NULL, template VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, status enum(\'active\', \'inactive\'), PRIMARY KEY(page_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media_relation (id INT AUTO_INCREMENT NOT NULL, media_ref_id INT NOT NULL, reference_id INT NOT NULL, reference_type VARCHAR(50) DEFAULT NULL, media_params LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, type VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forms (form_id INT AUTO_INCREMENT NOT NULL, type VARCHAR(50) NOT NULL, content LONGTEXT NOT NULL, user_ref_id INT NOT NULL, status VARCHAR(10) NOT NULL, ip VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(form_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_assignment (role_assignment_id INT AUTO_INCREMENT NOT NULL, user_ref_id INT NOT NULL, role_ref_id INT NOT NULL, PRIMARY KEY(role_assignment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roles (role_id INT AUTO_INCREMENT NOT NULL, role_name VARCHAR(50) NOT NULL, role_type VARCHAR(50) NOT NULL, PRIMARY KEY(role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rule (rule_id INT AUTO_INCREMENT NOT NULL, route_key VARCHAR(50) NOT NULL, route_name VARCHAR(50) NOT NULL, method VARCHAR(50) NOT NULL, role_ref_id INT NOT NULL, PRIMARY KEY(rule_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (media_id INT AUTO_INCREMENT NOT NULL, file_name VARCHAR(255) NOT NULL, mime VARCHAR(50) NOT NULL, uri VARCHAR(500) NOT NULL, status VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(media_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (user_id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(20) NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(50) DEFAULT NULL, user_params LONGTEXT DEFAULT NULL, status SMALLINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE log_requests');
        $this->addSql('DROP TABLE pages');
        $this->addSql('DROP TABLE media_relation');
        $this->addSql('DROP TABLE forms');
        $this->addSql('DROP TABLE role_assignment');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE rule');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE users');
    }
}
