// ─────────────────────────────────────────────────────────────
// TOUTES les infos de la villa sont centralisées ici.
// Pour changer un numéro, un texte ou une période réservée,
// c'est LE SEUL fichier à modifier.
// ─────────────────────────────────────────────────────────────

export const VILLA = {
  nom: "Villa Raffy",
  signature: "Oasis d'exception",
  hotes: "Stéphane & Sophie",
  adresse: "3 rue Georges Bouyssou, 47340 Saint-Robert",
  commune: "Saint-Robert, Lot-et-Garonne",
  telephoneFixe: "05 53 47 80 87",
  telephoneMobile: "06 83 63 89 66",
  telephoneMobileIntl: "+33683638966",
  email: "sraffanel@orange.fr",
  whatsapp: "33683638966",
  refGites: "Gîtes de France n°47G9070",
  urlAirbnb:
    "https://www.airbnb.fr/rooms/1396920426716525192",
}

// Périodes déjà réservées : le calendrier les affichera comme indisponibles.
// Format : "AAAA-MM-JJ". La date de fin est le jour du départ (libéré le soir même).
// Ajoutez une ligne { debut: "...", fin: "..." } par réservation confirmée.
export const PERIODES_RESERVEES = [
  // Exemple réel à adapter au planning :
  // { debut: "2026-08-08", fin: "2026-08-22" },
]

// Durée minimum de séjour (en nuits)
export const NUITS_MINIMUM = 2
