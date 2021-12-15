<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211215165813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE departement (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job (id INT AUTO_INCREMENT NOT NULL, departement_id INT NOT NULL, name VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_FBD8E0F8CCF9E01E (departement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, movie_id INT NOT NULL, job_id INT NOT NULL, person_id INT NOT NULL, INDEX IDX_C4E0A61F8F93B6FC (movie_id), INDEX IDX_C4E0A61FBE04EA9 (job_id), INDEX IDX_C4E0A61F217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F8CCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id)');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FBE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE movie ADD poster VARCHAR(255) DEFAULT NULL, ADD rating SMALLINT DEFAULT NULL, CHANGE release_date release_date DATE NOT NULL, CHANGE duration duration SMALLINT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job DROP FOREIGN KEY FK_FBD8E0F8CCF9E01E');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61FBE04EA9');
        $this->addSql('DROP TABLE departement');
        $this->addSql('DROP TABLE job');
        $this->addSql('DROP TABLE team');
        $this->addSql('ALTER TABLE movie DROP poster, DROP rating, CHANGE release_date release_date DATE DEFAULT NULL, CHANGE duration duration VARCHAR(8) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
