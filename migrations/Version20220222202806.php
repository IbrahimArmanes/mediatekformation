<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220222202806 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE formation_niveau (formation_id INT NOT NULL, niveau_id INT NOT NULL, INDEX IDX_6BC6FD465200282E (formation_id), INDEX IDX_6BC6FD46B3E9C81 (niveau_id), PRIMARY KEY(formation_id, niveau_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE formation_niveau ADD CONSTRAINT FK_6BC6FD465200282E FOREIGN KEY (formation_id) REFERENCES formation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formation_niveau ADD CONSTRAINT FK_6BC6FD46B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY formation_ibfk_1');
        $this->addSql('DROP INDEX ForeignKey_Name ON formation');
        $this->addSql('ALTER TABLE formation CHANGE id_niveau id_niveau_name_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BF2C659BA6 FOREIGN KEY (id_niveau_name_id) REFERENCES niveau (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_404021BF2C659BA6 ON formation (id_niveau_name_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE formation_niveau');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BF2C659BA6');
        $this->addSql('DROP INDEX UNIQ_404021BF2C659BA6 ON formation');
        $this->addSql('ALTER TABLE formation CHANGE id_niveau_name_id id_niveau INT DEFAULT NULL');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT formation_ibfk_1 FOREIGN KEY (id_niveau) REFERENCES niveau (id)');
        $this->addSql('CREATE INDEX ForeignKey_Name ON formation (id_niveau)');
    }
}
