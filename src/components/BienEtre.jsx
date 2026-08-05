import { Reveal } from './Reveal.jsx'
import { Photo } from './Photo.jsx'
import { Icon } from './Icon.jsx'

export function BienEtre() {
  return (
    <section className="mx-auto max-w-7xl px-5 py-20 lg:px-8 lg:py-28">
      <div className="grid items-center gap-12 lg:grid-cols-2 lg:gap-20">
        <Reveal className="order-2 lg:order-1">
          <div className="grid grid-cols-2 gap-4">
            <Photo
              src="/images/salle-sport.jpg"
              alt="La salle de sport privée de la Villa Raffy : vélo, elliptique, rameur, banc de musculation"
              label="La salle de sport"
              className="h-56 w-full rounded-2xl shadow-card lg:h-72"
            />
            <Photo
              src="/images/cheminee.jpg"
              alt="Le coin cheminée du salon"
              label="Le coin cheminée"
              className="h-56 w-full rounded-2xl shadow-card lg:h-72"
            />
          </div>
        </Reveal>

        <Reveal delay={0.1} className="order-1 lg:order-2">
          <p className="section-eyebrow mb-3">Sport & détente</p>
          <h2 className="text-3xl font-medium leading-snug lg:text-4xl">
            Votre salle de sport et votre cinéma, à domicile
          </h2>
          <p className="mt-6 leading-relaxed text-ink-soft">
            Même en vacances, gardez vos bonnes habitudes — ou prenez-en de nouvelles.
            La salle de sport attenante à la suite parentale est équipée d'un vélo
            d'appartement, d'un elliptique, d'un rameur, d'un banc de musculation et de
            tapis de gym.
          </p>
          <p className="mt-4 leading-relaxed text-ink-soft">
            Le soir venu, l'écran géant de 3m50 réunit tout le monde pour une séance de
            cinéma en famille, un match entre amis ou une soirée série sous un plaid.
            La fibre et Canal+ sont inclus.
          </p>
          <ul className="mt-8 space-y-3">
            {[
              "Vélo d'appartement, elliptique et rameur",
              'Banc de musculation et tapis de gym',
              'Écran géant 3m50, fibre & Canal+',
              "Cheminée pour les soirées d'hiver",
            ].map((item) => (
              <li key={item} className="flex items-center gap-3 text-[0.95rem]">
                <Icon name="check" className="h-4 w-4 shrink-0 text-brass" />
                {item}
              </li>
            ))}
          </ul>
        </Reveal>
      </div>
    </section>
  )
}
