import React from 'react';

export interface HandIconProps {
  name: 'blossom' | 'leaf' | 'cube' | 'receipt' | 'lantern' | 'shield' | 'quill' | 'compass';
  size?: number;
  className?: string;
}

export const HandIcon: React.FC<HandIconProps> = ({
  name,
  size = 22,
  className = '',
}) => {
  const common = {
    width: size,
    height: size,
    viewBox: '0 0 24 24',
    fill: 'none',
    stroke: 'currentColor',
    strokeWidth: 1.85,
    strokeLinecap: 'round' as const,
    strokeLinejoin: 'round' as const,
    'aria-hidden': true as const,
    className,
  };

  switch (name) {
    case 'blossom':
      return (
        <svg {...common}>
          <path d="M12 6.5C10.5 3.5 13.5 2.5 14 5.5C16.8 4 19 6.5 16.2 8.8C19.2 10 18.2 13.5 15 12.6C16.2 15.8 12.5 17.5 11.4 14.3C9.2 17 5.8 14.5 7.8 11.8C4.8 11 5.6 7.2 8.8 7.8C7.8 5 10.6 3.8 12 6.5Z" />
          <circle cx="11.8" cy="10.4" r="1.4" />
          <path d="M12 15.5V21" />
        </svg>
      );
    case 'leaf':
      return (
        <svg {...common}>
          <path d="M5 19C5 10.5 11 5 19 5C19 13 13.5 19 5 19Z" />
          <path d="M5 19C9 14.5 12.5 11 16 8" />
        </svg>
      );
    case 'cube':
      return (
        <svg {...common}>
          <path d="M12 3.5L19.5 7.5V16.5L12 20.5L4.5 16.5V7.5L12 3.5Z" />
          <path d="M4.8 7.7L12 11.6L19.2 7.7" />
          <path d="M12 11.6V20.2" />
        </svg>
      );
    case 'receipt':
      return (
        <svg {...common}>
          <path d="M6 3.5H18V20.5L15.5 18.8L13.5 20.5L12 18.8L10.5 20.5L8.5 18.8L6 20.5V3.5Z" />
          <path d="M9 8.5H15" />
          <path d="M9 12H15" />
          <path d="M9 15.2H12.5" />
        </svg>
      );
    case 'lantern':
      return (
        <svg {...common}>
          <path d="M9.5 4.5H14.5" />
          <path d="M12 3V4.5" />
          <path d="M7.5 8.5C7.5 6.8 9.2 6 12 6C14.8 6 16.5 6.8 16.5 8.5V16C16.5 17.8 14.8 18.5 12 18.5C9.2 18.5 7.5 17.8 7.5 16V8.5Z" />
          <path d="M12 10.5C13 11.7 13 13.5 12 14.5C11 13.5 11 11.7 12 10.5Z" />
          <path d="M8.5 18.5H15.5" />
        </svg>
      );
    case 'shield':
      return (
        <svg {...common}>
          <path d="M12 3.5L18.5 6.2V11.8C18.5 16 15.6 19.2 12 20.5C8.4 19.2 5.5 16 5.5 11.8V6.2L12 3.5Z" />
          <path d="M9.2 11.8L11.2 13.8L15 9.8" />
        </svg>
      );
    case 'quill':
      return (
        <svg {...common}>
          <path d="M19.5 4.5C13 5 8 10 6.5 17.5L4.5 19.5" />
          <path d="M19.5 4.5C18.5 10.5 14 14.5 8 15.5" />
          <path d="M11 10.5L13.5 13" />
        </svg>
      );
    case 'compass':
    default:
      return (
        <svg {...common}>
          <circle cx="12" cy="12" r="8.5" />
          <path d="M14.8 9.2L13.1 13.3L9.2 14.8L10.9 10.7L14.8 9.2Z" />
        </svg>
      );
  }
};
