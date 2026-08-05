# Villa Raffy — villa-raffy.fr

Refonte complète du site de la Villa Raffy (Saint-Robert, Lot-et-Garonne) :
site de réservation en direct, design luxe, animations, calendrier de réservation,
SEO local (location villa Agen / Villeneuve-sur-Lot / Lot-et-Garonne).

## Stack

- React + Vite
- Tailwind CSS 4
- Framer Motion (animations au scroll, parallaxe, accordéons)
- Typographies Fontshare : Sentient (titres) + Supreme (corps)

## Modifier les infos de la villa

Toutes les infos (téléphones, email, adresse, périodes réservées du calendrier)
sont dans **un seul fichier** : `src/config/villa.js`.

Pour bloquer des dates dans le calendrier, ajouter une ligne dans
`PERIODES_RESERVEES`, par exemple :

```js
{ debut: "2026-08-08", fin: "2026-08-22" },
```

## Ajouter les photos

Voir le guide complet : [PHOTOS.md](./PHOTOS.md). En résumé : déposer les photos
dans `public/images/` avec les bons noms de fichiers, elles s'affichent
automatiquement.

## Lancer en local

```bash
npm install
npm run dev
```

## Construire pour la production

```bash
npm run build
```

Le site généré se trouve dans `dist/`. Compatible avec un déploiement Vercel /
Netlify sans configuration supplémentaire.
