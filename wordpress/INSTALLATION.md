# Installation de la préproduction WordPress — Villa Raffy

Deux fichiers à installer : le **thème** (le design du site) et l'**extension** (le calendrier de réservation). Comptez dix minutes.

---

## 1 → Créer le WordPress sur Hostinger

Dans le panneau Hostinger, section **Sites web → Ajouter un site**, choisissez WordPress et installez-le sur un sous-domaine de test, par exemple `villaraffy.votredomaine.fr`.

Ne touchez surtout pas encore à villa-raffy.fr : le site Wix doit continuer de tourner pendant qu'on travaille.

Pendant l'installation, choisissez le **français** comme langue du site.

---

## 2 → Installer le thème

1 → Dans l'administration WordPress, allez dans **Apparence → Thèmes → Ajouter → Téléverser un thème**.

2 → Sélectionnez `villa-raffy-theme.zip`, cliquez sur **Installer maintenant**, puis sur **Activer**.

À l'activation, le thème crée automatiquement les contenus de démonstration : les quatre chambres (Suite Serenity, Chambre Urban Chic, Chambre Fleur de Charme, Suite Refuge des Lumières), les douze espaces de la visite guidée, les avis, les atouts, les lieux à découvrir et la FAQ. Tout se modifie ensuite depuis le menu **Ma villa**. Le site est immédiatement présentable, même sans photos.

---

## 3 → Installer l'extension de réservation

1 → Allez dans **Extensions → Ajouter → Téléverser une extension**.

2 → Sélectionnez `villa-raffy-reservations.zip`, cliquez sur **Installer maintenant**, puis sur **Activer**.

---

## 4 → La page d'accueil et les menus : rien à faire

À l'activation, le thème crée tout seul :

- la page **Accueil**, composée de treize blocs (un par section : grande image, chiffres clés, « Réserver en direct », visite guidée, la villa, les chambres, les formules, piscine & jardin, vidéo, avis, calendrier, la région, FAQ), et la désigne comme page d'accueil ;
- la page **Journal**, désignée comme page des articles du blog ;
- les deux menus (**Menu principal** et **Menu du pied de page**), déjà remplis, dans **Apparence → Menus**.

Si le thème était déjà installé avant cette version, la même chose se produit à votre prochaine connexion à l'administration, sans écraser une page Accueil déjà remplie.

### Modifier l'accueil comme sur Wix

