import {defineConfig, loadEnv} from "vite";

export default defineConfig(({command, mode }) => {

	//console.log(loadEnv(mode, process.cwd(), 'PUBLIC_'));

	return {

		root: './src',
		//base: '/assets/',
		envDir: process.cwd(),
		envPrefix: 'PUBLIC_',

		server: {
			port: 5173,
			https: false,
			//host: 'brm-calendar.local',
			cors: {
				origin: '*',
				methods: "GET,HEAD,PUT,PATCH,POST,DELETE"
			},
			proxy: {
				'/api': {
					target: 'http://rgsone.local:8888',
					changeOrigin: true,
					secure: false,
					rewrite: (path) => path.replace(/^\/api/, '')
				}
			}
		},

		build: {
			//manifest: true,
			emptyOutDir: false,
			outDir: '../../public',
			cssCodeSplit: false,
			//assetsDir: 'assets', // won't work because output in rollup is defined
			rollupOptions: {
				input: './src/main.js',
				output: {
					entryFileNames: 'assets/[name].js',
					chunkFileNames: 'assets/[name].js',
					assetFileNames: 'assets/[name].[ext]'
				}
			}
		}

	}

});
