<template>
    <div class="block block-bordered block-rounded block-shadow nice-copy">
        <div class="block-content">
            <div v-if="editor" class="controls">
                <button 
                    @click="editor.chain().focus().toggleBold().run()" class="btn btn-editor"
                    v-b-popover.hover.bottom="'Bold (CTRL + B)'"
                    :class="{ 'is-active': editor.isActive('bold') }" >
                    <i class="fi fi-rs-bold"></i>
                </button>
                <button
                    @click="editor.chain().focus().toggleItalic().run()" class="btn btn-editor"
                    v-b-popover.hover.bottom="'Italic (CTRL + I)'"
                    :class="{ 'is-active': editor.isActive('italic') }"
                >
                    <i class="fi fi-rs-italic"></i>
                </button>
                <button
                    @click="editor.chain().focus().toggleUnderline().run()" class="btn btn-editor"
                    v-b-popover.hover.bottom="'Underline (CTRL + U)'"
                    :class="{ 'is-active': editor.isActive('underline') }">
                    <i class="fi fi-rs-underline"></i>
                </button>


                <button 
                    @click="editor.chain().focus().toggleBulletList().run()" class="btn btn-editor"
                     v-b-popover.hover.bottom="'Unordered List (CTRL + U)'"
                    :class="{ 'is-active': editor.isActive('bulletList') }">
                    <i class="fa fa-list-ul"></i>
                </button>


                <button 
                    @click="editor.chain().focus().toggleOrderedList().run()" class="btn btn-editor" 
                     v-b-popover.hover.bottom="'Ordered List'"
                    :class="{ 'is-active': editor.isActive('orderedList') }">
                    <i class="fa fa-list-ol"></i>
                </button>

                <button 
                    @click="editor.chain().focus().toggleBlockquote().run()" class="btn btn-editor" 
                     v-b-popover.hover.bottom="'Blockquote (CTRL + U)'"
                    :class="{ 'is-active': editor.isActive('blockquote') }">
                    <i class="fa fa fa-quote-right"></i>
                </button>

                
                <button 
                    @click="openLinkModal" class="btn btn-editor" 
                     v-b-popover.hover.bottom="'Tambah Link (CTRL + K)'"
                    :class="{ 'is-active': editor.isActive('link') }">
                    <i class="fi fi-rs-link"></i>
                </button>


                <button 
                    @click="openImageModal" class="btn btn-editor" 
                     v-b-popover.hover.bottom="'Tambah Gambar (CTRL + K)'"
                    :class="{ 'is-active': editor.isActive('link') }">
                    <i class="fi fi-rs-picture"></i>
                </button>

            </div>
        </div>
        <bubble-menu class="bubble-menu" :tippy-options="{ animation: false }" :editor="editor" v-if="editor" v-show="editor.isActive('custom-image')">
            <button @click=" editor.chain().focus().setImage({ size: 'small' }).run()"
                :class="{
                    'is-active': editor.isActive('custom-image', {
                        size: 'small'
                    })
                }">
                Small
            </button>
            <button
                @click="editor.chain().focus().setImage({ size: 'medium' }).run()"
                :class="{
                    'is-active': editor.isActive('custom-image', {
                        size: 'medium'
                    })
                }">
                Medium
            </button>
            <button
                @click="editor.chain().focus().setImage({ size: 'large' }).run()"
                :class="{
                    'is-active': editor.isActive('custom-image', {
                        size: 'large'
                    })
                }">
                Large
            </button>
            <span style="color: #aaa">|</span>
            <button
                @click="editor.chain().focus().setImage({ float: 'left' }).run()"
                :class="{
                    'is-active': editor.isActive('custom-image', {
                        float: 'left'
                    })
                }">
                Left
            </button>
            <button
                @click="editor.chain().focus().setImage({ float: 'none' }).run()"
                :class="{
                    'is-active': editor.isActive('custom-image', {
                        float: 'none'
                    })
                }">
                No float
            </button>
            <button
                @click="editor.chain().focus().setImage({ float: 'right' }).run()"
                :class="{
                    'is-active': editor.isActive('custom-image', {
                        float: 'right'
                    })
                }">
                Right
            </button>
            <span style="color: #aaa">|</span>
            <!-- <button @click="addImage">Change</button> -->
        </bubble-menu>
        <div class="block-content block-content-full">
            <editor-content :editor="editor" class="editor" />
        </div>
        <LinkModal ref="linkModal" @onConfirm="setLink($event)"/>

        
        <ImageModal ref="imageModal" @onConfirm="setImage($event)"/>


        <!-- <b-modal id="linkModal" size="md" rounded body-class="p-0" centered hide-footer hide-header>
            <div class="block block-rounded  block-transparent mb-0">
                <form @submit.prevent="submit">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">{{ title }}</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" @click="reset">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content font-size-sm">

                    </div>
                    <div class="block-content block-content-full text-right border-top">
                        <b-button variant="alt-danger" class="mr-1" @click="reset">cancel</b-button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </b-modal> -->
    </div>
    