1 → **Pages → Accueil → Modifier** (ou le lien « Modifier les sections de l'accueil » dans la barre du haut quand vous êtes sur le site).

2 → Chaque section porte une étiquette en haut à gauche (« Grande image d'accueil », « Les chambres »…). Cliquez sur une section : ses textes et ses photos apparaissent dans la colonne de droite, onglet **Bloc**. Modifiez, l'aperçu se met à jour sous vos yeux.

3 → Pour **déplacer** une section : les flèches haut/bas de la barre d'outils du bloc, ou glisser-déposer. Pour **supprimer** une section : les trois points → Supprimer. Pour la **remettre** : le bouton « + » → catégorie **Villa Raffy — sections**.

4 → Vous pouvez glisser entre deux sections n'importe quel bloc WordPress classique (paragraphe, image, galerie, colonnes, bouton) : il s'affiche centré, dans la largeur du site.

5 → **Enregistrer** en haut à droite. C'est en ligne.

Un champ laissé vide dans un bloc reprend la valeur de **Apparence → Personnaliser**. Les listes (chambres, espaces, avis, lieux, FAQ) se gèrent toujours dans **Ma villa**. Les sections animées (visite guidée, vidéo, calendrier) s'affichent dans l'éditeur sous forme de carte sombre : elles sont trop lourdes pour y être jouées, mais leurs textes se modifient de la même façon.

### Le menu et le pied de page

**Apparence → Menus** : choisissez « Menu principal » ou « Menu du pied de page », ajoutez, renommez, réordonnez les liens, enregistrez. Le bandeau d'en-tête (le texte sombre au-dessus du menu, le téléphone, l'email) et les colonnes du pied de page se règlent dans **Apparence → Personnaliser → Vos coordonnées** et **→ Pied de page**. Un logo peut remplacer le nom de la villa : **Personnaliser → Identité du site → Logo**.

---

## 5 → Renseigner les informations de la villa

Allez dans **Apparence → Personnaliser**. Tout se règle depuis là, sans jamais toucher au code :

- **Vos coordonnées** — téléphone, WhatsApp, email, adresse, texte du bandeau du haut, lien Airbnb, lien vers vos avis Google (les logos Google et Airbnb de la section Avis et du pied de page renvoient vers ces pages ; les deux liens sont déjà pré-remplis)
- **Grande image d'accueil** — la photo plein écran et les textes du haut de page
- **Vos arguments de confiance** — les mentions sous la grande image et les quatre chiffres de la section Avis
- **Règles de réservation** — nombre de voyageurs maximum, horaires (les prix et les saisons se règlent dans l'extension, voir l'étape 7)
- **Chiffres clés** — une ligne par chiffre, au format `valeur | légende | icône` (huit chiffres par défaut, dont l'écran géant de 3,5 m et la salle de sport privée)
- **Bloc « Réserver en direct »** — les trois arguments
- **Titres et textes des sections** — dont les deux textes de la section « Deux façons de séjourner » (villa complète / formule cocooning)
- **Vidéo de la villa** — déposez un montage MP4 : il se lance en boucle, sans son, dans la section « La villa en mouvement ». Sans vidéo, cette section enchaîne automatiquement en fondu les photos de la visite guidée.
- **Carte de localisation** — latitude et longitude
- **Pied de page**

⚠️ **L'email renseigné ici est celui qui recevra les demandes de réservation.** À vérifier en priorité.

Pour le lien des avis Google : ouvrez votre fiche Google Maps, cliquez sur « Avis », puis copiez l'adresse de la page.

---

## 6 → Déposer les photos

Allez dans **Médias → Ajouter**, déposez toutes les photos d'un coup, puis rattachez-les :

- La photo plein écran : dans la page **Accueil**, bloc « Grande image d'accueil », bouton **Choisir une photo** (ou **Apparence → Personnaliser → Grande image d'accueil**)
- Les photos des chambres : **Ma villa → Chambres**. L'**image mise en avant** est la photo principale (la chambre) ; l'encadré **Photos supplémentaires** accepte jusqu'à trois autres photos (salle de bain, petit salon…). Sur le site, les visiteurs les font défiler avec des flèches.
- Les photos de la visite guidée : **Ma villa → Visite guidée**, même principe : une image mise en avant, plus jusqu'à trois photos supplémentaires pour la piscine et son bar immergé, la plage privée…
- Pour donner un nom à une photo (« Salle de bain », « Le bar immergé »), remplissez sa **légende** dans la médiathèque : elle s'affiche en petit sur la photo.

Les photos de la visite guidée alimentent aussi la galerie de la section « La villa », la mosaïque « Piscine, plage & jardin » (les espaces dont la zone contient « extérieur », « piscine », « jardin » ou « plage ») et le diaporama de la section vidéo : vous ne les déposez qu'une seule fois.

Les photos préparées sur Canva doivent être téléchargées depuis Canva (Partager → Télécharger → JPG, qualité maximale) puis déposées dans **Médias** comme les autres.

Tant qu'une photo manque, un dégradé élégant s'affiche à sa place avec le nom de la pièce — le site reste présentable.

---

## 7 → Régler les tarifs et les saisons

Le menu **Réservations → Tarifs & saisons** pilote tout ce que le calendrier public affiche. Aucune ligne de code à toucher.

**Capacités** — le nombre de voyageurs maximum pour la villa complète (8) et pour la formule Cocooning (4).

**Saisons** — une ligne par période, avec pour chacune :

- ses dates de début et de fin (jour/mois, valables chaque année)
- son type : *basse saison* ou *haute saison*
- le nombre de nuits minimum
- les jours d'arrivée et de départ autorisés (cochez uniquement « samedi » pour imposer le samedi au samedi)
- le prix par nuit de la villa complète
- le prix par nuit de la formule Cocooning — laissez vide pour ne pas la proposer sur cette période

Les trois saisons demandées sont déjà en place : mai–juin (290 € / 190 €, arrivées libres, 2 nuits minimum), juillet–août (350 €, samedi au samedi, 7 nuits minimum, pas de Cocooning), septembre (290 € / 190 €). **Toute date qui n'est couverte par aucune saison est fermée** : d'octobre à avril, le calendrier affiche « fermé ». Pour ouvrir une période, ajoutez simplement une saison.

Sur le site, le visiteur choisit sa formule, voit le prix sous chaque jour, et le total se calcule sous ses yeux : « 7 nuits × 350 € = 2 450 € ». Le message WhatsApp ou l'email qu'il vous envoie reprend la formule, les dates, le nombre de voyageurs et ce total.

---

## 8 → Prendre en main le calendrier

Le menu **Réservations** contient trois écrans :

**Calendrier** — la vue de l'année entière, avec le prix sous chaque jour et une couleur par état (basse saison, haute saison, fermé, bloqué, réservé, tarif spécial). Cliquez sur un premier jour, puis sur un dernier jour : la plage se surligne et une barre apparaît avec les actions possibles :

- **Bloquer ces dates** / **Libérer ces dates** — pour vos séjours personnels, des travaux, ou une réservation prise ailleurs
- **Appliquer ce tarif** — un prix spécial pour cette plage (Pentecôte, pont du 15 août…), pour la villa et/ou le Cocooning ; **Retirer le tarif spécial** revient à la grille des saisons

Un seul jour ? Cliquez deux fois dessus. Les changements sont visibles immédiatement sur le site.

**Toutes les réservations** — le carnet. Chaque fiche contient le nom du voyageur, ses coordonnées, les dates, la formule, le nombre de personnes, le tarif (calculé automatiquement selon la grille, modifiable), l'acompte reçu, le statut et vos notes. Les réservations dont le statut est *Option*, *Confirmée* ou *Soldée* bloquent automatiquement leurs dates sur le site : inutile de les bloquer une seconde fois dans le calendrier.

En haut du carnet, un résumé affiche les séjours à venir, le nombre de nuits louées, le taux d'occupation sur les nuits ouvertes et le chiffre d'affaires de l'année.

**Tarifs & saisons** — voir l'étape 7.

⚠️ **Important :** il n'y a pas de synchronisation avec Airbnb ou Booking, c'est voulu. Si vous acceptez une réservation sur une plateforme, bloquez les dates dans le calendrier (deux clics).

---

## 9 → La version anglaise

L'extension **villa-raffy-english.zip** ajoute une version anglaise du site à l'adresse `/en/` (par exemple `villa-raffy.fr/en/`), sans dupliquer les contenus. Elle reprend la page française et remplace chaque texte par sa traduction, calendrier et message WhatsApp compris. Un petit bouton FR / EN apparaît en bas à gauche de chaque page.

1 → **Extensions → Ajouter → Téléverser une extension**, choisir `villa-raffy-english.zip`, **Installer**, puis **Activer**.

2 → Ouvrir `/en/` : le site est en anglais. Google reçoit aussi les balises hreflang qui relient les deux versions.

3 → Pour corriger une traduction ou en ajouter une : **Réglages → Version anglaise**. Le dictionnaire se présente ainsi : une ligne en français (exactement comme sur le site), la ligne suivante en anglais, une ligne vide entre chaque paire. Enregistrer.

⚠️ Quand vous changez un texte français sur le site, il reste en français sur la version anglaise tant que sa traduction n'est pas ajoutée. L'écran **Réglages → Version anglaise** liste les textes rencontrés sans traduction et un bouton les ajoute en bas du dictionnaire : il ne reste qu'à écrire l'anglais sous chacun. Les prix, numéros de téléphone, emails et prénoms ne sont jamais traduits.

---

## Ce que voit le propriétaire

Le tableau de bord affiche un encadré « Gérer la Villa Raffy » avec les raccourcis du quotidien : modifier les sections de l'accueil, bloquer des dates, voir les réservations, coordonnées et pied de page, chambres et visite guidée, photos, écrire un article.

Les menus techniques inutiles (commentaires, outils) sont masqués pour les comptes non administrateurs. Créez donc le compte de Stéphane avec le rôle **Éditeur**, pas Administrateur.

---

## Extensions recommandées

- **SEOPress** (gratuit) — si vous l'activez, le thème lui laisse automatiquement la main sur les balises. Sans lui, le thème gère déjà le référencement.
- **UpdraftPlus** (gratuit) — sauvegardes automatiques.

Rien d'autre n'est nécessaire : aucune extension payante, aucun constructeur de page.

---

## Le jour de la bascule

1 → Vérifier que tout fonctionne sur le sous-domaine de test.

2 → Dans le tableau de bord Wix, remplacer les enregistrements A du domaine par les adresses IP de l'hébergement Hostinger.

3 → Activer le certificat SSL dans Hostinger et vérifier que `https://` répond.

4 → Les redirections des anciennes adresses Wix sont déjà prêtes dans le thème (fichier `inc/redirections.php`) : elles s'activent toutes seules.

5 → Ajouter le site dans la Search Console et soumettre le sitemap.

6 → **Transférer le domaine hors de Wix**, et seulement une fois le transfert confirmé, résilier l'abonnement Wix. Le domaine ayant été acheté via Wix (registrar EPAG/Tucows), une résiliation prématurée ferait perdre villa-raffy.fr.
