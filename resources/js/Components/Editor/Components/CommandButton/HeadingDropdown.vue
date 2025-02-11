<template>
    
    <el-dropdown class="align-middle"
      trigger="click"
      popper-class="el-tiptap-dropdown-popper"
      @command="toggleHeading">
        <el-button size="small" :class="{ 'is-active': editor.isActive('heading') }">
            {{ selected }}
        </el-button>
        <template #dropdown>
            <el-dropdown-menu>
                <el-dropdown-item v-for="level in [0, ...levels]" :key="level" :command="level">
                    <template v-if="level > 0">
                        <component :is="'h' + level" class="mb-0">
                            Heading {{ level }}
                        </component>
                        </template>
                        <span v-else>Paragraph</span>
                </el-dropdown-item>
            </el-dropdown-menu>
        </template>
    </el-dropdown>
</template>
  
<script>
  import {
      defineComponent,
      inject
  } from 'vue';
  import {
      ElDropdown,
      ElDropdownMenu,
      ElDropdownItem
  } from 'element-plus';
  import {
      Editor
  } from '@tiptap/core';
  import CommandButton from './CommandButton.vue';

  export default defineComponent({
      name: 'HeadingDropdown',

      components: {
          ElDropdown,
          ElDropdownMenu,
          ElDropdownItem,
          CommandButton,
      },

      props: {
          editor: {
              type: Editor,
              required: true,
          },

          levels: {
              type: Array,
              required: true,
          },
      },
      computed : {
        selected(){
            if(this.editor.isActive('heading', {level : 1})){
                return 'Heading 1';
            }else if(this.editor.isActive('heading', {level : 2})){
                return 'Heading 2';
            }else if(this.editor.isActive('heading', {level : 3})){
                return 'Heading 3';
            }else if(this.editor.isActive('heading', {level : 4})){
                return 'Heading 4';
            }else if(this.editor.isActive('heading', {level : 5})){
                return 'Heading 5';
            }else if(this.editor.isActive('paragraph')){
                return 'Paragraph';
            }
        }
      },

      methods: {
          toggleHeading(level) {
              if (level > 0) {
                  this.editor.commands.toggleHeading({
                      level
                  });
              } else {
                  this.editor.commands.setParagraph();
              }
          },
      },
  });
  </script>