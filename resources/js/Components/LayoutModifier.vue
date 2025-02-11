<template>
    <component
        :is="tag"
        :type="tag === 'button' ? 'button' : false"
        :href="tag === 'a' ? '#' : false"
        @click.prevent="layout(action)">
        <slot></slot>
    </component>
</template>
  
<script>
import { useAppBaseStore } from '@/Stores/base';

export default {
    name: 'LayoutModifier',
    props: {
        tag: {
            type: String,
            default: 'button',
            description: 'The HTML tag of the component (button, a)'
        },
        size: {
            type: String,
            description: 'Button size (sm, lg)'
        },
        variant: {
            type: String,
            default: 'primary',
            description: 'Button variant (primary, alt-primary, outline-primary, secondary, alt-secondar' +
                    'y, outline-secondary, light, alt-light, outline-light, dark, alt-dark, outline' +
                    '-dark, danger, alt-danger, outline-danger, info, alt-info, outline-info, succe' +
                    'ss, alt-success, outline-success, warning, alt-warning, outline-warning, dual)'
        },
        action: {
            type: String,
            description: 'Specify the layout modifier mode to apply on click'
        }
    },
    data(){
        return {
            appBase : useAppBaseStore(),
        }
    },
    methods: {
        layout(action) {
            // Set up layout API
            let layoutAPI = {
                sidebarOpen: () => this.appBase.sidebar('open'),
                sidebarClose: () => this.appBase.sidebar('close'),
                sidebarToggle: () => this.appBase.sidebar('toggle'),
                sidebarMiniOn: () => this.appBase.sidebarMini('on'),
                sidebarMiniOff: () => this.appBase.sidebarMini('off'),
                sidebarMiniToggle: () => this.appBase.sidebarMini('toggle')
            }
            
            if (layoutAPI[action]) {
                layoutAPI[action]()
            }
        }
    }
}
</script>