<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231118215057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exercice_seance_type DROP FOREIGN KEY FK_6B0E59121D3ED252');
        $this->addSql('ALTER TABLE exercice_seance_type DROP FOREIGN KEY FK_6B0E591289D40298');
        $this->addSql('ALTER TABLE programme_utilisateur DROP FOREIGN KEY FK_CB2DA86C62BB7AEE');
        $this->addSql('ALTER TABLE programme_utilisateur DROP FOREIGN KEY FK_CB2DA86CFB88E14F');
        $this->addSql('DROP TABLE exercice_seance_type');
        $this->addSql('DROP TABLE programme_utilisateur');
        $this->addSql('ALTER TABLE exercice DROP FOREIGN KEY FK_E418C74D73A201E5');
        $this->addSql('DROP INDEX IDX_E418C74D73A201E5 ON exercice');
        $this->addSql('ALTER TABLE exercice DROP createur_id');
        $this->addSql('ALTER TABLE objectif_seance DROP FOREIGN KEY FK_4379E796122EEC90');
        $this->addSql('DROP INDEX IDX_4379E796122EEC90 ON objectif_seance');
        $this->addSql('ALTER TABLE objectif_seance DROP semaine_id');
        $this->addSql('ALTER TABLE programme DROP FOREIGN KEY FK_3DDCB9FF73A201E5');
        $this->addSql('DROP INDEX IDX_3DDCB9FF73A201E5 ON programme');
        $this->addSql('ALTER TABLE programme DROP createur_id');
        $this->addSql('ALTER TABLE seance_type DROP FOREIGN KEY FK_404E75FA62BB7AEE');
        $this->addSql('ALTER TABLE seance_type DROP FOREIGN KEY FK_404E75FA73A201E5');
        $this->addSql('DROP INDEX IDX_404E75FA62BB7AEE ON seance_type');
        $this->addSql('DROP INDEX IDX_404E75FA73A201E5 ON seance_type');
        $this->addSql('ALTER TABLE seance_type DROP programme_id, DROP createur_id');
        $this->addSql('ALTER TABLE semaine DROP FOREIGN KEY FK_7B4D8BEAFB88E14F');
        $this->addSql('ALTER TABLE semaine DROP FOREIGN KEY FK_7B4D8BEA62BB7AEE');
        $this->addSql('DROP INDEX IDX_7B4D8BEA62BB7AEE ON semaine');
        $this->addSql('DROP INDEX IDX_7B4D8BEAFB88E14F ON semaine');
        $this->addSql('ALTER TABLE semaine DROP programme_id, DROP utilisateur_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE exercice_seance_type (exercice_id INT NOT NULL, seance_type_id INT NOT NULL, INDEX IDX_6B0E59121D3ED252 (seance_type_id), INDEX IDX_6B0E591289D40298 (exercice_id), PRIMARY KEY(exercice_id, seance_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE programme_utilisateur (programme_id INT NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_CB2DA86C62BB7AEE (programme_id), INDEX IDX_CB2DA86CFB88E14F (utilisateur_id), PRIMARY KEY(programme_id, utilisateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE exercice_seance_type ADD CONSTRAINT FK_6B0E59121D3ED252 FOREIGN KEY (seance_type_id) REFERENCES seance_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exercice_seance_type ADD CONSTRAINT FK_6B0E591289D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE programme_utilisateur ADD CONSTRAINT FK_CB2DA86C62BB7AEE FOREIGN KEY (programme_id) REFERENCES programme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE programme_utilisateur ADD CONSTRAINT FK_CB2DA86CFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exercice ADD createur_id INT NOT NULL');
        $this->addSql('ALTER TABLE exercice ADD CONSTRAINT FK_E418C74D73A201E5 FOREIGN KEY (createur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_E418C74D73A201E5 ON exercice (createur_id)');
        $this->addSql('ALTER TABLE objectif_seance ADD semaine_id INT NOT NULL');
        $this->addSql('ALTER TABLE objectif_seance ADD CONSTRAINT FK_4379E796122EEC90 FOREIGN KEY (semaine_id) REFERENCES semaine (id)');
        $this->addSql('CREATE INDEX IDX_4379E796122EEC90 ON objectif_seance (semaine_id)');
        $this->addSql('ALTER TABLE programme ADD createur_id INT NOT NULL');
        $this->addSql('ALTER TABLE programme ADD CONSTRAINT FK_3DDCB9FF73A201E5 FOREIGN KEY (createur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_3DDCB9FF73A201E5 ON programme (createur_id)');
        $this->addSql('ALTER TABLE seance_type ADD programme_id INT NOT NULL, ADD createur_id INT NOT NULL');
        $this->addSql('ALTER TABLE seance_type ADD CONSTRAINT FK_404E75FA62BB7AEE FOREIGN KEY (programme_id) REFERENCES programme (id)');
        $this->addSql('ALTER TABLE seance_type ADD CONSTRAINT FK_404E75FA73A201E5 FOREIGN KEY (createur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_404E75FA62BB7AEE ON seance_type (programme_id)');
        $this->addSql('CREATE INDEX IDX_404E75FA73A201E5 ON seance_type (createur_id)');
        $this->addSql('ALTER TABLE semaine ADD programme_id INT NOT NULL, ADD utilisateur_id INT NOT NULL');
        $this->addSql('ALTER TABLE semaine ADD CONSTRAINT FK_7B4D8BEAFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE semaine ADD CONSTRAINT FK_7B4D8BEA62BB7AEE FOREIGN KEY (programme_id) REFERENCES programme (id)');
        $this->addSql('CREATE INDEX IDX_7B4D8BEA62BB7AEE ON semaine (programme_id)');
        $this->addSql('CREATE INDEX IDX_7B4D8BEAFB88E14F ON semaine (utilisateur_id)');
    }
}
