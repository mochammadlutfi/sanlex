import { Node } from '@tiptap/core'

export default class Title extends Node {

  get name() {
    return 'title'
  }

  get schema() {
    return {
      content: 'inline*',
      parseDOM: [{
        tag: 'h1',
      }],
      toDOM: () => ['h1', {class: "title"}, 0],
    }
  }

}