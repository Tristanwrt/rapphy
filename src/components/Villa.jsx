import { Reveal } from './Reveal.jsx'
import { Photo } from './Photo.jsx'
import { Icon } from './Icon.jsx'

const EQUIPEMENTS = [
  { icon: 'film', label: 'Écran géant 3m50 — fibre & Canal+' },
  { icon: 'chef', label: 'Cuisine ouverte équipée avec bar' },
  { icon: 'wifi', label: 'WiFi fibre très haut débit' },
  { icon: 'sun', label: '3 terrasses exposées plein sud' },
  { icon: 'paw', label: 'Vos animaux sont les bienvenus' },
  { icon: 'shield', label: 'Terrain entièrement clos et privé' },
]

export function Villa() {
  return (
    <section id="villa" className="mx-auto max-w-7xl px-5 py-20 lg:px-8 lg:py-28">
      <div className="grid items-center gap-12 lg:grid-cols-2 lg:gap-20">
        <Reveal>
          <p className="section-eyebrow mb-3">La villa</p>
          <h2 className="text-3xl font-medium leading-snug lg:text-4xl">
            Un séjour de 70 m² où la vie s'installe naturellement
          </h2>
          <p className="mt-6 leading-relaxed text-ink-soft">
            Poussez la porte : le séjour cathédrale de 70 m² s'ouvre sur une cuisine
            équipée et son bar, pensés pour les longues tablées d'été comme pour les
            soirées d'hiver au coin du feu. Côté détente, l'écran géant de 3m50
            transforme le salon en salle de cinéma privée — fibre et Canal+ compris.
          </p>
          <p className="mt-4 leading-relaxed text-ink-soft">
            De plain-pied, baignée de lumière, la villa regarde la campagne du
            Lot-et-Garonne sans aucun vis-à-vis. Familles, couples, tribus d'amis :
            chacun y trouve son rythme, entre farniente au bord de l'eau et grandes
            soirées partagées.
          </p>

          <ul className="mt-8 grid gap-3.5 sm:grid-cols-2">
            {EQUIPEMENTS.map((e) => (
              <li key={e.label} className="flex items-center gap-3 text-[0.95rem]">
                <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brass/12 text-brass">
                  <Icon name={e.icon} className="h-4 w-4" />
                </span>
                {e.label}
              </li>
            ))}
          </ul>
        </Reveal>

        {/* Galerie bento */}
        <Reveal delay={0.15}>
          <div className="grid grid-cols-2 gap-4">
            <Photo
              src="/images/salon.jpg"
              alt="Le grand séjour de 70 m² de la Villa Raffy avec sa cuisine ouverte"
              label="Le séjour de 70 m²"
              className="col-span-2 h-64 w-full rounded-2xl shadow-card lg:h-72"
            />
            <Photo
              src="/images/cuisine.jpg"
              alt="La cuisine équipée et son bar"
              label="La cuisine & son bar"
              className="h-48 w-full rounded-2xl shadow-card"
            />
            <Photo
              src="/images/ecran-cinema.jpg"
              alt="Le coin cinéma avec écran géant de 3m50"
              label="Le cinéma 3m50"
              className="h-48 w-full rounded-2xl shadow-card"
            />
          </div>
        </Reveal>
      </div>
    </section>
  )
}
