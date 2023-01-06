<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230106110416 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce_produit DROP FOREIGN KEY FK_D3CA76478805AB2F');
        $this->addSql('ALTER TABLE annonce_produit DROP FOREIGN KEY FK_D3CA7647F347EFB');
        $this->addSql('DROP TABLE annonce_produit');
        $this->addSql('ALTER TABLE annonce ADD produit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_F65593E5F347EFB ON annonce (produit_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annonce_produit (annonce_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_D3CA7647F347EFB (produit_id), INDEX IDX_D3CA76478805AB2F (annonce_id), PRIMARY KEY(annonce_id, produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE annonce_produit ADD CONSTRAINT FK_D3CA76478805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE annonce_produit ADD CONSTRAINT FK_D3CA7647F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5F347EFB');
        $this->addSql('DROP INDEX IDX_F65593E5F347EFB ON annonce');
        $this->addSql('ALTER TABLE annonce DROP produit_id');
    }
}
