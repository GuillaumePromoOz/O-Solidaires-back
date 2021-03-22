<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210322130133 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE proposition ADD user_id INT NOT NULL, ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE proposition ADD CONSTRAINT FK_C7CDC353A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE proposition ADD CONSTRAINT FK_C7CDC35312469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_C7CDC353A76ED395 ON proposition (user_id)');
        $this->addSql('CREATE INDEX IDX_C7CDC35312469DE2 ON proposition (category_id)');
        $this->addSql('ALTER TABLE request ADD user_id INT NOT NULL, ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9F12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_3B978F9FA76ED395 ON request (user_id)');
        $this->addSql('CREATE INDEX IDX_3B978F9F12469DE2 ON request (category_id)');
        $this->addSql('ALTER TABLE user ADD department_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649AE80F5DF ON user (department_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE proposition DROP FOREIGN KEY FK_C7CDC353A76ED395');
        $this->addSql('ALTER TABLE proposition DROP FOREIGN KEY FK_C7CDC35312469DE2');
        $this->addSql('DROP INDEX IDX_C7CDC353A76ED395 ON proposition');
        $this->addSql('DROP INDEX IDX_C7CDC35312469DE2 ON proposition');
        $this->addSql('ALTER TABLE proposition DROP user_id, DROP category_id');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9FA76ED395');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9F12469DE2');
        $this->addSql('DROP INDEX IDX_3B978F9FA76ED395 ON request');
        $this->addSql('DROP INDEX IDX_3B978F9F12469DE2 ON request');
        $this->addSql('ALTER TABLE request DROP user_id, DROP category_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649AE80F5DF');
        $this->addSql('DROP INDEX IDX_8D93D649AE80F5DF ON user');
        $this->addSql('ALTER TABLE user DROP department_id');
    }
}
