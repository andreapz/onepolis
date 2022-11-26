<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181120075408 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE branch (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, note VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE relationship (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, note VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE citizen ADD surname VARCHAR(255) DEFAULT NULL, ADD city_birth VARCHAR(255) DEFAULT NULL, ADD phone VARCHAR(20) DEFAULT NULL, ADD email VARCHAR(50) DEFAULT NULL, ADD gender VARCHAR(2) DEFAULT NULL, ADD need_support TINYINT(1) NOT NULL, ADD note VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE branch');
        $this->addSql('DROP TABLE relationship');
        $this->addSql('ALTER TABLE citizen DROP surname, DROP city_birth, DROP phone, DROP email, DROP gender, DROP need_support, DROP note');
    }
}
