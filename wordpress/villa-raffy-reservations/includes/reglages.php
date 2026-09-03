<?php
/**
 * Page « Tarifs & saisons » : périodes, prix, règles de séjour, jours d'arrivée.
 *
 * @package VillaRaffyReservations
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vrr_menu_reglages() {
	add_submenu_page(
		'edit.php?post_type=vr_reservation',
		'Tarifs & saisons',
		'Tarifs & saisons',
		'edit_posts',
		'vr-tarifs',
		'vrr_page_reglages'
	);
}
add_action( 'admin_menu', 'vrr_menu_reglages' );

/**
 * « JJ/MM » saisi par le propriétaire → « MM-JJ » stocké.
 */
function vrr_jjmm_vers_mmjj( $texte ) {
	if ( preg_match( '/^(\d{1,2})\s*\/\s*(\d{1,2})$/', trim( (string) $texte ), $m ) ) {
		$jour = max( 1, min( 31, (int) $m[1] ) );
		$mois = max( 1, min( 12, (int) $m[2] ) );
		return sprintf( '%02d-%02d', $mois, $jour );
	}
	return '';
}

function vrr_mmjj_vers_jjmm( $mmjj ) {
	if ( preg_match( '/^(\d{2})-(\d{2})$/', (string) $mmjj, $m ) ) {
		return $m[2] . '/' . $m[1];
	}
	return '';
}

function vrr_jours_semaine() {
	return array( 1 => 'Lun', 2 => 'Mar', 3 => 'Mer', 4 => 'Jeu', 5 => 'Ven', 6 => 'Sam', 7 => 'Dim' );
}

