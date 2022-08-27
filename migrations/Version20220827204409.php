<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220827204409 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facility (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, room_id INT DEFAULT NULL, link VARCHAR(255) NOT NULL, INDEX IDX_C53D045F54177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, is_approved TINYINT(1) NOT NULL, INDEX IDX_5A8A6C8DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_category (post_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_B9A190604B89032C (post_id), INDEX IDX_B9A1906012469DE2 (category_id), PRIMARY KEY(post_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, capacity INT NOT NULL, surface INT NOT NULL, type VARCHAR(255) NOT NULL, price INT NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_729F519BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room_facility (room_id INT NOT NULL, facility_id INT NOT NULL, INDEX IDX_B0BF547654177093 (room_id), INDEX IDX_B0BF5476A7014910 (facility_id), PRIMARY KEY(room_id, facility_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, pseudo VARCHAR(255) NOT NULL, is_blocked TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE visitor (id INT AUTO_INCREMENT NOT NULL, ip VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE visitor_post (visitor_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_A406A78570BEE6D (visitor_id), INDEX IDX_A406A7854B89032C (post_id), PRIMARY KEY(visitor_id, post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE visitor_room (visitor_id INT NOT NULL, room_id INT NOT NULL, INDEX IDX_8C139A9370BEE6D (visitor_id), INDEX IDX_8C139A9354177093 (room_id), PRIMARY KEY(visitor_id, room_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F54177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post_category ADD CONSTRAINT FK_B9A190604B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_category ADD CONSTRAINT FK_B9A1906012469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE room_facility ADD CONSTRAINT FK_B0BF547654177093 FOREIGN KEY (room_id) REFERENCES room (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE room_facility ADD CONSTRAINT FK_B0BF5476A7014910 FOREIGN KEY (facility_id) REFERENCES facility (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE visitor_post ADD CONSTRAINT FK_A406A78570BEE6D FOREIGN KEY (visitor_id) REFERENCES visitor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE visitor_post ADD CONSTRAINT FK_A406A7854B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE visitor_room ADD CONSTRAINT FK_8C139A9370BEE6D FOREIGN KEY (visitor_id) REFERENCES visitor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE visitor_room ADD CONSTRAINT FK_8C139A9354177093 FOREIGN KEY (room_id) REFERENCES room (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F54177093');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('ALTER TABLE post_category DROP FOREIGN KEY FK_B9A190604B89032C');
        $this->addSql('ALTER TABLE post_category DROP FOREIGN KEY FK_B9A1906012469DE2');
        $this->addSql('ALTER TABLE room DROP FOREIGN KEY FK_729F519BA76ED395');
        $this->addSql('ALTER TABLE room_facility DROP FOREIGN KEY FK_B0BF547654177093');
        $this->addSql('ALTER TABLE room_facility DROP FOREIGN KEY FK_B0BF5476A7014910');
        $this->addSql('ALTER TABLE visitor_post DROP FOREIGN KEY FK_A406A78570BEE6D');
        $this->addSql('ALTER TABLE visitor_post DROP FOREIGN KEY FK_A406A7854B89032C');
        $this->addSql('ALTER TABLE visitor_room DROP FOREIGN KEY FK_8C139A9370BEE6D');
        $this->addSql('ALTER TABLE visitor_room DROP FOREIGN KEY FK_8C139A9354177093');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE facility');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE post_category');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE room_facility');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE visitor');
        $this->addSql('DROP TABLE visitor_post');
        $this->addSql('DROP TABLE visitor_room');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
