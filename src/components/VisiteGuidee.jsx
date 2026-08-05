import { useRef } from 'react'
import { motion, useScroll, useTransform } from 'framer-motion'
import { Photo } from './Photo.jsx'

// Visite guidée : le scroll vertical fait défiler les pièces horizontalement,
// comme un parcours dans la maison.
const ETAPES = [
  {
    num: '01',
    titre: 'Le séjour cathédrale',
    texte: "70 m² baignés de lumière, où les longues tablées s'éternisent.",
    photo: '/images/salon.jpg',
    alt: 'Le séjour cathédrale de 70 m² de la Villa Raffy',
  },
  {
    num: '02',
    titre: 'La cuisine & son bar',
    texte: "Équipée pour les chefs, pensée pour l'apéro.",
    photo: '/images/cuisine.jpg',
    alt: 'La cuisine équipée et son bar',
  },
  {
    num: '03',
    titre: 'Les suites prestige',
    texte: 'Literie grand format, salles de bain privées, silence absolu.',
    photo: '/images/chambre-1.jpg',
    alt: 'Une suite prestige de la Villa Raffy',
  },
  {
    num: '04',
    titre: 'Le cinéma 3m50',
    texte: 'Canal+, PlayStation et un écran géant pour des soirées mémorables.',
    photo: '/images/ecran-cinema.jpg',
    alt: "L'écran géant de 3m50 du salon",
  },
  {
    num: '05',
    titre: 'La piscine & son bar immergé',
    texte: "Un cocktail les pieds dans l'eau, face au soleil couchant.",
    photo: '/images/piscine-jour.jpg',
    alt: 'La piscine de 9 mètres avec bar immergé',
  },
  {
    num: '06',
    titre: 'Le jacuzzi sous les étoiles',
    texte: '5 places pour prolonger la nuit, la plage de sable fin à deux pas.',
    photo: '/images/jacuzzi.jpg',
    alt: 'Le jacuzzi 5 places de la villa',
  },
]

export function VisiteGuidee() {
  const ref = useRef(null)
  const { scrollYProgress } = useScroll({ target: ref })
  const x = useTransform(scrollYProgress, [0, 1], ['0%', `-${((ETAPES.length - 1) / ETAPES.length) * 100}%`])
  const progression = useTransform(scrollYProgress, [0, 1], ['0%', '100%'])

  return (
    <section ref={ref} aria-label="Visite guidée de la villa" style={{ height: `${ETAPES.length * 100}vh` }} className="relative bg-night">
      <div className="sticky top-0 flex h-screen flex-col overflow-hidden">
        <div className="pointer-events-none absolute left-0 right-0 top-0 z-10 bg-gradient-to-b from-night via-night/80 to-transparent px-5 pb-16 pt-24 text-center lg:px-8">
          <p className="section-eyebrow mb-2">La visite guidée</p>
          <h2 className="mx-auto max-w-xl font-display text-2xl font-medium text-linen lg:text-3xl">
            Poussez la porte, laissez-vous guider
          </h2>
        </div>

        <motion.div style={{ x }} className="flex h-full" >
          {ETAPES.map((e) => (
            <div key={e.num} className="relative h-full w-screen shrink-0">
              <Photo src={e.photo} alt={e.alt} label={e.titre} className="h-full w-full" />
              <div className="absolute inset-0 bg-gradient-to-t from-night/90 via-transparent to-night/40" />
              <div className="absolute bottom-0 left-0 right-0 px-6 pb-24 lg:px-16">
                <div className="mx-auto flex max-w-7xl items-end gap-6">
                  <span className="font-display text-6xl font-medium text-brass/70 lg:text-8xl">
                    {e.num}
                  </span>
                  <div className="pb-2 lg:pb-4">
                    <h3 className="font-display text-2xl font-medium text-linen lg:text-4xl">
                      {e.titre}
                    </h3>
                    <p className="mt-2 max-w-md text-sm text-linen/75 lg:text-base">{e.texte}</p>
                  </div>
                </div>
              </div>
            </div>
          ))}
        </motion.div>

        {/* Barre de progression de la visite */}
        <div className="absolute bottom-10 left-1/2 z-10 w-56 -translate-x-1/2 lg:w-80">
          <div className="h-0.5 w-full overflow-hidden rounded-full bg-linen/20">
            <motion.div style={{ width: progression }} className="h-full bg-brass" />
          </div>
          <p className="mt-3 text-center text-[0.65rem] uppercase tracking-[0.22em] text-linen/50">
            Faites défiler pour visiter
          </p>
        </div>
      </div>
    </section>
  )
}
