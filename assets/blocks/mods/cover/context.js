import { createContext } from '@wordpress/element'

const DummyContext = createContext( {} )

export function useBlockFullContext() {
  return useContext( DummyContext )
}
