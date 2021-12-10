<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211210163911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE casting DROP FOREIGN KEY FK_D11BBA5010684CB');
        $this->addSql('DROP INDEX IDX_D11BBA5010684CB ON casting');
        $this->addSql('ALTER TABLE casting CHANGE movie_id_id movie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE casting ADD CONSTRAINT FK_D11BBA508F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('CREATE INDEX IDX_D11BBA508F93B6FC ON casting (movie_id)');
        $this->addSql('ALTER TABLE movie CHANGE release_date release_date DATE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE casting DROP FOREIGN KEY FK_D11BBA508F93B6FC');
        $this->addSql('DROP INDEX IDX_D11BBA508F93B6FC ON casting');
        $this->addSql('ALTER TABLE casting CHANGE movie_id movie_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE casting ADD CONSTRAINT FK_D11BBA5010684CB FOREIGN KEY (movie_id_id) REFERENCES movie (id)');
        $this->addSql('CREATE INDEX IDX_D11BBA5010684CB ON casting (movie_id_id)');
        $this->addSql('ALTER TABLE movie CHANGE release_date release_date DATE NOT NULL');
    }
}
