<template>
  <v-layout>
    <v-flex sm12>
      <div class="mt-2">
        <v-data-table
          :mobile-breakpoint="0"
          :headers="headers"
          :items="allData"
          :items-per-page="20"
          :options.sync="options"
          :server-items-length="totalData"
          :loading="loading"
          class="elevation-1"
        >
          <template #body="{ items }">
            <tbody>
              <tr
                v-for="item in items"
                :key="item.id"
                class="test-tr"
                :style="{ backgroundColor: item.Color }"
              >
                <td class="text-xs-center">{{ item.step }}</td>
                <td class="d-flex">
                  <v-btn color="error" fab small dark>
                    <v-icon v-if="item.status === 'Y'">mdi-check-circle</v-icon>
                    <v-icon v-else>mdi-close-circle</v-icon>
                  </v-btn>
                </td>
                <td class="d-flex">
                  <v-tooltip top style="margin-top: 4px">
                    <template #activator="{ on, attrs }">
                      <v-btn icon v-bind="attrs" v-on="on">
                        <v-icon small @click="openAction('edit', item)"
                          >mdi-pencil</v-icon
                        >
                      </v-btn>
                    </template>
                    <span>Edit</span>
                  </v-tooltip>
                </td>
              </tr>
            </tbody>
          </template>
          <template #footer> </template>
        </v-data-table>
      </div>
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: 'ListDocument',
  data: () => ({
    headers: [
      { text: 'Tahap Pengidisian', value: 'step_desc' },
      { text: 'Status', value: 'status' },
      { text: 'Aksi', value: 'actions' },
    ],
    allData: [],
    options: {},
    totalData: 0,
    loading: false,
  }),
  methods: {
    openAction(type, item) {},
  },
}
</script>

<style scoped></style>
