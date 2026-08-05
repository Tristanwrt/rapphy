import { Reveal } from './Reveal.jsx'
import { Icon } from './Icon.jsx'
import { VILLA } from '../config/villa.js'

const AVANTAGES = [
  {
    icon: 'check',
    titre: 'Le meilleur tarif, garanti',
    texte:
      "En réservant en direct, vous évitez les commissions des plateformes (jusqu'à 15 %). C'est toujours ici que le prix est le plus doux.",
  },
  {
    icon: 'phone',
    titre: 'Un échange direct avec vos hôtes',
    texte:
      "Stéphane & Sophie vous répondent personnellement, en moins d'une heure (100 % de taux de réponse). Petites attentions sur demande — jusqu'aux huîtres et crevettes fraîches livrées à la villa.",
  },
  {
    icon: 'calendar',
    titre: 'Souplesse et simplicité',
    texte:
      "Horaires d'arrivée, demandes particulières, séjours sur mesure : tout se règle en un appel ou un message, sans intermédiaire.",
  },
]

export function DirectBanner() {
  return (
    <section className="border-y border-brass/20 bg-canvas-deep">
      <div className="mx-auto max-w-7xl px-5 py-16 lg:px-8 lg:py-20">
        <Reveal>
          <p className="section-eyebrow mb-3 text-center">Pourquoi réserver en direct ?</p>
          <h2 className="mx-auto max-w-2xl text-center text-3xl font-medium leading-snug lg:text-4xl">
            Ici, pas d'intermédiaire.
            <br />
            <span className="italic text-brass">Juste vous, et la villa.</span>
          </h2>
        </Reveal>

        <div className="mt-12 grid gap-8 md:grid-cols-3">
          {AVANTAGES.map((a, i) => (
            <Reveal key={a.titre} delay={i * 0.12}>
              <div className="h-full rounded-2xl bg-linen p-7 shadow-card">
                <div className="mb-4 flex h-11 w-11 items-center justify-center rounded-full bg-brass/12 text-brass">
                  <Icon name={a.icon} className="h-5 w-5" />
                </div>
                <h3 className="mb-2 text-lg font-medium">{a.titre}</h3>
                <p className="text-[0.95rem] leading-relaxed text-ink-soft">{a.texte}</p>
              </div>
            </Reveal>
          ))}
        </div>

        <Reveal delay={0.2} className="mt-10 text-center">
          <p className="text-ink-soft">
            Une question ? Appelez directement le{' '}
            <a href={`tel:${VILLA.telephoneMobileIntl}`} className="font-medium text-brass underline-offset-4 hover:underline">
              {VILLA.telephoneMobile}
            </a>
          </p>
        </Reveal>
      </div>
    </section>
  )
}
