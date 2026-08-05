import { Reveal, Stagger, StaggerItem } from './Reveal.jsx'
import { Photo } from './Photo.jsx'

const CHAMBRES = [
  {
    photo: '/images/chambre-1.jpg',
    titre: 'La suite parentale',
    detail: 'Lit 160 × 200 · salle de bain privée · accès direct à la salle de sport',
    texte:
      "Le refuge des parents : un grand lit, une salle de bain rien qu'à vous, et la salle de sport attenante pour commencer la journée en douceur.",
  },
  {
    photo: '/images/chambre-2.jpg',
    titre: 'Chambre Campagne',
    detail: 'Lit 160 × 200 · plain-pied · vue jardin',
    texte:
      'Une chambre paisible ouverte sur la verdure, à deux pas du séjour et de la terrasse. Le calme absolu de Saint-Robert.',
  },
  {
    photo: '/images/chambre-3.jpg',
    titre: 'Chambre Garonne',
    detail: 'Lit 160 × 200 · plain-pied · literie hôtelière',
    texte:
      'La troisième chambre de plain-pied, avec la même literie grand format que les autres : ici, personne ne dort dans « la petite chambre ».',
  },
  {
    photo: '/images/suite-etage.jpg',
    titre: "La suite de l'étage",
    detail: "25 m² · 2 lits électriques 80 cm (ou lit 160 × 200) · salle d'eau · terrasse privée",
    texte:
      "À l'étage, 25 m² d'indépendance totale : literie électrique modulable, salle d'eau privative et terrasse avec accès direct à la piscine et au jardin.",
  },
]

export function Chambres() {
  return (
    <section id="chambres" className="bg-canvas-deep py-20 lg:py-28">
      <div className="mx-auto max-w-7xl px-5 lg:px-8">
        <Reveal className="mb-12 text-center">
          <p className="section-eyebrow mb-3">Les chambres</p>
          <h2 className="mx-auto max-w-2xl text-3xl font-medium leading-snug lg:text-4xl">
            Quatre chambres, quatre lits grand format, zéro compromis
          </h2>
          <p className="mx-auto mt-5 max-w-2xl text-ink-soft">
            Toutes les chambres sont équipées de lits 160 × 200 et chaque espace nuit
            dispose de sa propre salle de bain ou salle d'eau. Jusqu'à 8 voyageurs, logés
            comme à l'hôtel.
          </p>
        </Reveal>

        <Stagger className="grid gap-7 sm:grid-cols-2 lg:grid-cols-4" gap={0.1}>
          {CHAMBRES.map((c) => (
            <StaggerItem key={c.titre}>
              <article className="group h-full overflow-hidden rounded-2xl bg-linen shadow-card transition-transform duration-300 hover:-translate-y-1.5">
                <div className="overflow-hidden">
                  <Photo
                    src={c.photo}
                    alt={`${c.titre} — Villa Raffy`}
                    label={c.titre}
                    className="h-52 w-full transition-transform duration-500 group-hover:scale-105"
                  />
                </div>
                <div className="p-6">
                  <h3 className="text-lg font-medium">{c.titre}</h3>
                  <p className="mt-1.5 text-xs uppercase tracking-wider text-brass">{c.detail}</p>
                  <p className="mt-3 text-sm leading-relaxed text-ink-soft">{c.texte}</p>
                </div>
              </article>
            </StaggerItem>
          ))}
        </Stagger>
      </div>
    </section>
  )
}
