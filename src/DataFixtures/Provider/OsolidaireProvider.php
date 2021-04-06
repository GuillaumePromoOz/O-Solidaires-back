<?php

namespace App\DataFixtures\Provider;

class OsolidaireProvider
{
    private $departments = [
        'Ain (01)',
        'Aisne (02)',
        'Allier (03)',
        'Alpes-de-Haute-Provence (04)',
        'Hautes-Alpes (05)',
        'Alpes-Maritimes (06)',
        'Ardèche (07)',
        'Ardennes (08)',
        'Ariège (09)',
        'Aube (10)',
        'Aude (11)',
        'Aveyron (12)',
        'Bouches-du-Rhône (13)',
        'Calvados (14)',
        'Cantal (15)',
        'Charente (16)',
        'Charente-Maritime (17)',
        'Cher (18)',
        'Corrèze (19)',
        'Corse-du-Sud (2A)',
        'Haute-Corse (2B)',
        'Côte-d\'Or (21)',
        'Côtes d\'Armor (22)',
        'Creuse (23)',
        'Dordogne (24)',
        'Doubs (25)',
        'Drôme (26)',
        'Eure (27)',
        'Eure-et-Loir (28)',
        'Finistère (29)',
        'Gard (30)',
        'Haute-Garonne (31)',
        'Gers (32)',
        'Gironde (33)',
        'Hérault (34)',
        'Ille-et-Vilaine (35)',
        'Indre (36)',
        'Indre-et-Loire (37)',
        'Isère (38)',
        'Jura (39)',
        'Landes (40)',
        'Loir-et-Cher (41)',
        'Loire (42)',
        'Haute-Loire (43)',
        'Loire-Atlantique (44)',
        'Loiret (45)',
        'Lot (46)',
        'Lot-et-Garonne (47)',
        'Lozère (48)',
        'Maine-et-Loire (49)',
        'Manche (50)',
        'Marne (51)',
        'Haute-Marne (52)',
        'Mayenne (53)',
        'Meurthe-et-Moselle (54)',
        'Meuse (55)',
        'Morbihan (56)',
        'Moselle (57)',
        'Nièvre (58)',
        'Nord (59)',
        'Oise (60)',
        'Orne (61)',
        'Pas-de-Calais (62)',
        'Puy-de-Dôme (63)',
        'Pyrénées-Atlantiques (64)',
        'Hautes-Pyrénées (65)',
        'Pyrénées-Orientales (66)',
        'Bas-Rhin (67)',
        'Haut-Rhin (68)',
        'Rhône (69)',
        'Haute-Saône (70)',
        'Saône-et-Loire (71)',
        'Sarthe (72)',
        'Savoie (73)',
        'Haute-Savoie (74)',
        'Paris (75)',
        'Seine-Maritime (76)',
        'Seine-et-Marne (77)',
        'Yvelines (78)',
        'Deux-Sèvres (79)',
        'Somme (80)',
        'Tarn (81)',
        'Tarn-et-Garonne (82)',
        'Var (83)',
        'Vaucluse (84)',
        'Vendée (85)',
        'Vienne (86)',
        'Haute-Vienne (87)',
        'Vosges (88)',
        'Yonne (89)',
        'Territoire de Belfort (90)',
        'Essonne (91)',
        'Hauts-de-Seine (92)',
        'Seine-St-Denis (93)',
        'Val-de-Marne (94)',
        'Val-D\'Oise (95)',
        'Guadeloupe (971)',
        'Martinique (972)',
        'Guyane (973)',
        'La Réunion (974)',
        'Mayotte (976)',

    ];

    private $categories = [
        'Animaux',
        'Jardinage',
        'Bricolage',
        'Courses',
        'Visite',
        'Cuisine',
    ];

    private $beneficiaries = [
        1 => [
            'lastname' => 'Marty',
            'firstname' => 'Maryse',
            'bio' => 'À la retraite depuis 5 ans',
        ],
        2 => [
            'lastname' => 'Hernandez',
            'firstname' => 'Suzanne',
            'bio' => 'J\'adore les fleurs et les arbres',
        ],
        3 => [
            'lastname' => 'Roger',
            'firstname' => 'Valérie',
            'bio' => 'Ancienne prof de philo',
        ],
        4 => [
            'lastname' => 'Ribeiro',
            'firstname' => 'Rémy',
            'bio' => 'J\'aime beaucoup le cinéma et surtout les Rambos',
        ],
        5 => [
            'lastname' => 'Bouvet',
            'firstname' => 'Gérard',
            'bio' => 'Amateur d\'histoire et de culture',
        ],
        6 => [
            'lastname' => 'Schmitt',
            'firstname' => 'Thomas',
            'bio' => 'passionné de bricolage et des outils pas cher chez Lidl',
        ],
    ];

