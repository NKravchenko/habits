<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160520212618 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE goals (id INT AUTO_INCREMENT NOT NULL, creator_id INT NOT NULL, goal_type INT NOT NULL, title VARCHAR(100) NOT NULL, description VARCHAR(255) DEFAULT NULL, in_weekend TINYINT(1) NOT NULL, date_add DATETIME NOT NULL, date_update DATETIME DEFAULT NULL, date_stop DATETIME DEFAULT NULL, is_active TINYINT(1) NOT NULL, result TINYINT(1) NOT NULL, INDEX IDX_C7241E2F61220EA6 (creator_id), INDEX IDX_C7241E2F92EE128B (goal_type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE goal_note (id INT AUTO_INCREMENT NOT NULL, text_type INT DEFAULT NULL, goal_id INT NOT NULL, result_text VARCHAR(255) DEFAULT NULL, create_at DATETIME NOT NULL, value_number INT DEFAULT NULL, value_time INT DEFAULT NULL, INDEX IDX_D18DBFB68BA0291C (text_type), INDEX IDX_D18DBFB6667D1AFE (goal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE goaltypes (id INT NOT NULL, title VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE texttypes (id INT NOT NULL, title VARCHAR(50) NOT NULL, importance INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE goals ADD CONSTRAINT FK_C7241E2F61220EA6 FOREIGN KEY (creator_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE goals ADD CONSTRAINT FK_C7241E2F92EE128B FOREIGN KEY (goal_type) REFERENCES goaltypes (id)');
        $this->addSql('ALTER TABLE goal_note ADD CONSTRAINT FK_D18DBFB68BA0291C FOREIGN KEY (text_type) REFERENCES texttypes (id)');
        $this->addSql('ALTER TABLE goal_note ADD CONSTRAINT FK_D18DBFB6667D1AFE FOREIGN KEY (goal_id) REFERENCES goals (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE goal_note DROP FOREIGN KEY FK_D18DBFB6667D1AFE');
        $this->addSql('ALTER TABLE goals DROP FOREIGN KEY FK_C7241E2F92EE128B');
        $this->addSql('ALTER TABLE goal_note DROP FOREIGN KEY FK_D18DBFB68BA0291C');
        $this->addSql('ALTER TABLE goals DROP FOREIGN KEY FK_C7241E2F61220EA6');
        $this->addSql('DROP TABLE goals');
        $this->addSql('DROP TABLE goal_note');
        $this->addSql('DROP TABLE goaltypes');
        $this->addSql('DROP TABLE texttypes');
        $this->addSql('DROP TABLE users');
    }
}
