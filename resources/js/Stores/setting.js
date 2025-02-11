import { defineStore } from 'pinia';
import Cookies from 'js-cookie';
import axios from 'axios';

export const useAppSettingsStore = defineStore('appSettings', {
    state: () => ({
        settings: {
            app_name: "",
            date_format: "",
            time_format: "",
            timezone: "",
            logo_light: null,
            logo_dark: null,
            locale: 'id-ID',
            currency: null,
        },
        loading : false,
    }),
    actions: {
        async loadSettings() {
            this.loading = true;
            const cookieData = Cookies.get('app_settings');
            if (cookieData) {
                this.settings = JSON.parse(cookieData);
            } else {
                await this.fetchSettingsFromAPI();
            }
            // this.loading = false;
        },
        async fetchSettingsFromAPI() {
            try {
                const response = await axios.get(route('app.settings.data'));
                this.settings = response.data;
                this.saveSettingsToCookie();
            } catch (error) {
                console.error("Failed to load settings from API:", error);
            }
        },
        saveSettingsToCookie() {
            Cookies.set('app_settings', JSON.stringify(this.settings), {
                expires: 7
            }); // 7 days
        },

        updateSetting(key, value) {
            if (this.settings.hasOwnProperty(key)) {
                this.settings[key] = value;
                this.saveSettingsToCookie();
            }
        },

        async refreshSettings() {
            await this.fetchSettingsFromAPI();
        },
    },
});
