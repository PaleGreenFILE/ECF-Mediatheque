<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211018202339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE emprunt_old (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, livre_id INT DEFAULT NULL, emprunter_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', rendre_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_rendu TINYINT(1) NOT NULL, num_emprunt VARCHAR(30) DEFAULT NULL, UNIQUE INDEX UNIQ_A34594C4A76ED395 (user_id), UNIQUE INDEX UNIQ_A34594C437D925CB (livre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation_livre (reservation_id INT NOT NULL, livre_id INT NOT NULL, INDEX IDX_EF1C9F3EB83297E7 (reservation_id), INDEX IDX_EF1C9F3E37D925CB (livre_id), PRIMARY KEY(reservation_id, livre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE emprunt_old ADD CONSTRAINT FK_A34594C4A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE emprunt_old ADD CONSTRAINT FK_A34594C437D925CB FOREIGN KEY (livre_id) REFERENCES livre (id)');
        $this->addSql('ALTER TABLE reservation_livre ADD CONSTRAINT FK_EF1C9F3EB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation_livre ADD CONSTRAINT FK_EF1C9F3E37D925CB FOREIGN KEY (livre_id) REFERENCES livre (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE emprunt');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495537D925CB');
        $this->addSql('DROP INDEX IDX_42C8495537D925CB ON reservation');
        $this->addSql('ALTER TABLE reservation DROP livre_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE emprunt (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, livre_id INT DEFAULT NULL, emprunter_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', rendre_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_rendu TINYINT(1) NOT NULL, num_emprunt VARCHAR(30) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_364071D737D925CB (livre_id), UNIQUE INDEX UNIQ_364071D7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT FK_364071D737D925CB FOREIGN KEY (livre_id) REFERENCES livre (id)');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT FK_364071D7A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('DROP TABLE emprunt_old');
        $this->addSql('DROP TABLE reservation_livre');
        $this->addSql('ALTER TABLE reservation ADD livre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495537D925CB FOREIGN KEY (livre_id) REFERENCES livre (id)');
        $this->addSql('CREATE INDEX IDX_42C8495537D925CB ON reservation (livre_id)');
    }
}
