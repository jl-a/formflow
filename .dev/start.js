import concurrently from 'concurrently'

concurrently( [
    { command: 'npm run watch',     name: 'FRONTEND  ', prefixColor: '#1895ab' },
    { command: 'npm run wordpress', name: 'WPRDPRESS ', prefixColor: '#18ab3f' }
] )

