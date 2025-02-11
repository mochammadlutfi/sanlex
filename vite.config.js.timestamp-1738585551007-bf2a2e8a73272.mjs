// vite.config.js
import { defineConfig } from "file:///D:/laragon/www/sanlex/node_modules/vite/dist/node/index.js";
import laravel from "file:///D:/laragon/www/sanlex/node_modules/laravel-vite-plugin/dist/index.js";
import vue from "file:///D:/laragon/www/sanlex/node_modules/@vitejs/plugin-vue/dist/index.mjs";
import AutoImport from "file:///D:/laragon/www/sanlex/node_modules/unplugin-auto-import/dist/vite.js";
import Components from "file:///D:/laragon/www/sanlex/node_modules/unplugin-vue-components/dist/vite.js";
import { ElementPlusResolver } from "file:///D:/laragon/www/sanlex/node_modules/unplugin-vue-components/dist/resolvers.js";
import i18n from "file:///D:/laragon/www/sanlex/node_modules/laravel-vue-i18n/dist/vite.mjs";
var vite_config_default = defineConfig({
  server: {
    hmr: {
      host: "sanlex.local"
    },
    https: {
      key: "D:/laragon/etc/ssl/laragon.key",
      cert: "D:/laragon/etc/ssl/laragon.crt"
    }
  },
  plugins: [
    laravel({
      input: [
        "resources/scss/app.scss",
        "resources/js/app.js"
      ],
      refresh: true
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false
        }
      }
    }),
    AutoImport({
      resolvers: [
        ElementPlusResolver({
          importStyle: "scss"
        })
      ]
    }),
    Components({
      extensions: ["vue", "md"],
      include: [/\.vue$/, /\.vue\?vue/, /\.md$/],
      resolvers: [
        ElementPlusResolver({
          importStyle: "scss"
        })
      ]
    }),
    i18n()
  ],
  css: {
    preprocessorOptions: {
      scss: {
        quietDeps: true
      }
    }
  }
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCJEOlxcXFxsYXJhZ29uXFxcXHd3d1xcXFxzYW5sZXhcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfZmlsZW5hbWUgPSBcIkQ6XFxcXGxhcmFnb25cXFxcd3d3XFxcXHNhbmxleFxcXFx2aXRlLmNvbmZpZy5qc1wiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9pbXBvcnRfbWV0YV91cmwgPSBcImZpbGU6Ly8vRDovbGFyYWdvbi93d3cvc2FubGV4L3ZpdGUuY29uZmlnLmpzXCI7aW1wb3J0IHsgZGVmaW5lQ29uZmlnIH0gZnJvbSAndml0ZSc7XG5pbXBvcnQgbGFyYXZlbCBmcm9tICdsYXJhdmVsLXZpdGUtcGx1Z2luJztcbmltcG9ydCB2dWUgZnJvbSAnQHZpdGVqcy9wbHVnaW4tdnVlJztcbmltcG9ydCBBdXRvSW1wb3J0IGZyb20gJ3VucGx1Z2luLWF1dG8taW1wb3J0L3ZpdGUnXG5pbXBvcnQgQ29tcG9uZW50cyBmcm9tICd1bnBsdWdpbi12dWUtY29tcG9uZW50cy92aXRlJ1xuaW1wb3J0IHsgRWxlbWVudFBsdXNSZXNvbHZlciB9IGZyb20gJ3VucGx1Z2luLXZ1ZS1jb21wb25lbnRzL3Jlc29sdmVycydcbmltcG9ydCBpMThuIGZyb20gJ2xhcmF2ZWwtdnVlLWkxOG4vdml0ZSc7XG5cbmV4cG9ydCBkZWZhdWx0IGRlZmluZUNvbmZpZyh7XG4gICAgc2VydmVyOiB7XG4gICAgICBobXI6IHtcbiAgICAgICAgaG9zdDogJ3NhbmxleC5sb2NhbCcsXG4gICAgICB9LFxuICAgICAgaHR0cHM6IHtcbiAgICAgICAgICBrZXk6ICdEOi9sYXJhZ29uL2V0Yy9zc2wvbGFyYWdvbi5rZXknLFxuICAgICAgICAgIGNlcnQ6ICdEOi9sYXJhZ29uL2V0Yy9zc2wvbGFyYWdvbi5jcnQnLFxuICAgICAgfSxcbiAgICB9LFxuICAgIHBsdWdpbnM6IFtcbiAgICAgICAgbGFyYXZlbCh7XG4gICAgICAgICAgICBpbnB1dDogW1xuICAgICAgICAgICAgICAgICdyZXNvdXJjZXMvc2Nzcy9hcHAuc2NzcycsXG4gICAgICAgICAgICAgICAgJ3Jlc291cmNlcy9qcy9hcHAuanMnLFxuICAgICAgICAgICAgXSxcbiAgICAgICAgICAgIHJlZnJlc2g6IHRydWUsXG4gICAgICAgIH0pLFxuICAgICAgICB2dWUoe1xuICAgICAgICAgICAgdGVtcGxhdGU6IHtcbiAgICAgICAgICAgICAgICB0cmFuc2Zvcm1Bc3NldFVybHM6IHtcbiAgICAgICAgICAgICAgICAgICAgYmFzZTogbnVsbCxcbiAgICAgICAgICAgICAgICAgICAgaW5jbHVkZUFic29sdXRlOiBmYWxzZSxcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgfSxcbiAgICAgICAgfSksXG4gICAgICAgIEF1dG9JbXBvcnQoe1xuICAgICAgICAgICAgcmVzb2x2ZXJzOiBbXG4gICAgICAgICAgICAgICAgRWxlbWVudFBsdXNSZXNvbHZlcih7XG4gICAgICAgICAgICAgICAgICBpbXBvcnRTdHlsZTogJ3Njc3MnLFxuICAgICAgICAgICAgICAgIH0pLFxuICAgICAgICAgICAgXSxcbiAgICAgICAgfSksXG4gICAgICAgIENvbXBvbmVudHMoe1xuICAgICAgICAgIGV4dGVuc2lvbnM6IFsndnVlJywgJ21kJ10sXG4gICAgICAgICAgaW5jbHVkZTogWy9cXC52dWUkLywgL1xcLnZ1ZVxcP3Z1ZS8sIC9cXC5tZCQvXSxcbiAgICAgICAgICByZXNvbHZlcnM6IFtcbiAgICAgICAgICAgIEVsZW1lbnRQbHVzUmVzb2x2ZXIoe1xuICAgICAgICAgICAgICBpbXBvcnRTdHlsZTogJ3Njc3MnLFxuICAgICAgICAgICAgfSksXG4gICAgICAgICAgXSxcbiAgICAgICAgfSksXG4gICAgICAgIFxuICAgICAgICBpMThuKCksXG4gICAgXSxcbiAgICBjc3M6IHtcbiAgICAgICAgcHJlcHJvY2Vzc29yT3B0aW9uczoge1xuICAgICAgICAgICAgc2Nzczoge1xuICAgICAgICAgICAgICAgIHF1aWV0RGVwczogdHJ1ZVxuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfVxufSk7XG4iXSwKICAibWFwcGluZ3MiOiAiO0FBQXlQLFNBQVMsb0JBQW9CO0FBQ3RSLE9BQU8sYUFBYTtBQUNwQixPQUFPLFNBQVM7QUFDaEIsT0FBTyxnQkFBZ0I7QUFDdkIsT0FBTyxnQkFBZ0I7QUFDdkIsU0FBUywyQkFBMkI7QUFDcEMsT0FBTyxVQUFVO0FBRWpCLElBQU8sc0JBQVEsYUFBYTtBQUFBLEVBQ3hCLFFBQVE7QUFBQSxJQUNOLEtBQUs7QUFBQSxNQUNILE1BQU07QUFBQSxJQUNSO0FBQUEsSUFDQSxPQUFPO0FBQUEsTUFDSCxLQUFLO0FBQUEsTUFDTCxNQUFNO0FBQUEsSUFDVjtBQUFBLEVBQ0Y7QUFBQSxFQUNBLFNBQVM7QUFBQSxJQUNMLFFBQVE7QUFBQSxNQUNKLE9BQU87QUFBQSxRQUNIO0FBQUEsUUFDQTtBQUFBLE1BQ0o7QUFBQSxNQUNBLFNBQVM7QUFBQSxJQUNiLENBQUM7QUFBQSxJQUNELElBQUk7QUFBQSxNQUNBLFVBQVU7QUFBQSxRQUNOLG9CQUFvQjtBQUFBLFVBQ2hCLE1BQU07QUFBQSxVQUNOLGlCQUFpQjtBQUFBLFFBQ3JCO0FBQUEsTUFDSjtBQUFBLElBQ0osQ0FBQztBQUFBLElBQ0QsV0FBVztBQUFBLE1BQ1AsV0FBVztBQUFBLFFBQ1Asb0JBQW9CO0FBQUEsVUFDbEIsYUFBYTtBQUFBLFFBQ2YsQ0FBQztBQUFBLE1BQ0w7QUFBQSxJQUNKLENBQUM7QUFBQSxJQUNELFdBQVc7QUFBQSxNQUNULFlBQVksQ0FBQyxPQUFPLElBQUk7QUFBQSxNQUN4QixTQUFTLENBQUMsVUFBVSxjQUFjLE9BQU87QUFBQSxNQUN6QyxXQUFXO0FBQUEsUUFDVCxvQkFBb0I7QUFBQSxVQUNsQixhQUFhO0FBQUEsUUFDZixDQUFDO0FBQUEsTUFDSDtBQUFBLElBQ0YsQ0FBQztBQUFBLElBRUQsS0FBSztBQUFBLEVBQ1Q7QUFBQSxFQUNBLEtBQUs7QUFBQSxJQUNELHFCQUFxQjtBQUFBLE1BQ2pCLE1BQU07QUFBQSxRQUNGLFdBQVc7QUFBQSxNQUNmO0FBQUEsSUFDSjtBQUFBLEVBQ0o7QUFDSixDQUFDOyIsCiAgIm5hbWVzIjogW10KfQo=
