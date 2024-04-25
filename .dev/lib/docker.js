import cmd from './cmd.js'
import os from 'os'

/**
 * Convenience function that handles the calling of docker compose with the correct config
 * file, and allows commands to be passed, and the result to be awaited
 *
 * @param {string} command
 *      Command to pass to docker compose
 * @example
 *      docker_command( 'up' )
 */
export async function docker( command ) {
    if ( typeof command !== 'string' ) {
        throw new Error( 'Invalid Docker command' )
    }

    // If can't get host UID, use 1000. This will avoid problems in 99% of cases
    const userInfo = os.userInfo()
    process.env.MY_UID = userInfo.uid >= 0 ? userInfo.uid : 1000
    process.env.MY_GID = userInfo.gid >= 0 ? userInfo.gid : 1000

    await cmd( `docker compose -f .dev/config/docker-compose.yml ${ command }` )
}