    private $volunteers = [
        1 => [
            'lastname' => 'Leblanc',
            'firstname' => 'Isabelle',
            'bio' => 'J\'ai deux chiens, trois chats et un hamster',
        ],
        2 => [
            'lastname' => 'Moreno',
            'firstname' => 'Marine',
            'bio' => 'Yoga, lecture, cuisine et dormir aussi !',
        ],
        3 => [
            'lastname' => 'Samson',
            'firstname' => 'Véronique',
            'bio' => 'Coach de vie',
        ],
        4 => [
            'lastname' => 'Charles',
            'firstname' => 'Daniel',
            'bio' => 'Animateur radio',
        ],
        5 => [
            'lastname' => 'Pages',
            'firstname' => 'François',
            'bio' => 'Metro, boulot, dodo mais toujours là quand on a besoin de moi',
        ],
        6 => [
            'lastname' => 'Vincent',
            'firstname' => 'Patrick',
            'bio' => 'Heureux papa de trois garçons et cinq filles',
        ],
    ];

    private $Requests = [
        1 => [
            'title' => 'Sortir mon chien',
            'content' => 'Etant alité pendant un mois j\'ai besoin que quelqu\'un fasse sortir mon chien une fois par jour',
            'category' => 'Animaux',
        ],
        2 => [
            'title' => 'Faire du jardinage',
            'content' => 'J\'ai besoin que quelqu`un vienne passer la tondeuse dans mon jardin une fois par mois. Tout le matériel nécessaire est disponible dans le garage.',
            'category' => 'Jardinage',
        ],
        3 => [
            'title' => 'Changer une ampoule',
            'content' => 'J\'ai besoin qu\'une personne vienne m\'aider à changer l\'ampoule du plafond de ma cuisine',
            'category' => 'Bricolage',
        ],
        4 => [
            'title' => 'Aide pour les courses',
            'content' => 'Ma voiture est en panne, si quelqu\'un peut me faire les courses cette semaine, ce serait gentil.',
            'category' => 'Courses',
        ],
        5 => [
            'title' => 'Visite',
            'content' => 'J\'ai du mal à marcher. Ayant besoin d\'une aide pour me déplacer, si une personne partage cette passion pour l\'histoire et la culture et souhaite m\'accompagner visiter un musée ou voir une exposition, merci de me laisser un petit message',
            'category' => 'Visite',
        ],
        6 => [
            'title' => 'Coup de main',
            'content' => 'Je construis une terrasse en bois et j\'ai besoin d\'une seconde paire de bras pour m\'aider à l\'installation',
            'category' => 'Bricolage',
        ],
    ];

    private $Propositions = [
        1 => [
            'title' => 'J\'aime les animaux',
            'content' => 'Si vous avez besoin d\'aide pour nourrir et/ou sortir vos animaux je suis disponible le jeudi et le vendredi après-midi',
            'category' => 'Animaux',
        ],
        2 => [
            'title' => 'Le jardinage ça me connait !',
            'content' => 'Etant une ancienne paysagiste à la retraite je me propose de vous aider dans vos travaux d\'espace vert',
            'category' => 'Jardinage',
        ],
        3 => [
            'title' => 'Problème éléctrique ?',
            'content' => 'Je ne suis pas electricienne de mêtier mais j\'adore ça, j\'ai d\'ailleur refais toute l\éléctricité de ma cuisine qui en avait franchement besoin (!!!). Si vous avez besoin d\'une intervention pour changer un luminaire, une prise, peu importe, même une ampoule ! N\'hésitez surtout pas à me contacter ce sera avec grand plaisir que je passerai soit en soirée après mon travail ou le weekend !',
            'category' => 'Bricolage',
        ],
        4 => [
            'title' => 'Bon appétit les amis !',
            'content' => 'Je me propose comme cuisinier à domicile (bon le mot est fort, vraiment ! Ha ha !) le mardi après-midi pour vous concocter des petits plats tout simple mais équilibrés et goûtu ! Je peux aussi vous faire quelques courses si vous avez du mal à vous déplacer ou un soucis quelconque, voiture en panne par exemple, ce sont des choses qui arrive on est là pour s\'entraider !',
            'category' => 'Cuisine',
        ],
        5 => [
            'title' => 'Culture',
            'content' => 'Avis au amateur de culture, mon épouse et moi-même organisons régulièrement des sorties en groupes, objectif : se cultiver! Si cela vous intéresse, on peux même venir vour récupèrer à votre domicile directement. Alors pourquoi s\'en priver ? Salutations.',
            'category' => 'Visite',
        ],
        6 => [
            'title' => 'Bricoleur du dimanche',
            'content' => 'Bonjour, je m\'appel Patrick et je suis bricoleur à mes heures perdues. Si vous avez besoin d\'aide pour refaire votre terrasse je suis l\'homme qu\'il vous faut. En plus j\'ai récemment investis dans divers outils de bonnes qualités que j\'ai acheté chez Lidl ! Tout le monde le dit, "Patrick c\'est un vrai bricolo et en plus il trouve toujours de bonnes affaires !"',
            'category' => 'Bricolage',
        ],
    ];


    /**
     * Get the value of departments
     */
    public function getDepartments()
    {
        return $this->departments;
    }

    /**
     * Get the value of categories
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Get the value of Propositions
     */
    public function getPropositions()
    {
        return $this->Propositions;
    }

    /**
     * Get the value of Requests
     */
    public function getRequests()
    {
        return $this->Requests;
    }

    /**
     * Get the value of volunteers
     */
    public function getVolunteers()
    {
        return $this->volunteers;
    }

    /**
     * Get the value of beneficiaries
     */
    public function getBeneficiaries()
    {
        return $this->beneficiaries;
    }
}
