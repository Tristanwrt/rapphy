import { useState } from 'react'
import { motion, AnimatePresence } from 'framer-motion'
import { Reveal } from './Reveal.jsx'

const QUESTIONS = [
  {
    q: 'Où se trouve la Villa Raffy exactement ?',
    r: "À Saint-Robert (47340), en Lot-et-Garonne, à mi-chemin entre Agen et Villeneuve-sur-Lot — environ 20 km de chacune des deux villes. Vous êtes au calme absolu de la campagne, tout en restant à 20 minutes des commerces, restaurants et marchés.",
  },
  {
    q: 'Combien de personnes la villa peut-elle accueillir ?',
    r: "La villa accueille jusqu'à 8 voyageurs dans 4 chambres, toutes équipées de lits 160 × 200 (la suite de l'étage dispose de 2 lits électriques 80 cm modulables en lit double). Chaque espace nuit a sa salle de bain ou salle d'eau.",
  },
  {
    q: 'La piscine est-elle adaptée aux enfants ?',
    r: "Oui : la piscine fait 9 × 3,50 m avec une profondeur unique de 1,30 m, idéale pour les enfants accompagnés. Le terrain de 2300 m² est entièrement clos. Le bar immergé et les éclairages en font aussi le spot préféré des adultes en soirée.",
  },
  {
    q: 'Les animaux sont-ils acceptés ?',
    r: 'Oui, vos compagnons sont les bienvenus à la villa. Le terrain clos leur permet de profiter en liberté et en sécurité. Signalez simplement leur présence lors de votre demande de réservation.',
  },
  {
    q: 'Comment réserver en direct et pourquoi est-ce plus avantageux ?',
    r: "Choisissez vos dates dans le calendrier ci-dessus, puis envoyez votre demande par téléphone, WhatsApp ou email. En direct, vous évitez les frais de service des plateformes (jusqu'à 15 % du séjour) et vous échangez directement avec Patrick et son épouse, vos hôtes.",
  },
  {
    q: "Qu'est-ce qui est inclus dans la location ?",
    r: 'La villa entière et ses 2300 m² de terrain : piscine avec bar immergé, jacuzzi 5 places, salle de sport équipée, écran cinéma 3m50 avec fibre et Canal+, cuisine équipée, 3 terrasses, parking privé et WiFi. Linge et accueil personnalisé avec panier de produits frais du jardin selon la saison.',
  },
]

export function Faq() {
  const [ouvert, setOuvert] = useState(0)

  return (
    <section className="mx-auto max-w-3xl px-5 py-20 lg:px-8 lg:py-28">
      <Reveal className="mb-10 text-center">
        <p className="section-eyebrow mb-3">Questions fréquentes</p>
        <h2 className="text-3xl font-medium leading-snug lg:text-4xl">
          Tout ce que vous voulez savoir
        </h2>
      </Reveal>

      <div className="space-y-3">
        {QUESTIONS.map((item, i) => {
          const actif = ouvert === i
          return (
            <Reveal key={item.q} delay={i * 0.05}>
              <div className="overflow-hidden rounded-2xl bg-linen shadow-card">
                <button
                  type="button"
                  onClick={() => setOuvert(actif ? -1 : i)}
                  aria-expanded={actif}
                  className="flex w-full cursor-pointer items-center justify-between gap-4 px-6 py-5 text-left"
                >
                  <span className="font-medium">{item.q}</span>
                  <motion.span
                    animate={{ rotate: actif ? 45 : 0 }}
                    transition={{ duration: 0.2 }}
                    className="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-brass/40 text-brass"
                  >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="h-4 w-4" aria-hidden="true">
                      <path d="M12 5v14M5 12h14" />
                    </svg>
                  </motion.span>
                </button>
                <AnimatePresence initial={false}>
                  {actif && (
                    <motion.div
                      initial={{ height: 0, opacity: 0 }}
                      animate={{ height: 'auto', opacity: 1 }}
                      exit={{ height: 0, opacity: 0 }}
                      transition={{ duration: 0.28, ease: [0.22, 0.61, 0.36, 1] }}
                    >
                      <p className="px-6 pb-6 text-[0.95rem] leading-relaxed text-ink-soft">
                        {item.r}
                      </p>
                    </motion.div>
                  )}
                </AnimatePresence>
              </div>
            </Reveal>
          )
        })}
      </div>
    </section>
  )
}