</template>

<script>

import LinkModal from './LinkModal.vue';
import ImageModal from './ImageModal.vue';
import CustomImage from './CustomImage';




import { Editor, EditorContent, FloatingMenu, BubbleMenu, generateHTML} from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit';
import Document from '@tiptap/extension-document';
import Placeholder from '@tiptap/extension-placeholder';
import EditorMenuButton from './EditorMenuButton.vue';
import { Underline } from '@tiptap/extension-underline';
import Link from '@tiptap/extension-link';
import Image from '@tiptap/extension-image';




const CustomDocument = Document.extend({
  content: 'heading block+',
})

export default {
    components: {
        Editor,
        EditorContent,
        BubbleMenu,
        FloatingMenu,
        EditorMenuButton,
        LinkModal,
        ImageModal
    },
    props: {
        value: {
            type: String,
            default: '',
        },
    },
    data() {
        return {
            editor: null,
            json : null,
            uploadedImages : [],
        }
    },
    watch: {
        value: {
            handler(value) {
                // this.editor.setContent(value);
            }
        }
    },
    mounted() {
        this.editor = new Editor({
            content : '<p></p>',
            extensions: [
                CustomDocument,
                Underline,
                Link.configure({
                    openOnClick: false,
                    HTMLAttributes: {
                        class: 'link-effect',
                    },
                }),
                StarterKit.configure({
                    document: false,
                    heading: {
                        levels: [1, 2],
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
                    showOnlyCurrent: false,
                    showOnlyWhenEditable: false,
                    includeChildren: false,
                    placeholder: ({ node }) => {
                        if (node.type.name === 'heading') {
                            return 'Tulis Judul Disini'
                        }

                        return 'Mulai menulis cerita'
                    },
                }),
            ],
            onUpdate: () => {
                let json = this.editor.getJSON();
                let title, description;
                console.log(json);
                // console.log(this.editor.getHTML());
                if(this.editor.getJSON().content.length){
                    var head = json.content.shift();
                    title = head.content ? head.content[0].text : '';

                    description = generateHTML(json, [
                        Underline,
                        Link,
                        CustomImage,
                        StarterKit,
                    ]);

                    this.$emit('input', {
                        title : title,
                        description : description
                    });
                }
            },
        })
    },
    methods :{
        setLink(url) {
            this.editor
                .chain()
                .focus()
                .extendMarkRange('link')
                .setLink({ href: url })
                .run()
        },
        
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

        openImageModal(command) {
            this.$refs.imageModal.showModal(command);
        },
        setImage(url, type){
            if(type == 'upload'){
                this.uploadedImages.push(url);
            }

            this.editor.chain().focus().setImage({ src: url }).run()
        }
    },

    beforeDestroy() {
        this.editor.destroy()
    },
};
</script>

<style lang="scss">
    .ProseMirror {
      text-align: initial;

        &:focus {
            outline: none;
        }
    }

// .ProseMirror *.is-empty:nth-child(1)::before,
// .ProseMirror *.is-empty:nth-child(2)::before {
//   content: attr(data-empty-text);
//   float: left;
//   color: #aaa;
//   pointer-events: none;
//   height: 0;
//   font-style: italic;
// }

    /* Placeholder (on every new line) */
    .ProseMirror .is-empty::before {
        content: attr(data-placeholder);
        float: left;
        color: #adb5bd;
        pointer-events: none;
        height: 0;
    }

  .editor {
      position: relative;

      &__floating-menu {
          position: absolute;
          z-index: 1;
          margin-top: -0.75rem;
          margin-left: 1rem;
          visibility: hidden;
          opacity: 0;
          transition: opacity 0.2s, visibility 0.2s;

          &.is-active {
              opacity: 1;
              visibility: visible;
          }
      }

      .menububble {
          position: absolute;
          display: flex;
          z-index: 20;
          margin-bottom: 0.5rem;
          transform: translateX(-50%);
          visibility: hidden;
          opacity: 0;
          transition: opacity 0.2s, visibility 0.2s;

          &.is-active {
              opacity: 1;
              visibility: visible;
          }

          &__form {
              display: flex;
              align-items: center;
          }

          &__input {
              font: inherit;
              border: none;
              background: transparent;
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
