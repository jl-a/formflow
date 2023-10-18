require( 'colors' )
const fs = require( 'fs-extra' )

const files = [
    'form-flow.php',
    'LICENSE.txt',
    'Admin',
    'App',
    'assets/build',
    'assets/images',
    'Data',
    'Frontend',
    'vendor',
]

const dir = '.plugin/form-flow'

fs.emptydirSync( dir )

for ( const file of files ) {
    fs.copySync( file, `${ dir }/${ file }` )
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
