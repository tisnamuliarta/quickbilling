<template>
  <div>
    <client-only>
      <!-- Use the component in the right place of the template -->
      <tiptap-vuetify
        v-model="content"
        :extensions="extensions"
        @input="handleInput"
      />

      <template #placeholder> Loading... </template>
    </client-only>
  </div>
</template>

<script>
// import the component and the necessary extensions
import {
  TiptapVuetify,
  Heading,
  Bold,
  Italic,
  Strike,
  Underline,
  Code,
  Paragraph,
  BulletList,
  OrderedList,
  ListItem,
  Link,
  Blockquote,
  HardBreak,
  HorizontalRule,
  History,
  Image,
  Table,
  TableCell,
  TableHeader,
  TableRow,
} from 'tiptap-vuetify'

export default {
  name: 'EditorText',
  // specify TiptapVuetify component in "components"
  components: { TiptapVuetify },
  data: () => ({
    // declare extensions you want to use
    extensions: [
      History,
      Table,
      TableCell,
      TableHeader,
      TableRow,
      Blockquote,
      Link,
      Underline,
      Strike,
      Italic,
      ListItem,
      BulletList,
      OrderedList,
      Image,
      [
        Heading,
        {
          options: {
            levels: [1, 2, 3],
          },
        },
      ],
      Bold,
      Link,
      Code,
      HorizontalRule,
      Paragraph,
      HardBreak,
    ],
    // starting editor's content
    content: '',
  }),
  methods: {
    handleInput(e) {
      this.$emit('input', this.content)
    },
  },
}
</script>
