<?php
if (!defined('ABSPATH')) exit;

/**
 * Per-category seed content (name, slug, a short "your work of..." phrase,
 * and the tradesperson label) sourced from ViteUnDevis's own category CSV.
 * Used only to give the bulk page generator a real starting point — pages
 * are still created as drafts so the actual written content should be
 * reviewed/expanded before publishing.
 */
function vud_get_category_seed_data() {
    return array(
    46 => array('nom' => 'Abattage d\'arbres', 'slug' => 'abattage-d-arbres', 'texte' => 'vos travaux d\'abattage', 'artisan' => 'élagueurs'),
    91 => array('nom' => 'Abris de jardin', 'slug' => 'abris-de-jardin', 'texte' => 'la création d\'un abris de jardin', 'artisan' => 'professionnels'),
    107 => array('nom' => 'Abris de piscine', 'slug' => 'abris-de-piscine', 'texte' => 'la création d\'un abris de piscine', 'artisan' => 'professionnels'),
    154 => array('nom' => 'Adoucisseur d\'eau', 'slug' => 'adoucisseur-d-eau', 'texte' => 'la pose d\'un adoucisseur d\'eau', 'artisan' => 'professionnels'),
    33 => array('nom' => 'Alarme', 'slug' => 'alarme', 'texte' => 'une fourniture ou pose d\'alarme', 'artisan' => 'installateurs'),
    34 => array('nom' => 'Alarme incendie', 'slug' => 'alarme-incendie', 'texte' => 'une fourniture ou pose d\'alarme incendie', 'artisan' => 'professionnels'),
    138 => array('nom' => 'Allées et chemins', 'slug' => 'allees-et-chemins', 'texte' => 'vos créations d\'allées et de chemins', 'artisan' => 'professionnels'),
    121 => array('nom' => 'Aménagement de placard', 'slug' => 'amenagement-de-placard', 'texte' => 'la création et l\'aménagement de placards', 'artisan' => 'professionnels'),
    75 => array('nom' => 'Aménagement des combles', 'slug' => 'amenagement-des-combles', 'texte' => 'l\'aménagement de vos combles', 'artisan' => 'artisans'),
    32 => array('nom' => 'Antenne TV', 'slug' => 'antenne-tv', 'texte' => 'une fourniture ou pose d\'antenne TV/TNT/Satellite', 'artisan' => 'poseurs'),
    1 => array('nom' => 'Architecte - construction de maison', 'slug' => 'architecte-construction-de-maison', 'texte' => 'vos travaux d\'architecture', 'artisan' => 'architectes'),
    78 => array('nom' => 'Architecture d\'intèrieur', 'slug' => 'architecture-d-interieur', 'texte' => 'vos travaux d\'architecture d\'intèrieur', 'artisan' => 'architectes'),
    48 => array('nom' => 'Arrosage automatique', 'slug' => 'arrosage-automatique', 'texte' => 'la création d\'un arrosage automatique', 'artisan' => 'jardiniers paysagistes'),
    134 => array('nom' => 'Ascenseur', 'slug' => 'ascenseur', 'texte' => 'la création d\'un ascenseur', 'artisan' => 'professionnels'),
    21 => array('nom' => 'Aspiration centralisée', 'slug' => 'aspiration-centralisee', 'texte' => 'une installation d\'aspiration centralisée', 'artisan' => 'installateurs'),
    172 => array('nom' => 'Assurance emprunteur', 'slug' => 'assurance-emprunteur', 'texte' => 'une assurance emprunteur', 'artisan' => 'assureurs'),
    56 => array('nom' => 'Audit d\'amiante / plomb', 'slug' => 'audit-d-amiante-plomb', 'texte' => 'un audit', 'artisan' => 'professionnels'),
    109 => array('nom' => 'Automatisme d\'éclairage', 'slug' => 'automatisme-d-eclairage', 'texte' => 'pour vos travaux d\'automatisme d\'éclairage', 'artisan' => 'électricien'),
    155 => array('nom' => 'Baignoire à porte', 'slug' => 'baignoire-a-porte', 'texte' => 'la pose d\'une baignoire à porte', 'artisan' => 'professionnels'),
    102 => array('nom' => 'Bardage', 'slug' => 'bardage', 'texte' => 'la réalisation d\'un bardage', 'artisan' => 'professionnels'),
    127 => array('nom' => 'Béton ciré', 'slug' => 'beton-cire', 'texte' => 'la réalisation d\'un béton ciré', 'artisan' => 'professionnels'),
    25 => array('nom' => 'Carrelage', 'slug' => 'carrelage', 'texte' => 'une fourniture ou pose de carrelage', 'artisan' => 'carreleurs'),
    146 => array('nom' => 'Changement de vitre', 'slug' => 'changement-de-vitre', 'texte' => 'le changement d\'un vitrage', 'artisan' => 'vitriers'),
    8 => array('nom' => 'Charpente', 'slug' => 'charpente', 'texte' => 'vos travaux de charpente', 'artisan' => 'charpentiers'),
    64 => array('nom' => 'Chaudière bois - granulés', 'slug' => 'chaudiere-bois-granules', 'texte' => 'une fourniture ou pose d\'une chaudière bois', 'artisan' => 'chauffagistes'),
    88 => array('nom' => 'Chaudière fioul', 'slug' => 'chaudiere-fioul', 'texte' => 'une fourniture ou pose de chaudière fioul', 'artisan' => 'professionnels'),
    65 => array('nom' => 'Chaudière gaz', 'slug' => 'chaudiere-gaz', 'texte' => 'une fourniture ou pose d\'une chaudière gaz', 'artisan' => 'chauffagistes'),
    156 => array('nom' => 'Chauffage', 'slug' => 'chauffage', 'texte' => 'la pose d\'un systeme de chauffage', 'artisan' => 'chauffagiste'),
    93 => array('nom' => 'Chauffage géothermique', 'slug' => 'chauffage-geothermique', 'texte' => 'une installation géothermique', 'artisan' => 'chauffagistes'),
    86 => array('nom' => 'Chauffage solaire', 'slug' => 'chauffage-solaire', 'texte' => 'une fourniture ou pose de chauffage solaire', 'artisan' => 'professionnels'),
    15 => array('nom' => 'Chauffage électrique', 'slug' => 'chauffage-electrique', 'texte' => 'une fourniture ou pose de chauffage électrique', 'artisan' => 'électriciens'),
    139 => array('nom' => 'Chauffe-eau', 'slug' => 'chauffe-eau', 'texte' => 'la pose d\'un chauffe-eau', 'artisan' => 'plombiers'),
    42 => array('nom' => 'Chauffe-eau solaire', 'slug' => 'chauffe-eau-solaire', 'texte' => 'une installation de chauffe eau solaire', 'artisan' => 'professionnels'),
    104 => array('nom' => 'Chauffe-eau thermodynamique', 'slug' => 'chauffe-eau-thermodynamique', 'texte' => 'la pose d\'un chauffe-eau thermodynamique', 'artisan' => 'plombiers'),
    130 => array('nom' => 'Chemin d\'accès', 'slug' => 'chemin-d-acces', 'texte' => 'la création d\'un chemin d\'acces', 'artisan' => 'professionnels'),
    18 => array('nom' => 'Cheminée', 'slug' => 'cheminee', 'texte' => 'la création d\'une cheminée', 'artisan' => 'cheministes'),
    19 => array('nom' => 'Climatisation', 'slug' => 'climatisation', 'texte' => 'une installation de climatisation', 'artisan' => 'professionnels'),
    11 => array('nom' => 'Cloison', 'slug' => 'cloison', 'texte' => 'vos travaux de cloison', 'artisan' => 'plaquistes'),
    94 => array('nom' => 'Clôture', 'slug' => 'cloture', 'texte' => 'une fourniture ou pose de cloture', 'artisan' => 'artisans'),
    4 => array('nom' => 'Constructeur de maisons', 'slug' => 'constructeur-de-maisons', 'texte' => 'la construction d\'une maison', 'artisan' => 'constructeurs'),
    6 => array('nom' => 'Construction', 'slug' => 'construction', 'texte' => 'vos travaux de construction', 'artisan' => 'entreprises du batiment'),
    132 => array('nom' => 'Construction garage', 'slug' => 'construction-garage', 'texte' => 'la construction d\'un garage', 'artisan' => 'entreprises du batiment'),
    80 => array('nom' => 'Couverture', 'slug' => 'pose-de-tuiles', 'texte' => 'vos travaux de pose de tuiles', 'artisan' => 'couvreurs'),
    76 => array('nom' => 'Création dressing', 'slug' => 'creation-dressing', 'texte' => 'la création d\'un dressing', 'artisan' => 'artisans'),
    22 => array('nom' => 'Cuisine', 'slug' => 'cuisine', 'texte' => 'la création ou l\'installation d\'une cuisine', 'artisan' => 'cuisinistes'),
    126 => array('nom' => 'Câblage informatique', 'slug' => 'cablage-informatique', 'texte' => 'la réalisation d\'un cablage informatique', 'artisan' => 'éléctriciens'),
    148 => array('nom' => 'DPE', 'slug' => 'dpe', 'texte' => 'un diagnostic de performance énergétique', 'artisan' => 'professionnels'),
    173 => array('nom' => 'Dommage ouvrage', 'slug' => 'dommage-ouvrage', 'texte' => 'une assurance dommage ouvrage', 'artisan' => 'assureurs'),
    69 => array('nom' => 'Domotique', 'slug' => 'domotique', 'texte' => 'une installation domotique', 'artisan' => 'électriciens'),
    160 => array('nom' => 'Douche sénior', 'slug' => 'douche-securisee', 'texte' => 'la création d\'une douche sécurisée', 'artisan' => 'artisans'),
    131 => array('nom' => 'Douche à l\'italienne', 'slug' => 'douche-a-l-italienne', 'texte' => 'la création d\'une douche à l\'italienne', 'artisan' => 'professionnels'),
    31 => array('nom' => 'Décorateur', 'slug' => 'decorateur', 'texte' => 'la décoration de votre intérieur', 'artisan' => 'décorateurs'),
    81 => array('nom' => 'Décrassage ou démoussage de toiture', 'slug' => 'decrassage-ou-demoussage-de-toiture', 'texte' => 'le démoussage de votre toiture', 'artisan' => 'professionnels'),
    123 => array('nom' => 'Démolition', 'slug' => 'demolition', 'texte' => 'pour la démolition complète d\'un batiment', 'artisan' => 'démolisseurs'),
    145 => array('nom' => 'Déménagement', 'slug' => 'demenagement', 'texte' => 'un déménagement', 'artisan' => 'déménageurs'),
    171 => array('nom' => 'Dépannage pompe à chaleur / climatisation', 'slug' => 'depannage-pompe-a-chaleur-climatisation', 'texte' => 'la réparation d\'une pompe à chaleur ou climatisation', 'artisan' => 'artisans'),
    122 => array('nom' => 'Ebéniste', 'slug' => 'ebeniste', 'texte' => 'des travaux d\'ébénisterie', 'artisan' => 'ébénistes'),
    125 => array('nom' => 'Eclairage', 'slug' => 'eclairage', 'texte' => 'la pose d\'éclairages intérieur ou extérieur', 'artisan' => 'électriciens'),
    90 => array('nom' => 'Elagage - taille d\'arbre', 'slug' => 'elagage-taille-d-arbre', 'texte' => 'vos travaux d\'élagage', 'artisan' => 'élagueurs'),
    13 => array('nom' => 'Electricité (Travaux électriques)', 'slug' => 'electricite', 'texte' => 'vos travaux électriques', 'artisan' => 'électriciens'),
    97 => array('nom' => 'Enrobée', 'slug' => 'enrobee', 'texte' => 'vos travaux d\'enrobée ou de groudronnage', 'artisan' => 'professionnels'),
    87 => array('nom' => 'Entretien chaudière', 'slug' => 'entretien-chaudiere', 'texte' => 'l\'entretien de votre chaudière', 'artisan' => 'professionnels'),
    54 => array('nom' => 'Entretien jardin', 'slug' => 'entretien-jardin', 'texte' => 'l\'entretien de votre jardin', 'artisan' => 'jardiniers'),
    55 => array('nom' => 'Entretien piscine', 'slug' => 'entretien-piscine', 'texte' => 'l\'entretien de votre piscine', 'artisan' => 'piscinistes'),
    162 => array('nom' => 'Entretien pompe à chaleur', 'slug' => 'entretien-pompe-a-chaleur', 'texte' => 'l\'entretien d\'une pompe à chaleur', 'artisan' => 'chauffagistes'),
    117 => array('nom' => 'Equipement piscine', 'slug' => 'equipement-piscine', 'texte' => 'la fourniture ou la pose de matériel pour votre piscine', 'artisan' => 'piscinistes'),
    16 => array('nom' => 'Escalier', 'slug' => 'escalier', 'texte' => 'la création d\'un escalier', 'artisan' => 'professionnels'),
    83 => array('nom' => 'Etanchéité toit terrasse', 'slug' => 'etancheite-toit-terrasse', 'texte' => 'vos travaux d\'étanchéité de toit terrasse', 'artisan' => 'professionnels'),
    163 => array('nom' => 'Etude de sol', 'slug' => 'etude-de-sol', 'texte' => 'une étude de sol', 'artisan' => 'experts'),
    105 => array('nom' => 'Etude thermique', 'slug' => 'etude-thermique', 'texte' => 'une étude thermique', 'artisan' => 'professionnels'),
    124 => array('nom' => 'Expert en bâtiment', 'slug' => 'expert-en-batiment', 'texte' => 'une expertise en batiment', 'artisan' => 'experts'),
    5 => array('nom' => 'Extension de maison', 'slug' => 'extension-de-maison', 'texte' => 'une extension de maison', 'artisan' => 'entreprises du batiment'),
    62 => array('nom' => 'Facades - enduits', 'slug' => 'facades-enduits', 'texte' => 'vos travaux de facade', 'artisan' => 'enduiseurs'),
    77 => array('nom' => 'Faux plafonds - plafonds tendus', 'slug' => 'faux-plafonds-plafonds-tendus', 'texte' => 'vos travaux de faux plafond', 'artisan' => 'professionnels'),
    72 => array('nom' => 'Fenêtre', 'slug' => 'fenetre', 'texte' => 'changer vos fenêtres', 'artisan' => 'menuisiers'),
    118 => array('nom' => 'Fondations', 'slug' => 'fondations', 'texte' => 'la réalisation de fondations', 'artisan' => 'maçons'),
    115 => array('nom' => 'Garde corps', 'slug' => 'garde-corps', 'texte' => 'la création d\'un garde corps', 'artisan' => 'professionnels'),
    151 => array('nom' => 'Gazon', 'slug' => 'gazon', 'texte' => 'la pose d\'un gazon', 'artisan' => 'jardiniers'),
    150 => array('nom' => 'Géomètre', 'slug' => 'geometre', 'texte' => 'relevé géométrique', 'artisan' => 'géomètres'),
    129 => array('nom' => 'Home cinéma', 'slug' => 'home-cinema', 'texte' => 'une installation home cinéma personnelle', 'artisan' => 'professionnels'),
    73 => array('nom' => 'Interphone', 'slug' => 'interphone', 'texte' => 'une fourniture ou pose d\'interphone', 'artisan' => 'électriciens'),
    12 => array('nom' => 'Isolation', 'slug' => 'isolation', 'texte' => 'vos travaux d\'isolation', 'artisan' => 'professionnels'),
    153 => array('nom' => 'Isolation des combles', 'slug' => 'isolation-des-combles', 'texte' => 'l\'isolation de vos combles', 'artisan' => 'professionnels'),
    103 => array('nom' => 'Isolation par l\'exterieur', 'slug' => 'isolation-par-l-exterieur', 'texte' => 'vos travaux d\'isolation exterieure', 'artisan' => 'professionnels'),
    157 => array('nom' => 'Isolation phonique', 'slug' => 'isolation-phonique', 'texte' => 'l\'isolation phonique d\'une piece', 'artisan' => 'artisans'),
    111 => array('nom' => 'Lambris', 'slug' => 'lambris', 'texte' => 'vos travaux de pose de lambris', 'artisan' => 'poseurs'),
    112 => array('nom' => 'Linos', 'slug' => 'linos', 'texte' => 'une fourniture ou pose de linoléum', 'artisan' => 'poseurs'),
    60 => array('nom' => 'Maison bois', 'slug' => 'constructeur-de-maisons-a-ossature-bois', 'texte' => 'la construction d\'une maison bois', 'artisan' => 'constructeurs bois'),
    7 => array('nom' => 'Maçonnerie', 'slug' => 'maconnerie', 'texte' => 'vos travaux de maçonnerie', 'artisan' => 'maçons'),
    2 => array('nom' => 'Maître d\'oeuvre', 'slug' => 'maitre-d-oeuvre', 'texte' => 'une maitrise d\'oeuvre en batiment', 'artisan' => 'maîtres d\'oeuvre'),
    10 => array('nom' => 'Menuiserie', 'slug' => 'menuiserie', 'texte' => 'vos travaux de menuiserie', 'artisan' => 'professionnels'),
    120 => array('nom' => 'Mezzanine', 'slug' => 'mezzanine', 'texte' => 'la création d\'une mezzanine', 'artisan' => 'professionnels'),
    106 => array('nom' => 'Micro station d\'épuration', 'slug' => 'micro-station-d-epuration', 'texte' => 'l\'installation d\'une micro station d\'épuration', 'artisan' => 'professionnels'),
    144 => array('nom' => 'Monte escalier', 'slug' => 'monte-escalier', 'texte' => 'pour la pose d\'un monte escalier', 'artisan' => 'professionnels'),
    113 => array('nom' => 'Moquette', 'slug' => 'moquette', 'texte' => 'une fourniture ou pose de moquette', 'artisan' => 'poseurs'),
    50 => array('nom' => 'Motorisation pour fermeture de portes et portails', 'slug' => 'motorisation-pour-fermeture-de-portes-et-portails', 'texte' => 'une fourniture ou pose de motorisation', 'artisan' => 'professionnels'),
    37 => array('nom' => 'Panneaux photovoltaïques', 'slug' => 'panneaux-photovoltaiques', 'texte' => 'une installation de panneaux solaires photovoltaïques', 'artisan' => 'installateurs'),
    26 => array('nom' => 'Parquet', 'slug' => 'parquet', 'texte' => 'une fourniture ou pose de parquet', 'artisan' => 'poseurs'),
    47 => array('nom' => 'Paysagiste', 'slug' => 'paysagiste', 'texte' => 'la création et la décoration d\'un jardin', 'artisan' => 'paysagistes'),
    28 => array('nom' => 'Peinture', 'slug' => 'peinture', 'texte' => 'vos travaux de peinture', 'artisan' => 'peintres'),
    133 => array('nom' => 'Pergola - carport', 'slug' => 'pergola', 'texte' => 'la création d\'une pergola', 'artisan' => 'installateurs'),
    3 => array('nom' => 'Permis de construire', 'slug' => 'permis-de-construire', 'texte' => 'la réalisation de votre permis de construire', 'artisan' => 'professionnels'),
    41 => array('nom' => 'Petites éoliennes', 'slug' => 'petites-eoliennes', 'texte' => 'une installation de petite éolienne', 'artisan' => 'professionnels'),
    44 => array('nom' => 'Piscine', 'slug' => 'piscine', 'texte' => 'la construction d\'une piscine', 'artisan' => 'piscinistes'),
    63 => array('nom' => 'Piscine coque', 'slug' => 'piscine-coque', 'texte' => 'une fourniture ou pose de piscine coque', 'artisan' => 'piscinistes'),
    159 => array('nom' => 'Piscine en dur', 'slug' => 'piscine-en-dur', 'texte' => 'une création de piscine traditionnelle', 'artisan' => 'piscinistes'),
    92 => array('nom' => 'Piscine en kit', 'slug' => 'piscine-en-kit', 'texte' => 'une fourniture ou pose de piscine en kit', 'artisan' => 'piscinistes'),
    79 => array('nom' => 'Plan de maison', 'slug' => 'plan-de-maison', 'texte' => 'la création d\'un plan de maison personnalisé', 'artisan' => 'concepteurs de plans'),
    89 => array('nom' => 'Plancher chauffant (eau chaude)', 'slug' => 'plancher-chauffant-eau-chaude-', 'texte' => 'une fourniture ou pose de plancher chauffant', 'artisan' => 'chauffagistes'),
    68 => array('nom' => 'Plancher chauffant rayonnant', 'slug' => 'plancher-chauffant-rayonnant', 'texte' => 'une installation de plancher chauffant rayonnant', 'artisan' => 'chauffagistes'),
    14 => array('nom' => 'Plomberie', 'slug' => 'plomberie', 'texte' => 'vos travaux de plomberie', 'artisan' => 'plombiers'),
    70 => array('nom' => 'Poele', 'slug' => 'poele', 'texte' => 'une fourniture ou pose de poêle', 'artisan' => 'cheministes'),
    36 => array('nom' => 'Pompe à chaleur', 'slug' => 'pompe-a-chaleur', 'texte' => 'une installation de pompe à chaleur', 'artisan' => 'chauffagistes'),
    40 => array('nom' => 'Pompe à chaleur air/air', 'slug' => 'aerothermie', 'texte' => 'une installation de pompe à chaleur air/air', 'artisan' => 'chauffagistes'),
    158 => array('nom' => 'Pompe à chaleur air/eau', 'slug' => 'pompe-a-chaleur-air-eau', 'texte' => 'la fourniture et pose d\'une pompe à chaleur air/eau', 'artisan' => 'chauffagiste'),
    71 => array('nom' => 'Portail', 'slug' => 'portail', 'texte' => 'une fourniture ou pose de portail', 'artisan' => 'professionnels'),
    96 => array('nom' => 'Porte blindée', 'slug' => 'porte-blindee', 'texte' => 'une fourniture ou pose de porte blindée', 'artisan' => 'professionnels'),
    128 => array('nom' => 'Porte d\'entrée', 'slug' => 'porte-d-entree', 'texte' => 'la pose d\'une porte d\'entrée', 'artisan' => 'professionnel'),
    108 => array('nom' => 'Porte de garage', 'slug' => 'porte-de-garage', 'texte' => 'la fourniture ou pose d\'une porte de garage', 'artisan' => 'professionnels'),
    137 => array('nom' => 'Portes intérieures', 'slug' => 'portes-interieures', 'texte' => 'la fourniture ou la pose de portes intérieures', 'artisan' => 'professionnels'),
    164 => array('nom' => 'Pose de borne de recharge', 'slug' => 'pose-de-borne-de-recharge', 'texte' => 'la pose d\'une borne de recharge pour voiture électrique', 'artisan' => 'electriciens'),
    84 => array('nom' => 'Pose de gouttières', 'slug' => 'pose-de-gouttieres', 'texte' => 'une pose de gouttières', 'artisan' => 'professionnels'),
    167 => array('nom' => 'Pose de prise de recharge', 'slug' => 'pose-de-prise-de-recharge', 'texte' => 'la pose d\'une prise pour recharger une voiture électrique', 'artisan' => 'electriciens'),
    169 => array('nom' => 'Punaise de lit', 'slug' => 'punaise-de-lit', 'texte' => 'l\'éradication des punaises de lit', 'artisan' => 'professionnels'),
    136 => array('nom' => 'Ragréage', 'slug' => 'ragreage', 'texte' => 'un ragréage de sol (garage, salon, cuisine, ...)', 'artisan' => 'artisans'),
    53 => array('nom' => 'Ramonage', 'slug' => 'ramonage', 'texte' => 'vos travaux de ramonage', 'artisan' => 'ramoneurs'),
    43 => array('nom' => 'Récupération des eaux de pluie', 'slug' => 'recuperation-des-eaux-de-pluie', 'texte' => 'une installation de récuparateur d\'eau de pluie', 'artisan' => 'professionnels'),
    66 => array('nom' => 'Rénovation', 'slug' => 'renovation', 'texte' => 'vos travaux de rénovation', 'artisan' => 'entreprises du batiment'),
    20 => array('nom' => 'Rénovation intérieure', 'slug' => 'renovation-interieure', 'texte' => 'vos travaux de rénovation', 'artisan' => 'professionnels'),
    170 => array('nom' => 'Rénovation énergétique', 'slug' => 'renovation-energetique', 'texte' => 'la rénovation énergétique de votre habitation', 'artisan' => 'artisans'),
    149 => array('nom' => 'SPA', 'slug' => 'spa', 'texte' => 'la pose d\'un spa', 'artisan' => 'professionnels'),
    23 => array('nom' => 'Salles de bains', 'slug' => 'salles-de-bains', 'texte' => 'la création ou l\'installation d\'une salle de bains', 'artisan' => 'professionnels'),
    165 => array('nom' => 'Serrurerie', 'slug' => 'serrurerie', 'texte' => 'des travaux de serrurerie', 'artisan' => 'serruriers'),
    95 => array('nom' => 'Store banne', 'slug' => 'store-banne', 'texte' => 'une fourniture ou pose de store banne', 'artisan' => 'storistes'),
    116 => array('nom' => 'Sur élévation de toiture', 'slug' => 'sur-elevation-de-toiture', 'texte' => 'une sur-élévation de toiture', 'artisan' => 'entreprises du batiment'),
    29 => array('nom' => 'Tapisserie - Papier peint', 'slug' => 'tapisserie-papier-peint', 'texte' => 'vos travaux de tapisserie', 'artisan' => 'poseurs'),
    52 => array('nom' => 'Termites', 'slug' => 'termites', 'texte' => 'un traitement anti-termite', 'artisan' => 'professionnels'),
    142 => array('nom' => 'Terrasse bois', 'slug' => 'terrasse-bois', 'texte' => 'pour la réalisation d\'une terrasse en bois', 'artisan' => 'artisans'),
    141 => array('nom' => 'Terrasse béton', 'slug' => 'terrasse-beton', 'texte' => 'la réalisation d\'une terrasse en dur', 'artisan' => 'maçons'),
    9 => array('nom' => 'Terrassement', 'slug' => 'terrassement', 'texte' => 'vos travaux de terrassement', 'artisan' => 'terrassiers'),
    49 => array('nom' => 'Terrasses', 'slug' => 'terrasses', 'texte' => 'la création d\'une terrasse', 'artisan' => 'professionnels'),
    143 => array('nom' => 'Toiture', 'slug' => 'toiture', 'texte' => 'la réfection d\'une toiture', 'artisan' => 'professionnels'),
    114 => array('nom' => 'Traitement contre l\'humidité', 'slug' => 'traitement-contre-l-humidite', 'texte' => 'vos problèmes d\'humidité', 'artisan' => 'professionnels'),
    161 => array('nom' => 'Travaux d\'architecture', 'slug' => 'travaux-d-architecture', 'texte' => 'des travaux d\'architecture (plan, rénovation, ...)', 'artisan' => 'architectes'),
    110 => array('nom' => 'Télésurveillance - vidéosurveillance', 'slug' => 'telesurveillance-videosurveillance', 'texte' => 'une fourniture ou pose de vidéosurveillance', 'artisan' => 'installateurs'),
    98 => array('nom' => 'VMC', 'slug' => 'vmc', 'texte' => 'une fourniture ou pose de VMC', 'artisan' => 'professionnels'),
    61 => array('nom' => 'VRD / Fosse septique', 'slug' => 'vrd-fosse-septique', 'texte' => 'vos travaux de VRD', 'artisan' => 'terrassiers'),
    166 => array('nom' => 'Velux - fenêtre de toit', 'slug' => 'velux-fenetre-de-toit', 'texte' => 'la fourniture et pose d\'un velux', 'artisan' => 'professionnels'),
    135 => array('nom' => 'Verrière - cloison atelier', 'slug' => 'verriere', 'texte' => 'fabrication ou pose de verriere type atelier', 'artisan' => 'professionnel'),
    168 => array('nom' => 'Vidange fosse septique', 'slug' => 'vidange-fosse-septique', 'texte' => 'la vidange d\'une fosse septique', 'artisan' => 'professionnels'),
    74 => array('nom' => 'Visiophone (fourniture et pose)', 'slug' => 'visiophone-fourniture-et-pose-', 'texte' => 'une fourniture ou pose de visiophone', 'artisan' => 'électriciens'),
    147 => array('nom' => 'Volet roulant', 'slug' => 'volet-roulant', 'texte' => 'la pose de volets roulants', 'artisan' => 'professionnels'),
    45 => array('nom' => 'Véranda', 'slug' => 'veranda', 'texte' => 'la création d\'une véranda', 'artisan' => 'professionnels'),
    24 => array('nom' => 'WC', 'slug' => 'wc', 'texte' => 'une installation de WC', 'artisan' => 'plombiers'),
    152 => array('nom' => 'chauffage piscine', 'slug' => 'chauffage-piscine', 'texte' => 'la pose d\'un chauffage de piscine', 'artisan' => 'professionnels'),
);
}

