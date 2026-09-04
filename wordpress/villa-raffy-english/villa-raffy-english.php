<?php
/**
 * Plugin Name: Villa Raffy — Version anglaise
 * Description: Ajoute une version anglaise du site à l'adresse /en/, sans dupliquer les contenus : les textes français sont traduits à l'affichage grâce à un dictionnaire modifiable dans Réglages → Version anglaise.
 * Version: 1.0.0
 * Author: Tristan Wiart
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Text Domain: villa-raffy-english
 *
 * @package VillaRaffyEnglish
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VRE_VERSION', '1.0.0' );
define( 'VRE_DIR', plugin_dir_path( __FILE__ ) );
define( 'VRE_URL', plugin_dir_url( __FILE__ ) );
define( 'VRE_PREFIXE', '/en' );

/* ═══════════════════════════════════════════════════════════
   1. DÉTECTION DE LA LANGUE
   L'adresse /en/… est reconnue avant que WordPress ne lise l'URL :
   on retire « /en » et WordPress sert la page française habituelle,
   que l'on traduit ensuite à la volée (étape 3).
   ═══════════════════════════════════════════════════════════ */

function vre_detecter_langue() {
	if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
		return;
	}

	$uri = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '';

	// Le site peut être installé dans un sous-dossier : on l'ignore.
	$racine = rtrim( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ), '/' );
	$chemin = $racine && 0 === strpos( $uri, $racine ) ? substr( $uri, strlen( $racine ) ) : $uri;

	if ( ! preg_match( '#^' . preg_quote( VRE_PREFIXE, '#' ) . '(?=/|\?|$)#', $chemin ) ) {
		return;
	}

	$GLOBALS['vre_langue'] = 'en';

	$reste = substr( $chemin, strlen( VRE_PREFIXE ) );
	if ( '' === $reste || '?' === $reste[0] ) {
		$reste = '/' . $reste;
	}
	$_SERVER['REQUEST_URI'] = $racine . $reste;
}
add_action( 'plugins_loaded', 'vre_detecter_langue', 1 );

/**
 * Vrai quand la page demandée est la version anglaise.
 */
function vre_est_anglais() {
	return ! empty( $GLOBALS['vre_langue'] ) && 'en' === $GLOBALS['vre_langue'];
}

/**
 * Chemin de la page courante sans le préfixe /en (ex. « /journal/ »).
 */
function vre_chemin_courant() {
	$uri    = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '/';
	$racine = rtrim( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ), '/' );
	if ( $racine && 0 === strpos( $uri, $racine ) ) {
		$uri = substr( $uri, strlen( $racine ) );
	}
	return '' === $uri ? '/' : $uri;
}

/* ═══════════════════════════════════════════════════════════
   2. RÉGLAGES DE WORDPRESS EN MODE ANGLAIS
   ═══════════════════════════════════════════════════════════ */

/**
 * Les liens internes gardent le préfixe /en (menu, boutons, articles),
 * sauf les adresses techniques (API, administration, fichiers).
 */
function vre_filtrer_home_url( $url, $path ) {
	if ( ! vre_est_anglais() ) {
		return $url;
	}
	$techniques = array( '/wp-json', '/wp-admin', '/wp-content', '/wp-includes', '/wp-login', '/xmlrpc', '/feed', '?rest_route' );
	foreach ( $techniques as $t ) {
		if ( $path && 0 === strpos( $path, $t ) ) {
			return $url;
		}
	}
	$base = untrailingslashit( get_option( 'home' ) );
	if ( 0 !== strpos( $url, $base ) ) {
		return $url;
	}
	$suite = substr( $url, strlen( $base ) );
	if ( '' === $suite ) {
		$suite = '/';
	}
	if ( VRE_PREFIXE === $suite || 0 === strpos( $suite, VRE_PREFIXE . '/' ) || 0 === strpos( $suite, VRE_PREFIXE . '?' ) ) {
		return $url;
	}
	return $base . VRE_PREFIXE . $suite;
}
add_filter( 'home_url', 'vre_filtrer_home_url', 10, 2 );

