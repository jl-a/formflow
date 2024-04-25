import 'colors'
import fs from 'fs/promises'
import { existsSync } from 'fs'
import cmd from './lib/cmd.js'

const files = [
    'formflow.php',
    'LICENSE.txt',
    'App',
    'assets',
    'vendor',
]

const dir = '.plugin/form-flow'

await cmd( 'node .dev/build.js' )

if ( ! existsSync( dir ) ) {
    await fs.mkdir( dir, { recursive: true } )
}

for ( const file of files ) {
    await fs.cp( file, `${ dir }/${ file }`, { recursive: true } )
}

console.log(
`

----------------------------------------------------------------------------------

`,
`Success`.bold.green,
`
  - Production code has been built
  - Relevant files have been copied to ${ dir }
`,
`

The plugin may be deployed by using the ${ dir } directory
`.yellow
)
