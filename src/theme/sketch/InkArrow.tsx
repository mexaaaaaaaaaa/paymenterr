import React from 'react';

export interface InkArrowProps {
  direction?: 'right' | 'left' | 'down' | 'curved-down-right';
  color?: string;
  className?: string;
}

export const InkArrow: React.FC<InkArrowProps> = ({
  direction = 'curved-down-right',
  color = 'currentColor',
  className = '',
}) => {
  if (direction === 'right') {
    return (
      <svg
        aria-hidden="true"
        viewBox="0 0 64 24"
        fill="none"
        className={`h-5 w-14 ${className}`}
      >
        <path
          d="M3 13C19 10 38 11 58 12"
          stroke={color}
          strokeWidth="2.2"
          strokeLinecap="round"
        />
        <path
          d="M49 5C53 8 56 10 60 12C55 15 51 17 48 20"
          stroke={color}
          strokeWidth="2.2"
          strokeLinecap="round"
          strokeLinejoin="round"
        />
      </svg>
    );
  }

  if (direction === 'left') {
    return (
      <svg
        aria-hidden="true"
        viewBox="0 0 64 24"
        fill="none"
        className={`h-5 w-14 ${className}`}
      >
        <path
          d="M61 12C44 10 25 11 6 12"
          stroke={color}
          strokeWidth="2.2"
          strokeLinecap="round"
        />
        <path
          d="M15 5C11 8 8 10 4 12C9 15 13 17 16 20"
          stroke={color}
          strokeWidth="2.2"
          strokeLinecap="round"
          strokeLinejoin="round"
        />
      </svg>
    );
  }

  if (direction === 'down') {
    return (
      <svg
        aria-hidden="true"
        viewBox="0 0 28 56"
        fill="none"
        className={`h-12 w-6 ${className}`}
      >
        <path
          d="M14 4C12 20 15 36 14 50"
          stroke={color}
          strokeWidth="2.2"
          strokeLinecap="round"
        />
        <path
          d="M7 42C10 46 12 49 14 52C17 48 19 45 22 41"
          stroke={color}
          strokeWidth="2.2"
          strokeLinecap="round"
          strokeLinejoin="round"
        />
      </svg>
    );
  }

  return (
    <svg
      aria-hidden="true"
      viewBox="0 0 56 44"
      fill="none"
      className={`h-9 w-11 ${className}`}
    >
      <path
        d="M5 6C19 6 35 15 44 35"
        stroke={color}
        strokeWidth="2.2"
        strokeLinecap="round"
      />
      <path
        d="M34 31C39 33 42 35 45 38C46 33 47 29 48 24"
        stroke={color}
        strokeWidth="2.2"
        strokeLinecap="round"
        strokeLinejoin="round"
      />
    </svg>
  );
};
