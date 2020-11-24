<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201124194311 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE basket_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE basket_product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE basket (id INT NOT NULL, label VARCHAR(64) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE basket_product (id INT NOT NULL, basket_id INT NOT NULL, product_id INT NOT NULL, amount INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_17ED14B41BE1FB52 ON basket_product (basket_id)');
        $this->addSql('CREATE INDEX IDX_17ED14B44584665A ON basket_product (product_id)');
        $this->addSql('CREATE TABLE product (id INT NOT NULL, name VARCHAR(64) NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE basket_product ADD CONSTRAINT FK_17ED14B41BE1FB52 FOREIGN KEY (basket_id) REFERENCES basket (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE basket_product ADD CONSTRAINT FK_17ED14B44584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE basket_product DROP CONSTRAINT FK_17ED14B41BE1FB52');
        $this->addSql('ALTER TABLE basket_product DROP CONSTRAINT FK_17ED14B44584665A');
        $this->addSql('DROP SEQUENCE basket_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE basket_product_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_id_seq CASCADE');
        $this->addSql('DROP TABLE basket');
        $this->addSql('DROP TABLE basket_product');
        $this->addSql('DROP TABLE product');
    }
}
