import { Reveal, Stagger, StaggerItem } from './Reveal.jsx'
import { Icon } from './Icon.jsx'
import { VILLA } from '../config/villa.js'

// Avis réels de voyageurs, publiés sur l'annonce Airbnb de la villa.
const AVIS = [
  {
    texte:
      'Excellent séjour. Magnifique maison. Intérieur et extérieur conformes à la description. Une bulle de dépaysement. Merci à nos hôtes pour leur réactivité et leur bienveillance. Nous recommandons ce lieu à 400 pour cent.',
    auteur: 'Virginie',
    source: 'Airbnb',
    date: 'Mai 2026',
  },
  {
    texte:
      "Tout était réuni pour passer un très bon séjour : une literie impeccable, des équipements de qualité et un logement parfaitement agréable. Mention spéciale pour les huîtres et crevettes ultra fraîches commandées directement auprès de Stéphane, livrées ouvertes et prêtes à déguster. Des hôtes attentionnés et disponibles.",
    auteur: 'Déborah',
    source: 'Airbnb',
    date: 'Mai 2026',
  },
  {
    texte:
      'La maison de Stéphane est pratique et assez spacieuse pour être à 8 personnes. Les extérieurs sont topissime !! Je recommande vivement.',
    auteur: 'Benoit',
    source: 'Airbnb',
    date: 'Mai 2025',
  },
  {
    texte:
      "Maison intérieur et extérieur magique, je reviendrai. Des personnes très accueillantes, nous avons passé un week-end au top.",
    auteur: 'Benny',
    source: 'Airbnb',
    date: 'Mai 2026',
  },
]

const BADGES = [
  { valeur: '4,86/5', label: 'sur Airbnb · Coup de cœur voyageurs' },
  { valeur: '5/5', label: 'sur Google' },
  { valeur: '5,0', label: 'en propreté, arrivée et qualité-prix' },
  { valeur: '100 %', label: 'de réponse, en moins d’une heure' },
]

export function Avis() {
  return (
    <section id="avis" className="bg-canvas-deep py-20 lg:py-28">
      <div className="mx-auto max-w-7xl px-5 lg:px-8">
        <Reveal className="mb-12 text-center">
          <p className="section-eyebrow mb-3">Ils ont séjourné à la villa</p>
          <h2 className="mx-auto max-w-2xl text-3xl font-medium leading-snug lg:text-4xl">
            Nos voyageurs le disent mieux que nous
          </h2>
        </Reveal>

        {/* Badges de confiance */}
        <Stagger className="mb-14 grid grid-cols-2 gap-6 lg:grid-cols-4" gap={0.08}>
          {BADGES.map((b) => (
            <StaggerItem key={b.label} className="text-center">
              <div className="font-display text-3xl font-medium text-brass">{b.valeur}</div>
              <div className="mt-1 text-sm text-ink-soft">{b.label}</div>
            </StaggerItem>
          ))}
        </Stagger>

        <Stagger className="grid gap-7 md:grid-cols-2" gap={0.12}>
          {AVIS.map((a) => (
            <StaggerItem key={a.auteur}>
              <figure className="relative h-full rounded-2xl bg-linen p-8 shadow-card">
                <Icon name="quote" className="absolute right-7 top-7 h-8 w-8 text-brass/25" />
                <div className="mb-4 flex gap-1 text-brass">
                  {[...Array(5)].map((_, i) => (
                    <Icon key={i} name="star" className="h-4 w-4 fill-current" />
                  ))}
                </div>
                <blockquote className="font-display text-lg italic leading-relaxed">
                  « {a.texte} »
                </blockquote>
                <figcaption className="mt-5 text-sm text-ink-soft">
                  <span className="font-medium text-ink">{a.auteur}</span> · {a.date}
                  <span className="mt-0.5 block text-xs uppercase tracking-wider text-brass">
                    Avis 5 étoiles · {a.source}
                  </span>
                </figcaption>
              </figure>
            </StaggerItem>
          ))}
        </Stagger>

        <Reveal delay={0.15} className="mt-10 text-center">
          <a
            href={VILLA.urlAirbnb}
            target="_blank"
            rel="noopener noreferrer"
            className="text-sm text-ink-soft underline decoration-brass/50 underline-offset-4 transition-colors hover:text-brass"
          >
            Voir les 7 commentaires sur Airbnb
          </a>
        </Reveal>
      </div>
    </section>
  )
}
