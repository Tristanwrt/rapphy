import { useMemo, useState } from 'react'
import { motion, AnimatePresence } from 'framer-motion'
import { Reveal } from './Reveal.jsx'
import { Icon } from './Icon.jsx'
import { VILLA, PERIODES_RESERVEES, NUITS_MINIMUM } from '../config/villa.js'

const MOIS = [
  'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
  'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre',
]
const JOURS = ['L', 'M', 'M', 'J', 'V', 'S', 'D']

const toKey = (d) =>
  `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`

const parseKey = (k) => {
  const [y, m, j] = k.split('-').map(Number)
  return new Date(y, m - 1, j)
}

function estReserve(date) {
  const t = date.getTime()
  return PERIODES_RESERVEES.some((p) => {
    const debut = parseKey(p.debut).getTime()
    const fin = parseKey(p.fin).getTime()
    return t >= debut && t < fin
  })
}

function chevaucheReservation(arrivee, depart) {
  for (let d = new Date(arrivee); d < depart; d.setDate(d.getDate() + 1)) {
    if (estReserve(d)) return true
  }
  return false
}

function Mois({ annee, mois, arrivee, depart, survol, onPick, onHover }) {
  const premier = new Date(annee, mois, 1)
  const decalage = (premier.getDay() + 6) % 7 // lundi = 0
  const nbJours = new Date(annee, mois + 1, 0).getDate()
  const aujourdhui = new Date()
  aujourdhui.setHours(0, 0, 0, 0)

  const cellules = []
  for (let i = 0; i < decalage; i++) cellules.push(null)
  for (let j = 1; j <= nbJours; j++) cellules.push(new Date(annee, mois, j))

  return (
    <div className="w-full">
      <div className="mb-4 text-center font-display text-lg font-medium">
        {MOIS[mois]} {annee}
      </div>
      <div className="grid grid-cols-7 gap-1 text-center text-xs text-ink-soft">
        {JOURS.map((j, i) => (
          <div key={i} className="py-1 font-medium">{j}</div>
        ))}
      </div>
      <div className="grid grid-cols-7 gap-1">
        {cellules.map((date, i) => {
          if (!date) return <div key={`v-${i}`} />
          const passe = date < aujourdhui
          const reserve = estReserve(date)
          const indisponible = passe || reserve

          const tA = arrivee?.getTime()
          const tD = depart?.getTime()
          const tS = survol?.getTime()
          const t = date.getTime()
          const finPlage = tD ?? (arrivee && tS > tA ? tS : null)
          const dansPlage = tA && finPlage && t > tA && t < finPlage
          const estArrivee = tA === t
          const estDepart = tD === t

          let classes =
            'relative flex h-10 w-full cursor-pointer items-center justify-center rounded-lg text-sm transition-colors duration-150 '
          if (indisponible) {
            classes += 'cursor-not-allowed text-ink/25 line-through decoration-ink/20 '
          } else if (estArrivee || estDepart) {
            classes += 'bg-brass font-medium text-linen '
          } else if (dansPlage) {
            classes += 'bg-brass/15 text-ink '
          } else {
            classes += 'hover:bg-brass/20 '
          }

          return (
            <button
              key={toKey(date)}
              type="button"
              disabled={indisponible}
              aria-label={`${date.getDate()} ${MOIS[mois]} ${annee}${indisponible ? ' — indisponible' : ''}`}
              onClick={() => onPick(date)}
              onMouseEnter={() => onHover(date)}
              className={classes}
            >
              {date.getDate()}
            </button>
          )
        })}
      </div>
    </div>
  )
}

