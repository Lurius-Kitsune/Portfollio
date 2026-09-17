--- Cleane DB

TRUNCATE TABLE project_content,
project,
project_media,
project_content_project_media RESTART IDENTITY CASCADE;

TRUNCATE TABLE project_content_translations RESTART IDENTITY CASCADE;

TRUNCATE TABLE project_translations RESTART IDENTITY CASCADE;
--- La Poste
INSERT INTO project (
        "name",
        "start_year",
        "end_date",
        "thumbnail_url",
        "link",
        "tags",
"type_id",
"slug",
"role",
"intro",
"conclusion_title",
"conclusion_content"
    )
VALUES  (
        'Nelli the seer',
        '2025-05-01',
        '2025-07-10',
        'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/3801320/9b91717442da3e858592eed7ee81677d0d37dd45/header_french.jpg?t=1752015149',
        'https://store.steampowered.com/app/3801320/Nelli_The_Seer/',
        'C++,UI,Game,Unreal',
        2,
        'nelli-the-seer',
        'UI / Lead Développeur',
        'Nelli The Seer est un jeu réalisé par des étudiants de première année en école de jeux vidéo. Les étudiants avaient 14 semaines pour rendre un jeu vidéo d''action-aventure à la troisième personne sur un temple historique. Les étudiants en Game Design / Level Design ont commencé la préproduction en premier, avant d''être rejoints par l''équipe d''Environment Artists, d''Animateurs et de Character Riggers 3 semaines plus tard. L''équipe de programmeurs est entrée dans le projet 7 semaines après les Game Designers.',
        'Un premier projet, un début dans le monde professionnel',
        '[
  "J''ai particulièrement apprécié ce projet, et je suis heureux qu''avec l''équipe que nous étions, nous ayons pu sortir ce jeu sur Steam avec plus de 20 000 téléchargements ! De mon côté, j''ai beaucoup appris sur le développement UI, et cela m''a permis de mieux m''organiser dans la création de Widgets réutilisables et faciles d''accès pour les Game Designers.",
  "Je pense que ce projet m''a aussi permis d''apprendre beaucoup côté soft skills, en travaillant avec divers pôles d''un jeu vidéo. J''ai ainsi pu remarquer que la communication est importante, et qu''il ne faut pas hésiter à demander de l''aide, ou à en proposer à son équipe en cas de difficulté. Le côté lead n''a pas été facile à gérer non plus, car diriger une équipe n''était pas dans mes habitudes, aussi bien côté travail que relationnel ; mais je trouve que cela m''a permis de me renforcer et d''apprendre à comprendre, écouter et débattre."
]'
    ),
    (
        'Steam Achievement Unlocker',
        '2026-01-15',
        '2026-03-29',
        'https://cdn.fastly.steamstatic.com/store/home/store_home_share.jpg',
        'https://github.com/Lurius-Kitsune/UNREAL-TOOL-SteamAchievementEditor',
        'C++,UI,Tools,Steam API,Unreal',
        2,
        'steam-achievement-unlocker',
        'Développeur',
'Steam Achievement Unlocker est un outil d’édition pour Unreal Engine permettant de centraliser et de simplifier la gestion des succès Steam directement depuis l’éditeur Unreal Engine.

Il propose une interface permettant de créer, modifier et configurer facilement les succès, sans avoir à modifier manuellement les fichiers de configuration du projet.

Le projet est divisé en deux parties : une partie « Editor », qui permet de gérer les succès depuis l’éditeur Unreal Engine, et une partie « Runtime », qui s’occupe de leur fonctionnement directement pendant l’exécution du jeu.',
        'Un outils permettant de gagner du temps',
        '[
"À travers cet outil, j’ai pu répondre à un besoin apparu lors du développement de Nelli The Seer : intégrer des succès et s’assurer de leur bon fonctionnement. Cette problématique nous avait fait perdre du temps, car certains succès pouvaient ne pas s’activer correctement ou leur activation pouvait manquer de fiabilité.",
"Nous avons donc pu tester l’outil que j’ai développé et constater concrètement son impact. L’ajout de trois nouveaux succès au jeu a notamment pu être réalisé rapidement, tout en garantissant une meilleure fiabilité de leur activation. Nous avons ainsi décidé de transférer l’activation des anciens succès vers cet outil.",
"Avec la mise en place du projet sur GitHub, je souhaite continuer à le maintenir et à l’améliorer, notamment en apportant des corrections en fonction des retours que je pourrai recevoir. L’objectif est également de pouvoir réutiliser cet outil sur les futurs jeux que je développerai."
]'
    ),
    (
        'PORTAGE Scroll of the Undead',
        '2025-10-01',
        '2025-11-01',
'https://raw.githubusercontent.com/mataktelis/Simple-SFML-2D-Game/refs/heads/master/Resources/media/Textures/mylogot.png',
        'https://github.com/Lurius-Kitsune/OBJECTIF3D-UNREAL-PORTAGE-Scroll-of-the-Undead',
        'C++,2D,Unreal',
2,
        'portage-scroll-of-the-undead',
        'Développeur',
        'Il s’agit d’un portage du projet original Simple-SFML-2D-Game vers Unreal Engine, avec pour objectif de conserver sa logique de gameplay, sa structure et son architecture d’origine, tout en les enrichissant grâce aux capacités modernes d’Unreal Engine en matière de 3D, de rendu et d’outils de développement.  Ce portage vers Unreal Engine respecte strictement l’architecture du projet SFML original, notamment sa structure de classes, la séparation de sa logique ainsi que ses systèmes de mise à jour et de rendu. L’objectif est ainsi d’assurer une transposition fidèle du projet 2D développé avec SFML vers le système basé sur les composants d’Unreal Engine n''étant pas spécialement fait pour de la 2D.',
        'Une meilleure compréhension du code',
        '[
  "Je suis assez heureux du résultat que cela a pu donner. L’UI était très basique et donc relativement facile à reproduire. Toute la partie 2D a été très intéressante à réaliser, car même si Unreal Engine n’est pas principalement prévu pour la 2D, il est tout de même possible de faire beaucoup de choses avec ses outils.",
  "Le petit builder que j’ai créé m’a également permis de travailler ma réflexion et de chercher des solutions efficaces qui correspondent réellement aux besoins du projet.",
  "Cela m’a appris à ne pas forcément chercher la solution la plus complexe, mais plutôt celle qui permet de répondre simplement et efficacement au problème rencontré."
]   '
    ),
    (
        'Formation La poste',
        '2026-03-01',
        '2026-06-01',
        'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRO77YHNnTwg_446XtGHIUQG9VqQ5fO7hmvAzbBMl1a-K_A5ZtMfthTTfM&s=10',
        'https://www.linkedin.com/posts/santaezsaezcuritaeztravail-laposte-ugcPost-7447257936340455425-iHXG/?utm_source=share&utm_medium=member_desktop&rcm=ACoAAGP3VwcBC5j46txAf7VX7F60ZpaOPMh-gRc',
'C#,Serious Game,UI,Unity',
1,
'formation-la-poste',
'Développeur UI',
'C''est un serious game destiné à la formation et à la sensibilisation des employés, conçu pour soutenir les procédures de sécurité et les bonnes pratiques internes. Développé sous Unity et C#, le projet a été réalisé dans le respect des contraintes de production.

        J’ai travaillé au sein d’un pipeline de production en équipe, en contribuant au débogage, à l’intégration des fonctionnalités et à la mise au point du gameplay dans le respect de délais stricts.',
'Les jeux vidéo ne se résume pas qu''au divertissement',
'[
        "Forts de l’expérience acquise lors de nos précédents projets, nous avons également pu mettre en pratique et transmettre nos bonnes pratiques de gestion de versions avec Perforce (P4V). En tant qu’étudiants en deuxième année de programmation, nous avons notamment accompagné les étudiants de première année dans leur prise en main de Perforce, en les formant aux bonnes pratiques de synchronisation, de gestion des fichiers et de travail collaboratif sur un projet Unity.",
        "À travers ce projet, nous avons également pu comprendre que les technologies du jeu vidéo peuvent être utilisées dans de nombreux domaines, comme l’armée, le médical ou encore la formation. La simulation permet notamment de former des professionnels dans des situations proches du réel, tout en limitant les risques. Cela montre que les compétences utilisées pour créer un jeu vidéo peuvent aussi avoir des applications concrètes dans d’autres métiers."
        ]'
    ),
    (
        'Unreal CrashReporter',
        '2026-07-20',
        '2026-08-30',
'/images/unrealcrash.gif',
        'https://github.com/Lurius-Kitsune/Python-Unreal-CrashReporter',
        'Python,Tools,Unreal,Docker,Logs',
2, 'unreal-crashreporter', 'Développeur', NULL, NULL, NULL
    ),
    (
        'New game : CodeName FGTB',
        '2026-07-01',
NULL, '', NULL,
        'C++,UI,Unreal,Game',
2,
        'new-game-codename-fgtb',
        'Développeur UI',
        NULL,
        NULL,
        NULL
    );

