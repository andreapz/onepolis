<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181120081534 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE citizen ADD branch_id INT DEFAULT NULL, ADD relationship_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE citizen ADD CONSTRAINT FK_A9531729DCD6CC49 FOREIGN KEY (branch_id) REFERENCES branch (id)');
        $this->addSql('ALTER TABLE citizen ADD CONSTRAINT FK_A95317292C41D668 FOREIGN KEY (relationship_id) REFERENCES relationship (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A9531729DCD6CC49 ON citizen (branch_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A95317292C41D668 ON citizen (relationship_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE citizen DROP FOREIGN KEY FK_A9531729DCD6CC49');
        $this->addSql('ALTER TABLE citizen DROP FOREIGN KEY FK_A95317292C41D668');
        $this->addSql('DROP INDEX UNIQ_A9531729DCD6CC49 ON citizen');
        $this->addSql('DROP INDEX UNIQ_A95317292C41D668 ON citizen');
        $this->addSql('ALTER TABLE citizen DROP branch_id, DROP relationship_id');
    }
}
