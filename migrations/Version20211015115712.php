<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211015115712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout de la table Emprunt';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE emprunt (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, livre_id INT DEFAULT NULL, emprunter_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', rendre_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_rendu TINYINT(1) NOT NULL, num_emprunt VARCHAR(30) DEFAULT NULL, UNIQUE INDEX UNIQ_364071D7A76ED395 (user_id), UNIQUE INDEX UNIQ_364071D737D925CB (livre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT FK_364071D7A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT FK_364071D737D925CB FOREIGN KEY (livre_id) REFERENCES livre (id)');
        $this->addSql('ALTER TABLE livre DROP exemplaire_de_pret');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE emprunt');
        $this->addSql('ALTER TABLE livre ADD exemplaire_de_pret VARCHAR(30) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