INSERT INTO
    project_content (
        "theme_name",
        "title",
        "content",
        "project_id_id"
    )
VALUES (
        'Collectible',
        'Le menu collectible',
        '["Le menu des collectibles a pour difficulté l''intégration des données sauvegardées avec le jeu, ainsi que l''affichage et la maniabilité du collectible sélectionné. De plus, je me devais de prendre en compte l''utilisation de la manette, avec un système que j''avais pu mettre en place et améliorer avec mon collègue en UI, sans avoir le droit à aucun plugin.","Pour ce qui est de la mise en place, avec l''aide des programmeurs en GPE, nous avons mis en place un actor qui possède tous les mesh installés (afin de ne le faire qu''une seule fois). Celui-ci charge notre mesh via l''activation du bouton correspondant, après quoi une SceneCaptureComponent2D faisait le rendu sur une texture 2D placée au centre de mon UI. À savoir que ces mesh n''étaient activés que lorsqu''on ouvrait ce menu, évitant l''apparition même de cet objet pendant la partie."]',
        1
    ),
    (
        'Navigation',
        'L''UI du menu Pause',
        '[
  "Je tenais à présenter le prototype réalisé lors de la conception du menu pause. Le but était d''avoir une UI simple, intuitive et agréable pour les joueurs (avec l''aide des graphistes). Il fallait donc que l''espace ne soit pas trop encombré tout en restant agréable, d''où l''affichage du jeu en fond, comme on peut le voir dans divers jeux vidéo.",
  "Par la suite, nous avons aussi intégré un système développé par un de nos camarades permettant de changer les paramètres du jeu (touches et graphisme), que nous avons ensuite modifié afin d''ajouter les paramètres sonores et de nouveaux paramètres graphiques.",
  "✓ Navigation claire et intuitive",
  "✓ Feedback visuel des interactions"
  ]',
        1
    ),
    (
        'Manette et clavier',
        'Intégration manette dans l''UI & UX',
        '[
  "Cela fut le plus gros défi du projet. Sans l''utilisation de plugin, nous devions gérer la navigation à la manette sur notre UI. Il a donc fallu rendre notre UI capable de changer sa mise en forme à n''importe quel moment, afin de correspondre à l''input utilisé par le joueur. Côté UX, nous avons donc aussi ajouté des boutons qui apparaissent quand le joueur passe en manette, ainsi que d''autres permettant d''interagir durant la partie. À noter que ces boutons sont capables de changer selon les touches que le joueur aura configurées.",
  "Afin de réaliser cela, nous nous sommes inspirés du plugin CommonUI, qui facilite l''interaction de la manette avec l''UI. Il y a donc eu un système de détection automatique des inputs, qui envoyait un événement directement au HUD afin de signaler le changement d''input. Toutes nos UI héritaient de la même classe, nommée BaseWidget, contenant une fonction overridable appelée lorsque le type d''input changeait. Pour finir, les icônes étaient chargées en début de partie par un subsystem cherchant tous les assets rangés dans un dossier correspondant aux touches possibles d''une manette."
]',
        1
    ),
    (
        'API STEAM',
        'Subsysteme communiquant avec l''API Steam',
        '[
"L’une des principales difficultés rencontrées lors du développement de cet outil a été la communication avec l’API Steam, tout en veillant à ce que ces échanges restent transparents pour le joueur. Il a donc été nécessaire de mettre en place des appels asynchrones et, surtout, de comprendre le fonctionnement de la communication entre Steam et Unreal Engine.",
"J’ai notamment étudié des fonctionnalités telles que STEAM_CALLBACK, ainsi que la logique permettant d’attendre la réception des données provenant des serveurs Steam (QUERY) avant d’appliquer les statistiques ou les succès au compte du joueur. Pour cela, j’ai développé des fonctions asynchrones utilisables directement dans les Blueprints.",
"Un système de débogage a également été prévu afin d’identifier les éventuelles erreurs lors de l’activation d’un succès ou lors de l’exécution d’une requête (QUERY)."
]',
        2
    ),
    (
        'Data Driven',
        'Asynchrone & Data',
        '[
"Cette partie permet aux utilisateurs du code, qu’il s’agisse de Game Designers ou de développeurs, de prototyper rapidement des mécaniques permettant d’activer un succès. Elle repose sur UBlueprintAsyncActionBase, ce qui permet d’exposer ces fonctionnalités directement dans les Blueprints. Ces classes restent également accessibles et utilisables en C++.",
"Faciliter la mise en place des succès constitue un point important de ce projet. Des Data Assets contenant les Steam Achievement IDs ont donc été créés afin de simplifier leur configuration et leur utilisation. Ces Data Assets sont également compatibles avec les mêmes fonctionnalités en C++, laissant ainsi à l’utilisateur le choix d’utiliser un Data Asset ou de renseigner directement l’ID du succès."
]',
        2
    ),
    (
        'UI',
        'Interface du tools',
        '[
"Pour finir, cet outil nécessitait une interface graphique afin de rendre son utilisation plus intuitive et accessible à tous les utilisateurs.",
"Cette interface permet notamment de supprimer certaines étapes fastidieuses, comme la modification manuelle des fichiers de configuration (.ini) ou la recherche de ces fichiers dans l’explorateur. Elle permet également de vérifier si un succès est déjà présent dans le fichier de configuration. De plus, elle informe l’utilisateur lorsque certains Data Assets peuvent être manquants, notamment dans le cas où le projet contient déjà des succès configurés.",
"La réflexion derrière cette interface était avant tout de gagner du temps en simplifiant certaines actions pouvant être répétitives, voire longues et complexes, tout en proposant une expérience d’utilisation plus claire et efficace."
]',
        2
    ),
    (
        'Compréhension',
        'Lecture du code et mise en place',
        '[
  "Avant de coder, il m’a fallu lire le code du projet afin de comprendre son architecture et le fonctionnement de chaque composant. J’ai commencé par identifier le rôle des différentes classes, la logique du jeu ainsi que sa boucle principale.",
  "Comme le projet d’origine utilisait SFML, j’ai dû faire le lien entre son fonctionnement et celui d’Unreal Engine afin de déterminer quels seraient les équivalents dans Unreal. Cela m’a permis d’isoler les différents groupes de composants (entités, jeu, objets, etc.) et de pouvoir travailler progressivement sur chaque groupe."
]',
        3
    ),
    (
        '2D',
        'Mise en palce de la 2D',
        '[
"Pour la mise en place de la 2D, j’ai commencé par comprendre comment les éléments graphiques du projet original étaient gérés avec SFML. J’ai ensuite cherché leurs équivalents dans Unreal Engine, notamment avec les sprites et le plugin PaperFlipbook, afin de pouvoir reproduire le même fonctionnement.",

"J’ai donc travaillé sur l’intégration des sprites, leur affichage ainsi que leur positionnement dans le monde. J’ai également dû mettre en place les éléments nécessaires pour gérer les animations et les déplacements en 2D. Pour les collisions, une TileMap est construite afin de gérer les collisions avec le joueur, les entités et les objets.",
"L’objectif était de conserver au maximum le rendu et le fonctionnement du projet original tout en utilisant les outils proposés par Unreal Engine."

]',
        3
    ),
    (
        'Map',
        'Construction de la map',
        '[
"Pour la génération de la map, je voulais rester le plus fidèle possible à ce qui existait déjà dans le projet original. J’ai donc créé un MapBuilder qui va lire les chaînes de caractères présentes dans les fichiers `map.txt` du jeu, que j’ai ensuite intégrées dans des Data Assets, avec un Data Asset par map.",

"Le MapBuilder récupère ensuite ces Data Assets et se charge de faire la correspondance entre les sprites et les différentes tiles. Il permet ainsi de faire apparaître les ennemis, le joueur et les tiles aux bonnes positions.",

"Cette méthode me permet de générer automatiquement les maps à partir des données du jeu original et d’obtenir une map identique à celle présente dans le projet de départ."


]',
        3
    ),
    (
        'UI',
        'Le quizz room',
        '[
  "Dans ce Serious Game, je me suis occupé de toute la partie Quiz, allant de la création du système, avec un camarade de classe, jusqu’à la mise en place de l’interface graphique, tout en respectant les contraintes du cahier des charges.",
  "Le cahier des charges imposait trois types de quiz : le choix, les 7 différences et le lien. Le système et l’interface devaient donc pouvoir gérer ces trois modes. Pour cela, j’ai choisi de mettre en place une architecture basée sur l’héritage, avec une classe BaseQuizGame servant de base aux différents types de quiz.",
  "Le QuizPanelBehaviour permet ensuite d’enregistrer et de gérer ces trois modes. Les questions sont stockées dans des Data Assets avec leur type, grâce à un outil que nous avons également créé. Le Behaviour peut ainsi identifier automatiquement le type de quiz demandé et mettre en place le bon fonctionnement ainsi que l’affichage de la question."
]',
        4
    ),
    (
        'Fonctionnement',
        '7 Différence et lien',
        '[
  "Pour ces deux modes, j’ai dérivé de BaseQuizGame et redéfini les fonctions mises à disposition afin de développer leur comportement spécifique.",
  "Pour le quiz des 7 différences, deux Prefabs étaient attendus dans le Data Asset : un contenant l’image originale et un autre contenant les différences. Dans celui avec les différences, des GameObjects invisibles étaient placés aux endroits correspondants. Ils attendaient d’être pointés et de recevoir un input, ce qui était possible grâce à l’interface IPointerClickHandler. Une fois que l’utilisateur avait trouvé les différences, il pouvait confirmer sa réponse. Le Game remontait alors le résultat au QuizBehaviour, qui déterminait s’il fallait afficher un indice ou si la réponse était correcte.",
  "Pour le quiz « lien », j’ai créé un LineCreator permettant de tracer une ligne d’un point A vers un point B. L’utilisateur devait commencer le tracé sur une réponse d’un côté, puis relâcher la gâchette sur une réponse du côté opposé afin d’enregistrer la liaison. Beaucoup de tests ont été nécessaires pour gérer les différents cas possibles, notamment pour empêcher l’utilisateur de relier deux réponses appartenant à la même colonne."
]',
        4
    );

