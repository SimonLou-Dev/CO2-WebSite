import { defineConfig } from 'vite'
import reactRefresh from '@vitejs/plugin-react-refresh'

const Global = "var process = { env: {NODE_ENV: 'production'}}"


//production //development
// https://vitejs.dev/config/
export default defineConfig({
    plugins: [reactRefresh()],
    server: {
        watch: {
            usePolling: true
        }
    },
    root: './resources',
    base: '/assets/',
    mode:  "production", //env.FRONT_DEBUG ? "development" : "production",
    define: {
        'process.env': {}
    },
    build: {
        outDir: '../public/assets',
        assetsDir: '',
        manifest: true,
        minify: true,
        rollupOptions: {
            output: {
                manualChunks: undefined,
                banner: Global
            },
            input:{
                'App.jsx': './resources/js/App.jsx'
            },

        }
    }
})

