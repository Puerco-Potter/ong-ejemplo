<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180904160404 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comentario_debate DROP FOREIGN KEY FK_185AAF64DBC809B8');
        $this->addSql('ALTER TABLE tipo_debate_tematica DROP FOREIGN KEY FK_FEA18D0BDBC809B8');
        $this->addSql('CREATE TABLE debate (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, ong_id INT DEFAULT NULL, titulo VARCHAR(255) NOT NULL, fecha_creacion DATETIME DEFAULT NULL, descripcion LONGTEXT DEFAULT NULL, INDEX IDX_DDC4A368A76ED395 (user_id), INDEX IDX_DDC4A36851930698 (ong_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE debate_tematica (debate_id INT NOT NULL, tematica_id INT NOT NULL, INDEX IDX_28FBFD2C39A6B6F6 (debate_id), INDEX IDX_28FBFD2C500BB20C (tematica_id), PRIMARY KEY(debate_id, tematica_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE debate ADD CONSTRAINT FK_DDC4A368A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE debate ADD CONSTRAINT FK_DDC4A36851930698 FOREIGN KEY (ong_id) REFERENCES ong (id)');
        $this->addSql('ALTER TABLE debate_tematica ADD CONSTRAINT FK_28FBFD2C39A6B6F6 FOREIGN KEY (debate_id) REFERENCES debate (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE debate_tematica ADD CONSTRAINT FK_28FBFD2C500BB20C FOREIGN KEY (tematica_id) REFERENCES tematica (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE tipo_debate');
        $this->addSql('DROP TABLE tipo_debate_tematica');
        $this->addSql('DROP INDEX IDX_185AAF64DBC809B8 ON comentario_debate');
        $this->addSql('ALTER TABLE comentario_debate CHANGE tipo_debate_id debate_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comentario_debate ADD CONSTRAINT FK_185AAF6439A6B6F6 FOREIGN KEY (debate_id) REFERENCES debate (id)');
        $this->addSql('CREATE INDEX IDX_185AAF6439A6B6F6 ON comentario_debate (debate_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comentario_debate DROP FOREIGN KEY FK_185AAF6439A6B6F6');
        $this->addSql('ALTER TABLE debate_tematica DROP FOREIGN KEY FK_28FBFD2C39A6B6F6');
        $this->addSql('CREATE TABLE tipo_debate (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, ong_id INT DEFAULT NULL, titulo VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, fecha_creacion DATETIME DEFAULT NULL, fecha_finalizacion DATETIME DEFAULT NULL, descripcion LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_487D5570A76ED395 (user_id), INDEX IDX_487D557051930698 (ong_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tipo_debate_tematica (tipo_debate_id INT NOT NULL, tematica_id INT NOT NULL, INDEX IDX_FEA18D0BDBC809B8 (tipo_debate_id), INDEX IDX_FEA18D0B500BB20C (tematica_id), PRIMARY KEY(tipo_debate_id, tematica_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tipo_debate ADD CONSTRAINT FK_487D557051930698 FOREIGN KEY (ong_id) REFERENCES ong (id)');
        $this->addSql('ALTER TABLE tipo_debate ADD CONSTRAINT FK_487D5570A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE tipo_debate_tematica ADD CONSTRAINT FK_FEA18D0B500BB20C FOREIGN KEY (tematica_id) REFERENCES tematica (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tipo_debate_tematica ADD CONSTRAINT FK_FEA18D0BDBC809B8 FOREIGN KEY (tipo_debate_id) REFERENCES tipo_debate (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE debate');
        $this->addSql('DROP TABLE debate_tematica');
        $this->addSql('DROP INDEX IDX_185AAF6439A6B6F6 ON comentario_debate');
        $this->addSql('ALTER TABLE comentario_debate CHANGE debate_id tipo_debate_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comentario_debate ADD CONSTRAINT FK_185AAF64DBC809B8 FOREIGN KEY (tipo_debate_id) REFERENCES tipo_debate (id)');
        $this->addSql('CREATE INDEX IDX_185AAF64DBC809B8 ON comentario_debate (tipo_debate_id)');
    }
}
