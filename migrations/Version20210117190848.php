<?php

declare (strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210117194730 extends AbstractMigration {
	public function getDescription(): string {
		return 'Created exchange_rate table';
	}

	public function up(Schema $schema): void{
		// this up() migration is auto-generated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

		$this->addSql('CREATE TABLE exchange_rate (id INT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, currency VARCHAR(10) NOT NULL, amount VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
	}

	public function down(Schema $schema): void{
		// this down() migration is auto-generated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

		$this->addSql('DROP TABLE exchange_rate');
	}
}
