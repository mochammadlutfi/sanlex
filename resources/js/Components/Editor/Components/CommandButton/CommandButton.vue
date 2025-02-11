<template>
    <el-tooltip
      :content="tooltip"
      :show-after="350"
      :disabled="!enableTooltip || readonly"
      effect="dark"
      placement="top">
        <!-- <v-icon :name="icon" /> -->
        <el-button size="small" @click="onClick" :class="commandButtonClass" @mousedown.prevent>
            <i class="fa fa-align-right"></i>
        </el-button>
    </el-tooltip>
  </template>
  
  <script lang="ts">
  import { defineComponent } from 'vue';
  import { ElTooltip } from 'element-plus';
  
  export default defineComponent({
    components: {
      ElTooltip
    },
    props: {
      icon: {
        type: String,
        required: true,
      },
  
      isActive: {
        type: Boolean,
        default: false,
      },
  
      tooltip: {
        type: String,
        required: true,
      },
  
      enableTooltip: {
        type: Boolean,
        required: true,
      },
  
      command: {
        type: Function,
        default: {},
      },
  
      readonly: {
        type: Boolean,
        default: false,
      },
    },
  
    computed: {
      commandButtonClass(): object {
        return {
          'el-tiptap-editor__command-button': true,
          'is-active': this.isActive,
          'el-tiptap-editor__command-button--readonly': this.readonly,
        };
      },
    },
  
    methods: {
      onClick() {
        if (!this.readonly) this.command();
      },
    },
  });
  </script>