--
-- TOC entry 3499 (class 0 OID 49330)
-- Dependencies: 238
-- Data for Name: project_content_project_media; Type: TABLE DATA; Schema: public; Owner: pf_admin_83
--

INSERT INTO
    project_media ("type","url","project_id_id")
VALUES (
        1,
        '/video/nellithesseer/nelliCollectibleMenu.webm',
        1
    ),
    (
        1,
        '/video/nellithesseer/pauseMenu.webm',
        1
    ),
    (
        1,
        '/video/nellithesseer/keyboardToController.webm',
        1
    ),
    (
        0,
        '/images/project/nellitheseer/collectibleMenu.png',
        1
    ),
    (
        0,
        '/images/project/nellitheseer/fakeHover.png',
        1
    ),
    (
        1,
        '/video/nellithesseer/nelliTrailer.mp4',
        1
    ),
    (
        0,
        '/images/project/steamachievement/toolsUI.png',
        2
    ),
    (
        0,
        '/images/project/steamachievement/dataAssets.png',
        2
    ),
    (
        0,
        '/images/project/steamachievement/code.png',
        2
    ),
    (
        0,
        'https://raw.githubusercontent.com/Lurius-Kitsune/OBJECTIF3D-UNREAL-PORTAGE-Scroll-of-the-Undead/refs/heads/master/gameplayScreen.png',
        3
    ),
    (
        1,
        '/video/sfmlscroll/map2.webm',
        3
    ),
    (
        1,
        '/video/sfmlscroll/map1.webm',
        3
    ),
    (
        0,
        '/images/project/sfmlscroll/tileMapEx.png',
        3
    ),
    (
        0,
        '/images/project/sfmlscroll/playerSheet.png',
        3
    ),
    (
        0,
        '/images/project/sfmlscroll/eyesSheet.png',
        3
    ),
    (
        0,
        '/images/project/laposte/script.png',
        4
    ),
    (
        1,
        '/video/laposte/quizDemo.webm',
        4
    ),
    (
        0, '/images/project/laposte/code.png', 4
    ),
    (
0, '/images/project/laposte/diff.png', 4 );

INSERT INTO
    public.project_content_project_media
VALUES (1, 1),
    (1, 4),
    (2, 2),
    (3, 3),
    (3, 5),
    (4, 9),
    (5, 8),
    (6, 7),
    (7, 10),
    (8, 13),
    (8, 14),
    (8, 15),
    (9, 11),
    (9, 12),
    (9, 13),
    (10, 17),
    (11, 18),
    (11, 19);
--
-- TOC entry 3496 (class 0 OID 40999)
-- Dependencies: 233
-- Data for Name: project_media; Type: TABLE DATA; Schema: public; Owner: pf_admin_83
--
SET session_replication_role = 'origin';