function vrr_page_reglages() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return;
	}

	// ─── Enregistrement ───
	if ( isset( $_POST['vrr_reglages_nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['vrr_reglages_nonce'] ), 'vrr_reglages' ) ) {

		$capacites = array(
			'complete'  => isset( $_POST['cap_complete'] ) ? max( 1, absint( $_POST['cap_complete'] ) ) : 8,
			'cocooning' => isset( $_POST['cap_cocooning'] ) ? max( 1, absint( $_POST['cap_cocooning'] ) ) : 4,
		);
		update_option( 'vrr_capacites', $capacites );

		$saisons = array();
		$lignes  = isset( $_POST['saison'] ) && is_array( $_POST['saison'] ) ? wp_unslash( $_POST['saison'] ) : array();

		foreach ( $lignes as $ligne ) {
			$debut = vrr_jjmm_vers_mmjj( isset( $ligne['debut'] ) ? $ligne['debut'] : '' );
			$fin   = vrr_jjmm_vers_mmjj( isset( $ligne['fin'] ) ? $ligne['fin'] : '' );
			$nom   = sanitize_text_field( isset( $ligne['nom'] ) ? $ligne['nom'] : '' );

			if ( ! $debut || ! $fin || ! $nom ) {
				continue;
			}

			$arrivee = isset( $ligne['arrivee'] ) ? array_map( 'intval', (array) $ligne['arrivee'] ) : array();
			$depart  = isset( $ligne['depart'] ) ? array_map( 'intval', (array) $ligne['depart'] ) : array();

			$saisons[] = array(
				'nom'            => $nom,
				'debut'          => $debut,
				'fin'            => $fin,
				'type'           => ( isset( $ligne['type'] ) && 'haute' === $ligne['type'] ) ? 'haute' : 'basse',
				'min_nuits'      => isset( $ligne['min_nuits'] ) ? max( 1, absint( $ligne['min_nuits'] ) ) : 1,
				'arrivee'        => $arrivee ? $arrivee : array( 1, 2, 3, 4, 5, 6, 7 ),
				'depart'         => $depart ? $depart : array( 1, 2, 3, 4, 5, 6, 7 ),
				'prix_complete'  => ( isset( $ligne['prix_complete'] ) && '' !== trim( $ligne['prix_complete'] ) ) ? absint( $ligne['prix_complete'] ) : '',
				'prix_cocooning' => ( isset( $ligne['prix_cocooning'] ) && '' !== trim( $ligne['prix_cocooning'] ) ) ? absint( $ligne['prix_cocooning'] ) : '',
			);
		}

		update_option( 'vrr_saisons', $saisons );
		vrr_vider_cache();

		echo '<div class="notice notice-success is-dismissible"><p>Vos tarifs et saisons ont bien été enregistrés.</p></div>';
	}

	$formules = vrr_formules();
	$saisons  = vrr_saisons();
	$jours    = vrr_jours_semaine();
	?>

	<div class="wrap vr-cal-admin">
		<h1>Tarifs &amp; saisons</h1>

		<div class="vr-cal-admin__rappel">
			<strong>Comment ça marche :</strong> chaque ligne définit une période de l'année (les dates se répètent chaque année), son prix par nuit pour chaque formule, le nombre de nuits minimum et les jours d'arrivée et de départ autorisés.
			<br />Toute date qui n'est couverte par aucune période est <strong>fermée à la réservation</strong> — c'est ainsi que la villa est fermée d'octobre à avril.
			<br />Laissez le prix « Cocooning » vide pour ne pas proposer cette formule sur la période (par exemple en haute saison).
		</div>

		<form method="post">
			<?php wp_nonce_field( 'vrr_reglages', 'vrr_reglages_nonce' ); ?>

			<h2>Les formules</h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="cap_complete">Villa complète — voyageurs maximum</label></th>
					<td><input type="number" id="cap_complete" name="cap_complete" min="1" max="30" value="<?php echo esc_attr( $formules['complete']['capacite'] ); ?>" class="small-text" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="cap_cocooning">Formule Cocooning — voyageurs maximum</label></th>
					<td>
						<input type="number" id="cap_cocooning" name="cap_cocooning" min="1" max="30" value="<?php echo esc_attr( $formules['cocooning']['capacite'] ); ?>" class="small-text" />
						<p class="description">La villa et le jardin restent entièrement privatisés ; deux chambres sont fermées.</p>
					</td>
				</tr>
			</table>

			<h2>Les périodes de l'année</h2>

			<div class="vr-saisons" id="vr-saisons">
				<?php foreach ( $saisons as $i => $saison ) : ?>
					<?php vrr_ligne_saison( $i, $saison, $jours ); ?>
				<?php endforeach; ?>
			</div>

			<p>
				<button type="button" class="button" id="vr-ajouter-saison">+ Ajouter une période</button>
			</p>

			<p style="margin-top:24px">
				<button type="submit" class="button button-primary button-hero">Enregistrer les tarifs et saisons</button>
			</p>
		</form>

		<!-- Modèle d'une ligne vierge, dupliqué par le bouton « Ajouter » -->
		<template id="vr-saison-modele">
			<?php
			vrr_ligne_saison( '__INDEX__', array(
				'nom'            => '',
				'debut'          => '',
				'fin'            => '',
				'type'           => 'basse',
				'min_nuits'      => 2,
				'arrivee'        => array( 1, 2, 3, 4, 5, 6, 7 ),
				'depart'         => array( 1, 2, 3, 4, 5, 6, 7 ),
				'prix_complete'  => '',
				'prix_cocooning' => '',
			), $jours );
			?>
		</template>
	</div>

	<script>
	( function () {
		var conteneur = document.getElementById( 'vr-saisons' );
		var modele = document.getElementById( 'vr-saison-modele' );
		var bouton = document.getElementById( 'vr-ajouter-saison' );

		if ( bouton && modele && conteneur ) {
			bouton.addEventListener( 'click', function () {
				var index = Date.now();
				var html = modele.innerHTML.replace( /__INDEX__/g, index );
				conteneur.insertAdjacentHTML( 'beforeend', html );
			} );
		}

		document.addEventListener( 'click', function ( e ) {
			if ( e.target.classList.contains( 'vr-saison__supprimer' ) ) {
				if ( window.confirm( 'Supprimer cette période ? Les dates qu\'elle couvre deviendront fermées.' ) ) {
					e.target.closest( '.vr-saison' ).remove();
				}
			}
		} );
	} )();
	</script>
	<?php
}

/**
 * Une ligne de période dans le formulaire.
 */
function vrr_ligne_saison( $i, $saison, $jours ) {
	$prefixe = 'saison[' . $i . ']';
	$arrivee = array_map( 'intval', (array) $saison['arrivee'] );
	$depart  = array_map( 'intval', (array) $saison['depart'] );
	?>
	<div class="vr-saison vr-saison--<?php echo esc_attr( $saison['type'] ); ?>">
		<div class="vr-saison__ligne">
			<label>
				<span>Nom de la période</span>
				<input type="text" name="<?php echo esc_attr( $prefixe ); ?>[nom]" value="<?php echo esc_attr( $saison['nom'] ); ?>" placeholder="Haute saison" class="regular-text" />
			</label>
			<label>
				<span>Du (JJ/MM)</span>
				<input type="text" name="<?php echo esc_attr( $prefixe ); ?>[debut]" value="<?php echo esc_attr( vrr_mmjj_vers_jjmm( $saison['debut'] ) ); ?>" placeholder="01/07" class="small-text" style="width:70px" />
			</label>
			<label>
				<span>Au (JJ/MM)</span>
				<input type="text" name="<?php echo esc_attr( $prefixe ); ?>[fin]" value="<?php echo esc_attr( vrr_mmjj_vers_jjmm( $saison['fin'] ) ); ?>" placeholder="31/08" class="small-text" style="width:70px" />
			</label>
			<label>
				<span>Type</span>
				<select name="<?php echo esc_attr( $prefixe ); ?>[type]">
					<option value="basse"<?php selected( $saison['type'], 'basse' ); ?>>Basse saison</option>
					<option value="haute"<?php selected( $saison['type'], 'haute' ); ?>>Haute saison</option>
				</select>
			</label>
			<button type="button" class="button-link-delete vr-saison__supprimer" title="Supprimer cette période">Supprimer</button>
		</div>

		<div class="vr-saison__ligne">
			<label>
				<span>Villa complète — € / nuit</span>
				<input type="number" name="<?php echo esc_attr( $prefixe ); ?>[prix_complete]" value="<?php echo esc_attr( $saison['prix_complete'] ); ?>" min="0" class="small-text" />
			</label>
			<label>
				<span>Cocooning — € / nuit <em>(vide = non proposé)</em></span>
				<input type="number" name="<?php echo esc_attr( $prefixe ); ?>[prix_cocooning]" value="<?php echo esc_attr( $saison['prix_cocooning'] ); ?>" min="0" class="small-text" />
			</label>
			<label>
				<span>Nuits minimum</span>
				<input type="number" name="<?php echo esc_attr( $prefixe ); ?>[min_nuits]" value="<?php echo esc_attr( $saison['min_nuits'] ); ?>" min="1" class="small-text" />
			</label>
		</div>

		<div class="vr-saison__ligne vr-saison__jours">
			<div>
				<span>Jours d'arrivée autorisés</span>
				<?php foreach ( $jours as $num => $nom ) : ?>
					<label class="vr-saison__jour">
						<input type="checkbox" name="<?php echo esc_attr( $prefixe ); ?>[arrivee][]" value="<?php echo esc_attr( $num ); ?>"<?php checked( in_array( $num, $arrivee, true ) ); ?> />
						<?php echo esc_html( $nom ); ?>
					</label>
				<?php endforeach; ?>
			</div>
			<div>
				<span>Jours de départ autorisés</span>
				<?php foreach ( $jours as $num => $nom ) : ?>
					<label class="vr-saison__jour">
						<input type="checkbox" name="<?php echo esc_attr( $prefixe ); ?>[depart][]" value="<?php echo esc_attr( $num ); ?>"<?php checked( in_array( $num, $depart, true ) ); ?> />
						<?php echo esc_html( $nom ); ?>
					</label>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php
}
