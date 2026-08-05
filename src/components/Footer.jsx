import { Reveal } from './Reveal.jsx'
import { Icon } from './Icon.jsx'
import { VILLA } from '../config/villa.js'

export function Footer() {
  return (
    <footer id="contact" className="bg-night text-linen">
      {/* Bandeau CTA final */}
      <div className="border-b border-linen/10">
        <div className="mx-auto max-w-7xl px-5 py-16 text-center lg:px-8 lg:py-20">
          <Reveal>
            <h2 className="mx-auto max-w-2xl font-display text-3xl font-medium leading-snug lg:text-4xl">
              Votre prochain séjour d'exception commence
              <span className="italic text-brass"> ici</span>
            </h2>
            <p className="mx-auto mt-4 max-w-xl text-linen/70">
              Un appel, un message — et la villa est à vous. Patrick et son épouse vous
              répondent personnellement, tous les jours.
            </p>
            <div className="mt-8 flex flex-wrap items-center justify-center gap-4">
              <a
                href="#reserver"
                className="rounded-full bg-brass px-7 py-3.5 font-medium text-linen shadow-soft transition-transform duration-200 hover:scale-[1.04]"
              >
                Vérifier les disponibilités
              </a>
              <a
                href={`tel:${VILLA.telephoneMobileIntl}`}
                className="flex items-center gap-2 rounded-full border border-linen/25 px-7 py-3.5 transition-colors hover:border-brass hover:text-brass"
              >
                <Icon name="phone" className="h-4 w-4" />
                {VILLA.telephoneMobile}
              </a>
            </div>
          </Reveal>
        </div>
      </div>

      {/* Coordonnées */}
      <div className="mx-auto grid max-w-7xl gap-10 px-5 py-14 sm:grid-cols-2 lg:grid-cols-4 lg:px-8">
        <div>
          <div className="font-display text-xl font-medium">Villa Raffy</div>
          <p className="mt-1 text-xs uppercase tracking-[0.22em] text-brass">Oasis d'exception</p>
          <p className="mt-4 text-sm leading-relaxed text-linen/60">
            Location de villa de luxe avec piscine, jacuzzi, salle de sport et cinéma
            privé en Lot-et-Garonne, entre Agen et Villeneuve-sur-Lot.
          </p>
        </div>

        <div>
          <h3 className="mb-4 text-sm font-medium uppercase tracking-wider text-linen/50">Contact</h3>
          <ul className="space-y-3 text-sm text-linen/75">
            <li>
              <a href={`tel:${VILLA.telephoneMobileIntl}`} className="flex items-center gap-2.5 transition-colors hover:text-brass">
                <Icon name="phone" className="h-4 w-4 text-brass" />
                {VILLA.telephoneMobile}
              </a>
            </li>
            <li>
              <a href={`mailto:${VILLA.email}`} className="flex items-center gap-2.5 transition-colors hover:text-brass">
                <Icon name="mail" className="h-4 w-4 text-brass" />
                {VILLA.email}
              </a>
            </li>
            <li className="flex items-start gap-2.5">
              <Icon name="pin" className="mt-0.5 h-4 w-4 shrink-0 text-brass" />
              {VILLA.adresse}
            </li>
          </ul>
        </div>

        <div>
          <h3 className="mb-4 text-sm font-medium uppercase tracking-wider text-linen/50">La villa</h3>
          <ul className="space-y-2.5 text-sm text-linen/75">
            <li><a href="#villa" className="transition-colors hover:text-brass">Le séjour & la cuisine</a></li>
            <li><a href="#chambres" className="transition-colors hover:text-brass">Les 4 chambres</a></li>
            <li><a href="#exterieurs" className="transition-colors hover:text-brass">Piscine, jacuzzi & jardin</a></li>
            <li><a href="#avis" className="transition-colors hover:text-brass">Avis des voyageurs</a></li>
            <li><a href="#reserver" className="transition-colors hover:text-brass">Réserver en direct</a></li>
          </ul>
        </div>

        <div>
          <h3 className="mb-4 text-sm font-medium uppercase tracking-wider text-linen/50">Confiance</h3>
          <ul className="space-y-2.5 text-sm text-linen/75">
            <li className="flex items-center gap-2">
              <Icon name="shield" className="h-4 w-4 text-brass" />
              {VILLA.refGites}
            </li>
            <li className="flex items-center gap-2">
              <Icon name="star" className="h-4 w-4 text-brass" />
              10/10 « Exceptionnel » sur Expedia
            </li>
            <li>
              <a
                href={VILLA.urlAirbnb}
                target="_blank"
                rel="noopener noreferrer"
                className="underline decoration-brass/40 underline-offset-4 transition-colors hover:text-brass"
              >
                Notre annonce Airbnb
              </a>
            </li>
          </ul>
        </div>
      </div>

      <div className="border-t border-linen/10">
        <div className="mx-auto flex max-w-7xl flex-col items-center justify-between gap-3 px-5 py-6 text-xs text-linen/40 sm:flex-row lg:px-8">
          <p>© {new Date().getFullYear()} Villa Raffy — Saint-Robert, Lot-et-Garonne. Tous droits réservés.</p>
          <p>Location saisonnière de standing · {VILLA.refGites}</p>
        </div>
      </div>
    </footer>
  )
}
