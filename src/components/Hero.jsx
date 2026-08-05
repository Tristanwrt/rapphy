import { motion, useScroll, useTransform } from 'framer-motion'
import { useRef } from 'react'
import { Icon } from './Icon.jsx'
import { SearchBar } from './SearchBar.jsx'

export function Hero() {
  const ref = useRef(null)
  const { scrollYProgress } = useScroll({ target: ref, offset: ['start start', 'end start'] })
  const y = useTransform(scrollYProgress, [0, 1], ['0%', '18%'])
  const opacity = useTransform(scrollYProgress, [0, 0.8], [1, 0])

  return (
    <section id="accueil" ref={ref} className="relative flex min-h-[100svh] items-end overflow-hidden">
      {/* Photo plein écran avec parallaxe léger + fallback dégradé */}
      <motion.div
        style={{ y }}
        className="photo-fallback absolute inset-0 scale-110 bg-cover bg-center"
      >
        <img
          src="/images/hero-piscine.jpg"
          alt="La piscine de la Villa Raffy au coucher du soleil, avec son bar immergé éclairé"
          className="h-full w-full object-cover"
          onError={(e) => { e.currentTarget.style.display = 'none' }}
        />
      </motion.div>
      {/* Voile pour la lisibilité du texte */}
      <div className="absolute inset-0 bg-gradient-to-t from-night/85 via-night/25 to-night/40" />

      <motion.div style={{ opacity }} className="relative z-10 mx-auto w-full max-w-7xl px-5 pb-16 pt-36 lg:px-8 lg:pb-24">
        <motion.p
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.7, delay: 0.15 }}
          className="section-eyebrow mb-5"
        >
          Saint-Robert · Entre Agen et Villeneuve-sur-Lot
        </motion.p>

        <motion.h1
          initial={{ opacity: 0, y: 30 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.8, delay: 0.3 }}
          className="max-w-3xl text-4xl font-medium leading-tight text-linen sm:text-5xl lg:text-6xl"
        >
          Votre oasis d'exception au cœur du Lot-et-Garonne
        </motion.h1>

        <motion.p
          initial={{ opacity: 0, y: 30 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.8, delay: 0.45 }}
          className="mt-5 max-w-xl text-lg text-linen/85"
        >
          Villa de 180 m² pour 8 voyageurs — piscine, jacuzzi, plage privée de
          sable fin, salle de sport et cinéma privé, sur 2300 m² de nature clôturée.
        </motion.p>

        {/* Barre de recherche type Airbnb : le CTA principal */}
        <SearchBar />

        <motion.a
          href="#villa"
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          transition={{ duration: 0.8, delay: 0.8 }}
          className="group mt-5 inline-flex items-center gap-2 text-sm text-linen/80 transition-colors hover:text-brass"
        >
          Ou commencez par découvrir la villa
          <Icon name="arrow" className="h-3.5 w-3.5 rotate-90 transition-transform duration-200 group-hover:translate-y-0.5" />
        </motion.a>

        {/* Preuve sociale immédiate */}
        <motion.div
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          transition={{ duration: 0.8, delay: 0.85 }}
          className="mt-12 flex flex-wrap items-center gap-x-8 gap-y-3 text-sm text-linen/80"
        >
          <span className="flex items-center gap-2">
            <span className="flex text-brass">
              {[...Array(5)].map((_, i) => (
                <Icon key={i} name="star" className="h-3.5 w-3.5 fill-current" />
              ))}
            </span>
            4,86/5 · Coup de cœur voyageurs Airbnb
          </span>
          <span className="flex items-center gap-2">
            <Icon name="shield" className="h-4 w-4 text-brass" />
            Noté 5 étoiles sur Google · Gîtes de France
          </span>
          <span className="flex items-center gap-2">
            <Icon name="check" className="h-4 w-4 text-brass" />
            Réservation en direct, sans commission
          </span>
        </motion.div>
      </motion.div>
    </section>
  )
}
