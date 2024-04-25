import { spawn } from 'child_process'

/**
 * Promisifies the Node functions to run commands. Performs some basic type checking and sets up a Promise
 * that handles Node errors and streams output to the host's command line.
 *
 * @param {string} command      Command to run
 * @returns {Promise}
 */
export default function( command ) {
    return new Promise( ( resolve, reject ) => {
        if (
            typeof command !== 'string'
            || ! command.trim().length
        ) {
            return reject( new Error( 'Invalid command' ) )
        }

        const commandItems = command.split( /\s+/ ) // split by whitespace
        const cmd = commandItems[ 0 ] // because we checked earlier that the command contains at least some non-whitespace, we're guaranteed to have at least 1 element
        const args = commandItems.slice( 1 )

        const child = spawn( cmd, args, {
            stdio: 'inherit',
            shell: true,
        } )

        child.on( 'close', code => {
            cleanup()
            if ( code === 0 ) {
                resolve()
            } else {
                reject( new Error( `Command '${ command }' failed with code ${ code }` ) )
            }
        } )

        child.on( 'error', error => {
            cleanup()
            reject( error )
        } )

        // Signal handling
        const signals = [ 'SIGINT', 'SIGTERM', 'SIGQUIT' ]
        const signalHandler = signal => {
            console.log( `${ signal } received, terminating child process...` )
            process.kill( -child.pid, signal ) // nuke it lol
        }

        signals.forEach( signal => process.on( signal, signalHandler ) )

        // Cleanup to remove signal listeners
        const cleanup = () => {
            signals.forEach( signal => process.off( signal, signalHandler ) )
        }

    } )
}
