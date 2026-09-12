<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260912212001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            "CREATE OR REPLACE FUNCTION generate_project_translation()
            RETURNS TRIGGER AS $$
            BEGIN
                INSERT INTO project_translations (locale, object_class, field, foreign_key)
                VALUES ('en', 'App\Entity\Project', 'intro', NEW.id);
                INSERT INTO project_translations (locale, object_class, field, foreign_key)
                VALUES ('en', 'App\Entity\Project', 'role', NEW.id);
                INSERT INTO project_translations (locale, object_class, field, foreign_key)
                VALUES ('en', 'App\Entity\Project', 'conclusionTitle', NEW.id);
                INSERT INTO project_translations (locale, object_class, field, foreign_key)
                VALUES ('en', 'App\Entity\Project', 'conclusionContent', NEW.id);
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;"
        );

        $this->addSql(
            "CREATE OR REPLACE FUNCTION generate_project_content_translation()
            RETURNS TRIGGER AS $$
            BEGIN
                INSERT INTO project_content_translations (locale, object_class, field, foreign_key)
                VALUES ('en', 'App\Entity\ProjectContent', 'title', NEW.id);
                INSERT INTO project_content_translations (locale, object_class, field, foreign_key)
                VALUES ('en', 'App\Entity\Project', 'content', NEW.id);
                INSERT INTO project_content_translations (locale, object_class, field, foreign_key)
                VALUES ('en', 'App\Entity\Project', 'themeName', NEW.id);
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;"
        );

        $this->addSql(
            "CREATE TRIGGER project_content_translation_trigger
            AFTER INSERT
            ON project_content
            FOR EACH ROW
            EXECUTE FUNCTION generate_project_content_translation();"
        );

        $this->addSql(
            "CREATE TRIGGER project_translation_trigger
            AFTER INSERT
            ON project
            FOR EACH ROW
            EXECUTE FUNCTION generate_project_translation();"
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(
            'DROP TRIGGER IF EXISTS project_content_translation_trigger ON project_content'
        );

        $this->addSql(
            'DROP TRIGGER IF EXISTS project_translation_trigger ON project'
        );

        $this->addSql(
            'DROP FUNCTION IF EXISTS generate_project_content_translation()'
        );

        $this->addSql(
            'DROP FUNCTION IF EXISTS generate_project_translation()'
        );
    }
}
