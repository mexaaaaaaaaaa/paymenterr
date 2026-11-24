import React, { createContext, useContext } from 'react';
import {
  UNCONNECTED_PAYMENTER_ACTIONS,
  type PaymenterThemeActions,
} from './adapters';
import {
  EMPTY_PAYMENTER_RUNTIME,
  type PaymenterThemeRuntime,
} from './contracts';

interface PaymenterContextValue {
  runtime: PaymenterThemeRuntime;
  actions: PaymenterThemeActions;
}

const PaymenterContext = createContext<PaymenterContextValue>({
  runtime: EMPTY_PAYMENTER_RUNTIME,
  actions: UNCONNECTED_PAYMENTER_ACTIONS,
});

export interface PaymenterProviderProps {
  runtime?: PaymenterThemeRuntime;
  actions?: PaymenterThemeActions;
  children: React.ReactNode;
}

export const PaymenterProvider: React.FC<PaymenterProviderProps> = ({
  runtime = EMPTY_PAYMENTER_RUNTIME,
  actions = UNCONNECTED_PAYMENTER_ACTIONS,
  children,
}) => {
  return (
    <PaymenterContext.Provider value={{ runtime, actions }}>
      {children}
    </PaymenterContext.Provider>
  );
};

export function usePaymenter(): PaymenterContextValue {
  return useContext(PaymenterContext);
}
