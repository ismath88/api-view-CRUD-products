<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220111 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE users ADD company_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD firstname VARCHAR(30) NOT NULL');
        $this->addSql('ALTER TABLE users ADD lastname VARCHAR(30) NOT NULL');
        $this->addSql('ALTER TABLE users ADD mobile VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE users ADD valid_from TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE users ADD status VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE users DROP email');
        $this->addSql('ALTER TABLE users DROP roles');
        $this->addSql('ALTER TABLE users ALTER password SET NOT NULL');
        $this->addSql('ALTER TABLE users ALTER username SET NOT NULL');
        $this->addSql('ALTER TABLE users ALTER username TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE users ADD mobile_user BOOLEAN NOT NULL DEFAULT FALSE');
        $this->addSql('ALTER TABLE users ADD web_user BOOLEAN NOT NULL DEFAULT TRUE');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9F85E0677 ON users (username)');
        $this->addSql('CREATE INDEX IDX_1483A5E9979B1AD6 ON users (company_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E9979B1AD6');
        $this->addSql('DROP INDEX UNIQ_1483A5E9F85E0677');
        $this->addSql('DROP INDEX IDX_1483A5E9979B1AD6');
        $this->addSql('ALTER TABLE users ADD email VARCHAR(254) DEFAULT NULL');
        $this->addSql('ALTER TABLE users DROP company_id');
        $this->addSql('ALTER TABLE users DROP firstname');
        $this->addSql('ALTER TABLE users DROP lastname');
        $this->addSql('ALTER TABLE users DROP mobile');
        $this->addSql('ALTER TABLE users DROP valid_from');
        $this->addSql('ALTER TABLE users DROP status');
        $this->addSql('ALTER TABLE users ALTER username DROP NOT NULL');
        $this->addSql('ALTER TABLE users ALTER username TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE users DROP mobile_user');
        $this->addSql('ALTER TABLE users DROP web_user');
        $this->addSql('ALTER TABLE users ALTER password DROP NOT NULL');
    }
}
