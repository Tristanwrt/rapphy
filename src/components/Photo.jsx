import { useState } from 'react'

// Photo de la villa. Si le fichier n'est pas encore déposé dans /public/images,
// un fond dégradé élégant s'affiche à la place avec le nom de la pièce.
export function Photo({ src, alt, className = '', label }) {
  const [missing, setMissing] = useState(false)

  if (missing) {
    return (
      <div className={`photo-fallback relative overflow-hidden ${className}`} role="img" aria-label={alt}>
        <div className="absolute inset-0 flex items-end p-5">
          <span className="font-display text-linen/70 text-sm italic tracking-wide">
            {label || alt}
          </span>
        </div>
      </div>
    )
  }

  return (
    <img
      src={src}
      alt={alt}
      loading="lazy"
      onError={() => setMissing(true)}
      className={`object-cover ${className}`}
    />
  )
}
