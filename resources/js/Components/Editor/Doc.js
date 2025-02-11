
import Document from '@tiptap/extension-document'

export default class CustomDoc extends Document {
  get schema() {
    return {
      content: 'title block+',
    }
  }

}