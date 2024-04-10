<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240409171751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE champion (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, pv INT NOT NULL, power INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fight (id INT AUTO_INCREMENT NOT NULL, user1_id INT NOT NULL, user2_id INT NOT NULL, winner_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_21AA445656AE248B (user1_id), INDEX IDX_21AA4456441B8B65 (user2_id), INDEX IDX_21AA44565DFCD4B8 (winner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_champion (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, champion_id INT NOT NULL, UNIQUE INDEX UNIQ_A5CE9AB4A76ED395 (user_id), INDEX IDX_A5CE9AB4FA7FD7EB (champion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fight ADD CONSTRAINT FK_21AA445656AE248B FOREIGN KEY (user1_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE fight ADD CONSTRAINT FK_21AA4456441B8B65 FOREIGN KEY (user2_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE fight ADD CONSTRAINT FK_21AA44565DFCD4B8 FOREIGN KEY (winner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_champion ADD CONSTRAINT FK_A5CE9AB4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_champion ADD CONSTRAINT FK_A5CE9AB4FA7FD7EB FOREIGN KEY (champion_id) REFERENCES champion (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fight DROP FOREIGN KEY FK_21AA445656AE248B');
        $this->addSql('ALTER TABLE fight DROP FOREIGN KEY FK_21AA4456441B8B65');
        $this->addSql('ALTER TABLE fight DROP FOREIGN KEY FK_21AA44565DFCD4B8');
        $this->addSql('ALTER TABLE user_champion DROP FOREIGN KEY FK_A5CE9AB4A76ED395');
        $this->addSql('ALTER TABLE user_champion DROP FOREIGN KEY FK_A5CE9AB4FA7FD7EB');
        $this->addSql('DROP TABLE champion');
        $this->addSql('DROP TABLE fight');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_champion');
    }
}
