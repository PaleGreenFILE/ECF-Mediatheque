<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211021131800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Création du schéma complet de base de donné et insertion de donnée de base';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE livre (id INT AUTO_INCREMENT NOT NULL, genre_id INT DEFAULT NULL, titre VARCHAR(100) NOT NULL, file VARCHAR(255) DEFAULT NULL, parution DATE DEFAULT NULL, description LONGTEXT DEFAULT NULL, auteur VARCHAR(255) NOT NULL, quantite INT NOT NULL, isbn VARCHAR(13) DEFAULT NULL, pret INT DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_AC634F994296D31F (genre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, is_validate TINYINT(1) NOT NULL, is_restitue TINYINT(1) NOT NULL, emprunted_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(255) NOT NULL, INDEX IDX_42C84955A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE reservation_livre (reservation_id INT NOT NULL, livre_id INT NOT NULL, INDEX IDX_EF1C9F3EB83297E7 (reservation_id), INDEX IDX_EF1C9F3E37D925CB (livre_id), PRIMARY KEY(reservation_id, livre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, date_naissance DATE NOT NULL, adresse LONGTEXT NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', is_verified TINYINT(1) NOT NULL, is_autorise TINYINT(1) NOT NULL, auth_code VARCHAR(255) DEFAULT NULL, emprunt_max INT DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE livre ADD CONSTRAINT FK_AC634F994296D31F FOREIGN KEY (genre_id) REFERENCES genre (id)');

        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');

        $this->addSql('ALTER TABLE reservation_livre ADD CONSTRAINT FK_EF1C9F3EB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE reservation_livre ADD CONSTRAINT FK_EF1C9F3E37D925CB FOREIGN KEY (livre_id) REFERENCES livre (id) ON DELETE CASCADE');

        $this->addSql('SET FOREIGN_KEY_CHECKS = 0');

        // Inserer un admin
        $this->addSql("INSERT INTO users (nom, prenom, date_naissance, adresse, email, password, roles, is_verified, is_autorise, auth_code, emprunt_max) values('Briffard','Pascal','1985-02-20','7 rue du col blanc 59245 Recquignies','bridevproject@gmail.com','\$2y\$13\$TDoGH80tbKITua5pPWqSou.TyMSadWszqsQSnxO9W0/VSq5rC0cQK','[\"ROLE_ADMIN\"]',1 , 1, '123456',999)");

        // Inserer un Employé
        $this->addSql("INSERT INTO users (nom, prenom, date_naissance, adresse, email, password, roles, is_verified, is_autorise, auth_code, emprunt_max) values('Doe','John','1945-12-20','28 rue de l\'amnesie 59000 Lille','pascal@email.fr','\$2y\$13\$TDoGH80tbKITua5pPWqSou.TyMSadWszqsQSnxO9W0/VSq5rC0cQK', '[\"ROLE_LIBRAIRE\"]',1, 1, '123456', 999)");

        // Inserer un utilisateur autorisé
        $this->addSql("INSERT INTO users (nom, prenom, date_naissance, adresse, email, password, roles, is_verified, is_autorise, auth_code, emprunt_max) values('Valjean','Jean','1769-3-14','28 rue du Bagne 83000 Toulon','user1@email.fr','\$2y\$13\$TDoGH80tbKITua5pPWqSou.TyMSadWszqsQSnxO9W0/VSq5rC0cQK', '[\"ROLE_USER\"]',1, 1, '123456',5)");

        // Inserer un utilisateur non autorisé
        $this->addSql("INSERT INTO users (nom, prenom, date_naissance, adresse, email, password, roles, is_verified, is_autorise, auth_code, emprunt_max) values('Maurin','Pierre','1770-5-24','36 rue des voleurs de pains 83000 Toulon','user2@email.fr','\$2y\$13\$TDoGH80tbKITua5pPWqSou.TyMSadWszqsQSnxO9W0/VSq5rC0cQK', '[\"ROLE_USER\"]',0, 0, '',5)");

        // Inserer un genre
        $this->addSql("INSERT INTO genre (nom) values('fiction')");
        $this->addSql("INSERT INTO genre (nom) values('policier')");
        $this->addSql("INSERT INTO genre (nom) values('thriller')");
        $this->addSql("INSERT INTO genre (nom) values('enfants')");
        $this->addSql("INSERT INTO genre (nom) values('scientifique')");

        // Inserer un livre
        $this->addSql("INSERT INTO livre (genre_id, titre, file, parution, description, auteur, quantite, isbn, pret, updated_at) values('1','Harry Potter à l\'école des sorciers', '1.jpg', '1997-1-12', 'L\'intrigue du premier roman débute durant l\'été 1991. Peu avant son onzième anniversaire, Harry reçoit une lettre l\'invitant à se présenter lors de la rentrée des classes à l\'école de sorcellerie de Poudlard. Malgré les tentatives de son oncle et de sa tante pour l\'empêcher de s\'y rendre, Rubeus Hagrid, un « demi-géant » envoyé par le directeur de Poudlard, Albus Dumbledore5, va faire découvrir à Harry le monde des sorciers et l\'amener à se rendre à la gare de King\'s Cross de Londres, où il prendra le Poudlard Express qui le conduira jusqu\'à sa nouvelle école. Une fois à Poudlard, Harry apprend à maîtriser et utiliser les pouvoirs magiques qu\'il possède et se fait deux amis inséparables : Ronald Weasley et Hermione Granger. Le trio tente d\'empêcher Voldemort de s\'emparer de la pierre philosophale de Nicolas Flamel, conservée sous bonne garde à Poudlard.', 'J. K. Rowling', '4', '1234567890123', '0', '2021-10-21')");


        // Inserer un livre
        $this->addSql("INSERT INTO livre (genre_id, titre, file, parution, description, auteur, quantite, isbn, pret, updated_at) values('1','Harry Potter et la Chambre des secrets', '2.jpg', '1998-1-3', 'L\'année suivante, Harry et ses amis doivent faire face à une nouvelle menace à Poudlard. La fameuse Chambre des secrets, bâtie plusieurs siècles plus tôt par l\'un des fondateurs de l\'école, Salazar Serpentard, aurait été rouverte par son « héritier ». Cette Chambre, selon la légende, contiendrait un gigantesque monstre destiné à tuer les enfants sorciers nés de parents moldus acceptés à l\'école contre le souhait de Serpentard. Hermione, née de parents moldus, se retrouve elle aussi menacée. Harry, sachant parler le fourchelang, est accusé en premier lieu d\'être l\'héritier de Serpentard par la plupart des élèves, tandis que Ginny Weasley, la sœur de Ron, est curieusement manipulée par un journal intime ayant appartenu à un certain Tom Jedusor. Harry apprend par la suite que Jedusor et Voldemort sont une seule et même personne, et que Jedusor est le véritable héritier de Serpentard, agissant sur l\'école par le biais de ses souvenirs conservés dans son journal', 'J. K. Rowling', '8', '154985314972', '0', '2021-10-21')");


        // Inserer un livre
        $this->addSql("INSERT INTO livre (genre_id, titre, file, parution, description, auteur, quantite, isbn, pret, updated_at) values('1','Harry Potter et le Prisonnier d\'Azkaban', '3.jpg', '1999-04-23', 'A l\'été 1993, les sorciers, ainsi que les Moldus, sont informés de l\'évasion de prison d\'un dangereux criminel nommé Sirius Black. Un peu plus tard, Harry apprend que la motivation de Black est de le tuer afin de permettre à Voldemort, son maître, de retrouver l\'étendue de son pouvoir. Un important dispositif de sécurité est donc mis en place à Poudlard pour assurer la protection de Harry durant l\'année. En parallèle, celui-ci fait la connaissance de son nouveau professeur de défense contre les forces du mal, le professeur Lupin, un ancien ami de ses parents et dont il devient très proche. Harry utilise régulièrement la cape d\'invisibilité de son père ainsi que la carte du Maraudeur pour explorer les recoins méconnus du château et se rendre au village voisin de Pré-au-Lard, malgré son interdiction de quitter l\'école. En fin d\'année, Sirius Black parvient à attirer Harry, Ron et Hermione à l\'extérieur de l\'école et, en présence de Lupin qui vient les retrouver, leur explique les réelles motivations de son évasion : retrouver et tuer Peter Pettigrow, un sorcier qui se cache depuis douze ans sous l\'apparence du rat de compagnie de Ron. Selon Black, Pettigrow serait le responsable de la trahison de James et Lily Potter. Avant de mourir, ceux-ci avaient fait de Sirius Black leur témoin de mariage et le parrain de leur fils, Harry', 'J. K. Rowling', '3', '75469982347', '0', '2021-10-21')");

        // Inserer un livre
        $this->addSql("INSERT INTO livre (genre_id, titre, file, parution, description, auteur, quantite, isbn, pret, updated_at) values('1','Harry Potter et la Coupe de feu', '4.jpg', '2000-06-27', 'Dans l\'intrigue du quatrième roman, une édition du célèbre tournoi des Trois Sorciers se déroule exceptionnellement à Poudlard et deux autres délégations européennes se rendent sur place pour participer à la compétition : des élèves de l\'Académie de magie Beauxbâtons et ceux de l\'Institut Durmstrang. La Coupe de feu, juge impartiale chargée de sélectionner le champion de chaque école, choisit exceptionnellement deux champions pour Poudlard : Cedric Diggory et Harry Potter, ce dernier n\'ayant pourtant pas l\'âge requis pour participer à la compétition. Mais le règlement est strict et stipule que les organisateurs doivent obéir au choix de la Coupe de feu. Par conséquent, Harry se voit contraint de participer au tournoi, qui se déroule sur trois épreuves réparties sur l\’année. La première consiste à récupérer un œuf d\'or protégé par un dragon, la seconde à récupérer une personne aimée au fond du lac de Poudlard et la dernière, à progresser dans un labyrinthe à obstacles pour atteindre le trophée de la victoire dissimulé à l\'intérieur. Alors que Harry et Cedric saisissent le trophée en même temps, ils sont téléportés auprès de Peter Pettigrow. Après avoir tué Cedric Diggory, Pettigrow utilise le sang de Harry pour faire renaître Voldemort et ôter au garçon sa protection naturelle l\'ayant immunisé jusqu\'alors contre les pouvoirs du mage noir. Harry affronte Voldemort qui a repris forme humaine, mais parvient à lui échapper en attrapant une nouvelle fois le trophée qui le ramène à Poudlard. Convaincu par le récit de Harry, Dumbledore décide de reformer une ancienne organisation qui avait pris fin à la première chute de Voldemort, quinze ans plus tôt. Il fait alors appel à ses anciens membres, notamment Sirius Black, Remus Lupin, Severus Rogue, le professeur McGonagall et la famille Weasley', 'J. K. Rowling', '6', '45796423649', '0', '2021-10-21')");

        // Inserer un livre
        $this->addSql("INSERT INTO livre (genre_id, titre, file, parution, description, auteur, quantite, isbn, pret, updated_at) values('1','Harry Potter et l\'Ordre du Phénix', '5.jpg', '2003-05-09', 'Dans l\'intrigue du quatrième roman, une édition du célèbre tournoi des Trois Sorciers se déroule exceptionnellement à Poudlard et deux autres délégations européennes se rendent sur place pour participer à la compétition : des élèves de l\'Académie de magie Beauxbâtons et ceux de l\'Institut Durmstrang. La Coupe de feu, juge impartiale chargée de sélectionner le champion de chaque école, choisit exceptionnellement deux champions pour Poudlard : Cedric Diggory et Harry Potter, ce dernier n\'ayant pourtant pas l\'âge requis pour participer à la compétition. Mais le règlement est strict et stipule que les organisateurs doivent obéir au choix de la Coupe de feu. Par conséquent, Harry se voit contraint de participer au tournoi, qui se déroule sur trois épreuves réparties sur l\’année. La première consiste à récupérer un œuf d\'or protégé par un dragon, la seconde à récupérer une personne aimée au fond du lac de Poudlard et la dernière, à progresser dans un labyrinthe à obstacles pour atteindre le trophée de la victoire dissimulé à l\'intérieur. Alors que Harry et Cedric saisissent le trophée en même temps, ils sont téléportés auprès de Peter Pettigrow. Après avoir tué Cedric Diggory, Pettigrow utilise le sang de Harry pour faire renaître Voldemort et ôter au garçon sa protection naturelle l\'ayant immunisé jusqu\'alors contre les pouvoirs du mage noir. Harry affronte Voldemort qui a repris forme humaine, mais parvient à lui échapper en attrapant une nouvelle fois le trophée qui le ramène à Poudlard. Convaincu par le récit de Harry, Dumbledore décide de reformer une ancienne organisation qui avait pris fin à la première chute de Voldemort, quinze ans plus tôt. Il fait alors appel à ses anciens membres, notamment Sirius Black, Remus Lupin, Severus Rogue, le professeur McGonagall et la famille Weasley', 'J. K. Rowling', '2', '784563247924', '0', '2021-10-21')");

        // Inserer un livre
        $this->addSql("INSERT INTO livre (genre_id, titre, file, parution, description, auteur, quantite, isbn, pret, updated_at) values('1','Harry Potter et le Prince de sang-mêlé', '6.jpg', '2005-02-20', 'L\'intrigue de ce sixième roman se concentre davantage sur l\'histoire de Voldemort. Un passé que Harry et Dumbledore éclaircissent en visionnant les souvenirs des personnes ayant fréquenté le mage noir durant sa jeunesse. Ils apprennent ainsi l\'existence des horcruxes, des fragments d\'âmes de Voldemort que celui-ci aurait réparti en différents objets, et dont leur simple existence le rendrait immortel. En parallèle, durant ses cours de potions, Harry récupère un vieux manuel ayant appartenu à un certain « Prince de sang-mêlé ». Le livre regorge d\'une multitude de conseils et de notes ajoutés à la main par son ancien propriétaire (qui n\'était autre que Severus Rogue), et grâce auxquels Harry obtient d\'extraordinaires résultats. Par ailleurs, le professeur Rogue se voit chargé d\'une mission par Dumbledore, à l\'insu des autres membres de l\'Ordre (et du lecteur, qui n\'apprend ces détails qu\'à la fin du dernier roman). Le directeur sait que le jeune Drago Malefoy a été chargé par Voldemort de le tuer. Se sachant condamné depuis sa manipulation de l\'un des horcruxes, Dumbledore demande au professeur d\'intervenir à la place de Drago en temps voulu, permettant dans un même temps à Rogue de demeurer crédible aux yeux de Voldemort (qui pense l\'avoir enrôlé). Rogue engage sa parole auprès de Dumbledore, qui ne lui laisse pas le choix. Lorsque le directeur et Harry reviennent d\'une expédition avec un nouvel horcruxe, l\'école est attaquée par des mangemorts, que Drago Malefoy est parvenu à faire entrer. Se trouvant face à Dumbledore, Malefoy hésite. Dumbledore fait alors signe à Rogue, qui tue le directeur sous les yeux de Harry alors que ce dernier ignore tout de leur arrangement. Harry poursuit Rogue et les autres mangemorts en fuite avec acharnement. Rogue l\'empêche de combattre, puis disparaît, laissant Harry, les autres élèves et tout le personnel de Poudlard pleurer la perte du plus grand sorcier de sa génération et principal obstacle de Voldemort', 'J. K. Rowling', '5', '65248932403', '0', '2021-10-21')");

        // Inserer un livre
        $this->addSql("INSERT INTO livre (genre_id, titre, file, parution, description, auteur, quantite, isbn, pret, updated_at) values('1','Harry Potter et les Reliques de la Mort ', '7.jpg', '207-08-12', 'Harry, Ron et Hermione, âgés à présent de 17 ans, décident de ne pas retourner à Poudlard et de se consacrer entièrement à la recherche des horcruxes. Ils trouvent le médaillon de Serpentard au ministère de la Magie et apprennent que l\'épée de Gryffondor a permis à Dumbledore de briser la bague horcruxe des Gaunt l\'année précédente. Severus Rogue, par le biais de son patronus, guide Harry jusqu\'à la cachette de l\'épée et Ron s\'en sert pour détruire le médaillon. En parallèle, le trio apprend l\'existence de trois reliques très puissantes : la baguette de sureau (dont Voldemort serait déjà en possession), la pierre de Résurrection et la cape d\'invisibilité (dont Harry a hérité), faisant du sorcier qui les possède un « Maître de la mort ». Leur quête des horcruxes finit par les ramener à Poudlard où l\'un d\'eux est caché. Le trio retourne donc au château, très vite attaqué par Voldemort et ses partisans. Hermione détruit la Coupe horcruxe de Poufsouffle à l\'aide d\'un croc de Basilic et Harry trouve dans la Salle sur Demande le diadème horcruxe de Serdaigle, qui est également détruit. Remus Lupin, Tonks et Fred Weasley sont tués dans la bataille. En voulant s\'approcher du serpent de Voldemort (et dernier horcruxe), Harry, Ron et Hermione sont témoins de l\'attaque mortelle infligée à Severus Rogue. Avant de mourir, le professeur confie ses souvenirs à Harry, lui prouve son allégeance, son amour envers sa mère Lily Potter et lui montre la clé de sa victoire contre Voldemort : Harry doit mourir, car il constitue lui-même un horcruxe involontaire depuis le jour où Voldemort a tenté de le tuer alors qu\'il n\'était qu\'un bébé. Résigné, Harry se rend à Voldemort dans la forêt interdite. En utilisant la pierre de Résurrection qu\'il trouve à l\'intérieur du vif d\'or, il fait réapparaître brièvement ses parents, ainsi que Sirius Black et Remus Lupin, qui le soutiennent et le rassurent sur la perspective de la mort. Mais Harry, une nouvelle fois, survit au sortilège de Voldemort. En ayant utilisé le sang de Harry pour recréer son corps après le tournoi des Trois Sorciers, Voldemort aurait transféré en lui-même une partie du charme de protection que Lily Potter avait transmis à son fils. Par conséquent, tant que ce charme est présent dans le corps de Voldemort (tant qu\'il existe), Harry ne peut mourir. Neville Londubat tire l\'épée de Gryffondor du Choixpeau magique et s\'en sert pour décapiter le serpent. Tous les horcruxes à présent détruits, Voldemort redevient par conséquent un simple mortel. La rencontre des deux sortilèges de Harry et de Voldemort fait voler la baguette de sureau des mains du mage noir, la baguette refusant de tuer Harry, son maître légitime et le seul à avoir accepté la mort. Voldemort est tué par son propre maléfice. Un épilogue est consacré à l\'embarquement des enfants des trois héros, dix-neuf ans plus tard, à bord du Poudlard Expres', 'J. K. Rowling', '13', '2549200364784', '0', '2021-10-21')");

        $this->addSql('SET FOREIGN_KEY_CHECKS = 1');
    }


    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE livre DROP FOREIGN KEY FK_AC634F994296D31F');

        $this->addSql('ALTER TABLE reservation_livre DROP FOREIGN KEY FK_EF1C9F3E37D925CB');

        $this->addSql('ALTER TABLE reservation_livre DROP FOREIGN KEY FK_EF1C9F3EB83297E7');

        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');

        $this->addSql('DROP TABLE genre');

        $this->addSql('DROP TABLE livre');

        $this->addSql('DROP TABLE reservation');

        $this->addSql('DROP TABLE reservation_livre');

        $this->addSql('DROP TABLE users');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
