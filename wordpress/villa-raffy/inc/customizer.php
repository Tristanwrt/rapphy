<?php
/**
 * Personnalisation du site — coordonnées, textes et réglages généraux,
 * sans toucher au code. Apparence → Personnaliser.
 *
 * Les textes des sections de l'accueil se modifient aussi directement
 * dans la page « Accueil » (éditeur de blocs) : ce qui est saisi là-bas
 * a priorité, et ce qui est vide reprend la valeur d'ici.
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Toutes les valeurs par défaut, au même endroit.
 * Tant qu'un réglage n'a pas été modifié, c'est cette valeur qui s'affiche sur le site.
 */
function vr_mod_defauts() {
	return array(
		// Coordonnées.
		'vr_hotes'            => 'Stéphane & Sophie',
		'vr_telephone'        => '06 83 63 89 66',
		'vr_whatsapp'         => '06 83 63 89 66',
		'vr_email'            => '',
		'vr_adresse'          => '3 rue Georges Bouyssou, 47340 Saint-Robert',
		'vr_classement'       => '',
		'vr_url_airbnb'       => 'https://www.airbnb.fr/rooms/1396920426716525192',
		'vr_url_google'       => 'https://www.google.com/maps?cid=501049054210734570',
		'vr_bandeau_texte'    => 'Saint-Robert, Lot-et-Garonne · Réservation en direct, sans commission',

		// Grande image.
		'vr_hero_surtitre'    => 'Saint-Robert · Entre Agen et Villeneuve-sur-Lot',
		'vr_hero_titre'       => 'Votre oasis d\'exception au cœur du Lot-et-Garonne',
		'vr_hero_sous_titre'  => 'Villa de 180 m² pour 8 voyageurs — piscine, jacuzzi, plage privée de sable fin, salle de sport et cinéma privé, sur 2300 m² de nature clôturée.',

		// Arguments de confiance.
		'vr_preuve_1'         => '4,86/5 · Coup de cœur voyageurs Airbnb',
		'vr_preuve_2'         => 'Noté 5 étoiles sur Google',
		'vr_preuve_3'         => 'Réservation en direct, sans commission',
		'vr_badge_1_valeur'   => '4,86/5',
		'vr_badge_1_label'    => 'sur Airbnb · Coup de cœur voyageurs',
		'vr_badge_2_valeur'   => '5/5',
		'vr_badge_2_label'    => 'sur Google',
		'vr_badge_3_valeur'   => '5,0',
		'vr_badge_3_label'    => 'en propreté, arrivée et qualité-prix',
		'vr_badge_4_valeur'   => '100 %',
		'vr_badge_4_label'    => 'de réponse, en moins d\'une heure',

		// Réservation.
		'vr_nuits_minimum'    => '2',
		'vr_capacite_max'     => '8',
		'vr_arrivee_horaires' => 'entre 16h et 23h',
		'vr_depart_horaire'   => 'avant 10h',
		'vr_resa_titre'       => 'Choisissez vos dates, la villa vous attend',
		'vr_resa_texte'       => 'Choisissez votre formule, votre arrivée puis votre départ : le tarif s\'affiche immédiatement. Votre demande part directement chez vos hôtes — par téléphone, WhatsApp ou email — sans aucune commission.',
		'vr_resa_note'        => 'Réponse rapide et personnelle de vos hôtes. Réserver en direct, c\'est le meilleur tarif garanti — sans les frais de service des plateformes.',

		// Sections.
		'vr_chiffres_liste'   => "180 m² | de villa de plain-pied | ruler\n8 | voyageurs | users\n4 | chambres & suites | bed\n2300 m² | de terrain clos | tree\n9 m | de piscine, bar immergé | waves\n5 places | de jacuzzi | spa",
		'vr_direct_titre'     => 'Ici, pas d\'intermédiaire.',
		'vr_direct_titre_2'   => 'Juste vous, et la villa.',
		'vr_direct_1_titre'   => 'Le meilleur tarif, garanti',
		'vr_direct_1_texte'   => 'En réservant en direct, vous évitez les commissions des plateformes, qui atteignent 15 %. C\'est toujours ici que le prix est le plus doux.',
		'vr_direct_2_titre'   => 'Un échange direct avec vos hôtes',
		'vr_direct_2_texte'   => 'Nous vous répondons personnellement et rapidement, et nous préparons votre arrivée. Petites attentions sur demande — jusqu\'aux huîtres et fruits frais livrés directement à la villa.',
		'vr_direct_3_titre'   => 'Souplesse et simplicité',
		'vr_direct_3_texte'   => 'Horaires d\'arrivée, demandes particulières, séjours sur mesure : tout se règle en un appel ou un message, sans intermédiaire.',
		'vr_visite_titre'     => 'Poussez la porte, laissez-vous guider',
		'vr_villa_titre'      => 'Un séjour de 70 m² où la vie s\'installe naturellement',
		'vr_villa_texte_1'    => 'Poussez la porte : le séjour cathédrale de 70 m² s\'ouvre sur une cuisine équipée et son bar, pensés pour les longues tablées d\'été comme pour les soirées d\'hiver au coin du feu. Côté détente, l\'écran géant de 3,5 m transforme le salon en salle de cinéma privée.',
		'vr_villa_texte_2'    => 'De plain-pied, baignée de lumière, la villa regarde la campagne sans aucun vis-à-vis. Familles, couples, tribus d\'amis : chacun y trouve son rythme.',
		'vr_chambres_titre'   => 'Quatre chambres, quatre lits grand format, zéro compromis',
		'vr_chambres_texte'   => 'Toutes les chambres sont équipées d\'une literie grand confort 160 × 200. Les deux suites disposent de leur salle d\'eau privative, les deux autres chambres partagent une salle de bain.',
		'vr_sejours_titre'    => 'La villa entière, ou la version cocooning',
		'vr_sejours_haute_texte' => 'Les 180 m² et les 2300 m² de parc rien que pour vous : 4 chambres, jusqu\'à 8 voyageurs, piscine, jacuzzi, plage privée, salle de sport et cinéma. La formule idéale pour les familles et les tribus d\'amis.',
		'vr_sejours_basse_texte' => 'Un privilège rare de basse saison : la villa entièrement privatisée pour 2 à 4 personnes. Deux chambres restent fermées, et vous gardez la piscine, le jacuzzi, l\'intégralité du jardin et le calme absolu, rien que pour vous.',
		'vr_ext_titre'        => 'Un cocktail au bar immergé,',
		'vr_ext_titre_2'      => 'les pieds dans l\'eau',
		'vr_video_surtitre'   => 'La villa en mouvement',
		'vr_video_titre'      => 'Trente secondes pour tomber sous le charme',
		'vr_avis_titre'       => 'Nos voyageurs le disent mieux que nous',
		'vr_region_titre'     => 'Idéalement placée entre Agen et Villeneuve-sur-Lot',
		'vr_region_texte'     => 'Vous êtes au centre exact du Lot-et-Garonne. La position parfaite pour rayonner : marchés, bastides, vignobles et baignades.',
		'vr_faq_titre'        => 'Tout ce que vous voulez savoir',

		// Carte.
		'vr_carte_latitude'   => '44.2469',
		'vr_carte_longitude'  => '0.8',
		'vr_carte_legende'    => 'Saint-Robert, Nouvelle-Aquitaine, France — entre Agen et Villeneuve-sur-Lot',

		// Pied de page.
		'vr_pied_texte'       => 'Location de villa de luxe avec piscine, jacuzzi, salle de sport et cinéma privé en Lot-et-Garonne, entre Agen et Villeneuve-sur-Lot.',
		'vr_pied_cta_titre'   => 'Votre prochain séjour d\'exception commence ici',
		'vr_pied_cta_texte'   => 'Un appel, un message — et la villa est à vous. {hotes} vous répondent personnellement.',
		'vr_pied_legal'       => 'Location saisonnière de standing',
	);
}

