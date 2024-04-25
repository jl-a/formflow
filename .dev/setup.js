/**
 * This script installs the Composer packages for this project, and then runs
 * a first time build action. This means frontend assets will be ready for load
 * when the development WordPress environment is started.
 *
 * @example
 *      npm run setup
 */

import 'colors'
import cmd from './lib/cmd.js'

console.log( `\n----------------------------`.yellow )
console.log( `INSTALLING COMPOSER PACKAGES`.green )
console.log( `----------------------------`.yellow )

await cmd( 'npm run composer install' )

console.log( `\n----------------------------`.yellow )
console.log( `BUILDING REACT APP`.green )
console.log( `----------------------------`.yellow )

await cmd( 'npm run build' )

console.log( `\n----------------------------`.yellow )
console.log( `All items completed.\nDone.`.green )
