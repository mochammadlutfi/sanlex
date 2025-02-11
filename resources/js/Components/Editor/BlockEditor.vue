<template>
    <div class="block block-editor">
        <div class="block-control" v-if="editor">
            <heading-dropdown :editor="editor" :levels="[1,2,3,4,5]"/>
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
            <add-image-button :editor="editor"/>
        </div>

        <div v-if="editor">
            <menu-bubble :editor="editor" />
        </div>
        <el-scrollbar height="400px" class="p-2">
            <editor-content :editor="editor" class="editor" style=""/>
        </el-scrollbar>
    </div>
</template>

<script>
import CustomImage from './CustomImage';
import AddLinkButton from './Components/Link/AddLinkButton.vue';
import AddImageButton from './Components/Image/AddImageButton.vue';
import MenuBubble from './Components/MenuBubble/MenuBubble.vue';

import { Editor, EditorContent, FloatingMenu, BubbleMenu, generateHTML} from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit';
import Placeholder from '@tiptap/extension-placeholder';
import EditorMenuButton from './EditorMenuButton.vue';
import { Underline } from '@tiptap/extension-underline';
import Link from '@tiptap/extension-link';
import HeadingDropdown from './Components/CommandButton/HeadingDropdown.vue';
export default {
    name : 'block-editor',
    components: {
        Editor,
        EditorContent,
        BubbleMenu,
        FloatingMenu,
        EditorMenuButton,
        AddLinkButton,
        AddImageButton,
        MenuBubble,
        HeadingDropdown
    },
    props: {
        content : {
            type : String, 
            default : ""
        },
        placeholder : {
            type : String,
            default : "Masukan Konten",
        },
        controls : {
            type : Object,
            default : {
                textBold : true,
                textItalic : true,
                textUnderline : true,
                unorderedList : true,
                orderedList : true,
                blockquote : true,
                link : true,
            }
        },
        height : {
            type : String
        },
        error: {
            type : String
        }
    },
    watch : {
        content(v){
            // console.log('Ini Di watch');
            // console.log(v);
        }
    },
    // created(){
    //     console.log('Ini Di mounted');
    //     console.log(this.content);
    // },
    setup(props, { emit }){
        const onUpdate = ({
            editor
        }) => {
            let output = editor.getHTML();

            emit('update:content', output);
            // emit('onUpdate', output, editor);
        };
        const editor = new Editor({
            content : props.content,
            extensions: [
                Underline,
                Link.configure({
                    openOnClick: false,
                    HTMLAttributes: {
                        class: 'link-effect',
                    },
                }),
                StarterKit.configure({
                    heading: {
                        levels: [1, 2, 3, 4, 5],
                    },
                    blockquote : {
                        HTMLAttributes: {
                            class: 'blockquote',
                        },
                    },
                    bulletList :{
                        HTMLAttributes: {
                            class: 'my-custom-class',
                        },
                    },
                    orderedList : {
                        HTMLAttributes: {
                            class: 'my-custom-class',
                        },
                    },
                }),
                CustomImage.configure({
                    HTMLAttributes: {
                        class: 'custom-image'
                    }
                }),
                Placeholder.configure({
                    placeholder: ({ node }) => {
                        return props.placeholder
                    },
                }),
            ],
            onCreate: (options) => {
                emit('onCreate', options);
            },
            onTransaction: (options) => {
                emit('onTransaction', options);
            },
            onFocus: (options) => {
                emit('onFocus', options);
            },
            onBlur: (options) => {
                emit('onBlur', options);
            },
            onDestroy: (options) => {
                emit('onDestroy', options);
            },
            onUpdate
        });

        return {
            editor
        }
    },
    data() {
        return {
            json : null,
            uploadedImages : [],
        }
    },
    // mounted() {
        // console.log(JSON.stringify(this.modelValue));
        // this.editor = new Editor({
        //     content : this.modelValue,
        //     extensions: [
        //         Underline,
        //         Link.configure({
        //             openOnClick: false,
        //             HTMLAttributes: {
        //                 class: 'link-effect',
        //             },
        //         }),
        //         StarterKit.configure({
        //             heading: {
        //                 levels: [2, 3],
        //             },
        //             blockquote : {
        //                 HTMLAttributes: {
        //                     class: 'blockquote',
        //                 },
        //             },
        //             bulletList :{
        //                 HTMLAttributes: {
        //                     class: 'my-custom-class',
        //                 },
        //             },
        //             orderedList : {
        //                 HTMLAttributes: {
        //                     class: 'my-custom-class',
        //                 },
        //             },
                    
        //         }),
        //         CustomImage.configure({
        //             HTMLAttributes: {
        //                 class: 'custom-image'
        //             }
        //         }),
        //         Placeholder.configure({
        //             placeholder: ({ node }) => {
        //                 return this.placeholder
        //             },
        //         }),
        //     ],
        //     onUpdate: () => {
        //         let html = this.editor.getHTML();
        //         this.$emit('update:modelValue', html);
        //     },
        // });
    // },
    methods :{
        openLinkModal(command) {
            if(!this.editor.state.selection.empty){
                this.$refs.linkModal.showModal(command);
            }else{
                this.editor
                .chain()
                .focus()
                .extendMarkRange('link')
                .unsetLink()
                .run()
                return
            }
        },
    },

    beforeDestroy() {
        this.editor.destroy()
    },
};
</script>

<style lang="scss">
.el-form-item.is-error .block-editor {
    border : 1px solid var(--el-color-danger);
}
.block-editor {
    width : 100%;
    border : 1px solid #dcdfe6;
    border-radius : 4px;
    margin-bottom : 0px !important;

    .block-control {
        padding : .5rem;

        button:not(:first-child) {
            margin-left : 5px;
        }
    }

    .ProseMirror {
        text-align: initial;
        min-height : 300px;

        p {
            margin-bottom: .9rem;
        }

        &:focus {
            outline: none;
        }
    }
}
.btn-editor.is-active {
    background:#eeeeee !important;
}

.btn-editor {
    height: fit-content;
    background: none;
    padding: 8px;
    border-radius: 3px;

    &:hover {
        background:#eeeeee;
    }

    

}


</style>