add_action('admin_menu', 'vud_page_generator_menu');
function vud_page_generator_menu() {
    add_submenu_page('vud-dashboard', 'Générer les pages', 'Générer les pages', 'manage_options', 'vud-page-generator', 'vud_page_generator_page_html');
}

add_action('admin_post_vud_generate_pages', 'vud_handle_generate_pages');
function vud_handle_generate_pages() {
    if (!current_user_can('manage_options') || !isset($_POST['vud_gen_nonce']) || !wp_verify_nonce($_POST['vud_gen_nonce'], 'vud_generate_pages')) {
        wp_die('Accès refusé.');
    }

    $selected = isset($_POST['cat_ids']) ? array_map('absint', (array) $_POST['cat_ids']) : array();
    $seed = vud_get_category_seed_data();
    $categories = vud_get_categories();

    $created = 0;
    $skipped = 0;

    foreach ($selected as $cat_id) {
        if (!isset($categories[$cat_id])) continue;

        // Skip if a page for this category was already generated before.
        $existing = get_posts(array(
            'post_type'   => 'page',
            'meta_key'    => '_vud_generated_cat_id',
            'meta_value'  => $cat_id,
            'post_status' => array('publish', 'draft', 'pending'),
            'numberposts' => 1,
            'fields'      => 'ids',
        ));
        if (!empty($existing)) { $skipped++; continue; }

        $s = isset($seed[$cat_id]) ? $seed[$cat_id] : array('nom' => $categories[$cat_id], 'texte' => '', 'artisan' => 'professionnels', 'slug' => sanitize_title($categories[$cat_id]));
        $nom = $s['nom'];
        $texte = $s['texte'] ?: ('vos travaux de ' . mb_strtolower($nom));
        $artisan = $s['artisan'] ?: 'professionnels';

        $title = sprintf('Devis %s : trouver des %s pour %s', $nom, $artisan, $texte);
        $slug  = 'devis-' . $s['slug'];

        $content  = "<!-- wp:heading -->\n<h2>" . esc_html($title) . "</h2>\n<!-- /wp:heading -->\n\n";
        $content .= "<!-- wp:paragraph -->\n<p>[À COMPLÉTER : rédigez ici 150 à 300 mots uniques décrivant " . esc_html($texte) . ", pourquoi passer par des " . esc_html($artisan) . " qualifiés, ce que ViteUnDevis apporte (3 devis gratuits, sans engagement), et toute question fréquente locale. Ne publiez pas cette page telle quelle.]</p>\n<!-- /wp:paragraph -->\n\n";
        $content .= "<!-- wp:shortcode -->\n[vud_lead_form cat_id=\"" . esc_attr($cat_id) . "\"]\n<!-- /wp:shortcode -->\n\n";
        $content .= "<!-- wp:paragraph -->\n<p>[À COMPLÉTER : ajoutez ici une FAQ ou des informations complémentaires propres à cette catégorie.]</p>\n<!-- /wp:paragraph -->";

        $post_id = wp_insert_post(array(
            'post_type'    => 'page',
            'post_title'   => $title,
            'post_name'    => $slug,
            'post_content' => $content,
            'post_status'  => 'draft',
        ), true);

        if (!is_wp_error($post_id)) {
            update_post_meta($post_id, '_vud_generated_cat_id', $cat_id);
            $created++;
        }
    }

    set_transient('vud_page_gen_result', array('created' => $created, 'skipped' => $skipped), 60);
    wp_safe_redirect(admin_url('admin.php?page=vud-page-generator&done=1'));
    exit;
}