/**
 * Applique les valeurs par défaut partout où un réglage n'a pas encore été rempli,
 * pour que le site ne soit jamais vide.
 */
function vr_appliquer_defauts_mods() {
	foreach ( vr_mod_defauts() as $id => $defaut ) {
		add_filter( 'theme_mod_' . $id, function ( $valeur ) use ( $defaut ) {
			return ( '' === $valeur || null === $valeur || false === $valeur ) ? $defaut : $valeur;
		}, 5 );
	}
}
add_action( 'after_setup_theme', 'vr_appliquer_defauts_mods' );

function vr_customizer( $wp_customize ) {

	$defauts = vr_mod_defauts();

	/* ─── Champ texte simple ─── */
	$champ = function ( $id, $label, $section, $type = 'text', $description = '' ) use ( $wp_customize, $defauts ) {
		$wp_customize->add_setting( $id, array(
			'default'           => isset( $defauts[ $id ] ) ? $defauts[ $id ] : '',
			'sanitize_callback' => ( 'textarea' === $type ) ? 'wp_kses_post' : ( ( 'url' === $type ) ? 'esc_url_raw' : 'sanitize_text_field' ),
			'transport'         => 'refresh',
		) );
		$wp_customize->add_control( $id, array(
			'label'       => $label,
			'section'     => $section,
			'type'        => ( 'textarea' === $type ) ? 'textarea' : ( ( 'number' === $type ) ? 'number' : ( ( 'url' === $type ) ? 'url' : 'text' ) ),
			'description' => $description,
		) );
	};

	/* ═══ COORDONNÉES ═══ */
	$wp_customize->add_section( 'vr_contact', array(
		'title'       => 'Vos coordonnées',
		'priority'    => 20,
		'description' => 'Ces informations apparaissent dans le bandeau du haut, la section réservation, les avis et le pied de page.',
	) );

	$champ( 'vr_hotes', 'Prénoms des hôtes', 'vr_contact' );
	$champ( 'vr_telephone', 'Téléphone affiché', 'vr_contact', 'text', 'Écrivez-le comme vous voulez qu\'il s\'affiche, par exemple 06 83 63 89 66.' );
	$champ( 'vr_whatsapp', 'Numéro WhatsApp', 'vr_contact', 'text', 'Souvent le même que le téléphone. Laissez vide pour masquer le bouton WhatsApp.' );
	$champ( 'vr_email', 'Adresse email', 'vr_contact', 'text', 'L\'adresse qui recevra les demandes de réservation.' );
	$champ( 'vr_adresse', 'Adresse de la villa', 'vr_contact' );
	$champ( 'vr_bandeau_texte', 'Texte du bandeau du haut', 'vr_contact', 'text', 'La petite ligne sombre au-dessus du menu. Laissez vide pour la masquer.' );
	$champ( 'vr_classement', 'Classement ou label', 'vr_contact', 'text', 'Laissez vide si vous n\'en avez pas.' );
	$champ( 'vr_url_airbnb', 'Lien vers votre annonce Airbnb', 'vr_contact', 'url', 'Le logo Airbnb (section Avis et pied de page) renvoie vers cette page.' );
	$champ( 'vr_url_google', 'Lien vers vos avis Google', 'vr_contact', 'url', 'Le logo Google (section Avis et pied de page) renvoie vers cette page.' );

	/* ═══ EN-TÊTE (HÉRO) ═══ */
	$wp_customize->add_section( 'vr_hero', array(
		'title'       => 'Grande image d\'accueil',
		'priority'    => 21,
		'description' => 'La première chose que voient vos visiteurs. Modifiable aussi depuis la page Accueil.',
	) );

	$wp_customize->add_setting( 'vr_hero_image', array( 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'vr_hero_image', array(
		'label'       => 'Photo plein écran',
		'description' => 'Choisissez votre plus belle photo, de préférence horizontale (la piscine au coucher du soleil fonctionne très bien).',
		'section'     => 'vr_hero',
		'mime_type'   => 'image',
	) ) );

	$champ( 'vr_hero_surtitre', 'Petite ligne au-dessus du titre', 'vr_hero' );
	$champ( 'vr_hero_titre', 'Grand titre', 'vr_hero', 'textarea' );
	$champ( 'vr_hero_sous_titre', 'Phrase de présentation', 'vr_hero', 'textarea' );

	/* ═══ ARGUMENTS DE CONFIANCE ═══ */
	$wp_customize->add_section( 'vr_confiance', array(
		'title'       => 'Vos arguments de confiance',
		'priority'    => 22,
		'description' => 'Les trois mentions affichées sous la grande image, et les quatre notes de la section Avis. Une légende contenant « Airbnb » ou « Google » affiche le logo correspondant, cliquable.',
	) );

	$champ( 'vr_preuve_1', 'Mention 1 (avec étoiles)', 'vr_confiance' );
	$champ( 'vr_preuve_2', 'Mention 2 (avec bouclier)', 'vr_confiance' );
	$champ( 'vr_preuve_3', 'Mention 3 (avec coche)', 'vr_confiance' );

	for ( $i = 1; $i <= 4; $i++ ) {
		$champ( "vr_badge_{$i}_valeur", "Note {$i}", 'vr_confiance' );
		$champ( "vr_badge_{$i}_label", "Légende de la note {$i}", 'vr_confiance' );
	}

	/* ═══ RÉSERVATION ═══ */
	$wp_customize->add_section( 'vr_reservation', array(
		'title'       => 'Réservation',
		'priority'    => 23,
		'description' => 'Les prix, saisons et règles de séjour se règlent dans Réservations → Tarifs & saisons.',
	) );

	$champ( 'vr_capacite_max', 'Nombre de voyageurs maximum', 'vr_reservation', 'number' );
	$champ( 'vr_arrivee_horaires', 'Horaires d\'arrivée', 'vr_reservation' );
	$champ( 'vr_depart_horaire', 'Heure de départ', 'vr_reservation' );
	$champ( 'vr_resa_titre', 'Titre de la section', 'vr_reservation', 'textarea' );
	$champ( 'vr_resa_texte', 'Texte d\'introduction', 'vr_reservation', 'textarea' );
	$champ( 'vr_resa_note', 'Petite note sous les boutons', 'vr_reservation', 'textarea' );

	/* ═══ TEXTES DES SECTIONS ═══ */
	$wp_customize->add_section( 'vr_sections', array(
		'title'       => 'Titres et textes des sections',
		'priority'    => 28,
		'description' => 'Les intitulés des grandes parties de la page d\'accueil. Chaque section se modifie aussi directement dans la page Accueil.',
	) );

	$champ( 'vr_visite_titre', 'Section « Visite guidée » — titre', 'vr_sections', 'textarea' );
	$champ( 'vr_villa_titre', 'Section « La villa » — titre', 'vr_sections', 'textarea' );
	$champ( 'vr_villa_texte_1', 'Section « La villa » — premier paragraphe', 'vr_sections', 'textarea' );
	$champ( 'vr_villa_texte_2', 'Section « La villa » — second paragraphe', 'vr_sections', 'textarea' );
	$champ( 'vr_chambres_titre', 'Section « Chambres » — titre', 'vr_sections', 'textarea' );
	$champ( 'vr_chambres_texte', 'Section « Chambres » — introduction', 'vr_sections', 'textarea' );
	$champ( 'vr_sejours_titre', 'Section « Formules » — titre', 'vr_sections', 'textarea' );
	$champ( 'vr_sejours_haute_texte', 'Formule villa complète — description', 'vr_sections', 'textarea' );
	$champ( 'vr_sejours_basse_texte', 'Formule cocooning — description', 'vr_sections', 'textarea' );
	$champ( 'vr_ext_titre', 'Section « Piscine & jardin » — titre', 'vr_sections' );
	$champ( 'vr_ext_titre_2', 'Section « Piscine & jardin » — suite du titre (en doré)', 'vr_sections' );
	$champ( 'vr_avis_titre', 'Section « Avis » — titre', 'vr_sections', 'textarea' );
	$champ( 'vr_region_titre', 'Section « La région » — titre', 'vr_sections', 'textarea' );
	$champ( 'vr_region_texte', 'Section « La région » — introduction', 'vr_sections', 'textarea' );
	$champ( 'vr_faq_titre', 'Section « Questions fréquentes » — titre', 'vr_sections', 'textarea' );

	/* ═══ VIDÉO ═══ */
	$wp_customize->add_section( 'vr_video', array(
		'title'       => 'Vidéo de présentation',
		'priority'    => 29,
		'description' => 'Un montage court et rythmé de la villa. Sans fichier vidéo, le site enchaîne automatiquement les photos de la visite guidée en fondu.',
	) );

	$wp_customize->add_setting( 'vr_video_fichier', array( 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'vr_video_fichier', array(
		'label'       => 'Fichier vidéo (MP4)',
		'description' => 'Format MP4, idéalement moins de 20 Mo, sans son ou avec un son discret : la vidéo démarre en muet.',
		'section'     => 'vr_video',
		'mime_type'   => 'video',
	) ) );

	$champ( 'vr_video_surtitre', 'Petite ligne au-dessus du titre', 'vr_video' );
	$champ( 'vr_video_titre', 'Titre', 'vr_video', 'textarea' );

	/* ═══ CARTE ═══ */
	$wp_customize->add_section( 'vr_carte', array(
		'title'    => 'Carte de localisation',
		'priority' => 24,
	) );

	$champ( 'vr_carte_latitude', 'Latitude', 'vr_carte', 'text', 'Trouvez-la sur Google Maps : clic droit sur votre villa, le premier chiffre est la latitude.' );
	$champ( 'vr_carte_longitude', 'Longitude', 'vr_carte' );
	$champ( 'vr_carte_legende', 'Légende sous la carte', 'vr_carte' );

	/* ═══ CHIFFRES CLÉS ═══ */
	$wp_customize->add_section( 'vr_chiffres', array(
		'title'       => 'Chiffres clés',
		'priority'    => 26,
		'description' => 'La bande de chiffres sous la grande image, répartis sur deux colonnes symétriques. Une ligne par chiffre, au format : valeur | légende | icône',
	) );

	$champ(
		'vr_chiffres_liste',
		'Vos chiffres',
		'vr_chiffres',
		'textarea',
		'Six lignes donnent le meilleur équilibre. Icônes possibles : ruler, users, bed, tree, waves, spa, sun, wifi, film, dumbbell, chef, paw, shield, check, calendar, beach.'
	);

	/* ═══ RÉSERVER EN DIRECT ═══ */
	$wp_customize->add_section( 'vr_direct', array(
		'title'       => 'Bloc « Réserver en direct »',
		'priority'    => 27,
		'description' => 'Les trois arguments qui expliquent pourquoi réserver chez vous plutôt que sur une plateforme.',
	) );

	$champ( 'vr_direct_titre', 'Titre du bloc', 'vr_direct' );
	$champ( 'vr_direct_titre_2', 'Suite du titre (en doré)', 'vr_direct' );

	for ( $n = 1; $n <= 3; $n++ ) {
		$champ( "vr_direct_{$n}_titre", "Argument {$n} — titre", 'vr_direct' );
		$champ( "vr_direct_{$n}_texte", "Argument {$n} — texte", 'vr_direct', 'textarea' );
	}

	/* ═══ PIED DE PAGE ═══ */
	$wp_customize->add_section( 'vr_pied', array(
		'title'       => 'Pied de page',
		'priority'    => 25,
		'description' => 'Les liens du pied de page se modifient dans Apparence → Menus (emplacement « Menu du pied de page »).',
	) );

	$champ( 'vr_pied_cta_titre', 'Titre de l\'appel final', 'vr_pied', 'textarea' );
	$champ( 'vr_pied_cta_texte', 'Texte de l\'appel final', 'vr_pied', 'textarea', 'Écrivez {hotes} pour insérer automatiquement les prénoms des hôtes.' );
	$champ( 'vr_pied_texte', 'Phrase de présentation (colonne centrale)', 'vr_pied', 'textarea' );
	$champ( 'vr_pied_legal', 'Mention en bas de page', 'vr_pied' );
}
add_action( 'customize_register', 'vr_customizer' );

/**
 * Valeurs par défaut des quatre notes de la section Avis.
 */
function vr_badges_defaut() {
	$d = vr_mod_defauts();
	return array(
		array( $d['vr_badge_1_valeur'], $d['vr_badge_1_label'] ),
		array( $d['vr_badge_2_valeur'], $d['vr_badge_2_label'] ),
		array( $d['vr_badge_3_valeur'], $d['vr_badge_3_label'] ),
		array( $d['vr_badge_4_valeur'], $d['vr_badge_4_label'] ),
	);
}

function vr_badge( $index, $partie ) {
	$defauts = vr_badges_defaut();
	$cle     = ( 'valeur' === $partie ) ? "vr_badge_{$index}_valeur" : "vr_badge_{$index}_label";
	$defaut  = ( 'valeur' === $partie ) ? $defauts[ $index - 1 ][0] : $defauts[ $index - 1 ][1];
	$valeur  = get_theme_mod( $cle, '' );

	return $valeur ? $valeur : $defaut;
}
