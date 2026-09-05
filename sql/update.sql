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
--- Nelli The seer ----
INSERT INTO
    "project" (
        "name",
        "start_year",
        "end_date",
        "thumbnail_url",
        "link",
        "tags",
        "type_id",
        "slug",
        "content"
    )
VALUES (
        'Nelli the seer',
        '2024-05-01',
        '2024-07-10',
        'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/3801320/9b91717442da3e858592eed7ee81677d0d37dd45/header_french.jpg?t=1752015149',
        'https://store.steampowered.com/app/3801320/Nelli_The_Seer/',
        'C++,Unreal',
        2,
        'nelli-the-seer',
        '<div

	class="bg-gray-950 text-white">
	<section class="relative overflow-hidden">
		<div class="mx-auto px-6 py-20 lg:px-8">
			<div class="mx-auto text-center">
				<span class="mb-4 inline-flex items-center rounded-full border border-gray-700 bg-gray-900 px-4 py-1.5 text-sm font-medium text-gray-300">
					UI / Lead Developer
				</span>
				<p class="mt-2 text-lg leading-8 text-gray-400">
					Nelli The Seer est un jeu réalisé par des étudiants de première année en école de jeux vidéo.
					Les étudiants avaient 14 semaines pour rendre un jeu vidéo d''action-aventure à la troisième personne sur un temple historique.
					Les étudiants en Game Design / Level Design ont commencé la préproduction en premier, avant d''être rejoints par l''équipe d''Environment Artists, d''Animateurs et de Character Riggers 3 semaines plus tard.
					L''équipe de programmeurs est entrée dans le projet 7 semaines après les Game Designers.
				</p>
			</div>

			<!-- Compétences -->
			<div class="mx-auto mt-16 grid max-w-5xl gap-6 md:grid-cols-2 lg:grid-cols-3">

				<div class="rounded-xl border border-gray-800 bg-gray-900 p-6">
					<div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-gray-800">
						🎨
					</div>
					<h3 class="font-semibold">Développement de l''Interface Utilisateur</h3>
					<p class="mt-2 text-sm text-gray-400">
						Intégration de l''interface utilisateur dans le jeu, avec prototypage et test d''adaptation des contrôles manette et clavier.
					</p>
				</div>

				<div class="rounded-xl border border-gray-800 bg-gray-900 p-6">
					<div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-gray-800">
						🎮
					</div>
					<h3 class="font-semibold">UX & Gameplay</h3>
					<p class="mt-2 text-sm text-gray-400">
						S''assurer que l''utilisateur ne soit pas surchargé par l''UI, tout en permettant à celle-ci d''accompagner le joueur.
					</p>
				</div>

				<div class="rounded-xl border border-gray-800 bg-gray-900 p-6">
					<div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-gray-800">
						🕹️
					</div>
					<h3 class="font-semibold">Adaptation Manette</h3>
					<p class="mt-2 text-sm text-gray-400">
						L''UI devait être capable de s''adapter à tout moment aux besoins de l''utilisateur, en passant du clavier à la manette et inversement.
					</p>
				</div>

			</div>
		</div>
	</section>


	<!-- SECTION 1 : MENU COLLECTIBLE -->
	<section class="border-t border-gray-800">
		<div class="mx-auto max-w-7xl px-6 py-20 lg:px-8">

			<div
				class="grid items-center gap-12 lg:grid-cols-2">

				<!-- Contenu -->
				<div>
					<span class="text-sm font-semibold uppercase tracking-widest text-gray-500">
						01 · Collectible
					</span>

					<h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">
						Le menu collectible
					</h2>

					<p class="mt-6 leading-7 text-gray-400">
						Le menu des collectibles a pour difficulté l''intégration des données sauvegardées avec le jeu, ainsi que l''affichage et la maniabilité du collectible sélectionné.
						De plus, je me devais de prendre en compte l''utilisation de la manette, avec un système que j''avais pu mettre en place et améliorer avec mon collègue en UI, sans avoir le droit à aucun plugin.
					</p>

					<p class="mt-4 leading-7 text-gray-400">
						Pour ce qui est de la mise en place, avec l''aide des programmeurs en GPE, nous avons mis en place un actor qui possède tous les mesh installés (afin de ne le faire qu''une seule fois).
						Celui-ci charge notre mesh via l''activation du bouton correspondant, après quoi une SceneCaptureComponent2D faisait le rendu sur une texture 2D placée au centre de mon UI.
						À savoir que ces mesh n''étaient activés que lorsqu''on ouvrait ce menu, évitant l''apparition même de cet objet pendant la partie.
					</p>
				</div>

				<!-- MEDIA -->
				<div class="overflow-hidden rounded-2xl border border-gray-800 bg-gray-900">
					<div class="flex aspect-video items-center justify-center">
						<video autoplay muted loop playsinline class="h-full w-full object-cover" src="/video/nellithesseer/nelliCollectibleMenu.webm"/>
					</div>
				</div>

			</div>
		</div>
	</section>


	<!-- SECTION 2 : MENU PAUSE -->
	<section class="border-t border-gray-800 bg-gray-900/30">
		<div class="mx-auto max-w-7xl px-6 py-20 lg:px-8">

			<div
				class="grid items-center gap-12 lg:grid-cols-2">

				<!-- MEDIA -->
				<div class="order-2 overflow-hidden rounded-2xl border border-gray-800 bg-gray-900 lg:order-1">
					<div class="flex aspect-video items-center justify-center">
						<video autoplay muted loop playsinline class="h-full w-full object-cover" src="/video/nellithesseer/pauseMenu.webm"/>
					</div>
				</div>

				<!-- Contenu -->
				<div class="order-1 lg:order-2">
					<span class="text-sm font-semibold uppercase tracking-widest text-gray-500">
						02 · Navigation
					</span>

					<h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">
						L''UI du menu Pause
					</h2>

					<p class="mt-6 leading-7 text-gray-400">
						Je tenais à présenter le prototype réalisé lors de la conception du menu pause. Le but était d''avoir une UI simple, intuitive et agréable pour les joueurs (avec l''aide des graphistes).
						Il fallait donc que l''espace ne soit pas trop encombré tout en restant agréable, d''où l''affichage du jeu en fond, comme on peut le voir dans divers jeux vidéo.
					</p>

					<p class="mt-4 leading-7 text-gray-400">
						Par la suite, nous avons aussi intégré un système développé par un de nos camarades permettant de changer les paramètres du jeu (touches et graphisme),
						que nous avons ensuite modifié afin d''ajouter les paramètres sonores et de nouveaux paramètres graphiques.
					</p>

					<ul class="mt-8 space-y-4 text-sm text-gray-400">
						<li class="flex gap-3">
							<span class="text-white">✓</span>
							Navigation claire et intuitive
						</li>
						<li class="flex gap-3">
							<span class="text-white">✓</span>
							Feedback visuel des interactions
						</li>
					</ul>
				</div>

			</div>
		</div>
	</section>


	<!-- SECTION 3 : MANETTE ET CLAVIER -->
	<section class="border-t border-gray-800">
		<div class="mx-auto max-w-7xl px-6 py-20 lg:px-8">

			<div class="grid items-center gap-12 lg:grid-cols-2">

				<div>
					<span class="text-sm font-semibold uppercase tracking-widest text-gray-500">
						03 · Manette et clavier
					</span>

					<h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">
						Intégration manette dans l''UI & UX
					</h2>

					<p class="mt-6 leading-7 text-gray-400">
						Cela fut le plus gros défi du projet. Sans l''utilisation de plugin, nous devions gérer la navigation à la manette sur notre UI.
						Il a donc fallu rendre notre UI capable de changer sa mise en forme à n''importe quel moment, afin de correspondre à l''input utilisé par le joueur.
						Côté UX, nous avons donc aussi ajouté des boutons qui apparaissent quand le joueur passe en manette, ainsi que d''autres permettant d''interagir durant la partie.
						À noter que ces boutons sont capables de changer selon les touches que le joueur aura configurées.
					</p>

					<p class="mt-4 leading-7 text-gray-400">
						Afin de réaliser cela, nous nous sommes inspirés du plugin CommonUI, qui facilite l''interaction de la manette avec l''UI.
						Il y a donc eu un système de détection automatique des inputs, qui envoyait un événement directement au HUD afin de signaler le changement d''input.
						Toutes nos UI héritaient de la même classe, nommée BaseWidget, contenant une fonction overridable appelée lorsque le type d''input changeait.
						Pour finir, les icônes étaient chargées en début de partie par un subsystem cherchant tous les assets rangés dans un dossier correspondant aux touches possibles d''une manette.
					</p>

				</div>

				<!-- MEDIA -->
				<div class="overflow-hidden rounded-2xl border border-gray-800 bg-gray-900">
					<div class="flex aspect-video items-center justify-center">
						<div class="text-center">
							<div class="text-5xl">🎮</div>
							<p class="mt-4 text-sm text-gray-500">
								Zone image / GIF / vidéo
							</p>
							<p class="mt-1 text-xs text-gray-600">
								Démonstration de l''utilisation de la manette
							</p>
						</div>
					</div>
				</div>

			</div>
		</div>
	</section>

	<!-- CONCLUSION -->
	<section class="border-t border-gray-800">
		<div class="mx-auto max-w-4xl px-6 py-24 text-center lg:px-8">

			<span class="text-sm font-semibold uppercase tracking-widest text-gray-500">
				Conclusion
			</span>

			<h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">
				Un premier projet, un début dans le monde professionnel
			</h2>

			<p class="mt-6 text-lg leading-8 text-gray-400">
				J''ai particulièrement apprécié ce projet, et je suis heureux qu''avec l''équipe que nous étions, nous ayons pu sortir ce jeu sur Steam avec plus de 20 000 téléchargements !
				De mon côté, j''ai beaucoup appris sur le développement UI, et cela m''a permis de mieux m''organiser dans la création de Widgets réutilisables et faciles d''accès pour les Game Designers.
			</p>

			<p class="mt-5 leading-7 text-gray-400">
				Je pense que ce projet m''a aussi permis d''apprendre beaucoup côté soft skills, en travaillant avec divers pôles d''un jeu vidéo.
				J''ai ainsi pu remarquer que la communication est importante, et qu''il ne faut pas hésiter à demander de l''aide, ou à en proposer à son équipe en cas de difficulté.
				Le côté lead n''a pas été facile à gérer non plus, car diriger une équipe n''était pas dans mes habitudes, aussi bien côté travail que relationnel ; mais je trouve que cela m''a permis de me renforcer et d''apprendre à comprendre, écouter et débattre.
			</p>

		</div>
	</section>

</div>'
    );

