<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221209170306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, street VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, postcode VARCHAR(10) NOT NULL, province VARCHAR(2) NOT NULL, state VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE branch (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, note VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bus (id INT AUTO_INCREMENT NOT NULL, transport INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, total INT NOT NULL, days INT NOT NULL, init_date DATETIME NOT NULL, end_date DATETIME NOT NULL, INDEX IDX_2F566B6966AB212E (transport), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bus_cost (id INT AUTO_INCREMENT NOT NULL, bus INT DEFAULT NULL, name VARCHAR(255) NOT NULL, price VARCHAR(30) NOT NULL, min_age INT NOT NULL, max_age INT NOT NULL, total INT NOT NULL, initial_date DATETIME NOT NULL, end_date DATETIME NOT NULL, INDEX IDX_472BEBB62F566B69 (bus), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bus_cost_citizen (id INT AUTO_INCREMENT NOT NULL, bus_cost INT DEFAULT NULL, citizen INT DEFAULT NULL, name VARCHAR(255) NOT NULL, price VARCHAR(30) NOT NULL, order_date DATETIME NOT NULL, event INT NOT NULL, uid INT NOT NULL, update_date DATETIME NOT NULL, bid INT NOT NULL, cid INT NOT NULL, transport INT NOT NULL, INDEX IDX_4B9DAA35472BEBB6 (bus_cost), INDEX IDX_4B9DAA35A9531729 (citizen), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cap (id INT AUTO_INCREMENT NOT NULL, city VARCHAR(10) NOT NULL, cap VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE check_in (id INT AUTO_INCREMENT NOT NULL, citizen INT DEFAULT NULL, type TINYINT(1) NOT NULL, check_date DATETIME NOT NULL, uid INT NOT NULL, INDEX IDX_90466CF9A9531729 (citizen), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE citizen (id INT AUTO_INCREMENT NOT NULL, task INT DEFAULT NULL, address_id INT DEFAULT NULL, branch_id INT DEFAULT NULL, relationship_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) DEFAULT NULL, city_birth VARCHAR(255) DEFAULT NULL, birth_date DATETIME NOT NULL, phone VARCHAR(20) DEFAULT NULL, email VARCHAR(50) DEFAULT NULL, gender VARCHAR(2) DEFAULT NULL, need_support TINYINT(1) NOT NULL, transport TINYINT(1) NOT NULL, note VARCHAR(255) DEFAULT NULL, room_note VARCHAR(255) DEFAULT NULL, delegate INT NOT NULL, guest TINYINT(1) NOT NULL, partner TINYINT(1) NOT NULL, deleted TINYINT(1) NOT NULL, valid TINYINT(1) NOT NULL, first TINYINT(1) NOT NULL, eid INT NOT NULL, uid INT NOT NULL, INDEX IDX_A9531729527EDB25 (task), UNIQUE INDEX UNIQ_A9531729F5B7AF75 (address_id), INDEX IDX_A9531729DCD6CC49 (branch_id), INDEX IDX_A95317292C41D668 (relationship_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE citizen_payment (id INT AUTO_INCREMENT NOT NULL, task INT DEFAULT NULL, value NUMERIC(10, 2) NOT NULL, payment_date DATETIME NOT NULL, update_date DATETIME NOT NULL, description VARCHAR(255) DEFAULT NULL, code VARCHAR(20) DEFAULT NULL, type VARCHAR(255) NOT NULL, tid INT NOT NULL, uid INT NOT NULL, delete_date DATETIME DEFAULT NULL, duid INT DEFAULT NULL, deleted TINYINT(1) NOT NULL, INDEX IDX_AA0D39F3527EDB25 (task), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, province VARCHAR(5) NOT NULL, chief_town TINYINT(1) NOT NULL, cap VARCHAR(7) DEFAULT NULL, cc VARCHAR(10) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, address_id INT DEFAULT NULL, ueid VARCHAR(64) NOT NULL, title VARCHAR(128) NOT NULL, slug VARCHAR(10) NOT NULL, initial_date DATETIME NOT NULL, end_date DATETIME NOT NULL, UNIQUE INDEX UNIQ_3BAE0AA7F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE extra_cost (id INT AUTO_INCREMENT NOT NULL, hotel_real INT DEFAULT NULL, price NUMERIC(10, 2) NOT NULL, type VARCHAR(30) NOT NULL, INDEX IDX_8C5B57E221491FDD (hotel_real), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fos_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_957A647992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_957A6479A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_957A6479C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotel (id INT AUTO_INCREMENT NOT NULL, event INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_3535ED93BAE0AA7 (event), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotel_match (id INT AUTO_INCREMENT NOT NULL, citizen INT NOT NULL, roomreal INT NOT NULL, uid INT NOT NULL, d INT NOT NULL, last DATETIME NOT NULL, note VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotel_real (id INT AUTO_INCREMENT NOT NULL, address_id INT DEFAULT NULL, hotel INT DEFAULT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, email VARCHAR(50) DEFAULT NULL, latitude VARCHAR(255) DEFAULT NULL, longitude VARCHAR(255) DEFAULT NULL, note VARCHAR(255) DEFAULT NULL, event INT NOT NULL, UNIQUE INDEX UNIQ_21491FDDF5B7AF75 (address_id), INDEX IDX_21491FDD3535ED9 (hotel), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE interest (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, note VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE relationship (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, note VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant (id INT AUTO_INCREMENT NOT NULL, event INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_EB95123F3BAE0AA7 (event), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant_cost (id INT AUTO_INCREMENT NOT NULL, restaurant_meal INT DEFAULT NULL, name VARCHAR(255) NOT NULL, price VARCHAR(30) NOT NULL, min_age INT NOT NULL, max_age INT NOT NULL, total INT NOT NULL, book_init_date DATETIME NOT NULL, book_end_date DATETIME NOT NULL, type VARCHAR(30) NOT NULL, INDEX IDX_DA3004985CE01EF8 (restaurant_meal), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant_cost_citizen (id INT AUTO_INCREMENT NOT NULL, restaurant_cost INT DEFAULT NULL, citizen INT DEFAULT NULL, name VARCHAR(255) NOT NULL, price VARCHAR(255) NOT NULL, book_date DATETIME NOT NULL, update_date DATETIME NOT NULL, type VARCHAR(255) NOT NULL, order_date DATETIME NOT NULL, event INT NOT NULL, uid INT NOT NULL, mid INT NOT NULL, cid INT NOT NULL, restaurant INT NOT NULL, INDEX IDX_120E1D56DA300498 (restaurant_cost), INDEX IDX_120E1D56A9531729 (citizen), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant_extra_cost (id INT AUTO_INCREMENT NOT NULL, restaurant_real INT DEFAULT NULL, price NUMERIC(10, 2) NOT NULL, type VARCHAR(30) NOT NULL, INDEX IDX_296177D3549A5931 (restaurant_real), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant_match (id INT AUTO_INCREMENT NOT NULL, restaurantcost INT NOT NULL, mealreal INT NOT NULL, restaurant INT NOT NULL, uid INT NOT NULL, d INT NOT NULL, last DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant_meal (id INT AUTO_INCREMENT NOT NULL, restaurant INT DEFAULT NULL, name VARCHAR(255) NOT NULL, meal_date DATETIME NOT NULL, type VARCHAR(255) NOT NULL, total INT NOT NULL, eid INT NOT NULL, INDEX IDX_5CE01EF8EB95123F (restaurant), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant_real (id INT AUTO_INCREMENT NOT NULL, address_id INT DEFAULT NULL, restaurant INT DEFAULT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, email VARCHAR(50) DEFAULT NULL, latitude VARCHAR(255) DEFAULT NULL, longitude VARCHAR(255) DEFAULT NULL, note VARCHAR(255) DEFAULT NULL, event INT NOT NULL, UNIQUE INDEX UNIQ_549A5931F5B7AF75 (address_id), INDEX IDX_549A5931EB95123F (restaurant), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant_real_meal (id INT AUTO_INCREMENT NOT NULL, restaurant_real INT DEFAULT NULL, name VARCHAR(255) NOT NULL, guests INT NOT NULL, rid INT NOT NULL, mealid INT NOT NULL, INDEX IDX_C7C48AEF549A5931 (restaurant_real), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant_real_meal_price (id INT AUTO_INCREMENT NOT NULL, restaurant_real_meal INT DEFAULT NULL, price NUMERIC(10, 2) NOT NULL, guests INT NOT NULL, INDEX IDX_317B36B5C7C48AEF (restaurant_real_meal), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, room_base INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, total INT NOT NULL, days INT NOT NULL, init_date DATETIME NOT NULL, end_date DATETIME NOT NULL, hid INT NOT NULL, eid INT NOT NULL, INDEX IDX_729F519BA3B01405 (room_base), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room_base (id INT AUTO_INCREMENT NOT NULL, hotel INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, total INT NOT NULL, days INT NOT NULL, init_date DATETIME NOT NULL, end_date DATETIME NOT NULL, eid INT NOT NULL, INDEX IDX_A3B014053535ED9 (hotel), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room_base_real (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room_cost (id INT AUTO_INCREMENT NOT NULL, room INT DEFAULT NULL, name VARCHAR(255) NOT NULL, price VARCHAR(30) NOT NULL, min_age INT NOT NULL, max_age INT NOT NULL, total INT NOT NULL, initial_date DATETIME NOT NULL, end_date DATETIME NOT NULL, eid INT NOT NULL, INDEX IDX_7B227E98729F519B (room), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room_cost_citizen (id INT AUTO_INCREMENT NOT NULL, room_cost INT DEFAULT NULL, citizen INT DEFAULT NULL, name VARCHAR(255) NOT NULL, price VARCHAR(30) NOT NULL, order_date DATETIME NOT NULL, event INT NOT NULL, uid INT NOT NULL, update_date DATETIME NOT NULL, rbid INT NOT NULL, rid INT NOT NULL, cid INT NOT NULL, INDEX IDX_594F87857B227E98 (room_cost), INDEX IDX_594F8785A9531729 (citizen), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room_meal (id INT AUTO_INCREMENT NOT NULL, room INT NOT NULL, meal INT NOT NULL, event INT NOT NULL, status VARCHAR(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room_real (id INT AUTO_INCREMENT NOT NULL, hotel_real INT DEFAULT NULL, room_base INT DEFAULT NULL, name VARCHAR(255) NOT NULL, floor INT NOT NULL, rooms INT NOT NULL, guests INT NOT NULL, bath TINYINT(1) NOT NULL, access INT NOT NULL, single INT NOT NULL, doublebed INT NOT NULL, twin INT NOT NULL, sofa INT NOT NULL, bunk INT NOT NULL, INDEX IDX_F588233121491FDD (hotel_real), INDEX IDX_F5882331A3B01405 (room_base), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room_real_price (id INT AUTO_INCREMENT NOT NULL, room_real INT DEFAULT NULL, price NUMERIC(10, 2) NOT NULL, guests INT NOT NULL, INDEX IDX_92556076F5882331 (room_real), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stat (id INT AUTO_INCREMENT NOT NULL, total INT NOT NULL, men INT NOT NULL, women INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, event INT DEFAULT NULL, payed NUMERIC(10, 2) NOT NULL, ueid VARCHAR(64) DEFAULT NULL, utid VARCHAR(64) DEFAULT NULL, uid INT NOT NULL, ordered INT NOT NULL, ordered_date DATETIME NOT NULL, code VARCHAR(20) DEFAULT NULL, amount NUMERIC(10, 2) NOT NULL, INDEX IDX_527EDB253BAE0AA7 (event), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket_cost (id INT AUTO_INCREMENT NOT NULL, ticket_type INT DEFAULT NULL, name VARCHAR(255) NOT NULL, price VARCHAR(30) NOT NULL, min_age INT NOT NULL, max_age INT NOT NULL, total INT NOT NULL, book_init_date DATETIME NOT NULL, book_end_date DATETIME NOT NULL, INDEX IDX_2AFD81C4BE054211 (ticket_type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket_cost_citizen (id INT AUTO_INCREMENT NOT NULL, ticket_cost INT DEFAULT NULL, citizen INT DEFAULT NULL, name VARCHAR(255) NOT NULL, price VARCHAR(30) NOT NULL, book_date DATETIME NOT NULL, order_date DATETIME NOT NULL, event INT NOT NULL, ttid INT NOT NULL, uid INT NOT NULL, update_date DATETIME NOT NULL, cid INT NOT NULL, INDEX IDX_2FEB63BD2AFD81C4 (ticket_cost), INDEX IDX_2FEB63BDA9531729 (citizen), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket_event_type (id INT AUTO_INCREMENT NOT NULL, event INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, total INT NOT NULL, days INT NOT NULL, init_date DATETIME NOT NULL, end_date DATETIME NOT NULL, INDEX IDX_58689D63BAE0AA7 (event), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket_type (id INT AUTO_INCREMENT NOT NULL, event INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, total INT NOT NULL, days INT NOT NULL, init_date DATETIME NOT NULL, end_date DATETIME NOT NULL, INDEX IDX_BE0542113BAE0AA7 (event), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transport (id INT AUTO_INCREMENT NOT NULL, event INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_66AB212E3BAE0AA7 (event), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bus ADD CONSTRAINT FK_2F566B6966AB212E FOREIGN KEY (transport) REFERENCES transport (id)');
        $this->addSql('ALTER TABLE bus_cost ADD CONSTRAINT FK_472BEBB62F566B69 FOREIGN KEY (bus) REFERENCES bus (id)');
        $this->addSql('ALTER TABLE bus_cost_citizen ADD CONSTRAINT FK_4B9DAA35472BEBB6 FOREIGN KEY (bus_cost) REFERENCES bus_cost (id)');
        $this->addSql('ALTER TABLE bus_cost_citizen ADD CONSTRAINT FK_4B9DAA35A9531729 FOREIGN KEY (citizen) REFERENCES citizen (id)');
        $this->addSql('ALTER TABLE check_in ADD CONSTRAINT FK_90466CF9A9531729 FOREIGN KEY (citizen) REFERENCES citizen (id)');
        $this->addSql('ALTER TABLE citizen ADD CONSTRAINT FK_A9531729527EDB25 FOREIGN KEY (task) REFERENCES task (id)');
        $this->addSql('ALTER TABLE citizen ADD CONSTRAINT FK_A9531729F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE citizen ADD CONSTRAINT FK_A9531729DCD6CC49 FOREIGN KEY (branch_id) REFERENCES branch (id)');
        $this->addSql('ALTER TABLE citizen ADD CONSTRAINT FK_A95317292C41D668 FOREIGN KEY (relationship_id) REFERENCES relationship (id)');
        $this->addSql('ALTER TABLE citizen_payment ADD CONSTRAINT FK_AA0D39F3527EDB25 FOREIGN KEY (task) REFERENCES task (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE extra_cost ADD CONSTRAINT FK_8C5B57E221491FDD FOREIGN KEY (hotel_real) REFERENCES hotel_real (id)');
        $this->addSql('ALTER TABLE hotel ADD CONSTRAINT FK_3535ED93BAE0AA7 FOREIGN KEY (event) REFERENCES event (id)');
        $this->addSql('ALTER TABLE hotel_real ADD CONSTRAINT FK_21491FDDF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE hotel_real ADD CONSTRAINT FK_21491FDD3535ED9 FOREIGN KEY (hotel) REFERENCES hotel (id)');
        $this->addSql('ALTER TABLE restaurant ADD CONSTRAINT FK_EB95123F3BAE0AA7 FOREIGN KEY (event) REFERENCES event (id)');
        $this->addSql('ALTER TABLE restaurant_cost ADD CONSTRAINT FK_DA3004985CE01EF8 FOREIGN KEY (restaurant_meal) REFERENCES restaurant_meal (id)');
        $this->addSql('ALTER TABLE restaurant_cost_citizen ADD CONSTRAINT FK_120E1D56DA300498 FOREIGN KEY (restaurant_cost) REFERENCES restaurant_cost (id)');
        $this->addSql('ALTER TABLE restaurant_cost_citizen ADD CONSTRAINT FK_120E1D56A9531729 FOREIGN KEY (citizen) REFERENCES citizen (id)');
        $this->addSql('ALTER TABLE restaurant_extra_cost ADD CONSTRAINT FK_296177D3549A5931 FOREIGN KEY (restaurant_real) REFERENCES restaurant_real (id)');
        $this->addSql('ALTER TABLE restaurant_meal ADD CONSTRAINT FK_5CE01EF8EB95123F FOREIGN KEY (restaurant) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE restaurant_real ADD CONSTRAINT FK_549A5931F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE restaurant_real ADD CONSTRAINT FK_549A5931EB95123F FOREIGN KEY (restaurant) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE restaurant_real_meal ADD CONSTRAINT FK_C7C48AEF549A5931 FOREIGN KEY (restaurant_real) REFERENCES restaurant_real (id)');
        $this->addSql('ALTER TABLE restaurant_real_meal_price ADD CONSTRAINT FK_317B36B5C7C48AEF FOREIGN KEY (restaurant_real_meal) REFERENCES restaurant_real_meal (id)');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519BA3B01405 FOREIGN KEY (room_base) REFERENCES room_base (id)');
        $this->addSql('ALTER TABLE room_base ADD CONSTRAINT FK_A3B014053535ED9 FOREIGN KEY (hotel) REFERENCES hotel (id)');
        $this->addSql('ALTER TABLE room_cost ADD CONSTRAINT FK_7B227E98729F519B FOREIGN KEY (room) REFERENCES room (id)');
        $this->addSql('ALTER TABLE room_cost_citizen ADD CONSTRAINT FK_594F87857B227E98 FOREIGN KEY (room_cost) REFERENCES room_cost (id)');
        $this->addSql('ALTER TABLE room_cost_citizen ADD CONSTRAINT FK_594F8785A9531729 FOREIGN KEY (citizen) REFERENCES citizen (id)');
        $this->addSql('ALTER TABLE room_real ADD CONSTRAINT FK_F588233121491FDD FOREIGN KEY (hotel_real) REFERENCES hotel_real (id)');
        $this->addSql('ALTER TABLE room_real ADD CONSTRAINT FK_F5882331A3B01405 FOREIGN KEY (room_base) REFERENCES room_base (id)');
        $this->addSql('ALTER TABLE room_real_price ADD CONSTRAINT FK_92556076F5882331 FOREIGN KEY (room_real) REFERENCES room_real (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB253BAE0AA7 FOREIGN KEY (event) REFERENCES event (id)');
        $this->addSql('ALTER TABLE ticket_cost ADD CONSTRAINT FK_2AFD81C4BE054211 FOREIGN KEY (ticket_type) REFERENCES ticket_type (id)');
        $this->addSql('ALTER TABLE ticket_cost_citizen ADD CONSTRAINT FK_2FEB63BD2AFD81C4 FOREIGN KEY (ticket_cost) REFERENCES ticket_cost (id)');
        $this->addSql('ALTER TABLE ticket_cost_citizen ADD CONSTRAINT FK_2FEB63BDA9531729 FOREIGN KEY (citizen) REFERENCES citizen (id)');
        $this->addSql('ALTER TABLE ticket_event_type ADD CONSTRAINT FK_58689D63BAE0AA7 FOREIGN KEY (event) REFERENCES event (id)');
        $this->addSql('ALTER TABLE ticket_type ADD CONSTRAINT FK_BE0542113BAE0AA7 FOREIGN KEY (event) REFERENCES event (id)');
        $this->addSql('ALTER TABLE transport ADD CONSTRAINT FK_66AB212E3BAE0AA7 FOREIGN KEY (event) REFERENCES event (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bus DROP FOREIGN KEY FK_2F566B6966AB212E');
        $this->addSql('ALTER TABLE bus_cost DROP FOREIGN KEY FK_472BEBB62F566B69');
        $this->addSql('ALTER TABLE bus_cost_citizen DROP FOREIGN KEY FK_4B9DAA35472BEBB6');
        $this->addSql('ALTER TABLE bus_cost_citizen DROP FOREIGN KEY FK_4B9DAA35A9531729');
        $this->addSql('ALTER TABLE check_in DROP FOREIGN KEY FK_90466CF9A9531729');
        $this->addSql('ALTER TABLE citizen DROP FOREIGN KEY FK_A9531729527EDB25');
        $this->addSql('ALTER TABLE citizen DROP FOREIGN KEY FK_A9531729F5B7AF75');
        $this->addSql('ALTER TABLE citizen DROP FOREIGN KEY FK_A9531729DCD6CC49');
        $this->addSql('ALTER TABLE citizen DROP FOREIGN KEY FK_A95317292C41D668');
        $this->addSql('ALTER TABLE citizen_payment DROP FOREIGN KEY FK_AA0D39F3527EDB25');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7F5B7AF75');
        $this->addSql('ALTER TABLE extra_cost DROP FOREIGN KEY FK_8C5B57E221491FDD');
        $this->addSql('ALTER TABLE hotel DROP FOREIGN KEY FK_3535ED93BAE0AA7');
        $this->addSql('ALTER TABLE hotel_real DROP FOREIGN KEY FK_21491FDDF5B7AF75');
        $this->addSql('ALTER TABLE hotel_real DROP FOREIGN KEY FK_21491FDD3535ED9');
        $this->addSql('ALTER TABLE restaurant DROP FOREIGN KEY FK_EB95123F3BAE0AA7');
        $this->addSql('ALTER TABLE restaurant_cost DROP FOREIGN KEY FK_DA3004985CE01EF8');
        $this->addSql('ALTER TABLE restaurant_cost_citizen DROP FOREIGN KEY FK_120E1D56DA300498');
        $this->addSql('ALTER TABLE restaurant_cost_citizen DROP FOREIGN KEY FK_120E1D56A9531729');
        $this->addSql('ALTER TABLE restaurant_extra_cost DROP FOREIGN KEY FK_296177D3549A5931');
        $this->addSql('ALTER TABLE restaurant_meal DROP FOREIGN KEY FK_5CE01EF8EB95123F');
        $this->addSql('ALTER TABLE restaurant_real DROP FOREIGN KEY FK_549A5931F5B7AF75');
        $this->addSql('ALTER TABLE restaurant_real DROP FOREIGN KEY FK_549A5931EB95123F');
        $this->addSql('ALTER TABLE restaurant_real_meal DROP FOREIGN KEY FK_C7C48AEF549A5931');
        $this->addSql('ALTER TABLE restaurant_real_meal_price DROP FOREIGN KEY FK_317B36B5C7C48AEF');
        $this->addSql('ALTER TABLE room DROP FOREIGN KEY FK_729F519BA3B01405');
        $this->addSql('ALTER TABLE room_base DROP FOREIGN KEY FK_A3B014053535ED9');
        $this->addSql('ALTER TABLE room_cost DROP FOREIGN KEY FK_7B227E98729F519B');
        $this->addSql('ALTER TABLE room_cost_citizen DROP FOREIGN KEY FK_594F87857B227E98');
        $this->addSql('ALTER TABLE room_cost_citizen DROP FOREIGN KEY FK_594F8785A9531729');
        $this->addSql('ALTER TABLE room_real DROP FOREIGN KEY FK_F588233121491FDD');
        $this->addSql('ALTER TABLE room_real DROP FOREIGN KEY FK_F5882331A3B01405');
        $this->addSql('ALTER TABLE room_real_price DROP FOREIGN KEY FK_92556076F5882331');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB253BAE0AA7');
        $this->addSql('ALTER TABLE ticket_cost DROP FOREIGN KEY FK_2AFD81C4BE054211');
        $this->addSql('ALTER TABLE ticket_cost_citizen DROP FOREIGN KEY FK_2FEB63BD2AFD81C4');
        $this->addSql('ALTER TABLE ticket_cost_citizen DROP FOREIGN KEY FK_2FEB63BDA9531729');
        $this->addSql('ALTER TABLE ticket_event_type DROP FOREIGN KEY FK_58689D63BAE0AA7');
        $this->addSql('ALTER TABLE ticket_type DROP FOREIGN KEY FK_BE0542113BAE0AA7');
        $this->addSql('ALTER TABLE transport DROP FOREIGN KEY FK_66AB212E3BAE0AA7');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE branch');
        $this->addSql('DROP TABLE bus');
        $this->addSql('DROP TABLE bus_cost');
        $this->addSql('DROP TABLE bus_cost_citizen');
        $this->addSql('DROP TABLE cap');
        $this->addSql('DROP TABLE check_in');
        $this->addSql('DROP TABLE citizen');
        $this->addSql('DROP TABLE citizen_payment');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE extra_cost');
        $this->addSql('DROP TABLE fos_user');
        $this->addSql('DROP TABLE hotel');
        $this->addSql('DROP TABLE hotel_match');
        $this->addSql('DROP TABLE hotel_real');
        $this->addSql('DROP TABLE interest');
        $this->addSql('DROP TABLE relationship');
        $this->addSql('DROP TABLE restaurant');
        $this->addSql('DROP TABLE restaurant_cost');
        $this->addSql('DROP TABLE restaurant_cost_citizen');
        $this->addSql('DROP TABLE restaurant_extra_cost');
        $this->addSql('DROP TABLE restaurant_match');
        $this->addSql('DROP TABLE restaurant_meal');
        $this->addSql('DROP TABLE restaurant_real');
        $this->addSql('DROP TABLE restaurant_real_meal');
        $this->addSql('DROP TABLE restaurant_real_meal_price');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE room_base');
        $this->addSql('DROP TABLE room_base_real');
        $this->addSql('DROP TABLE room_cost');
        $this->addSql('DROP TABLE room_cost_citizen');
        $this->addSql('DROP TABLE room_meal');
        $this->addSql('DROP TABLE room_real');
        $this->addSql('DROP TABLE room_real_price');
        $this->addSql('DROP TABLE stat');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE ticket_cost');
        $this->addSql('DROP TABLE ticket_cost_citizen');
        $this->addSql('DROP TABLE ticket_event_type');
        $this->addSql('DROP TABLE ticket_type');
        $this->addSql('DROP TABLE transport');
    }
}
