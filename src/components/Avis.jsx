import { Reveal, Stagger, StaggerItem } from './Reveal.jsx'
import { Icon } from './Icon.jsx'
import { VILLA } from '../config/villa.js'

// Avis réels de voyageurs, publiés sur les plateformes de réservation.
const AVIS = [
  {
    texte:
      'Confort optimal et propreté sont au rendez-vous. Rien ne manque, tout est prévu.',
    auteur: 'Voyageur vérifié',
    source: 'Expedia · 10/10 Exceptionnel',
    date: 'Août 2024',
  },
  {
    texte:
      "Accueil très chaleureux de Patrick et son épouse, avec un panier de fruits et légumes frais offert à l'arrivée. Une attention qui change tout.",
    auteur: 'Voyageur vérifié',
    source: 'Expedia',
    date: 'Été 2024',
  },
  {
    texte:
      'Très belle propriété et des hôtes adorables. Une ambiance dépaysante le soir avec les éclairages de la piscine.',
    auteur: 'Voyageur international',
    source: 'Expedia · avis traduit',
    date: '2024',
  },
  {
    texte:
      'Nous avons adoré la piscine, le coin repas extérieur et le terrain de pétanque. Communication parfaite avec des hôtes très sympathiques : nous recommandons à 100 %.',
    auteur: 'Voyageur vérifié',
    source: 'Vrbo · avis traduit',
    date: '2024',
  },
]

const BADGES = [
  { valeur: '10/10', label: '« Exceptionnel » sur Expedia' },
  { valeur: 'Top', label: 'villa la mieux notée sur Vrbo' },
  { valeur: '47G9070', label: 'classée Gîtes de France' },
  { valeur: '100 %', label: 'des voyageurs recommandent' },
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
            <StaggerItem key={a.texte}>
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
                    {a.source}
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
            Voir aussi tous les avis sur Airbnb
          </a>
        </Reveal>
      </div>
    </section>
  )
}
