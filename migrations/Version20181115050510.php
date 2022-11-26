<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181115050510 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE task CHANGE ordered_date ordered_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F813BAE0AA7');
        $this->addSql('DROP INDEX IDX_D4E6F813BAE0AA7 ON address');
        $this->addSql('ALTER TABLE address DROP event');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE address ADD event INT DEFAULT NULL');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F813BAE0AA7 FOREIGN KEY (event) REFERENCES event (id)');
        $this->addSql('CREATE INDEX IDX_D4E6F813BAE0AA7 ON address (event)');
        $this->addSql('ALTER TABLE task CHANGE ordered_date ordered_date DATETIME DEFAULT NULL');
    }
}
