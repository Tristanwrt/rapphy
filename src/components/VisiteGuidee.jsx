import { useRef, useState } from 'react'
import { motion, useScroll, useTransform, useMotionValueEvent } from 'framer-motion'
import { Photo } from './Photo.jsx'

// Visite guidée serpentin : le scroll fait avancer la visite latéralement,
// puis descendre d'une zone à l'autre — comme un vrai parcours dans la maison.
// direction = mouvement pour ALLER à cette étape ('droite' ou 'bas').
const ETAPES = [
  {
    zone: 'Espace jour', titre: 'Le séjour cathédrale',
    texte: "70 m² baignés de lumière, où les longues tablées s'éternisent.",
    photo: '/images/salon.jpg', alt: 'Le séjour cathédrale de 70 m² de la Villa Raffy',
    direction: null,
  },
  {
    zone: 'Espace jour', titre: 'La cuisine & son bar',
    texte: "Équipée pour les chefs, pensée pour l'apéro.",
    photo: '/images/cuisine.jpg', alt: 'La cuisine équipée et son bar',
    direction: 'droite',
  },
  {
    zone: 'Espace jour', titre: 'Le cinéma 3m50',
    texte: 'Canal+, PlayStation et un écran géant pour des soirées mémorables.',
    photo: '/images/ecran-cinema.jpg', alt: "L'écran géant de 3m50 du salon",
    direction: 'droite',
  },
  {
    zone: 'Espace jour', titre: 'Le coin cheminée',
    texte: "La flamme qui réchauffe les soirées d'hiver, un plaid et un bon livre.",
    photo: '/images/cheminee.jpg', alt: 'Le coin cheminée du salon',
    direction: 'droite',
  },
  {
    zone: 'Espace nuit', titre: 'La suite parentale',
    texte: 'Lit 160 × 200, salle de bain privée, et la salle de sport attenante.',
    photo: '/images/chambre-1.jpg', alt: 'La suite parentale de la Villa Raffy',
    direction: 'bas',
  },
  {
    zone: 'Espace nuit', titre: 'La chambre Campagne',
    texte: 'Ouverte sur la verdure, à deux pas du séjour. Le calme absolu.',
    photo: '/images/chambre-2.jpg', alt: 'La chambre Campagne et sa vue jardin',
    direction: 'droite',
  },
  {
    zone: 'Espace nuit', titre: 'La chambre Garonne',
    texte: 'Literie hôtelière grand format : personne ne dort dans « la petite chambre ».',
    photo: '/images/chambre-3.jpg', alt: 'La chambre Garonne',
    direction: 'droite',
  },
  {
    zone: "L'étage", titre: "La suite de l'étage",
    texte: "25 m² d'indépendance, salle d'eau privée et terrasse sur la piscine.",
    photo: '/images/suite-etage.jpg', alt: "La suite de 25 m² à l'étage",
    direction: 'bas',
  },
  {
    zone: "L'étage", titre: 'La salle de sport',
    texte: 'Vélo, elliptique, rameur, banc de musculation : la forme, même en vacances.',
    photo: '/images/salle-sport.jpg', alt: 'La salle de sport privée',
    direction: 'droite',
  },
  {
    zone: 'Les extérieurs', titre: 'La piscine & son bar immergé',
    texte: "9 mètres plein sud, un cocktail les pieds dans l'eau.",
    photo: '/images/piscine-jour.jpg', alt: 'La piscine de 9 mètres avec bar immergé',
    direction: 'bas',
  },
  {
    zone: 'Les extérieurs', titre: 'Le jacuzzi sous les étoiles',
    texte: '5 places pour prolonger la nuit, éclairages compris.',
    photo: '/images/jacuzzi.jpg', alt: 'Le jacuzzi 5 places de la villa',
    direction: 'droite',
  },
  {
    zone: 'Les extérieurs', titre: 'Plage privée & jardin exotique',
    texte: 'Sable fin, kiosque zen, trois terrasses et un terrain de pétanque.',
    photo: '/images/terrasse.jpg', alt: 'La plage privée de sable fin et les terrasses',
    direction: 'droite',
  },
]

// Position de chaque étape sur le grand plan (serpentin), en écrans.
const POSITIONS = ETAPES.reduce((acc, e, i) => {
  if (i === 0) return [{ x: 0, y: 0 }]
  const prev = acc[i - 1]
  acc.push(e.direction === 'bas' ? { x: prev.x, y: prev.y + 1 } : { x: prev.x + 1, y: prev.y })
  return acc
}, [])