/**
 * La langue déclarée de la page, et le format des dates de WordPress.
 */
add_filter( 'language_attributes', function ( $attr ) {
	return vre_est_anglais() ? 'lang="en"' : $attr;
} );

add_filter( 'locale', function ( $locale ) {
	return vre_est_anglais() ? 'en_US' : $locale;
} );

add_filter( 'body_class', function ( $classes ) {
	if ( vre_est_anglais() ) {
		$classes[] = 'vre-en';
	}
	return $classes;
} );

/**
 * Balises hreflang : Google sait que /en/ est la version anglaise de la page.
 */
function vre_hreflang() {
	$chemin = vre_chemin_courant();
	$base   = untrailingslashit( get_option( 'home' ) );
	$fr     = $base . $chemin;
	$en     = $base . VRE_PREFIXE . $chemin;
	printf( '<link rel="alternate" hreflang="fr" href="%s" />' . "\n", esc_url( $fr ) );
	printf( '<link rel="alternate" hreflang="en" href="%s" />' . "\n", esc_url( $en ) );
	printf( '<link rel="alternate" hreflang="x-default" href="%s" />' . "\n", esc_url( $fr ) );
}
add_action( 'wp_head', 'vre_hreflang', 2 );

/**
 * Script qui traduit ce que le navigateur génère lui-même
 * (calendrier, messages, texte WhatsApp), et bouton FR / EN.
 */
function vre_assets() {
	wp_enqueue_style( 'vre-style', VRE_URL . 'assets/english.css', array(), VRE_VERSION );
	if ( vre_est_anglais() ) {
		wp_enqueue_script( 'vre-script', VRE_URL . 'assets/english.js', array( 'vr-script' ), VRE_VERSION, true );
		wp_localize_script( 'vre-script', 'vreData', array( 'dico' => vre_dictionnaire() ) );
	}
}
add_action( 'wp_enqueue_scripts', 'vre_assets', 20 );

function vre_bouton_langue() {
	$chemin = vre_chemin_courant();
	$base   = untrailingslashit( get_option( 'home' ) );
	$fr     = $base . $chemin;
	$en     = $base . VRE_PREFIXE . $chemin;
	$anglais = vre_est_anglais();
	printf(
		'<nav class="vre-switch" aria-label="%s"><a href="%s" hreflang="fr"%s>FR</a><a href="%s" hreflang="en"%s>EN</a></nav>',
		$anglais ? 'Language' : 'Langue',
		esc_url( $fr ),
		$anglais ? '' : ' aria-current="true"',
		esc_url( $en ),
		$anglais ? ' aria-current="true"' : ''
	);
}
add_action( 'wp_footer', 'vre_bouton_langue' );

/* ═══════════════════════════════════════════════════════════
   3. TRADUCTION DE LA PAGE À L'AFFICHAGE
   ═══════════════════════════════════════════════════════════ */

/**
 * Normalise un texte pour la recherche dans le dictionnaire :
 * espaces multiples, espaces insécables, apostrophes typographiques.
 */
