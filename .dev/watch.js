import 'colors'
import chokidar from 'chokidar'
import { debounce } from 'throttle-debounce'
import build from './build.js'

/** Watch files within these top level directories */
const watchDirs = [
    './src-admin',
    './src-frontend',
]

/** Only watch files with these extensions */
const watchExts = [
    'css',
    'js',
    'jsx',
    'json',
    'scss',
    'ts',
    'tsx',
]

/** Ignore files that have any of these strings in their path and name */
const ignoredDirs = [
    'build',
    'dist',
    'node_modules',
    'vendor',
]

// Defaults to development builds, but can do production if needed
const mode = process.argv[ 2 ]?.startsWith( 'prod' ) ? 'production' : 'development'

const paths = []
for ( const dir of watchDirs ) {
    for ( const ext of watchExts ) {
        paths.push( `${ dir }/**/*.${ ext }` )
    }
}

/**
 * Calls the build function, allowing for a debounce delay to prevent building from being called
 * again too rapidly.
 *
 * @param {number} debounceDelay
 *      Debounce delay in milliseconds
 * @param {boolean} specifyTask
 *      Whether or not to specify the build task. If true, then the file extension of the file that
 *      has changed will be checked, and the apporpriate build task (script or style) will be set
 *      so that type of file will be built.
 * @returns {function}              Function to run when building
 */
const onChange = ( debounceDelay ) => debounce(
    debounceDelay,
    async ( file ) => {
        try { await build( mode ) }
        catch( e ) {
            console.error( e )
        }
    },
	{ atBegin: true }
)

const watcher = chokidar.watch( paths, {
    ignored: path => ignoredDirs.some( str => path.includes( str ) )
} )

watcher
    // Debounce for two seconds when first added, to allow for lots of files being added. Also ensure all
    // files are built on first run
    .on( 'add', onChange( 2000, false ) )
    // On subsequent builds, just do a very short debounce and allow specifying the build task
    .on( 'change', onChange( 50, true ) )
    .on( 'unlink', onChange( 50, true ) )

