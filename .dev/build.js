import colors from 'colors/safe.js'
import fs from 'fs/promises'
import path from 'path'
import { existsSync } from 'fs'
import { fileURLToPath } from 'url'
import * as esbuild from 'esbuild'
import { sassPlugin } from 'esbuild-sass-plugin'
import cmd from './lib/cmd.js'

const buildDir = path.resolve( './assets/build' )

const build = async mode => {
    if ( mode !== 'development' ) {
        mode = 'production' // always production unless explicitly set to development
    }

    const date = new Date()
    console.log(
        colors.green.bold( `${ date.toLocaleTimeString() }` )
        + colors.green( ` - Building assets in ` )
        + colors.green.bold( mode )
        + colors.green( ' mode' )
    )

    const aliases = {
        '@': '.',
        '@admin': './src-admin',
        '@frontend': './src-frontend',
    }
    
    const entries = [
        { in: 'src-frontend/main.ts', out: 'main.js' },
        { in: 'src-admin/admin.tsx', out: 'admin.js' },
    ]

    if ( ! existsSync( buildDir ) ) {
        await fs.mkdir( buildDir )
    }

    // Full type checking if building for production. Esbuild doesn't do type checking, it treats
    // Typescript types like comments, so we have to do a separate step with tsc to get the full
    // benefits of strict types
    if ( mode === 'production' ) {
        console.log( 'Type checking' )
        await cmd( 'tsc --noEmit' )
    }

    for ( const entry of entries ) {
        console.log( `Building ${ entry.in }` )
        await esbuild.build( {
            stdin: {
                contents: `import "./${ entry.in }"`,
                resolveDir: path.resolve( '.' )
            },
            bundle: true,
            alias: aliases,
            minify: mode === 'production',
            target: 'es6',
            sourcemap: true,
            plugins: [ sassPlugin( {
                style: mode === 'production' ? 'compressed' : 'expanded',
            } ) ],
            outfile: `./assets/build/${ entry.out }`,
        } )
    }

    console.log( colors.green( 'Built' ) )
}

// Check if this file is being called directly from Node, or is being imported.
// If called from Node we run the build function.
// See https://stackoverflow.com/a/66309132/1427563
const filePath = path.resolve( fileURLToPath( import.meta.url ) )
const argPath = path.resolve( process.argv[ 1 ] )
if ( filePath.includes( argPath ) ) {
    const mode = process.argv[ 2 ]?.startsWith( 'dev' ) ? 'development' : 'production'
    build( mode )
}

export default build
