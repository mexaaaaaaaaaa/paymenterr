import React from 'react';

export interface SproutMascotProps {
  stage?: 'seedling' | 'growing' | 'blooming' | 'resting';
  size?: number;
  className?: string;
  label?: string;
}

/**
 * Sprout — Original Petalnode botanical sketchbook companion SVG.
 */
export const SproutMascot: React.FC<SproutMascotProps> = ({
  stage = 'blooming',
  size = 96,
  className = '',
  label = 'Sprout mascot',
}) => {
  return (
    <svg
      role="img"
      aria-label={label}
      width={size}
      height={size}
      viewBox="0 0 110 110"
      fill="none"
      className={`select-none ${className}`}
    >
      {/* Ground shadow */}
      <ellipse cx="55" cy="96" rx="28" ry="5" fill="#E4D6C1" />

      {/* Terracotta / Bark sketch pot */}
      <path
        d="M35 68H75L70 93H40L35 68Z"
        fill="#F1E7D6"
        stroke="#2B2230"
        strokeWidth="2.4"
        strokeLinejoin="round"
      />
      <rect
        x="31"
        y="61"
        width="48"
        height="9"
        rx="3"
        fill="#FFC9D6"
        stroke="#2B2230"
        strokeWidth="2.4"
      />

      {/* Pot face */}
      {stage === 'resting' ? (
        <>
          <path d="M44 79C46 81 48 81 50 79" stroke="#2B2230" strokeWidth="2" strokeLinecap="round" />
          <path d="M60 79C62 81 64 81 66 79" stroke="#2B2230" strokeWidth="2" strokeLinecap="round" />
        </>
      ) : (
        <>
          <circle cx="47" cy="79" r="2.2" fill="#2B2230" />
          <circle cx="63" cy="79" r="2.2" fill="#2B2230" />
          <path d="M51 82.5C53 84.8 57 84.8 59 82.5" stroke="#2B2230" strokeWidth="2" strokeLinecap="round" />
        </>
      )}

      {/* Stem */}
      <path
        d={stage === 'seedling' ? 'M55 61V45' : 'M55 61C55 50 54 39 55 30'}
        stroke="#6B4A3A"
        strokeWidth="3"
        strokeLinecap="round"
      />

      {/* Leaves */}
      <g className="pn-animate-sway" style={{ transformOrigin: '55px 52px' }}>
        <path
          d="M55 48C43 47 36 39 40 34C45 30 53 38 55 48Z"
          fill="#6FAF5B"
          stroke="#2B2230"
          strokeWidth="2.2"
          strokeLinejoin="round"
        />
        {stage !== 'seedling' && (
          <path
            d="M55 44C66 43 74 35 70 30C65 26 57 34 55 44Z"
            fill="#6FAF5B"
            stroke="#2B2230"
            strokeWidth="2.2"
            strokeLinejoin="round"
          />
        )}
      </g>

      {/* Blossom crown for blooming/growing */}
      {stage === 'blooming' && (
        <g transform="translate(55, 24)">
          <circle cx="0" cy="-7" r="6" fill="#FFC9D6" stroke="#2B2230" strokeWidth="2" />
          <circle cx="7" cy="-1" r="6" fill="#FF8FAB" stroke="#2B2230" strokeWidth="2" />
          <circle cx="-7" cy="-1" r="6" fill="#FFC9D6" stroke="#2B2230" strokeWidth="2" />
          <circle cx="4" cy="6" r="6" fill="#FFC9D6" stroke="#2B2230" strokeWidth="2" />
          <circle cx="-4" cy="6" r="6" fill="#FF8FAB" stroke="#2B2230" strokeWidth="2" />
          <circle cx="0" cy="0" r="4.5" fill="#FFC75A" stroke="#2B2230" strokeWidth="2" />
        </g>
      )}
      {stage === 'growing' && (
        <circle cx="55" cy="27" r="6.5" fill="#FFC9D6" stroke="#2B2230" strokeWidth="2.2" />
      )}
    </svg>
  );
};
