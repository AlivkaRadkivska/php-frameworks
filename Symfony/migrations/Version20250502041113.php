<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250502041113 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE course (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(1000) NOT NULL, credits VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE exam (id INT AUTO_INCREMENT NOT NULL, course_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, duration VARCHAR(255) NOT NULL, max_grade INT NOT NULL, start_date DATETIME NOT NULL, INDEX IDX_38BBA6C6591CC992 (course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE exam_result (id INT AUTO_INCREMENT NOT NULL, exam_id INT DEFAULT NULL, student_name VARCHAR(255) NOT NULL, answer VARCHAR(1000) NOT NULL, obtained_grade INT NOT NULL, INDEX IDX_D8599799578D5E91 (exam_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `group` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, major VARCHAR(255) NOT NULL, year INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE schedule_event (id INT AUTO_INCREMENT NOT NULL, course_id INT DEFAULT NULL, group_id INT DEFAULT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, meeting_link VARCHAR(255) NOT NULL, INDEX IDX_C7F7CAFB591CC992 (course_id), INDEX IDX_C7F7CAFBFE54D947 (group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exam ADD CONSTRAINT FK_38BBA6C6591CC992 FOREIGN KEY (course_id) REFERENCES course (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exam_result ADD CONSTRAINT FK_D8599799578D5E91 FOREIGN KEY (exam_id) REFERENCES exam (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE schedule_event ADD CONSTRAINT FK_C7F7CAFB591CC992 FOREIGN KEY (course_id) REFERENCES course (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE schedule_event ADD CONSTRAINT FK_C7F7CAFBFE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE exam DROP FOREIGN KEY FK_38BBA6C6591CC992
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exam_result DROP FOREIGN KEY FK_D8599799578D5E91
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE schedule_event DROP FOREIGN KEY FK_C7F7CAFB591CC992
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE schedule_event DROP FOREIGN KEY FK_C7F7CAFBFE54D947
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE course
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE exam
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE exam_result
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `group`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE schedule_event
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
