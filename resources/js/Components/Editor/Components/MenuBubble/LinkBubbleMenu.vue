<template>
    <div class="link-bubble-menu">
      <slot name="prepend" />
    </div>
  </template>
  
  <script lang="ts">
  import { Editor } from '@tiptap/vue-3';
  import { defineComponent } from 'vue';
  
  export default defineComponent({
    name: 'LinkBubbleMenu',
  
    components: {

    },
  
    props: {
      editor: {
        type: Editor,
        required: true,
      },
    },
  
    computed: {
      linkAttrs() {
        return this.editor.getAttributes('link');
      },
    },
  });
  </script>
  
  <style lang="scss">
  .link-bubble-menu {
    display: flex;
  }
  </style>