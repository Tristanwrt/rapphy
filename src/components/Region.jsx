import { Reveal, Stagger, StaggerItem } from './Reveal.jsx'
import { Photo } from './Photo.jsx'

const DECOUVERTES = [
  {
    photo: '/images/region-agen.jpg',
    titre: 'Agen, à 20 minutes',
    texte:
      "La capitale du pruneau, ses ruelles, son musée des Beaux-Arts et les berges de Garonne. Marchés gourmands le samedi matin, restaurants et golf à un quart d'heure de la villa.",
  },
  {
    photo: '/images/region-villeneuve.jpg',
    titre: 'Villeneuve-sur-Lot & la vallée du Lot',
    texte:
      "Bastide médiévale, marché couvert, balades en gabarre sur le Lot. Et tout autour, les plus beaux villages de France : Pujols, Penne-d'Agenais, Monflanquin.",
  },
  {
    photo: '/images/region-campagne.jpg',
    titre: 'La campagne du Lot-et-Garonne',
    texte:
      'Vergers de pruniers, tournesols, chemins de randonnée et routes à vélo au départ de la villa. Le Sud-Ouest authentique, entre Bordeaux et Toulouse.',
  },
]

export function Region() {
  return (
    <section id="region" className="bg-canvas-deep py-20 lg:py-28">
      <div className="mx-auto max-w-7xl px-5 lg:px-8">
        <Reveal className="mb-12 text-center">
          <p className="section-eyebrow mb-3">La région</p>
          <h2 className="mx-auto max-w-2xl text-3xl font-medium leading-snug lg:text-4xl">
            Idéalement placée entre Agen et Villeneuve-sur-Lot
          </h2>
          <p className="mx-auto mt-5 max-w-2xl text-ink-soft">
            À Saint-Robert, vous êtes à 20 kilomètres des deux villes, au centre exact du
            Lot-et-Garonne. La position parfaite pour rayonner : marchés, bastides,
            vignobles de Buzet et baignades dans le Lot.
          </p>
        </Reveal>

        <Stagger className="grid gap-7 md:grid-cols-3" gap={0.12}>
          {DECOUVERTES.map((d) => (
            <StaggerItem key={d.titre}>
              <article className="group h-full overflow-hidden rounded-2xl bg-linen shadow-card transition-transform duration-300 hover:-translate-y-1.5">
                <div className="overflow-hidden">
                  <Photo
                    src={d.photo}
                    alt={d.titre}
                    label={d.titre}
                    className="h-48 w-full transition-transform duration-500 group-hover:scale-105"
                  />
                </div>
                <div className="p-6">
                  <h3 className="text-lg font-medium">{d.titre}</h3>
                  <p className="mt-3 text-sm leading-relaxed text-ink-soft">{d.texte}</p>
                </div>
              </article>
            </StaggerItem>
          ))}
        </Stagger>
      </div>
    </section>
  )
}
