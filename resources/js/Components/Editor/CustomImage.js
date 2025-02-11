import Image from '@tiptap/extension-image'
import { mergeAttributes } from '@tiptap/core'

export default Image.extend({
    name: 'custom-image',

    addAttributes() {
        return {
            ...Image.config.addAttributes(),
            size: {
                default: 'small',
                rendered: true
            },
            float: {
                default: 'none',
                rendered: true
            },
            source :{
                default : 'up',
                rendered : true,
            }
        }
    },

    addCommands() {
        return {
            setImage: (options) => ({ tr, commands }) => {
                if(tr.selection?.node?.type?.name == 'custom-image') {
                    return commands.updateAttributes('custom-image', options)
                }
                else {
                    return commands.insertContent({
                        type: this.name,
                        attrs: options
                    })
                }
            },
        }
    },

    renderHTML({ node, HTMLAttributes }) {

        HTMLAttributes.class = 'custom-image custom-image-' + node.attrs.size
        HTMLAttributes.class += ' custom-image-float-' + node.attrs.float
        HTMLAttributes.class += ' custom-image-' + node.attrs.source

        return [
            'img',
            mergeAttributes(this.options.HTMLAttributes, HTMLAttributes)
        ]
    }
})