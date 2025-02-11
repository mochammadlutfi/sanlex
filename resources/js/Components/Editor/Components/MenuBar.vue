<template>
    <div class="block-control">
        <component
        v-for="(spec, i) in generateCommandButtonComponentSpecs()"
        :key="'command-button' + i"
        :is="spec.component"
        :enable-tooltip="enableTooltip"
        v-bind="spec.componentProps"
        :readonly="isCodeViewMode"
        v-on="spec.componentEvents || {}"
        />
        <!-- <heading-dropdown :editor="editor" />
        
        <el-tooltip class="box-item" effect="dark" content="Bold (CTRL + B)" placement="bottom">
            <el-button size="small" @click="editor.chain().focus().toggleBold().run()" :class="{ 'is-active': editor.isActive('bold') }">
                <i class="fa fa-bold"></i>
            </el-button>
        </el-tooltip>
        <el-tooltip class="box-item" effect="dark" content="Italic (CTRL + I)" placement="bottom">
            <el-button size="small" @click="editor.chain().focus().toggleItalic().run()" :class="{ 'is-active': editor.isActive('italic') }">
                <i class="fa fa-italic"></i>
            </el-button>
        </el-tooltip>
        <el-tooltip class="box-item" effect="dark" content="Underline (CTRL + U)" placement="bottom">
            <el-button size="small" @click="editor.chain().focus().toggleUnderline().run()" :class="{ 'is-active': editor.isActive('underline') }">
                <i class="fa fa-underline"></i>
            </el-button>
        </el-tooltip>
        <el-tooltip class="box-item" effect="dark" content="Unordered List (CTRL + U)" placement="bottom">
            <el-button size="small" @click="editor.chain().focus().toggleBulletList().run()" :class="{ 'is-active': editor.isActive('bulletList') }">
                <i class="fa fa-list-ul"></i>
            </el-button>
        </el-tooltip>
        <el-tooltip class="box-item" effect="dark" content="Strike (CTRL + U)" placement="bottom">
            <el-button size="small" @click="editor.chain().focus().toggleStrike().run()" :class="{ 'is-active': editor.isActive('strike') }">
                <i class="fa fa-strikethrough"></i>
            </el-button>
        </el-tooltip>
        <el-tooltip class="box-item" effect="dark" content="Ordered List" placement="bottom">
            <el-button size="small" @click="editor.chain().focus().toggleOrderedList().run()" :class="{ 'is-active': editor.isActive('orderedList') }">
                <i class="fa fa-list-ol"></i>
            </el-button>
        </el-tooltip>
        <el-tooltip class="box-item" effect="dark" content="Ordered List" placement="bottom">
            <el-button size="small" @click="editor.chain().focus().toggleBlockquote().run()" :class="{ 'is-active': editor.isActive('blockquote') }">
                <i class="fa fa-quote-right"></i>
            </el-button>
        </el-tooltip>

        <el-tooltip class="box-item" effect="dark" content="Rata Kiri" placement="bottom">
            <el-button size="small" @click="editor.chain().focus().setTextAlign('left').run()" :class="{ 'is-active': editor.isActive({ textAlign: 'left' }) }">
                <i class="fa fa-align-left"></i>
            </el-button>
        </el-tooltip>
        <el-tooltip class="box-item" effect="dark" content="Rata Tengah" placement="bottom">
            <el-button size="small" @click="editor.chain().focus().setTextAlign('center').run()" :class="{ 'is-active': editor.isActive({ textAlign: 'center' }) }">
                <i class="fa fa-align-center"></i>
            </el-button>
        </el-tooltip>
        <el-tooltip class="box-item" effect="dark" content="Rata Kanan" placement="bottom">
            <el-button size="small" @click="editor.chain().focus().setTextAlign('right').run()" :class="{ 'is-active': editor.isActive({ textAlign: 'right' }) }">
                <i class="fa fa-align-right"></i>
            </el-button>
        </el-tooltip>
        <add-link-button :editor="editor"/>
        <add-image-button :editor="editor"/> -->
    </div>
</template>
<script>
import { defineComponent, inject } from 'vue';
import { Editor } from '@tiptap/core';

export default defineComponent({
  name: 'Menubar',
  components: {},
  props: {
    editor: {
      type: Editor,
      required: true,
    },
  },

  setup() {
    const t = inject('t');
    const enableTooltip = inject('enableTooltip', true);
    const isCodeViewMode = inject('isCodeViewMode', false);

    return { t, enableTooltip, isCodeViewMode };
  },

  methods: {
    generateCommandButtonComponentSpecs() {
      const extensionManager = this.editor.extensionManager;
      return extensionManager.extensions.reduce((acc, extension) => {
        const { button } = extension.options;
        if (!button || typeof button !== 'function') return acc;

        const menuBtnComponentSpec = button({
          editor: this.editor,
          t: this.t,
          extension,
        });

        if (Array.isArray(menuBtnComponentSpec)) {
          return [...acc, ...menuBtnComponentSpec];
        }

        return [...acc, menuBtnComponentSpec];
      }, []);
    },
  },
});
</script>