export function Booking() {
  const maintenant = new Date()
  const [vue, setVue] = useState({ annee: maintenant.getFullYear(), mois: maintenant.getMonth() })
  const [arrivee, setArrivee] = useState(null)
  const [depart, setDepart] = useState(null)
  const [survol, setSurvol] = useState(null)
  const [voyageurs, setVoyageurs] = useState(4)
  const [erreur, setErreur] = useState('')

  const vueSuivante = useMemo(() => {
    const m = vue.mois + 1
    return { annee: vue.annee + (m > 11 ? 1 : 0), mois: m % 12 }
  }, [vue])

  const nuits = arrivee && depart ? Math.round((depart - arrivee) / 86400000) : 0

  const changerMois = (sens) => {
    const m = vue.mois + sens
    const nouvelle = { annee: vue.annee + (m < 0 ? -1 : m > 11 ? 1 : 0), mois: ((m % 12) + 12) % 12 }
    const limite = new Date(maintenant.getFullYear(), maintenant.getMonth(), 1)
    if (new Date(nouvelle.annee, nouvelle.mois, 1) >= limite) setVue(nouvelle)
  }

  const choisir = (date) => {
    setErreur('')
    if (!arrivee || (arrivee && depart)) {
      setArrivee(date)
      setDepart(null)
      return
    }
    if (date <= arrivee) {
      setArrivee(date)
      return
    }
    const n = Math.round((date - arrivee) / 86400000)
    if (n < NUITS_MINIMUM) {
      setErreur(`Séjour minimum : ${NUITS_MINIMUM} nuits.`)
      return
    }
    if (chevaucheReservation(arrivee, date)) {
      setErreur('Cette période contient des dates déjà réservées. Choisissez une autre plage.')
      return
    }
    setDepart(date)
  }

  const fmt = (d) =>
    d.toLocaleDateString('fr-FR', { weekday: 'short', day: 'numeric', month: 'long', year: 'numeric' })

  const message = arrivee && depart
    ? `Bonjour, je souhaite réserver la Villa Raffy du ${fmt(arrivee)} au ${fmt(depart)} (${nuits} nuits) pour ${voyageurs} voyageur${voyageurs > 1 ? 's' : ''}. Merci de me confirmer la disponibilité et le tarif.`
    : ''

  const lienWhatsApp = `https://wa.me/${VILLA.whatsapp}?text=${encodeURIComponent(message)}`
  const lienEmail = `mailto:${VILLA.email}?subject=${encodeURIComponent('Demande de réservation — Villa Raffy')}&body=${encodeURIComponent(message)}`

  return (
    <section id="reserver" className="mx-auto max-w-7xl scroll-mt-20 px-5 py-20 lg:px-8 lg:py-28">
      <Reveal className="mb-12 text-center">
        <p className="section-eyebrow mb-3">Réservation</p>
        <h2 className="mx-auto max-w-2xl text-3xl font-medium leading-snug lg:text-4xl">
          Choisissez vos dates, la villa vous attend
        </h2>
        <p className="mx-auto mt-5 max-w-xl text-ink-soft">
          Sélectionnez votre arrivée puis votre départ. Votre demande part directement
          chez vos hôtes — par téléphone, WhatsApp ou email — sans aucune commission.
        </p>
      </Reveal>

      <Reveal delay={0.1}>
        <div className="mx-auto max-w-4xl rounded-3xl bg-linen p-6 shadow-soft sm:p-10">
          {/* Navigation des mois */}
          <div className="mb-6 flex items-center justify-between">
            <button
              type="button"
              onClick={() => changerMois(-1)}
              aria-label="Mois précédent"
              className="flex h-11 w-11 cursor-pointer items-center justify-center rounded-full border border-ink/10 transition-colors hover:border-brass hover:text-brass"
            >
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="h-5 w-5" aria-hidden="true">
                <path d="M15 18l-6-6 6-6" />
              </svg>
            </button>
            <div className="flex items-center gap-3 text-sm text-ink-soft">
              <span className="inline-block h-3 w-3 rounded bg-brass" /> sélection
              <span className="ml-3 inline-block h-3 w-3 rounded bg-ink/10 line-through" /> indisponible
            </div>
            <button
              type="button"
              onClick={() => changerMois(1)}
              aria-label="Mois suivant"
              className="flex h-11 w-11 cursor-pointer items-center justify-center rounded-full border border-ink/10 transition-colors hover:border-brass hover:text-brass"
            >
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="h-5 w-5" aria-hidden="true">
                <path d="M9 6l6 6-6 6" />
              </svg>
            </button>
          </div>

          <div className="grid gap-10 md:grid-cols-2" onMouseLeave={() => setSurvol(null)}>
            <Mois {...vue} arrivee={arrivee} depart={depart} survol={survol} onPick={choisir} onHover={setSurvol} />
            <div className="hidden md:block">
              <Mois {...vueSuivante} arrivee={arrivee} depart={depart} survol={survol} onPick={choisir} onHover={setSurvol} />
            </div>
          </div>

          <AnimatePresence>
            {erreur && (
              <motion.p
                initial={{ opacity: 0, y: 8 }}
                animate={{ opacity: 1, y: 0 }}
                exit={{ opacity: 0 }}
                className="mt-5 text-center text-sm text-[#a1421f]"
              >
                {erreur}
              </motion.p>
            )}
          </AnimatePresence>

          {/* Récapitulatif + envoi */}
          <div className="mt-8 border-t border-ink/10 pt-8">
            <div className="flex flex-wrap items-center justify-center gap-x-10 gap-y-4 text-center">
              <div>
                <div className="text-xs uppercase tracking-wider text-ink-soft">Arrivée</div>
                <div className="mt-1 font-display font-medium">
                  {arrivee ? fmt(arrivee) : '— choisissez une date —'}
                </div>
              </div>
              <div>
                <div className="text-xs uppercase tracking-wider text-ink-soft">Départ</div>
                <div className="mt-1 font-display font-medium">
                  {depart ? fmt(depart) : '—'}
                </div>
              </div>
              <div>
                <div className="text-xs uppercase tracking-wider text-ink-soft">Voyageurs</div>
                <div className="mt-1 flex items-center justify-center gap-3">
                  <button
                    type="button"
                    aria-label="Moins de voyageurs"
                    onClick={() => setVoyageurs(Math.max(1, voyageurs - 1))}
                    className="flex h-8 w-8 cursor-pointer items-center justify-center rounded-full border border-ink/15 transition-colors hover:border-brass hover:text-brass"
                  >
                    −
                  </button>
                  <span className="w-6 font-display font-medium">{voyageurs}</span>
                  <button
                    type="button"
                    aria-label="Plus de voyageurs"
                    onClick={() => setVoyageurs(Math.min(8, voyageurs + 1))}
                    className="flex h-8 w-8 cursor-pointer items-center justify-center rounded-full border border-ink/15 transition-colors hover:border-brass hover:text-brass"
                  >
                    +
                  </button>
                </div>
              </div>
              {nuits > 0 && (
                <div>
                  <div className="text-xs uppercase tracking-wider text-ink-soft">Durée</div>
                  <div className="mt-1 font-display font-medium text-brass">{nuits} nuits</div>
                </div>
              )}
            </div>

            <div className="mt-8 grid gap-3 sm:grid-cols-3">
              <a
                href={arrivee && depart ? lienWhatsApp : undefined}
                target="_blank"
                rel="noopener noreferrer"
                aria-disabled={!(arrivee && depart)}
                className={`flex items-center justify-center gap-2 rounded-full px-6 py-3.5 font-medium transition-all duration-200 ${
                  arrivee && depart
                    ? 'bg-brass text-linen shadow-card hover:scale-[1.03]'
                    : 'cursor-not-allowed bg-ink/8 text-ink/35'
                }`}
              >
                <Icon name="whatsapp" className="h-4.5 w-4.5" />
                Demander sur WhatsApp
              </a>
              <a
                href={arrivee && depart ? lienEmail : undefined}
                aria-disabled={!(arrivee && depart)}
                className={`flex items-center justify-center gap-2 rounded-full border px-6 py-3.5 font-medium transition-colors duration-200 ${
                  arrivee && depart
                    ? 'border-brass text-brass hover:bg-brass/10'
                    : 'cursor-not-allowed border-ink/10 text-ink/35'
                }`}
              >
                <Icon name="mail" className="h-4.5 w-4.5" />
                Envoyer par email
              </a>
              <a
                href={`tel:${VILLA.telephoneMobileIntl}`}
                className="flex items-center justify-center gap-2 rounded-full border border-ink/15 px-6 py-3.5 font-medium transition-colors duration-200 hover:border-brass hover:text-brass"
              >
                <Icon name="phone" className="h-4.5 w-4.5" />
                {VILLA.telephoneMobile}
              </a>
            </div>

            <p className="mt-6 text-center text-xs leading-relaxed text-ink-soft">
              Réponse rapide et personnelle de vos hôtes. Réserver en direct, c'est le
              meilleur tarif garanti — sans les frais de service des plateformes.
            </p>
          </div>
        </div>
      </Reveal>
    </section>
  )
}
