<template>
  <v-row no-gutters align="center" align-content="center" justify="center">
    <v-col cols="12" sm="6" md="6" lg="6" xl="6" align-self="center">
      <v-card>
        <v-card-title>
          <span v-text="documents.type"></span>
          <v-spacer></v-spacer>
        </v-card-title>
        <v-divider></v-divider>

        <v-simple-table>
          <template v-slot:default>
            <tbody>
            <tr>
              <td>Document No</td>
              <td>{{ documents.document_number }}</td>
            </tr>
            <tr>
              <td>Expired Date</td>
              <td>{{ documents.due_at }}</td>
            </tr>
            <tr>
              <td>Total</td>
              <td>{{ $formatter.formatPrice(documents.sub_total) }}</td>
            </tr>
            <tr>
              <td><b>Balance Due</b></td>
              <td><b>{{ $formatter.formatPrice(documents.amount) }}</b></td>
            </tr>
            </tbody>
          </template>
        </v-simple-table>

        <v-divider></v-divider>
        <v-card-actions>
        </v-card-actions>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: "view",
  layout: "auth",
  middleware: 'verification',

  data() {
    return {
      documents: []
    }
  },

  head() {
    return {
      title: 'Document View',
    }
  },

}
</script>
