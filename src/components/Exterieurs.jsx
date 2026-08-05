import { Reveal } from './Reveal.jsx'
import { Photo } from './Photo.jsx'
import { Icon } from './Icon.jsx'

export function Exterieurs() {
  return (
    <section id="exterieurs" className="bg-night py-20 text-linen lg:py-28">
      <div className="mx-auto max-w-7xl px-5 lg:px-8">
        <Reveal className="mb-14 text-center">
          <p className="section-eyebrow mb-3">Piscine & jardin</p>
          <h2 className="mx-auto max-w-2xl text-3xl font-medium leading-snug text-linen lg:text-4xl">
            Un cocktail au bar immergé,
            <br />
            <span className="italic text-brass">les pieds dans l'eau</span>
          </h2>
        </Reveal>

        <div className="grid gap-5 md:grid-cols-12">
          <Reveal className="md:col-span-7">
            <Photo
              src="/images/piscine-jour.jpg"
              alt="La piscine de 9 mètres de la Villa Raffy, exposée plein sud, avec son bar immergé"
              label="La piscine 9 × 3,50 m"
              className="h-72 w-full rounded-2xl shadow-soft lg:h-[26rem]"
            />
          </Reveal>
          <Reveal delay={0.1} className="md:col-span-5">
            <Photo
              src="/images/jacuzzi.jpg"
              alt="Le jacuzzi 5 places de la Villa Raffy"
              label="Le jacuzzi 5 places"
              className="h-72 w-full rounded-2xl shadow-soft lg:h-[26rem]"
            />
          </Reveal>
          <Reveal delay={0.05} className="md:col-span-5">
            <Photo
              src="/images/piscine-nuit.jpg"
              alt="La piscine éclairée à la tombée de la nuit"
              label="La piscine à la nuit tombée"
              className="h-64 w-full rounded-2xl shadow-soft lg:h-80"
            />
          </Reveal>
          <Reveal delay={0.12} className="md:col-span-7">
            <Photo
              src="/images/terrasse.jpg"
              alt="Une des trois terrasses de la villa, dressée pour un dîner d'été"
              label="Les terrasses"
              className="h-64 w-full rounded-2xl shadow-soft lg:h-80"
            />
          </Reveal>
        </div>

        <div className="mt-14 grid gap-10 md:grid-cols-3">
          <Reveal>
            <div className="flex gap-4">
              <span className="flex h-11 w-11 shrink-0 items-center justify-center rounded-full border border-brass/40 text-brass">
                <Icon name="waves" className="h-5 w-5" />
              </span>
              <div>
                <h3 className="font-medium text-linen">Piscine 9 × 3,50 m, plein sud</h3>
                <p className="mt-1.5 text-sm leading-relaxed text-linen/70">
                  Profondeur douce de 1,30 m, idéale pour les enfants comme pour les
                  longueurs tranquilles. Et son bar immergé pour siroter un cocktail
                  dans l'eau, éclairages compris pour les soirées.
                </p>
              </div>
            </div>
          </Reveal>
          <Reveal delay={0.1}>
            <div className="flex gap-4">
              <span className="flex h-11 w-11 shrink-0 items-center justify-center rounded-full border border-brass/40 text-brass">
                <Icon name="spa" className="h-5 w-5" />
              </span>
              <div>
                <h3 className="font-medium text-linen">Jacuzzi 5 places</h3>
                <p className="mt-1.5 text-sm leading-relaxed text-linen/70">
                  Sous le ciel étoilé du Lot-et-Garonne, le spa 5 places prolonge les
                  journées d'été et réchauffe les soirées de printemps.
                </p>
              </div>
            </div>
          </Reveal>
          <Reveal delay={0.2}>
            <div className="flex gap-4">
              <span className="flex h-11 w-11 shrink-0 items-center justify-center rounded-full border border-brass/40 text-brass">
                <Icon name="tree" className="h-5 w-5" />
              </span>
              <div>
                <h3 className="font-medium text-linen">2300 m² rien que pour vous</h3>
                <p className="mt-1.5 text-sm leading-relaxed text-linen/70">
                  Un parc entièrement clos, trois terrasses, un terrain de pétanque et la
                  campagne à perte de vue. Vos enfants et vos animaux y sont en liberté,
                  en toute sécurité.
                </p>
              </div>
            </div>
          </Reveal>
        </div>
      </div>
    </section>
  )
}
