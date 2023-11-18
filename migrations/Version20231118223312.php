<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231118223312 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE exercice (id INT AUTO_INCREMENT NOT NULL, createur_id INT NOT NULL, nom_exercice VARCHAR(255) NOT NULL, video VARCHAR(255) NOT NULL, INDEX IDX_E418C74D73A201E5 (createur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercice_seance_type (exercice_id INT NOT NULL, seance_type_id INT NOT NULL, INDEX IDX_6B0E591289D40298 (exercice_id), INDEX IDX_6B0E59121D3ED252 (seance_type_id), PRIMARY KEY(exercice_id, seance_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE objectif_seance (id INT AUTO_INCREMENT NOT NULL, semaine_id INT NOT NULL, jour_objectif INT NOT NULL, descriptif LONGTEXT NOT NULL, INDEX IDX_4379E796122EEC90 (semaine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE programme (id INT AUTO_INCREMENT NOT NULL, createur_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_3DDCB9FF73A201E5 (createur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE programme_utilisateur (programme_id INT NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_CB2DA86C62BB7AEE (programme_id), INDEX IDX_CB2DA86CFB88E14F (utilisateur_id), PRIMARY KEY(programme_id, utilisateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seance_type (id INT AUTO_INCREMENT NOT NULL, programme_id INT NOT NULL, createur_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, objectif VARCHAR(255) NOT NULL, descriptif LONGTEXT NOT NULL, duree TIME NOT NULL, INDEX IDX_404E75FA62BB7AEE (programme_id), INDEX IDX_404E75FA73A201E5 (createur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE semaine (id INT AUTO_INCREMENT NOT NULL, programme_id INT NOT NULL, utilisateur_id INT NOT NULL, date_debut DATE NOT NULL, INDEX IDX_7B4D8BEA62BB7AEE (programme_id), INDEX IDX_7B4D8BEAFB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE suivi_seance (id INT AUTO_INCREMENT NOT NULL, semaine_id INT NOT NULL, jour_seance INT NOT NULL, descriptif LONGTEXT NOT NULL, INDEX IDX_7DB9805C122EEC90 (semaine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, mail VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, poids DOUBLE PRECISION NOT NULL, taille INT NOT NULL, metabolisme VARCHAR(255) NOT NULL, is_coach TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE exercice ADD CONSTRAINT FK_E418C74D73A201E5 FOREIGN KEY (createur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE exercice_seance_type ADD CONSTRAINT FK_6B0E591289D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exercice_seance_type ADD CONSTRAINT FK_6B0E59121D3ED252 FOREIGN KEY (seance_type_id) REFERENCES seance_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE objectif_seance ADD CONSTRAINT FK_4379E796122EEC90 FOREIGN KEY (semaine_id) REFERENCES semaine (id)');
        $this->addSql('ALTER TABLE programme ADD CONSTRAINT FK_3DDCB9FF73A201E5 FOREIGN KEY (createur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE programme_utilisateur ADD CONSTRAINT FK_CB2DA86C62BB7AEE FOREIGN KEY (programme_id) REFERENCES programme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE programme_utilisateur ADD CONSTRAINT FK_CB2DA86CFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE seance_type ADD CONSTRAINT FK_404E75FA62BB7AEE FOREIGN KEY (programme_id) REFERENCES programme (id)');
        $this->addSql('ALTER TABLE seance_type ADD CONSTRAINT FK_404E75FA73A201E5 FOREIGN KEY (createur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE semaine ADD CONSTRAINT FK_7B4D8BEA62BB7AEE FOREIGN KEY (programme_id) REFERENCES programme (id)');
        $this->addSql('ALTER TABLE semaine ADD CONSTRAINT FK_7B4D8BEAFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE suivi_seance ADD CONSTRAINT FK_7DB9805C122EEC90 FOREIGN KEY (semaine_id) REFERENCES semaine (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exercice DROP FOREIGN KEY FK_E418C74D73A201E5');
        $this->addSql('ALTER TABLE exercice_seance_type DROP FOREIGN KEY FK_6B0E591289D40298');
        $this->addSql('ALTER TABLE exercice_seance_type DROP FOREIGN KEY FK_6B0E59121D3ED252');
        $this->addSql('ALTER TABLE objectif_seance DROP FOREIGN KEY FK_4379E796122EEC90');
        $this->addSql('ALTER TABLE programme DROP FOREIGN KEY FK_3DDCB9FF73A201E5');
        $this->addSql('ALTER TABLE programme_utilisateur DROP FOREIGN KEY FK_CB2DA86C62BB7AEE');
        $this->addSql('ALTER TABLE programme_utilisateur DROP FOREIGN KEY FK_CB2DA86CFB88E14F');
        $this->addSql('ALTER TABLE seance_type DROP FOREIGN KEY FK_404E75FA62BB7AEE');
        $this->addSql('ALTER TABLE seance_type DROP FOREIGN KEY FK_404E75FA73A201E5');
        $this->addSql('ALTER TABLE semaine DROP FOREIGN KEY FK_7B4D8BEA62BB7AEE');
        $this->addSql('ALTER TABLE semaine DROP FOREIGN KEY FK_7B4D8BEAFB88E14F');
        $this->addSql('ALTER TABLE suivi_seance DROP FOREIGN KEY FK_7DB9805C122EEC90');
        $this->addSql('DROP TABLE exercice');
        $this->addSql('DROP TABLE exercice_seance_type');
        $this->addSql('DROP TABLE objectif_seance');
        $this->addSql('DROP TABLE programme');
        $this->addSql('DROP TABLE programme_utilisateur');
        $this->addSql('DROP TABLE seance_type');
        $this->addSql('DROP TABLE semaine');
        $this->addSql('DROP TABLE suivi_seance');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
