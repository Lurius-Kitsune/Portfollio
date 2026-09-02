INSERT INTO
    hard_skill_type (id, name)
VALUES (1, 'Développement Web'),
    (2, 'Outils de production'),
    (3, 'Développement Applicatif'),
    (4, 'Moteur de jeux');

INSERT INTO
    hard_skill_type_translations (
        locale,
        object_class,
        field,
        foreign_key,
        content
    )
VALUES (
        'en',
        'App\Entity\HardSkillType',
        'name',
        1,
        'Web Development'
    ),
    (
        'en',
        'App\Entity\HardSkillType',
        'name',
        2,
        'Production Tools'
    ),
    (
        'en',
        'App\Entity\HardSkillType',
        'name',
        3,
        'Application Development'
    ),
    (
        'en',
        'App\Entity\HardSkillType',
        'name',
        4,
        'Game Engine'
    );

INSERT INTO
    hard_skill (id, name, type_id, image_url)
VALUES (
        1,
        'HTML',
        1,
        'https://uxwing.com/wp-content/themes/uxwing/download/brands-and-social-media/html-icon.svg'
    ),
    (
        2,
        'CSS',
        1,
        'https://uxwing.com/wp-content/themes/uxwing/download/brands-and-social-media/css-icon.svg'
    ),
    (
        3,
        'JavaScript',
        1,
        'https://uxwing.com/wp-content/themes/uxwing/download/brands-and-social-media/javascript-programming-language-icon.svg'
    ),
    (
        4,
        'PHP',
        1,
        'https://uxwing.com/wp-content/themes/uxwing/download/brands-and-social-media/php-programming-language-icon.svg'
    ),
    (
        5,
        'Symfony',
        1,
        'https://uxwing.com/wp-content/themes/uxwing/download/brands-and-social-media/symfony-icon.svg'
    ),
    (
        7,
        'Git',
        2,
        'https://uxwing.com/wp-content/themes/uxwing/download/brands-and-social-media/git-icon.svg'
    ),
    (
        8,
        'Github',
        2,
        'https://uxwing.com/wp-content/themes/uxwing/download/brands-and-social-media/github-icon.svg'
    ),
    (
        9,
        'Github Actions',
        2,
        'https://icon.icepanel.io/Technology/svg/GitHub-Actions.svg'
    ),
    (
        10,
        'Docker',
        2,
        'https://uxwing.com/wp-content/themes/uxwing/download/brands-and-social-media/docker-icon.svg'
    ),
    (
        11,
        'Trello',
        2,
        'https://uxwing.com/wp-content/themes/uxwing/download/brands-and-social-media/trello-logo-icon.svg'
    ),
    (
        12,
        'C#',
        3,
        'https://uxwing.com/wp-content/themes/uxwing/download/brands-and-social-media/c-sharp-programming-language-icon.svg'
    ),
    (
        13,
        'C++',
        3,
        'https://uxwing.com/wp-content/themes/uxwing/download/brands-and-social-media/c-plus-plus-programming-language-icon.svg'
    ),
    (
        14,
        'Python',
        3,
        'https://uxwing.com/wp-content/themes/uxwing/download/brands-and-social-media/python-programming-language-icon.svg'
    ),
    (
        15,
        'PostgreSQL',
        3,
        'https://uxwing.com/wp-content/themes/uxwing/download/brands-and-social-media/postgresql-icon.svg'
    ),
    (
        16,
        'Unity',
        4,
        'https://uxwing.com/wp-content/themes/uxwing/download/brands-and-social-media/unity-game-engine-icon.svg'
    ),
    (
        17,
        'Unreal Engine',
        4,
        'https://uxwing.com/wp-content/themes/uxwing/download/brands-and-social-media/unreal-engine-icon.svg'
    );

---- Project ----
INSERT INTO
    "project" (
        "name",
        "start_year",
        "end_date",
        "thumbnail_url",
        "link",
        "tags",
"type_id"
    )
VALUES (
        'Nelli the seer',
        '2024-05-01',
        '2024-07-10',
        'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/3801320/9b91717442da3e858592eed7ee81677d0d37dd45/header_french.jpg?t=1752015149',
        'https://store.steampowered.com/app/3801320/Nelli_The_Seer/',
        'C++,Unreal',
        2
    );
CREATE OR REPLACE FUNCTION generate_project_slug()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.slug IS NULL OR NEW.slug = '' THEN
        NEW.slug := LOWER(
            REGEXP_REPLACE(
                TRIM(NEW.name),
                '[^a-zA-Z0-9]+',
                '-',
                'g'
            )
        );
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER project_slug_trigger
BEFORE INSERT OR UPDATE OF name
ON project
FOR EACH ROW
EXECUTE FUNCTION generate_project_slug();