INSERT INTO
    "project_translations" (
        "locale",
        "object_class",
        "field",
        "foreign_key",
        "content"
    )
VALUES (
        'en',
        'App\Entity\Project',
        'content',
        '3',
        '<div class="bg-gray-950 text-white"> 	<section class="relative overflow-hidden"> 		<div class="mx-auto px-6 py-20 lg:px-8"> 			<div class="mx-auto text-center"> 				<span class="mb-4 inline-flex items-center rounded-full border border-gray-700 bg-gray-900 px-4 py-1.5 text-sm font-medium text-gray-300"> 					UI / Lead Developer 				</span> 				<p class="mt-2 text-lg leading-8 text-gray-400"> 					Nelli The Seer is a game made by first-year students at a video game school. 					Students had 14 weeks to deliver a third-person action-adventure game set in a historic temple. 					The Game Design / Level Design students started pre-production first, before being joined by the Environment Artists, Animators, and Character Riggers 3 weeks later. 					The programming team joined the project 7 weeks after the Game Designers. 				</p> 			</div>  			<!-- Skills --> 			<div class="mx-auto mt-16 grid max-w-5xl gap-6 md:grid-cols-2 lg:grid-cols-3">  				<div class="rounded-xl border border-gray-800 bg-gray-900 p-6"> 					<div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-gray-800"> 						🎨 					</div> 					<h3 class="font-semibold">User Interface Development</h3> 					<p class="mt-2 text-sm text-gray-400"> 						Integrating the user interface into the game, with prototyping and testing of controller and keyboard control adaptation. 					</p> 				</div>  				<div class="rounded-xl border border-gray-800 bg-gray-900 p-6"> 					<div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-gray-800"> 						🎮 					</div> 					<h3 class="font-semibold">UX & Gameplay</h3> 					<p class="mt-2 text-sm text-gray-400"> 						Making sure the player isn''t overwhelmed by the UI, while still letting it support them throughout the game. 					</p> 				</div>  				<div class="rounded-xl border border-gray-800 bg-gray-900 p-6"> 					<div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-gray-800"> 						🕹️ 					</div> 					<h3 class="font-semibold">Controller Adaptation</h3> 					<p class="mt-2 text-sm text-gray-400"> 						The UI had to be able to adapt at any moment to the user''s needs, switching from keyboard to controller and back. 					</p> 				</div>  			</div> 		</div> 	</section>   	<!-- SECTION 1: COLLECTIBLE MENU --> 	<section class="border-t border-gray-800"> 		<div class="mx-auto max-w-7xl px-6 py-20 lg:px-8">  			<div 				class="grid items-center gap-12 lg:grid-cols-2">  				<!-- Content --> 				<div> 					<span class="text-sm font-semibold uppercase tracking-widest text-gray-500"> 						01 · Collectible 					</span>  					<h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl"> 						The Collectible Menu 					</h2>  					<p class="mt-6 leading-7 text-gray-400"> 						The main challenge of the collectibles menu was integrating the game''s saved data, as well as handling the display and handling of the selected collectible. 						On top of that, I had to account for controller support, using a system I built and improved together with my UI colleague, without being allowed to use any plugins. 					</p>  					<p class="mt-4 leading-7 text-gray-400"> 						For the implementation, with help from the GPE programmers, we set up an actor that holds every installed mesh (so this only had to be done once). 						It loads our mesh when the corresponding button is activated, after which a SceneCaptureComponent2D renders it onto a 2D texture placed at the center of my UI. 						Note that these meshes were only activated while this menu was open, avoiding the object even appearing during gameplay. 					</p> 				</div>  				<!-- MEDIA --> 				<div class="overflow-hidden rounded-2xl border border-gray-800 bg-gray-900"> 					<div class="flex aspect-video items-center justify-center"> 						<video autoplay muted loop playsinline class="h-full w-full object-cover" src="/video/nellithesseer/nelliCollectibleMenu.webm"/> 					</div> 				</div>  			</div> 		</div> 	</section>   	<!-- SECTION 2: PAUSE MENU --> 	<section class="border-t border-gray-800 bg-gray-900/30"> 		<div class="mx-auto max-w-7xl px-6 py-20 lg:px-8">  			<div 				class="grid items-center gap-12 lg:grid-cols-2">  				<!-- MEDIA --> 				<div class="order-2 overflow-hidden rounded-2xl border border-gray-800 bg-gray-900 lg:order-1"> 					<div class="flex aspect-video items-center justify-center"> 						<video autoplay muted loop playsinline class="h-full w-full object-cover" src="/video/nellithesseer/pauseMenu.webm"/> 					</div> 				</div>  				<!-- Content --> 				<div class="order-1 lg:order-2"> 					<span class="text-sm font-semibold uppercase tracking-widest text-gray-500"> 						02 · Navigation 					</span>  					<h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl"> 						The Pause Menu UI 					</h2>  					<p class="mt-6 leading-7 text-gray-400"> 						I wanted to showcase the prototype made during the design of the pause menu. The goal was to have a UI that was simple, intuitive, and pleasant for players (with help from the artists). 						The layout needed to stay uncluttered while still feeling pleasant, hence displaying the game in the background, as can be seen in many video games. 					</p>  					<p class="mt-4 leading-7 text-gray-400"> 						We later also integrated a system built by one of our teammates that let us change the game''s settings (keybinds and graphics), 						which we then modified further to add sound settings and new graphics options. 					</p>  					<ul class="mt-8 space-y-4 text-sm text-gray-400"> 						<li class="flex gap-3"> 							<span class="text-white">✓</span> 							Clear and intuitive navigation 						</li> 						<li class="flex gap-3"> 							<span class="text-white">✓</span> 							Visual feedback on interactions 						</li> 					</ul> 				</div>  			</div> 		</div> 	</section>   	<!-- SECTION 3: CONTROLLER AND KEYBOARD --> 	<section class="border-t border-gray-800"> 		<div class="mx-auto max-w-7xl px-6 py-20 lg:px-8">  			<div class="grid items-center gap-12 lg:grid-cols-2">  				<div> 					<span class="text-sm font-semibold uppercase tracking-widest text-gray-500"> 						03 · Controller and keyboard 					</span>  					<h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl"> 						Controller Integration in the UI & UX 					</h2>  					<p class="mt-6 leading-7 text-gray-400"> 						This was by far the biggest challenge of the project. Without using any plugins, we had to build controller navigation for our UI ourselves. 						We had to make our UI able to change its layout at any moment to match whichever input the player was using. 						On the UX side, we also added buttons that appear when the player switches to controller, as well as others for interacting during gameplay. 						Note that these buttons can change depending on which keys the player has configured. 					</p>  					<p class="mt-4 leading-7 text-gray-400"> 						To pull this off, we drew inspiration from the CommonUI plugin, which makes it easier to handle controller interaction with the UI. 						This gave us an automatic input-detection system that sent an event straight to the HUD to signal an input change. 						All our UI widgets inherited from the same class, called BaseWidget, which contained an overridable function called whenever the input type changed. 						Finally, the icons were loaded at the start of the game by a subsystem that scanned for all assets stored in a folder matching the possible controller inputs. 					</p>  				</div>  				<!-- MEDIA --> 				<div class="overflow-hidden rounded-2xl border border-gray-800 bg-gray-900"> 					<div class="flex aspect-video items-center justify-center"> 						<div class="text-center"> 							<div class="text-5xl">🎮</div> 							<p class="mt-4 text-sm text-gray-500"> 								Image / GIF / video area 							</p> 							<p class="mt-1 text-xs text-gray-600"> 								Demonstration of controller usage 							</p> 						</div> 					</div> 				</div>  			</div> 		</div> 	</section>  	<!-- CONCLUSION --> 	<section class="border-t border-gray-800"> 		<div class="mx-auto max-w-4xl px-6 py-24 text-center lg:px-8">  			<span class="text-sm font-semibold uppercase tracking-widest text-gray-500"> 				Conclusion 			</span>  			<h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl"> 				A first project, a start in the professional world 			</h2>  			<p class="mt-6 text-lg leading-8 text-gray-400"> 				I really enjoyed this project, and I''m proud that, with the team we had, we managed to release this game on Steam with over 20,000 downloads! 				On my end, I learned a lot about UI development, and it helped me get better at organizing the creation of reusable widgets that are easy for Game Designers to use. 			</p>  			<p class="mt-5 leading-7 text-gray-400"> 				I think this project also taught me a lot on the soft-skills side, working alongside various departments of a video game team. 				I noticed just how important communication is, and that you shouldn''t hesitate to ask for help, or to offer it to your team when they run into difficulties. 				Being lead wasn''t easy either, since leading a team, both work-wise and interpersonally, wasn''t something I was used to — but I feel it helped me grow and learn to understand, listen, and debate. 			</p>  		</div> 	</section>  </div>'
    );