<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200305082833 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D3BCF5E72D');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP INDEX IDX_161498D3BCF5E72D ON card');
        $this->addSql('ALTER TABLE card ADD attack INT NOT NULL, ADD cost INT NOT NULL, DROP attaque, DROP cout, CHANGE categorie_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D312469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_161498D312469DE2 ON card (category_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D312469DE2');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP INDEX IDX_161498D312469DE2 ON card');
        $this->addSql('ALTER TABLE card ADD attaque INT NOT NULL, ADD cout INT NOT NULL, DROP attack, DROP cost, CHANGE category_id categorie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D3BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_161498D3BCF5E72D ON card (categorie_id)');
    }
}
