import dayjs from 'dayjs';
import { useAppSettingsStore } from '@/Stores/setting';
  
export const formatMixin = {
  methods: {
    formatDate(date) {
        const appSettingsStore = useAppSettingsStore();
        const dateFormat = appSettingsStore.settings.date_format; 
  
        const timeFormat = appSettingsStore.settings.time_format;
        
        const isTimeIncluded = dayjs(date).hour() || dayjs(date).minute() || dayjs(date).second();
        const formatString = isTimeIncluded ? `${dateFormat} ${timeFormat}` : dateFormat;
  
        return dayjs(date).format(formatString);
    },

    formatCurrency(amount) {
        const appSettingsStore = useAppSettingsStore();
        const code = appSettingsStore.settings.currency ? appSettingsStore.settings.currency.code : 'USD';
        const locale = appSettingsStore.settings.locale || 'en-US';
        return new Intl.NumberFormat(locale, {
            style: 'currency',
            currency: code,
            minimumFractionDigits: 0
        }).format(amount ?? 0);
    },
  }
};
