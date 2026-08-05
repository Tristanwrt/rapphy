import { Reveal } from './Reveal.jsx'
import { Icon } from './Icon.jsx'

export function Sejours() {
  return (
    <section className="mx-auto max-w-7xl px-5 py-20 lg:px-8 lg:py-24">
      <Reveal className="mb-12 text-center">
        <p className="section-eyebrow mb-3">Deux façons de séjourner</p>
        <h2 className="mx-auto max-w-2xl text-3xl font-medium leading-snug lg:text-4xl">
          La villa entière, ou la version cocooning
        </h2>
      </Reveal>

      <div className="grid gap-7 md:grid-cols-2">
        <Reveal>
          <div className="flex h-full flex-col rounded-3xl bg-night p-9 text-linen shadow-soft">
            <p className="section-eyebrow mb-2">Haute saison · été</p>
            <h3 className="font-display text-2xl font-medium">La villa complète</h3>
            <p className="mt-4 leading-relaxed text-linen/75">
              Les 180 m² et les 2300 m² de parc rien que pour vous : 4 chambres,
              jusqu'à 8 voyageurs, piscine, jacuzzi, plage privée, salle de sport et
              cinéma. La formule idéale pour les familles et les tribus d'amis.
            </p>
            <ul className="mt-6 space-y-2.5 text-sm text-linen/80">
              {[
                'Jusqu’à 8 voyageurs · 4 chambres, 4 salles d’eau',
                'Toutes les prestations premium incluses',
                'Grandes tablées, soirées piscine, pétanque',
              ].map((item) => (
                <li key={item} className="flex items-center gap-3">
                  <Icon name="check" className="h-4 w-4 shrink-0 text-brass" />
                  {item}
                </li>
              ))}
            </ul>
            <a
              href="#reserver"
              className="mt-8 inline-flex w-fit items-center gap-2 rounded-full bg-brass px-6 py-3 font-medium text-linen transition-transform duration-200 hover:scale-[1.04]"
            >
              Réserver la villa complète
              <Icon name="arrow" className="h-4 w-4" />
            </a>
          </div>
        </Reveal>

        <Reveal delay={0.12}>
          <div className="flex h-full flex-col rounded-3xl border border-brass/25 bg-linen p-9 shadow-card">
            <p className="section-eyebrow mb-2">Basse saison · avril à juillet & septembre-octobre</p>
            <h3 className="font-display text-2xl font-medium">La version cocooning</h3>
            <p className="mt-4 leading-relaxed text-ink-soft">
              Un privilège rare : la villa entièrement privatisée pour 2 à 4 personnes,
              en configuration intimiste. Deux suites prestige avec salles de bain
              privées, un tarif plus doux — et toujours la piscine, le jacuzzi et le
              calme absolu, rien que pour vous.
            </p>
            <ul className="mt-6 space-y-2.5 text-sm text-ink-soft">
              {[
                '2 à 4 voyageurs · 2 suites prestige (lit bébé disponible)',
                'Villa et extérieurs 100 % privatisés',
                'Parfaite pour les couples et petits comités',
              ].map((item) => (
                <li key={item} className="flex items-center gap-3">
                  <Icon name="check" className="h-4 w-4 shrink-0 text-brass" />
                  {item}
                </li>
              ))}
            </ul>
            <a
              href="#reserver"
              className="mt-8 inline-flex w-fit items-center gap-2 rounded-full border border-brass px-6 py-3 font-medium text-brass transition-colors duration-200 hover:bg-brass/10"
            >
              Demander la version cocooning
              <Icon name="arrow" className="h-4 w-4" />
            </a>
          </div>
        </Reveal>
      </div>
    </section>
  )
}