function vre_cle( $texte ) {
	$texte = html_entity_decode( $texte, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
	$texte = str_replace( array( "\xc2\xa0", "\xe2\x80\xaf", "\xe2\x80\x99" ), array( ' ', ' ', "'" ), $texte );
	$texte = preg_replace( '/\s+/u', ' ', $texte );
	return trim( $texte );
}

/**
 * Le dictionnaire complet : celui livré avec l'extension,
 * complété ou corrigé par celui enregistré dans les réglages.
 */
function vre_dictionnaire() {
	static $dico = null;
	if ( null !== $dico ) {
		return $dico;
	}
	$dico = array();
	foreach ( (array) include VRE_DIR . 'dictionnaire.php' as $fr => $en ) {
		$dico[ vre_cle( $fr ) ] = $en;
	}
	foreach ( vre_analyser_texte( (string) get_option( 'vre_dictionnaire', '' ) ) as $fr => $en ) {
		$dico[ vre_cle( $fr ) ] = $en;
	}
	return $dico;
}

/**
 * Lit le format des réglages : une ligne en français, la ligne suivante
 * en anglais, une ligne vide entre chaque paire.
 */
function vre_analyser_texte( $texte ) {
	$paires = array();
	$blocs  = preg_split( '/\n\s*\n/', str_replace( "\r", '', $texte ) );
	foreach ( $blocs as $bloc ) {
		$lignes = array_values( array_filter( array_map( 'trim', explode( "\n", $bloc ) ), 'strlen' ) );
		if ( count( $lignes ) >= 2 ) {
			$paires[ $lignes[0] ] = implode( ' ', array_slice( $lignes, 1 ) );
		}
	}
	return $paires;
}

/**
 * Traduit un texte (ou le renvoie inchangé). Les textes sans lettres
 * (prix, numéros, symboles) ne sont jamais traduits.
 */
function vre_traduire( $texte, &$manquants = null ) {
	$cle = vre_cle( $texte );
	if ( '' === $cle || ! preg_match( '/\p{L}{2}/u', $cle ) ) {
		return $texte;
	}
	$dico = vre_dictionnaire();
	if ( isset( $dico[ $cle ] ) ) {
		return $dico[ $cle ];
	}
	if ( is_array( $manquants ) && count( $manquants ) < 300 && vre_vaut_signalement( $cle ) ) {
		$manquants[ $cle ] = true;
	}
	return null;
}

/**
 * Faut-il signaler ce texte comme « sans traduction » au propriétaire ?
 * On ignore les adresses web, emails, noms propres isolés et adresses postales.
 */
function vre_vaut_signalement( $cle ) {
	if ( preg_match( '#^(https?://|www\.|[\w.+-]+@)#i', $cle ) ) {
		return false;
	}
	if ( false === strpos( $cle, ' ' ) ) {
		return false; // Un seul mot : prénom, marque, sigle.
	}
	if ( preg_match( '/\b\d{5}\b/', $cle ) || preg_match( '/^\W*\d+\s+(rue|avenue|chemin|route|place|impasse|allée|boulevard|lieu-dit)\b/iu', $cle ) ) {
		return false; // Adresse postale.
	}
	return true;
}

/**
 * Traduit le HTML complet d'une page : les textes entre les balises
 * et les attributs visibles (alt, title, placeholder, aria-label, meta).
 * Les scripts et les styles sont laissés intacts.
 */
function vre_traduire_html( $html ) {
	$manquants = array();
	$morceaux  = preg_split( '#(<script\b[^>]*>.*?</script>|<style\b[^>]*>.*?</style>)#si', $html, -1, PREG_SPLIT_DELIM_CAPTURE );

	foreach ( $morceaux as $i => $morceau ) {
		if ( $i % 2 ) {
			continue; // Script ou style.
		}

		$morceau = preg_replace_callback(
			'/>([^<]+)</u',
			function ( $m ) use ( &$manquants ) {
				$en = vre_traduire( $m[1], $manquants );
				if ( null === $en || $en === $m[1] ) {
					return $m[0];
				}
				preg_match( '/^\s*/', $m[1], $avant );
				preg_match( '/\s*$/', $m[1], $apres );
				return '>' . $avant[0] . htmlspecialchars( $en, ENT_NOQUOTES, 'UTF-8', false ) . $apres[0] . '<';
			},
			$morceau
		);

		// Attributs visibles des balises (image, bouton, lien…), sauf les balises techniques.
		$morceau = preg_replace_callback(
			'/<([a-z][a-z0-9-]*)\b([^>]*)>/i',
			function ( $m ) use ( &$manquants ) {
				$balise = strtolower( $m[1] );
				if ( in_array( $balise, array( 'meta', 'link', 'script', 'style', 'html', 'head', 'body' ), true ) ) {
					return $m[0];
				}
				$attributs = preg_replace_callback(
					'/\s(alt|title|placeholder|aria-label)="([^"]*)"/u',
					function ( $a ) use ( &$manquants ) {
						$en = vre_traduire( $a[2], $manquants );
						if ( null === $en || $en === $a[2] ) {
							return $a[0];
						}
						return ' ' . $a[1] . '="' . htmlspecialchars( $en, ENT_QUOTES, 'UTF-8', false ) . '"';
					},
					$m[2]
				);
				return '<' . $m[1] . $attributs . '>';
			},
			$morceau
		);

		// Balises meta du référencement : description, Open Graph, Twitter.
		$morceau = preg_replace_callback(
			'/<meta\b[^>]*\b(?:name|property)="(description|og:[a-z_]+|twitter:[a-z_]+)"[^>]*>/i',
			function ( $m ) use ( &$manquants ) {
				return preg_replace_callback(
					'/\scontent="([^"]*)"/u',
					function ( $a ) use ( &$manquants ) {
						$en = vre_traduire( $a[1], $manquants );
						if ( null === $en || $en === $a[1] ) {
							return $a[0];
						}
						return ' content="' . htmlspecialchars( $en, ENT_QUOTES, 'UTF-8', false ) . '"';
					},
					$m[0]
				);
			},
			$morceau
		);

		$morceaux[ $i ] = $morceau;
	}

	if ( $manquants ) {
		vre_memoriser_manquants( array_keys( $manquants ) );
	}

	return implode( '', $morceaux );
}

/**
 * Garde en mémoire les textes français rencontrés sans traduction,
 * pour les proposer dans Réglages → Version anglaise.
 */
function vre_memoriser_manquants( $nouveaux ) {
	$connus  = (array) get_option( 'vre_manquants', array() );
	$fusion  = array_values( array_unique( array_merge( $connus, $nouveaux ) ) );
	$fusion  = array_slice( $fusion, -300 );
	if ( $fusion !== $connus ) {
		update_option( 'vre_manquants', $fusion, false );
	}
}

function vre_demarrer_traduction() {
	if ( vre_est_anglais() && ! is_feed() && ! is_robots() ) {
		ob_start( 'vre_traduire_html' );
	}
}
add_action( 'template_redirect', 'vre_demarrer_traduction', 0 );

/* ═══════════════════════════════════════════════════════════
   4. ÉCRAN DE RÉGLAGES
   ═══════════════════════════════════════════════════════════ */

function vre_menu() {
	add_options_page( 'Version anglaise', 'Version anglaise', 'edit_theme_options', 'villa-raffy-english', 'vre_page_reglages' );
}
add_action( 'admin_menu', 'vre_menu' );

function vre_enregistrer() {
	if ( ! isset( $_POST['vre_action'] ) || ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	check_admin_referer( 'vre_reglages' );

	if ( 'reinitialiser' === $_POST['vre_action'] ) {
		delete_option( 'vre_dictionnaire' );
		delete_option( 'vre_manquants' );
	} else {
		$texte = isset( $_POST['vre_dictionnaire'] ) ? wp_unslash( $_POST['vre_dictionnaire'] ) : '';
		update_option( 'vre_dictionnaire', sanitize_textarea_field( $texte ), false );
		delete_option( 'vre_manquants' );
	}

	wp_safe_redirect( add_query_arg( array( 'page' => 'villa-raffy-english', 'enregistre' => 1 ), admin_url( 'options-general.php' ) ) );
	exit;
}
add_action( 'admin_init', 'vre_enregistrer' );

/**
 * Le dictionnaire au format texte, prêt à être modifié.
 */
function vre_texte_dictionnaire() {
	$perso = (string) get_option( 'vre_dictionnaire', '' );
	if ( '' !== trim( $perso ) ) {
		return $perso;
	}
	$lignes = array();
	foreach ( (array) include VRE_DIR . 'dictionnaire.php' as $fr => $en ) {
		$lignes[] = $fr . "\n" . $en;
	}
	return implode( "\n\n", $lignes );
}

function vre_page_reglages() {
	$manquants = (array) get_option( 'vre_manquants', array() );
	$en_url    = untrailingslashit( get_option( 'home' ) ) . VRE_PREFIXE . '/';
	?>
	<div class="wrap">
		<h1>Version anglaise</h1>

		<?php if ( isset( $_GET['enregistre'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p>Dictionnaire enregistré. La version anglaise est à jour.</p></div>
		<?php endif; ?>

		<p>La version anglaise du site est en ligne à l'adresse <a href="<?php echo esc_url( $en_url ); ?>" target="_blank"><?php echo esc_html( $en_url ); ?></a>. Elle reprend la version française et remplace chaque texte par sa traduction ci-dessous.</p>

		<div style="background:#fff;border:1px solid #dcdcde;padding:14px 18px;max-width:900px;margin:12px 0">
			<strong>Comment ça marche :</strong> une ligne en français (exactement comme sur le site), la ligne suivante en anglais, puis une ligne vide. Quand vous changez un texte français sur le site, ajoutez ou corrigez sa ligne ici : sinon il reste en français sur la version anglaise. Les prix, numéros de téléphone et emails ne sont jamais traduits.
		</div>

		<?php if ( $manquants ) : ?>
			<div class="notice notice-warning" style="max-width:900px">
				<p><strong><?php echo count( $manquants ); ?> texte<?php echo count( $manquants ) > 1 ? 's' : ''; ?> sans traduction</strong> rencontré<?php echo count( $manquants ) > 1 ? 's' : ''; ?> sur la version anglaise. Cliquez sur « Ajouter ces textes » : ils apparaissent en bas du dictionnaire, il ne reste qu'à écrire l'anglais sous chacun.</p>
				<p><button type="button" class="button" id="vre-ajouter">Ajouter ces textes au dictionnaire</button></p>
				<ul id="vre-manquants" style="list-style:disc;padding-left:20px;max-height:180px;overflow:auto">
					<?php foreach ( $manquants as $m ) : ?>
						<li><?php echo esc_html( $m ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

		<form method="post">
			<?php wp_nonce_field( 'vre_reglages' ); ?>
			<input type="hidden" name="vre_action" value="enregistrer" />
			<textarea name="vre_dictionnaire" id="vre-dictionnaire" rows="30" style="width:100%;max-width:900px;font-family:monospace;font-size:13px;line-height:1.5"><?php echo esc_textarea( vre_texte_dictionnaire() ); ?></textarea>
			<p>
				<button type="submit" class="button button-primary">Enregistrer le dictionnaire</button>
				<button type="submit" class="button" onclick="this.form.vre_action.value='reinitialiser';return confirm('Remettre le dictionnaire d\'origine ? Vos modifications seront perdues.');">Remettre le dictionnaire d'origine</button>
			</p>
		</form>
	</div>
	<script>
	( function () {
		var bouton = document.getElementById( 'vre-ajouter' );
		if ( ! bouton ) { return; }
		bouton.addEventListener( 'click', function () {
			var zone = document.getElementById( 'vre-dictionnaire' );
			var lignes = Array.prototype.map.call( document.querySelectorAll( '#vre-manquants li' ), function ( li ) { return li.textContent; } );
			zone.value = zone.value.replace( /\s+$/, '' ) + '\n\n' + lignes.map( function ( l ) { return l + '\n'; } ).join( '\n' );
			zone.focus();
			zone.scrollTop = zone.scrollHeight;
			bouton.disabled = true;
			bouton.textContent = 'Ajoutés en bas du dictionnaire : écrivez l\'anglais sous chaque ligne, puis enregistrez.';
		} );
	} )();
	</script>
	<?php
}

/* ═══════════════════════════════════════════════════════════
   5. ACTIVATION
   ═══════════════════════════════════════════════════════════ */

register_activation_hook( __FILE__, function () {
	delete_option( 'vre_manquants' );
} );
