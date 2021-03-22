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
        'Jardinage',
        'Bricolage',
        'Courses',
        'Animaux',
        'Visite',
        'Cuisine',
    ];

    private $contents = [
        'Laisser le passage à un automobiliste qui semble pressé.',
        'Offrir un ticket de métro (ou d’autobus) à quelqu’un qui semble manquer d’argent.',
        'Aller chercher quelque chose (du lait par exemple) avant que la famille n’en manque ou ne le demande.',
        'Acheter un repas à une personne sans logis.',
        'Défendre quelqu’un qui est calomnié en son absence.',
        'Donner du sang à la croix rouge.',
        'Monter les bagages dans le train de quelqu’un qui peine.',
        'Ramasser et replacer une marchandise tombée d’un rayonnage dans un magasin.',
        'Placer en secret des fleurs sur la table.',
        'Conduire un auto-stoppeur jusqu’au seuil de sa destination.',
        'Téléphoner aux autorités pour signaler un objet dangereux sur la chaussée.',
        'Faire un don à un organisme de charité.',
        'Envoyer une carte d’anniversaire ou une carte de souhaits.',
        'Donner un cadeau sans raison particulière à l’un de nos proches.',
        'Protéger l’environnement : planter un arbre, faire du recyclage, réduire sa vitesse sur la route…',
        'Donner de son temps à la fondation bénévole de notre hôpital local.',
        'Garder les enfants d’un parent qui a besoin d’être dépanné.',
        'Être bénévole pour briser l’isolement des personnes âgées.',
        'Être entraîneur dans une organisation de sport pour les jeunes.',
        'Saluer les gens avec un sourire sincère.',
        'Organiser une fête pour célébrer l’anniversaire d’un ami.',
        'Aider un voisin âgé à faire son marché ou ses tâches ménagères.',
        'Nourrir ou adopter un animal abandonné.',
        'Écouter avec attention une personne qui raconte un événement important pour elle.',
        'Encourager une personne qui en a besoin.',
        'Conduire quelqu’un qui n’a pas de voiture.',
        'Faire des compliments sincères et respectueux, lorsque le moment s’y prête.',
        'Dans le métro ou l’autobus, offrir sa place assise à quelqu’un qui en a besoin.',
        'Se proposer pour guider une personne perdue devant un plan.',
        'Préparer le petit déjeuner avant que tout le monde ne se lève.',
        'Faire la vaisselle même si ce n’est pas à nous de la faire.',
        'Prendre dans ses bras un proche qui semble en avoir besoin.',
        'Orienter volontairement les conversations vers des sujets d’intérêts pour nos interlocuteurs.',
        'Cultiver des fleurs sur notre balcon ou devant notre maison.',
        'Remercier tous ceux qui nous rendent service.',
        'Dire quelque chose d’agréable à la personne qui nous sert dans un magasin, un restaurant ou autres commerces.',
        'Ramasser les déchets sur notre chemin (rue, trottoir, parc) pour les jeter dans un lieu adéquat (corbeille, poubelle).',
        'Envoyer un email à quelqu’un pour lui faire savoir que nous pensons à lui.',
        'Prendre l’initiative de la réconciliation, lorsque nous sommes en conflit avec quelqu’un.',
        'Être activiste dans une organisation bienfaitrice, Amnistie internationale par exemple : Algérie, Belgique, Canada, France, Mali, Maroc, Sénégal, Suisse et Tunisie.',
        'Donner du temps ou de l’argent à une organisation qui supporte la recherche en médecine, ex. recherche sur le cancer, le SIDA, les maladies du cœur…',
    ];

    private $titles= [
        'aller à l’université / au bureau',
        'aller à son cours de piano',
        'avoir un cours de français',
        'boire un thé',
        'déjeuner',
        'dîner',
        'étudier',
        'faire des courses',
        'faire du shopping',
        'faire du sport',
        'faire la cuisine',
        'faire la vaisselle',
        'faire le ménage',
        'faire ses devoirs',
        'faire une promenade',
        'jouer à des jeux vidéo',
        'jouer au foot',
        'lire toute la journée',
        'manger au restaurant',
        'monter une vidéo',
        'passer la journée en famille',
        'prendre le petit déjeuner',
        'prendre le train',
        'prendre un café',
        'prendre une photo',
        'préparer un gâteau',
        'promener son chien',
        'regarder la télé',
        'rendre visite à quelqu’un',
        'travailler',
        'visiter le musée du Louvre',
        'visiter un musée',
        'voir un match de foot',
    ];


    /**
     * 
     * Get a random departement
     */ 
    public function departmentName()
    {
        return $this->departments[array_rand($this->departments)];
    }

    /**
     * 
     * Get a random category
     */ 
    public function categoryName()
    {
        return $this->categories[array_rand($this->categories)];
    }

    /**
     * 
     * Get a random content
     */ 
    public function content()
    {
        return $this->contents[array_rand($this->contents)];
    }

    
    /**
     * 
     * Get a random title
     */ 
    public function title()
    {
        return $this->titles[array_rand($this->titles)];
    }


    /**
     * Get the value of departments
     */ 
    public function getDepartments()
    {
        return $this->departments;
    }
}
