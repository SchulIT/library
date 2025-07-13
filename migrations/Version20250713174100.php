<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250713174100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE book (id INT UNSIGNED AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, subtitle VARCHAR(255) DEFAULT NULL, barcode_title VARCHAR(255) DEFAULT NULL, publisher VARCHAR(255) NOT NULL, isbn VARCHAR(17) NOT NULL, comment LONGTEXT DEFAULT NULL, cover_file_name VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, uuid VARCHAR(36) NOT NULL, UNIQUE INDEX UNIQ_CBE5A331D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE book_copy (id INT UNSIGNED AUTO_INCREMENT NOT NULL, book_id INT UNSIGNED DEFAULT NULL, can_checkout TINYINT(1) NOT NULL, comment LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, uuid VARCHAR(36) NOT NULL, UNIQUE INDEX UNIQ_5427F08AD17F50A6 (uuid), INDEX IDX_5427F08A16A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE borrower (id INT UNSIGNED AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, barcode_id VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, grade VARCHAR(255) DEFAULT NULL, uuid VARCHAR(36) NOT NULL, UNIQUE INDEX UNIQ_DB904DB429439E58 (barcode_id), UNIQUE INDEX UNIQ_DB904DB4D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE checkout (id INT UNSIGNED AUTO_INCREMENT NOT NULL, book_copy_id INT UNSIGNED DEFAULT NULL, borrower_id INT UNSIGNED DEFAULT NULL, start DATETIME NOT NULL, end DATETIME DEFAULT NULL, comment LONGTEXT DEFAULT NULL, uuid VARCHAR(36) NOT NULL, UNIQUE INDEX UNIQ_AF382D4ED17F50A6 (uuid), INDEX IDX_AF382D4E3B550FE4 (book_copy_id), INDEX IDX_AF382D4E11CE312B (borrower_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE id_entity (entity_id VARCHAR(255) NOT NULL, id VARCHAR(255) NOT NULL, expiry DATETIME NOT NULL, PRIMARY KEY(entity_id, id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE label_template (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, `rows` INT NOT NULL, `columns` INT NOT NULL, top_margin_mm DOUBLE PRECISION NOT NULL, bottom_margin_mm DOUBLE PRECISION NOT NULL, left_margin_mm DOUBLE PRECISION NOT NULL, right_margin_mm DOUBLE PRECISION NOT NULL, cell_width_mm DOUBLE PRECISION NOT NULL, cell_height_mm DOUBLE PRECISION NOT NULL, cell_padding_mm DOUBLE PRECISION NOT NULL, uuid VARCHAR(36) NOT NULL, UNIQUE INDEX UNIQ_D56E0646D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE log (id INT AUTO_INCREMENT NOT NULL, channel VARCHAR(255) NOT NULL, level INT NOT NULL, message LONGTEXT NOT NULL, time DATETIME NOT NULL, details JSON DEFAULT NULL COMMENT '(DC2Type:json)', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_processed_messages (id INT AUTO_INCREMENT NOT NULL, run_id INT NOT NULL, attempt SMALLINT NOT NULL, message_type VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, dispatched_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', received_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', finished_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', wait_time BIGINT NOT NULL, handle_time BIGINT NOT NULL, memory_usage INT NOT NULL, transport VARCHAR(255) NOT NULL, tags VARCHAR(255) DEFAULT NULL, failure_type VARCHAR(255) DEFAULT NULL, failure_message LONGTEXT DEFAULT NULL, results JSON DEFAULT NULL COMMENT '(DC2Type:json)', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE setting (id INT UNSIGNED AUTO_INCREMENT NOT NULL, `key` VARCHAR(255) NOT NULL, `data` JSON DEFAULT NULL COMMENT '(DC2Type:json)', UNIQUE INDEX UNIQ_9F74B8984E645A7E (`key`), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT UNSIGNED AUTO_INCREMENT NOT NULL, idp_identifier BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)', username VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, roles JSON NOT NULL COMMENT '(DC2Type:json)', uuid VARCHAR(36) NOT NULL, UNIQUE INDEX UNIQ_8D93D64966D2FA6C (idp_identifier), UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE cache_items (item_id VARBINARY(255) NOT NULL, item_data MEDIUMBLOB NOT NULL, item_lifetime INT UNSIGNED DEFAULT NULL, item_time INT UNSIGNED NOT NULL, PRIMARY KEY(item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE book_copy ADD CONSTRAINT FK_5427F08A16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE checkout ADD CONSTRAINT FK_AF382D4E3B550FE4 FOREIGN KEY (book_copy_id) REFERENCES book_copy (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE checkout ADD CONSTRAINT FK_AF382D4E11CE312B FOREIGN KEY (borrower_id) REFERENCES borrower (id) ON DELETE SET NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE book_copy DROP FOREIGN KEY FK_5427F08A16A2B381
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE checkout DROP FOREIGN KEY FK_AF382D4E3B550FE4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE checkout DROP FOREIGN KEY FK_AF382D4E11CE312B
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE book
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE book_copy
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE borrower
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE checkout
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE id_entity
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE label_template
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE log
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_processed_messages
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE setting
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cache_items
        SQL);
    }
}
