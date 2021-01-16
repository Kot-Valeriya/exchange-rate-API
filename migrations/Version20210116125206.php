<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210116125206 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE exchange_rate_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE exchange_rate (id INT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, currency VARCHAR(10) NOT NULL, buy_rate VARCHAR(255) NOT NULL, sell_rate VARCHAR(255) NOT NULL, central_rate VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE exchange_rate_id_seq CASCADE');
        $this->addSql('DROP TABLE exchange_rate');
    }
}
