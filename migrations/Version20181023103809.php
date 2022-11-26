<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181023103809 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE restaurant_cost ADD CONSTRAINT FK_DA3004985CE01EF8 FOREIGN KEY (restaurant_meal) REFERENCES restaurant_meal (id)');
        $this->addSql('CREATE INDEX IDX_DA3004985CE01EF8 ON restaurant_cost (restaurant_meal)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE restaurant_cost DROP FOREIGN KEY FK_DA3004985CE01EF8');
        $this->addSql('DROP INDEX IDX_DA3004985CE01EF8 ON restaurant_cost');
    }
}
