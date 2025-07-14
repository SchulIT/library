<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250714151745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE user_borrower (user_id INT UNSIGNED NOT NULL, borrower_id INT UNSIGNED NOT NULL, INDEX IDX_3B1DA9B4A76ED395 (user_id), INDEX IDX_3B1DA9B411CE312B (borrower_id), PRIMARY KEY(user_id, borrower_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_borrower ADD CONSTRAINT FK_3B1DA9B4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_borrower ADD CONSTRAINT FK_3B1DA9B411CE312B FOREIGN KEY (borrower_id) REFERENCES borrower (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE email email VARCHAR(255) DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE user_borrower DROP FOREIGN KEY FK_3B1DA9B4A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_borrower DROP FOREIGN KEY FK_3B1DA9B411CE312B
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_borrower
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE email email VARCHAR(255) NOT NULL
        SQL);
    }
}
