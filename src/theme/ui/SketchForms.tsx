import React, { useId } from 'react';

export interface FormFieldProps {
  label: string;
  htmlFor?: string;
  hint?: string;
  error?: string;
  required?: boolean;
  children: React.ReactNode;
}

export const FormField: React.FC<FormFieldProps> = ({
  label,
  htmlFor,
  hint,
  error,
  required,
  children,
}) => (
  <div className="flex flex-col gap-1.5">
    <label
      htmlFor={htmlFor}
      className="text-sm font-semibold text-[var(--pn-ink)]"
    >
      {label}
      {required && (
        <span className="ml-1 text-[var(--pn-blossom-700)]" aria-hidden="true">
          *
        </span>
      )}
    </label>
    {children}
    {error ? (
      <p role="alert" className="text-xs font-semibold text-[var(--pn-status-danger)]">
        {error}
      </p>
    ) : hint ? (
      <p className="text-xs text-[var(--pn-ink-soft)]">{hint}</p>
    ) : null}
  </div>
);

export interface SketchInputProps extends React.InputHTMLAttributes<HTMLInputElement> {
  label: string;
  hint?: string;
  error?: string;
}

export const SketchInput: React.FC<SketchInputProps> = ({
  label,
  hint,
  error,
  id,
  required,
  className = '',
  ...rest
}) => {
  const autoId = useId();
  const inputId = id ?? autoId;

  return (
    <FormField label={label} htmlFor={inputId} hint={hint} error={error} required={required}>
      <input
        id={inputId}
        required={required}
        aria-invalid={error ? true : undefined}
        className={`h-11 w-full rounded-[var(--pn-radius-sm)] border-[1.5px] bg-[#FFFDF9] px-3.5 text-sm text-[var(--pn-ink)] placeholder:text-[var(--pn-ink-soft)]/70 transition-colors focus:border-[var(--pn-blossom-600)] focus:outline-none ${
          error ? 'border-[var(--pn-status-danger)]' : 'border-[var(--pn-paper-line)]'
        } ${className}`}
        {...rest}
      />
    </FormField>
  );
};

export interface SketchTextareaProps extends React.TextareaHTMLAttributes<HTMLTextAreaElement> {
  label: string;
  hint?: string;
  error?: string;
}

export const SketchTextarea: React.FC<SketchTextareaProps> = ({
  label,
  hint,
  error,
  id,
  required,
  className = '',
  ...rest
}) => {
  const autoId = useId();
  const inputId = id ?? autoId;

  return (
    <FormField label={label} htmlFor={inputId} hint={hint} error={error} required={required}>
      <textarea
        id={inputId}
        required={required}
        aria-invalid={error ? true : undefined}
        className={`min-h-[96px] w-full rounded-[var(--pn-radius-sm)] border-[1.5px] bg-[#FFFDF9] p-3.5 text-sm text-[var(--pn-ink)] placeholder:text-[var(--pn-ink-soft)]/70 transition-colors focus:border-[var(--pn-blossom-600)] focus:outline-none ${
          error ? 'border-[var(--pn-status-danger)]' : 'border-[var(--pn-paper-line)]'
        } ${className}`}
        {...rest}
      />
    </FormField>
  );
};

export interface SketchSelectProps extends React.SelectHTMLAttributes<HTMLSelectElement> {
  label: string;
  hint?: string;
  error?: string;
  options: Array<{ value: string; label: string; disabled?: boolean }>;
}

export const SketchSelect: React.FC<SketchSelectProps> = ({
  label,
  hint,
  error,
  options,
  id,
  required,
  className = '',
  ...rest
}) => {
  const autoId = useId();
  const selectId = id ?? autoId;

  return (
    <FormField label={label} htmlFor={selectId} hint={hint} error={error} required={required}>
      <select
        id={selectId}
        required={required}
        aria-invalid={error ? true : undefined}
        className={`h-11 w-full rounded-[var(--pn-radius-sm)] border-[1.5px] bg-[#FFFDF9] px-3.5 text-sm text-[var(--pn-ink)] transition-colors focus:border-[var(--pn-blossom-600)] focus:outline-none ${
          error ? 'border-[var(--pn-status-danger)]' : 'border-[var(--pn-paper-line)]'
        } ${className}`}
        {...rest}
      >
        {options.map((opt) => (
          <option key={opt.value} value={opt.value} disabled={opt.disabled}>
            {opt.label}
          </option>
        ))}
      </select>
    </FormField>
  );
};

export interface SketchCheckboxProps extends Omit<React.InputHTMLAttributes<HTMLInputElement>, 'type'> {
  label: string;
  description?: string;
}

export const SketchCheckbox: React.FC<SketchCheckboxProps> = ({
  label,
  description,
  id,
  className = '',
  ...rest
}) => {
  const autoId = useId();
  const checkId = id ?? autoId;

  return (
    <label htmlFor={checkId} className={`flex cursor-pointer items-start gap-3 ${className}`}>
      <input
        id={checkId}
        type="checkbox"
        className="mt-0.5 h-5 w-5 rounded-[4px] border-[1.5px] border-[var(--pn-ink)] accent-[var(--pn-blossom-700)] cursor-pointer"
        {...rest}
      />
      <span className="text-sm">
        <span className="block font-semibold text-[var(--pn-ink)]">{label}</span>
        {description && <span className="block text-xs text-[var(--pn-ink-soft)]">{description}</span>}
      </span>
    </label>
  );
};
