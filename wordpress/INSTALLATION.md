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

À l'activation, le thème crée automatiquement les contenus de démonstration : les quatre chambres, les douze espaces de la visite guidée, les avis, les atouts, les lieux à découvrir et la FAQ. Le site est immédiatement présentable, même sans photos.

---

## 3 → Installer l'extension de réservation

1 → Allez dans **Extensions → Ajouter → Téléverser une extension**.

2 → Sélectionnez `villa-raffy-reservations.zip`, cliquez sur **Installer maintenant**, puis sur **Activer**.

---

## 4 → Régler la page d'accueil

1 → Allez dans **Réglages → Lecture**.

2 → Cochez **Une page statique**, puis créez ou choisissez une page nommée « Accueil » comme page d'accueil.

3 → Créez une seconde page nommée « Journal » et désignez-la comme **page des articles** (c'est là que le blog s'affichera).

Le thème détecte automatiquement la page d'accueil et y affiche toutes les sections : la grande image, la visite guidée, les chambres, le calendrier, la carte et la FAQ.

---

## 5 → Renseigner les informations de la villa

Allez dans **Apparence → Personnaliser**. Tout se règle depuis là, sans jamais toucher au code :

- **Vos coordonnées** — téléphone, WhatsApp, email, adresse, lien Airbnb
- **Grande image d'accueil** — la photo plein écran et les textes du haut de page
- **Vos arguments de confiance** — les mentions sous la grande image et les quatre chiffres de la section Avis
- **Règles de réservation** — nuits minimum, nombre de voyageurs maximum, horaires
- **Chiffres clés** — une ligne par chiffre, au format `valeur | légende | icône`
- **Bloc « Réserver en direct »** — les trois arguments
- **Titres et textes des sections**
- **Carte de localisation** — latitude et longitude
- **Pied de page**

⚠️ **L'email renseigné ici est celui qui recevra les demandes de réservation.** À vérifier en priorité.

---

## 6 → Déposer les photos

Allez dans **Médias → Ajouter**, déposez toutes les photos d'un coup, puis rattachez-les :

- La photo plein écran : **Apparence → Personnaliser → Grande image d'accueil**
- Les photos des chambres : **Ma villa → Chambres**, une image mise en avant par chambre
- Les photos de la visite guidée : **Ma villa → Visite guidée**, une image par espace

Les photos de la visite guidée alimentent aussi la galerie de la section « La villa » et la mosaïque « Piscine & jardin » : vous ne les déposez qu'une seule fois.

Tant qu'une photo manque, un dégradé élégant s'affiche à sa place avec le nom de la pièce — le site reste présentable.

---

## 7 → Prendre en main le calendrier

Le menu **Réservations** contient deux écrans :

**Calendrier** — la vue de l'année entière. Un clic sur un jour le bloque, un second clic le libère. N'oubliez pas d'enregistrer en bas de page. Les dates bloquées deviennent immédiatement indisponibles sur le calendrier public du site.

**Toutes les réservations** — le carnet. Chaque fiche contient le nom du voyageur, ses coordonnées, les dates, le nombre de personnes, le tarif, l'acompte reçu, le statut et vos notes. Les réservations dont le statut est *Option*, *Confirmée* ou *Soldée* bloquent automatiquement leurs dates sur le site : inutile de les bloquer une seconde fois dans le calendrier.

En haut du carnet, un résumé affiche les séjours à venir, le nombre de nuits louées, le taux d'occupation et le chiffre d'affaires de l'année.

⚠️ **Important :** il n'y a pas de synchronisation avec Airbnb ou Booking. Si vous acceptez une réservation sur une plateforme, pensez à bloquer les dates dans le calendrier.

---

## Ce que voit le propriétaire

Le tableau de bord affiche un encadré « Gérer la Villa Raffy » avec les cinq raccourcis du quotidien : bloquer des dates, voir les réservations, modifier les textes, gérer les photos, écrire un article.

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
