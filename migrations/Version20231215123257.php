<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231215123257 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE picture ADD is_default TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91EA666E9DC');
        $this->addSql('DROP INDEX UNIQ_D8F0A91EA666E9DC ON trick');
        $this->addSql('ALTER TABLE trick DROP default_picture_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trick ADD default_picture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91EA666E9DC FOREIGN KEY (default_picture_id) REFERENCES picture (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D8F0A91EA666E9DC ON trick (default_picture_id)');
        $this->addSql('ALTER TABLE picture DROP is_default');
    }
}
