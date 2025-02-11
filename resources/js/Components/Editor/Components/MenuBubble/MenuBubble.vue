<template>
    <bubble-menu :editor="editor" :tippy-options="{ duration: 100 }" v-if="editor" 
    class="menu-bubble"
        :class="{
            'menu-bubble__active': bubbleMenuEnable,
        }">
        <template v-if="activeMenu === 'default'">
            <el-button size="small" @click="editor.chain().focus().toggleBold().run()" :class="{ 'is-active': editor.isActive('bold') }">
                <i class="fa fa-bold"></i>
            </el-button>
        </template>
        <template v-else-if="activeMenu === 'link'">
            <el-button size="small" @click="editor.chain().focus().toggleBold().run()" :class="{ 'is-active': editor.isActive('bold') }">
                <i class="fa fa-bold"></i>
            </el-button>
            <el-button size="small" @click="editor.chain().focus().toggleBold().run()" :class="{ 'is-active': editor.isActive('bold') }">
                <i class="fa fa-bold"></i>
            </el-button>
            <el-button size="small" @click="editor.chain().focus().toggleBold().run()" :class="{ 'is-active': editor.isActive('bold') }">
                <i class="fa fa-bold"></i>
            </el-button>
        </template>
        <template v-if="editor.isActive('custom-image')">
        <el-popover
        placement="top"
        trigger="click"
        popper-class="el-tiptap-popper"
        ref="popoverRef"
        >
        <div class="el-tiptap-popper__menu">
            <div class="el-tiptap-popper__menu__item">
                <el-button size="small" @click="editor.chain().focus().setImage({ size: 'small' }).run()" :class="{ 'is-active': editor.isActive('custom-image', { size: 'small' }) }">
                    Small
                </el-button>
                <el-button size="small" @click="editor.chain().focus().setImage({ size: 'medium' }).run()" :class="{ 'is-active': editor.isActive('custom-image', { size: 'medium' }) }">
                    Medium
                </el-button>
                <el-button size="small" @click="editor.chain().focus().setImage({ size: 'large' }).run()" :class="{ 'is-active': editor.isActive('custom-image', { size: 'large' }) }">
                    Large
                </el-button>
            </div>
        </div>
        <template #reference>
            <el-button size="small">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="m10.59 12l4-4H11V6h7v7h-2V9.41l-4 4V16h8V4H8v8zM22 2v16H12v4H2V12h4V2zM10 14H4v6h6z"/></svg>            </el-button>
        </template>
        </el-popover>
            <el-button size="small" @click="editor.chain().focus().setImage({ float: 'left' }).run()" :class="{ 'is-active': editor.isActive('custom-image', { float: 'left' }) }">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1zm10 1h6m-6 4h6M4 15h16M4 19h16"/></svg>
            </el-button>
            <el-button size="small" @click="editor.chain().focus().setImage({ float: 'center' }).run()" :class="{ 'is-active': editor.isActive('custom-image', { float: 'center' }) }">
                Center
            </el-button>
            <el-button size="small" @click="editor.chain().focus().setImage({ float: 'right' }).run()" :class="{ 'is-active': editor.isActive('custom-image', { float: 'right' }) }">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 6a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1zM4 7h6m-6 4h6m-6 4h16M4 19h16"/></svg>
            </el-button>
        </template>
    </bubble-menu>
</template>
<script>
    import {Editor, BubbleMenu} from '@tiptap/vue-3';
    import { getMarkRange } from '@tiptap/core';
    import { TextSelection, AllSelection, Selection } from '@tiptap/pm/state';
    
    export default {
        name: 'MenuBubble',
        setup() {},
        components: {
            BubbleMenu
        },
        props: {
            editor: {
                type: Editor,
                required: true
            },

            menuBubbleOptions: {
                type: Object,
                default: () => ({})
            }
        },
        
        watch: {
            'editor.state.selection': function (selection) {
                if (this.$_isLinkSelection(selection)) {
                    if (!this.isLinkBack) {
                        this.setMenuType('link');
                    }
                } else {
                    this.activeMenu = this.$_getCurrentMenuType();
                    this.isLinkBack = false;
                }
                console.log(this.activeMenu);
            },
        },
        data() {
            return {
                activeMenu: 'default', 
                isLinkBack: false,
            };
        },
        computed: {
            bubbleMenuEnable(){
                return this.linkMenuEnable || this.textMenuEnable;
            },
            linkMenuEnable(){
                const { schema } = this.editor;
                return !!schema.marks.link;
            },
            textMenuEnable(){
                const extensionManager = this.editor.extensionManager;
                return extensionManager.extensions.some((extension) => {
                    return extension.options.bubble;
                });
            },

            isLinkSelection(){
                const { state } = this.editor;
                const { tr } = state;
                const { selection } = tr;

                return this.$_isLinkSelection(selection);
            },
        },
        methods : {
            linkBack() {
                this.setMenuType('default');
                this.isLinkBack = true;
            },
            setMenuType(type) {
                this.activeMenu = type;
            },
            $_isLinkSelection(selection) {
                const { schema } = this.editor;
                const linkType = schema.marks.link;
                if (!linkType) return false;
                if (!selection) return false;

                const { $from, $to } = selection;
                const range = getMarkRange($from, linkType);
                if (!range) return false;

                return range.to === $to.pos;
            },
            $_getCurrentMenuType(){
                if (this.isLinkSelection) return 'link';
                if (
                    this.editor.state.selection instanceof TextSelection ||
                    this.editor.state.selection instanceof AllSelection
                ) {
                    return 'default';
                }
                return 'none';
            },
        }
        
    }
</script>