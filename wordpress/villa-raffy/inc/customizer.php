<?php
/**
 * Personnalisation du site — c'est ici que le propriétaire modifie ses textes,
 * ses coordonnées et ses photos, sans toucher au code.
 *
 * Apparence → Personnaliser
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vr_customizer( $wp_customize ) {

	/* ─── Champ texte simple ─── */
	$champ = function ( $id, $label, $defaut = '', $section = '', $type = 'text', $description = '' ) use ( $wp_customize ) {
		$wp_customize->add_setting( $id, array(
			'default'           => $defaut,
			'sanitize_callback' => ( 'textarea' === $type ) ? 'wp_kses_post' : ( ( 'url' === $type ) ? 'esc_url_raw' : 'sanitize_text_field' ),
			'transport'         => 'refresh',
		) );
		$wp_customize->add_control( $id, array(
			'label'       => $label,
			'section'     => $section,
			'type'        => ( 'textarea' === $type ) ? 'textarea' : ( ( 'number' === $type ) ? 'number' : 'text' ),
			'description' => $description,
		) );
	};

	/* ═══ COORDONNÉES ═══ */
	$wp_customize->add_section( 'vr_contact', array(
		'title'       => 'Vos coordonnées',
		'priority'    => 20,
		'description' => 'Ces informations apparaissent dans le menu, la section réservation et le pied de page.',
	) );

	$champ( 'vr_hotes', 'Prénoms des hôtes', 'Stéphane & Sophie', '', 'vr_contact' );
	$champ( 'vr_telephone', 'Téléphone affiché', '06 83 63 89 66', 'vr_contact', 'text', 'Écrivez-le comme vous voulez qu\'il s\'affiche, par exemple 06 83 63 89 66.' );
	$champ( 'vr_whatsapp', 'Numéro WhatsApp', '06 83 63 89 66', 'vr_contact', 'text', 'Souvent le même que le téléphone. Laissez vide pour masquer le bouton WhatsApp.' );
	$champ( 'vr_email', 'Adresse email', '', 'vr_contact', 'text', 'L\'adresse qui recevra les demandes de réservation.' );
	$champ( 'vr_adresse', 'Adresse de la villa', '3 rue Georges Bouyssou, 47340 Saint-Robert', 'vr_contact' );
	$champ( 'vr_classement', 'Classement ou label', 'Gîtes de France n°47G9070', 'vr_contact' );
	$champ( 'vr_url_airbnb', 'Lien vers votre annonce Airbnb', '', 'vr_contact', 'url', 'Laissez vide pour masquer le lien.' );

	/* ═══ EN-TÊTE (HÉRO) ═══ */
	$wp_customize->add_section( 'vr_hero', array(
		'title'       => 'Grande image d\'accueil',
		'priority'    => 21,
		'description' => 'La première chose que voient vos visiteurs.',
	) );

	$wp_customize->add_setting( 'vr_hero_image', array( 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'vr_hero_image', array(
		'label'       => 'Photo plein écran',
		'description' => 'Choisissez votre plus belle photo, de préférence horizontale (la piscine au coucher du soleil fonctionne très bien).',
		'section'     => 'vr_hero',
		'mime_type'   => 'image',
	) ) );

	$champ( 'vr_hero_surtitre', 'Petite ligne au-dessus du titre', 'Saint-Robert · Entre Agen et Villeneuve-sur-Lot', 'vr_hero' );
	$champ( 'vr_hero_titre', 'Grand titre', 'Votre oasis d\'exception au cœur du Lot-et-Garonne', 'vr_hero', 'textarea' );
	$champ( 'vr_hero_sous_titre', 'Phrase de présentation', 'Villa de 180 m² pour 8 voyageurs — piscine, jacuzzi, plage privée de sable fin, salle de sport et cinéma privé, sur 2300 m² de nature clôturée.', 'vr_hero', 'textarea' );

	/* ═══ ARGUMENTS DE CONFIANCE ═══ */
	$wp_customize->add_section( 'vr_confiance', array(
		'title'       => 'Vos arguments de confiance',
		'priority'    => 22,
		'description' => 'Les trois mentions affichées sous la grande image, et les quatre chiffres de la section Avis.',
	) );

	$champ( 'vr_preuve_1', 'Mention 1 (avec étoiles)', '4,86/5 · Coup de cœur voyageurs Airbnb', 'vr_confiance' );
	$champ( 'vr_preuve_2', 'Mention 2 (avec bouclier)', 'Noté 5 étoiles sur Google · Gîtes de France', 'vr_confiance' );
	$champ( 'vr_preuve_3', 'Mention 3 (avec coche)', 'Réservation en direct, sans commission', 'vr_confiance' );

	for ( $i = 1; $i <= 4; $i++ ) {
		$champ( "vr_badge_{$i}_valeur", "Chiffre {$i}", '', 'vr_confiance' );
		$champ( "vr_badge_{$i}_label", "Légende du chiffre {$i}", '', 'vr_confiance' );
	}

	/* ═══ RÉSERVATION ═══ */
	$wp_customize->add_section( 'vr_reservation', array(
		'title'       => 'Règles de réservation',
		'priority'    => 23,
		'description' => 'Ces règles s\'appliquent au calendrier de votre site.',
	) );

	$champ( 'vr_nuits_minimum', 'Nombre de nuits minimum', '2', 'vr_reservation', 'number' );
	$champ( 'vr_capacite_max', 'Nombre de voyageurs maximum', '8', 'vr_reservation', 'number' );
	$champ( 'vr_arrivee_horaires', 'Horaires d\'arrivée', 'entre 16h et 23h', 'vr_reservation' );
	$champ( 'vr_depart_horaire', 'Heure de départ', 'avant 10h', 'vr_reservation' );

	/* ═══ TEXTES DES SECTIONS ═══ */
	$wp_customize->add_section( 'vr_sections', array(
		'title'       => 'Titres et textes des sections',
		'priority'    => 28,
		'description' => 'Les intitulés des grandes parties de la page d\'accueil.',
	) );

	$champ( 'vr_villa_titre', 'Section « La villa » — titre', 'Un séjour de 70 m² où la vie s\'installe naturellement', 'vr_sections', 'textarea' );
	$champ( 'vr_villa_texte_1', 'Section « La villa » — premier paragraphe', 'Poussez la porte : le séjour cathédrale de 70 m² s\'ouvre sur une cuisine équipée et son bar, pensés pour les longues tablées d\'été comme pour les soirées d\'hiver au coin du feu.', 'vr_sections', 'textarea' );
	$champ( 'vr_villa_texte_2', 'Section « La villa » — second paragraphe', 'De plain-pied, baignée de lumière, la villa regarde la campagne sans aucun vis-à-vis. Familles, couples, tribus d\'amis : chacun y trouve son rythme.', 'vr_sections', 'textarea' );

	$champ( 'vr_chambres_titre', 'Section « Chambres » — titre', 'Quatre chambres, quatre lits grand format, zéro compromis', 'vr_sections', 'textarea' );
	$champ( 'vr_chambres_texte', 'Section « Chambres » — introduction', 'Toutes les chambres sont équipées de lits 160 × 200 et chaque espace nuit dispose de sa propre salle de bain ou salle d\'eau.', 'vr_sections', 'textarea' );

	$champ( 'vr_ext_titre', 'Section « Piscine & jardin » — titre', 'Un cocktail au bar immergé,', 'vr_sections' );
	$champ( 'vr_ext_titre_2', 'Section « Piscine & jardin » — suite du titre (en doré)', 'les pieds dans l\'eau', 'vr_sections' );

	$champ( 'vr_avis_titre', 'Section « Avis » — titre', 'Nos voyageurs le disent mieux que nous', 'vr_sections', 'textarea' );

	$champ( 'vr_region_titre', 'Section « La région » — titre', 'Idéalement placée entre Agen et Villeneuve-sur-Lot', 'vr_sections', 'textarea' );
	$champ( 'vr_region_texte', 'Section « La région » — introduction', 'Vous êtes au centre exact du Lot-et-Garonne. La position parfaite pour rayonner : marchés, bastides, vignobles et baignades.', 'vr_sections', 'textarea' );

	/* ═══ CARTE ═══ */
	$wp_customize->add_section( 'vr_carte', array(
		'title'    => 'Carte de localisation',
		'priority' => 24,
	) );

	$champ( 'vr_carte_latitude', 'Latitude', '44.2469', 'vr_carte', 'text', 'Trouvez-la sur Google Maps : clic droit sur votre villa, le premier chiffre est la latitude.' );
	$champ( 'vr_carte_longitude', 'Longitude', '0.8', 'vr_carte' );
	$champ( 'vr_carte_legende', 'Légende sous la carte', 'Saint-Robert, Nouvelle-Aquitaine, France — entre Agen et Villeneuve-sur-Lot', 'vr_carte' );

	/* ═══ CHIFFRES CLÉS ═══ */
	$wp_customize->add_section( 'vr_chiffres', array(
		'title'       => 'Chiffres clés',
		'priority'    => 26,
		'description' => 'La bande de six chiffres sous la grande image. Une ligne par chiffre, au format : valeur | légende | icône',
	) );

	$champ(
		'vr_chiffres_liste',
		'Vos chiffres',
		"180 m² | de villa de plain-pied | ruler\n8 | voyageurs | users\n4 | chambres & suites | bed\n2300 m² | de terrain clos | tree\n9 m | de piscine, bar immergé | waves\n5 places | de jacuzzi | spa",
		'vr_chiffres',
		'textarea',
		'Icônes possibles : ruler, users, bed, tree, waves, spa, sun, wifi, film, chef, paw, shield, check, calendar.'
	);

	/* ═══ RÉSERVER EN DIRECT ═══ */
	$wp_customize->add_section( 'vr_direct', array(
		'title'       => 'Bloc « Réserver en direct »',
		'priority'    => 27,
		'description' => 'Les trois arguments qui expliquent pourquoi réserver chez vous plutôt que sur une plateforme.',
	) );

	$champ( 'vr_direct_titre', 'Titre du bloc', 'Ici, pas d\'intermédiaire.', 'vr_direct' );
	$champ( 'vr_direct_titre_2', 'Suite du titre (en doré)', 'Juste vous, et la villa.', 'vr_direct' );

	$direct_defauts = array(
		array( 'Le meilleur tarif, garanti', 'En réservant en direct, vous évitez les commissions des plateformes, qui atteignent 15 %. C\'est toujours ici que le prix est le plus doux.', 'check' ),
		array( 'Un échange direct avec vos hôtes', 'Nous vous répondons personnellement, rapidement, et préparons votre arrivée. Les petites attentions sont possibles sur demande.', 'phone' ),
		array( 'Souplesse et simplicité', 'Horaires d\'arrivée, demandes particulières, séjours sur mesure : tout se règle en un appel ou un message, sans intermédiaire.', 'calendar' ),
	);

	foreach ( $direct_defauts as $i => $defaut ) {
		$n = $i + 1;
		$champ( "vr_direct_{$n}_titre", "Argument {$n} — titre", $defaut[0], 'vr_direct' );
		$champ( "vr_direct_{$n}_texte", "Argument {$n} — texte", $defaut[1], 'vr_direct', 'textarea' );
	}

	/* ═══ PIED DE PAGE ═══ */
	$wp_customize->add_section( 'vr_pied', array(
		'title'    => 'Pied de page',
		'priority' => 25,
	) );

	$champ( 'vr_pied_texte', 'Phrase de présentation', 'Location de villa de luxe avec piscine, jacuzzi, salle de sport et cinéma privé en Lot-et-Garonne, entre Agen et Villeneuve-sur-Lot.', 'vr_pied', 'textarea' );
	$champ( 'vr_pied_cta_titre', 'Titre de l\'appel final', 'Votre prochain séjour d\'exception commence ici', 'vr_pied', 'textarea' );
}
add_action( 'customize_register', 'vr_customizer' );

/**
 * Valeurs par défaut des quatre chiffres de la section Avis,
 * pour que le site ne soit jamais vide à l'installation.
 */
function vr_badges_defaut() {
	return array(
		array( '4,86/5', 'sur Airbnb · Coup de cœur voyageurs' ),
		array( '5/5', 'sur Google' ),
		array( '5,0', 'en propreté, arrivée et qualité-prix' ),
		array( '100 %', 'de réponse, en moins d\'une heure' ),
	);
}

function vr_badge( $index, $partie ) {
	$defauts = vr_badges_defaut();
	$cle     = ( 'valeur' === $partie ) ? "vr_badge_{$index}_valeur" : "vr_badge_{$index}_label";
	$defaut  = ( 'valeur' === $partie ) ? $defauts[ $index - 1 ][0] : $defauts[ $index - 1 ][1];
	$valeur  = get_theme_mod( $cle, '' );

	return $valeur ? $valeur : $defaut;
}
