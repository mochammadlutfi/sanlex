import { defineStore } from 'pinia'
import Cookies from 'js-cookie';
import axios from 'axios';

const helpers = {
    getWindowWidth () {
      return window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth
    },

    getCurrentYear () {
      return new Date().getFullYear()
    },
}
const APP = "APP";

const getJSONFromLocalStorage = (key) => {
    const value = window.localStorage.getItem(key);

    if (value === 'undefined' || value === 'null' || value === undefined || value === null) {
        return null;
    }
    else {
        return JSON.parse(value);
    }
};


export const useAppBaseStore  = defineStore({
    id : 'appBase',
    state: () => {
        return {
            initialized : false,
            app: getJSONFromLocalStorage(APP),
            layout: {
              header: true,
              sidebar: true,
              sideOverlay: true,
              footer: true
            },
            settings: {
              colorTheme: '',
              sidebarLeft: true,
              sidebarMini: false,
              sidebarDark: true,
              sidebarVisibleDesktop: true,
              sidebarVisibleMobile: false,
              headerFixed: true,
              pageLoader: false,
              rtlSupport: false,
              sideTransitions: true,
              mainContent: 'boxed'
            }
        }
    },
    
    getters: {
        isInitialized(state){
            return (state.app);
        },
    },

    actions : {
        // Sets the layout, useful for setting different layouts (under layouts/variations/) 
        setLayout (payload) {
          state.layout.header = payload.header
          state.layout.sidebar = payload.sidebar
          state.layout.sideOverlay = payload.sideOverlay
          state.layout.footer = payload.footer
        },
        // Sets sidebar visibility (open, close, toggle)
        sidebar (mode) {
          if (helpers.getWindowWidth() > 991) {
            if (mode === 'open') {
              this.settings.sidebarVisibleDesktop = true
            } else if (mode === 'close') {
              this.settings.sidebarVisibleDesktop = false
            } else if (mode === 'toggle') {
              this.settings.sidebarVisibleDesktop = !this.settings.sidebarVisibleDesktop
            }
          } else {
            if (mode === 'open') {
              this.settings.sidebarVisibleMobile = true
            } else if (mode === 'close') {
              this.settings.sidebarVisibleMobile = false
            } else if (mode === 'toggle') {
              this.settings.sidebarVisibleMobile = !this.settings.sidebarVisibleMobile
            }
          }
        },
    
        // Sets sidebar mini mode (on, off, toggle)
        sidebarMini (mode) {
          if (helpers.getWindowWidth() > 991) {
            if (mode === 'on') {
              this.settings.sidebarMini = true
            } else if (mode === 'off') {
              this.settings.sidebarMini = false
            } else if (mode === 'toggle') {
              this.settings.sidebarMini = !this.settings.sidebarMini
            }
          }
        },

        async initApp()
        {
            try {
                const response = await axios.get('/base');
                if(response.status == 200){
                    const data = response.data;
                    this.app = data;
                    window.localStorage.setItem(APP, JSON.stringify(data));
                }
            } catch (error) {
                console.error(error);
            }
        },

        async updateApp(){
            
        }
    }
})