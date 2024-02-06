import { defineConfig, loadEnv } from 'vite'
import reactRefresh from '@vitejs/plugin-react-refresh'

const Global = "var process = { env: {NODE_ENV: 'production'}}"


//production //development
// https://vitejs.dev/config/
export default defineConfig((command, mode) => {
    const env = loadEnv(mode, process.cwd(), '');
    return {
        plugins: [reactRefresh()],

        server: {
            watch: {
                usePolling: true
            }
        },
        root: './resources',
        base: '/assets/',
        mode: env.FRONT_DEBUG ? "development" : "production",
        define: {
            'process.env': env
        },
        build: {
            outDir: '../public/assets',
            assetsDir: '',
            manifest: !env.FRONT_DEBUG,
            minify: !env.FRONT_DEBUG,
            rollupOptions: {
                output: {
                    manualChunks: undefined,
                    banner: Global
                },
                input: {
                    'App.jsx': './resources/js/App.jsx'
                },

            }
        }
    }
})

