/**
 * This script allows running Composer commands from NPM, regardless of whether
 * it's installed on the host system. It uses the official Composer Docker image.
 *
 * @example
 *      npm run composer install
 * @example
 *      npm run composer update
 * @example
 *      # Without the package.json shortcut
 *      node .dev/composer.js install
 */

import { docker } from './lib/docker.js'

const inputCommand = process.argv
    .slice( 2 )
    .join( ' ' )
    .trim()

await docker( `run -t --rm composer composer ${ inputCommand }` )
