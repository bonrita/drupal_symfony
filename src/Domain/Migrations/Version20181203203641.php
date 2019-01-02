<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181203203641 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE task CHANGE user_id user_id INT DEFAULT NULL, CHANGE project_id project_id INT DEFAULT NULL, CHANGE due_date due_date DATETIME DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE workspace CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE name name VARCHAR(45) DEFAULT \'NULL\' NOT NULL');
        $this->addSql('ALTER TABLE app_roles CHANGE permissions permissions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\'');
        $this->addSql('ALTER TABLE project CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE workspace_id workspace_id INT DEFAULT NULL, CHANGE title title VARCHAR(45) DEFAULT \'NULL\' NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE app_roles CHANGE permissions permissions LONGTEXT DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:simple_array)\'');
        $this->addSql('ALTER TABLE project CHANGE id id SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE workspace_id workspace_id SMALLINT UNSIGNED DEFAULT NULL, CHANGE title title VARCHAR(45) DEFAULT \'NULL\' COLLATE utf8mb4_general_ci');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25166D1F9C');
        $this->addSql('ALTER TABLE task CHANGE user_id user_id INT DEFAULT NULL, CHANGE project_id project_id INT DEFAULT NULL, CHANGE due_date due_date DATETIME DEFAULT \'current_timestamp()\'');
        $this->addSql('ALTER TABLE workspace CHANGE id id SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE name name VARCHAR(45) DEFAULT \'NULL\' COLLATE utf8mb4_general_ci');
    }
}
