<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211005093025 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Mise en place de la double authentification et ajout admin';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD auth_code VARCHAR(255) DEFAULT NULL');

        // Créer un admin
        $this->addSql("INSERT INTO user (email, password, roles, is_verified, auth_code) values ('admin@studi.fr', '\$2y$13\$rrV9Cm9tDjOWpmztCZP1gOhu0s6Ce9V8rDPF8uW1Yo.dQHZ1uafrO', '[\"ROLE_ADMIN\"]', 1, 407642)");

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP auth_code');
    }
}
