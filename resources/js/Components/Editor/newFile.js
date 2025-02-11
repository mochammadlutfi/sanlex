import CustomImage from './CustomImage';
import { Editor } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Placeholder from '@tiptap/extension-placeholder';
import { Underline } from '@tiptap/extension-underline';
import Link from '@tiptap/extension-link';

export default (await import('vue')).defineComponent({
name: 'block-editor',
components: {
Editor,
EditorContent,
BubbleMenu,
FloatingMenu,
EditorMenuButton,
AddLinkButton,
MenuBubble
},
props: {
modelValue: {
type: String,
default: "<p></p>"
},
placeholder: {
type: String,
default: "Masukan Konten",
},
controls: {
type: Object,
default: {
textBold: true,
textItalic: true,
textUnderline: true,
unorderedList: true,
orderedList: true,
blockquote: true,
link: true,
}
},
height: {
type: String,
}
},
watch() {
modelValue(value); {
this.editor.setContent(value);
}
},
computed: {
optionControl() {


return list;
}
},
data() {
return {
editor: null,
json: null,
uploadedImages: [],
};
},
mounted() {
this.editor = new Editor({
content: (this.value) ? this.value : '<p></p>',
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
levels: [2, 3],
},
blockquote: {
HTMLAttributes: {
class: 'blockquote',
},
},
bulletList: {
HTMLAttributes: {
class: 'my-custom-class',
},
},
orderedList: {
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
return this.placeholder;
},
}),
],
onUpdate: () => {
let html = this.editor.getHTML();
this.$emit('update:modelValue', html);
},
});
},
methods: {
// setLink(url) {
//     this.editor
//         .chain()
//         .focus()
//         .extendMarkRange('link')
//         .setLink({ href: url })
//         .run()
// },
openLinkModal(command) {
if (!this.editor.state.selection.empty) {
this.$refs.linkModal.showModal(command);
} else {
this.editor
.chain()
.focus()
.extendMarkRange('link')
.unsetLink()
.run();
return;
}
},
// openImageModal(command) {
//     this.$refs.imageModal.showModal(command);
// },
// setImage(url, type){
//     if(type == 'upload'){
//         this.uploadedImages.push(url);
//     }
//     this.editor.chain().focus().setImage({ src: url }).run()
// }
},

beforeDestroy() {
this.editor.destroy();
},
});
