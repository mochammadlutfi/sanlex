import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite'
import { ElementPlusResolver } from 'unplugin-vue-components/resolvers'
import i18n from 'laravel-vue-i18n/vite';

export default defineConfig({
    server: {
      hmr: {
        host: 'sanlex.local',
      },
      https: {
          key: 'D:/laragon/etc/ssl/laragon.key',
          cert: 'D:/laragon/etc/ssl/laragon.crt',
      },
    },
    plugins: [
        laravel({
            input: [
                'resources/scss/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        AutoImport({
            resolvers: [
                ElementPlusResolver({
                  importStyle: 'scss',
                }),
            ],
        }),
        Components({
          extensions: ['vue', 'md'],
          include: [/\.vue$/, /\.vue\?vue/, /\.md$/],
          resolvers: [
            ElementPlusResolver({
              importStyle: 'scss',
            }),
          ],
        }),
        
        i18n(),
    ],
    css: {
        preprocessorOptions: {
            scss: {
                quietDeps: true
            }
        }
    }
});