const N = ETAPES.length
const TIMES = ETAPES.map((_, i) => i / (N - 1))

export function VisiteGuidee() {
  const ref = useRef(null)
  const [etape, setEtape] = useState(0)
  const { scrollYProgress } = useScroll({ target: ref })

  const x = useTransform(scrollYProgress, TIMES, POSITIONS.map((p) => `${-p.x * 100}vw`))
  const y = useTransform(scrollYProgress, TIMES, POSITIONS.map((p) => `${-p.y * 100}vh`))
  const progression = useTransform(scrollYProgress, [0, 1], ['0%', '100%'])

  useMotionValueEvent(scrollYProgress, 'change', (v) => {
    const i = Math.min(N - 1, Math.max(0, Math.round(v * (N - 1))))
    if (i !== etape) setEtape(i)
  })

  const prochaine = ETAPES[Math.min(etape + 1, N - 1)]

  return (
    <section
      ref={ref}
      aria-label="Visite guidée de la villa"
      style={{ height: `${N * 100}vh` }}
      className="relative bg-night"
    >
      <div className="sticky top-0 h-screen overflow-hidden">
        {/* Titre + zone courante */}
        <div className="pointer-events-none absolute left-0 right-0 top-0 z-10 bg-gradient-to-b from-night via-night/75 to-transparent px-5 pb-16 pt-24 text-center lg:px-8">
          <p className="section-eyebrow mb-2">La visite guidée · {ETAPES[etape].zone}</p>
          <h2 className="mx-auto max-w-xl font-display text-2xl font-medium text-linen lg:text-3xl">
            Poussez la porte, laissez-vous guider
          </h2>
        </div>

        {/* Le grand plan serpentin de la maison */}
        <motion.div style={{ x, y }} className="absolute inset-0">
          {ETAPES.map((e, i) => (
            <div
              key={e.titre}
              style={{ left: `${POSITIONS[i].x * 100}vw`, top: `${POSITIONS[i].y * 100}vh` }}
              className="absolute h-screen w-screen"
            >
              <Photo src={e.photo} alt={e.alt} label={e.titre} className="h-full w-full" />
              <div className="absolute inset-0 bg-gradient-to-t from-night/90 via-transparent to-night/45" />
              <div className="absolute bottom-0 left-0 right-0 px-6 pb-28 lg:px-16">
                <div className="mx-auto flex max-w-7xl items-end gap-6">
                  <span className="font-display text-6xl font-medium text-brass/70 lg:text-8xl">
                    {String(i + 1).padStart(2, '0')}
                  </span>
                  <div className="pb-2 lg:pb-4">
                    <p className="text-[0.65rem] uppercase tracking-[0.22em] text-brass">{e.zone}</p>
                    <h3 className="mt-1 font-display text-2xl font-medium text-linen lg:text-4xl">
                      {e.titre}
                    </h3>
                    <p className="mt-2 max-w-md text-sm text-linen/75 lg:text-base">{e.texte}</p>
                  </div>
                </div>
              </div>
            </div>
          ))}
        </motion.div>

        {/* Progression + compteur + direction de la prochaine étape */}
        <div className="absolute bottom-8 left-1/2 z-10 w-64 -translate-x-1/2 lg:w-96">
          <div className="mb-3 flex items-center justify-between text-[0.65rem] uppercase tracking-[0.22em] text-linen/60">
            <span className="whitespace-nowrap">
              {String(etape + 1).padStart(2, '0')} / {String(N).padStart(2, '0')}
            </span>
            {etape < N - 1 && (
              <span className="flex items-center gap-1.5 truncate pl-3">
                Suivant : {prochaine.titre}
                <svg
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  strokeWidth="2"
                  className={`h-3.5 w-3.5 text-brass ${prochaine.direction === 'bas' ? 'rotate-90' : ''}`}
                  aria-hidden="true"
                >
                  <path d="M5 12h14M12 5l7 7-7 7" />
                </svg>
              </span>
            )}
          </div>
          <div className="h-0.5 w-full overflow-hidden rounded-full bg-linen/20">
            <motion.div style={{ width: progression }} className="h-full bg-brass" />
          </div>
          <p className="mt-3 text-center text-[0.65rem] uppercase tracking-[0.22em] text-linen/50">
            Faites défiler pour visiter les 12 espaces
          </p>
        </div>
      </div>
    </section>
  )
}