function vud_page_generator_page_html() {
    if (!current_user_can('manage_options')) return;

    $categories = vud_get_categories();
    $result = get_transient('vud_page_gen_result');
    ?>
    <div class="wrap">
        <h1>Générer les pages par catégorie</h1>

        <?php if (!empty($_GET['done']) && $result): ?>
            <div class="notice notice-success"><p>
                <?php echo (int) $result['created']; ?> page(s) créée(s) en brouillon.
                <?php if ($result['skipped']) echo (int) $result['skipped'] . ' déjà existante(s), ignorée(s).'; ?>
                <a href="<?php echo esc_url(admin_url('edit.php?post_type=page')); ?>">Voir les pages</a>
            </p></div>
        <?php endif; ?>

        <p>Crée une page WordPress <strong>en brouillon</strong> par catégorie sélectionnée, avec le shortcode <code>[vud_lead_form cat_id="X"]</code> déjà en place et un plan de départ. <strong>Chaque page doit être relue et complétée</strong> avec un vrai contenu unique avant publication — des pages quasi identiques publiées en masse nuisent au référencement au lieu de l\'aider.</p>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('vud_generate_pages', 'vud_gen_nonce'); ?>
            <input type="hidden" name="action" value="vud_generate_pages">

            <p>
                <button type="button" class="button" onclick="document.querySelectorAll('.vud-cat-checkbox').forEach(c=>c.checked=true);">Tout sélectionner</button>
                <button type="button" class="button" onclick="document.querySelectorAll('.vud-cat-checkbox').forEach(c=>c.checked=false);">Tout désélectionner</button>
            </p>

            <div style="max-height:420px;overflow-y:auto;border:1px solid #dcdcde;padding:12px;background:#fff;columns:3;column-gap:20px;">
                <?php foreach ($categories as $id => $name): ?>
                    <label style="display:block;margin-bottom:6px;break-inside:avoid;">
                        <input type="checkbox" class="vud-cat-checkbox" name="cat_ids[]" value="<?php echo esc_attr($id); ?>">
                        <?php echo esc_html($name); ?>
                    </label>
                <?php endforeach; ?>
            </div>

            <?php submit_button('Générer les pages sélectionnées (brouillons)'); ?>
        </form>
    </div>
    <?php
}
