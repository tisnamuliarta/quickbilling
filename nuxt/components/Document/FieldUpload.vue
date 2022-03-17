<template>
  <dropzone
    id='attachment'
    ref='attachment'
    :options='options'
    :destroy-dropzone='true'
    @vdropzone-sending="(file, xhr, formData) => sendingParams(file, xhr, formData)"
    @vdropzone-success="(file, response) => reloadAttachment(file, response)"
    @vdropzone-error="(file, message, xhr) => handleError(file, message, xhr)"
  ></dropzone>
</template>

<script>
import Dropzone from 'nuxt-dropzone'
import 'nuxt-dropzone/dropzone.css'

export default {
  name: "FieldUpload",

  components: {Dropzone},

  props: {
    formType: {
      type: String,
      default: '',
    },
    formData: {
      type: Object,
      default() {
        return {}
      },
    },
  },

  data() {
    return {
      showLoadingAttachment: false,
      form: this.formData,
      options: {
        url: '/api/files',
        timeout: 9000000000,
        addRemoveLinks: true,
        withCredentials: true,
        thumbnailWidth: 50,
        thumbnailHeight: 50,
        acceptedFiles: 'image/*',
        dictDefaultMessage: '<span class=\'mdi mdi-cloud-upload\'></span> UPLOAD HERE',
        headers: {
          'X-XSRF-TOKEN': this.$cookies.get('XSRF-TOKEN')
        }
      },
    }
  },

  methods: {
    sendingParams(file, xhr, formData) {
      const temp_id = (this.form.id) ? this.form.id : this.form.temp_id;
      formData.append('temp_id', temp_id)
      formData.append('type', this.formType)
    },

    handleError(file, message, xhr) {
      this.$swal({
        type: 'error',
        title: 'Error...',
        text: message.message,
      })
    },

    reloadAttachment(file, response) {
      if (response.errors) {
        this.$swal({
          type: 'error',
          title: 'Oops...',
          text: response.message,
        })
      } else {
        setTimeout(() => {
          this.getFiles()
        }, 300)

        this.$swal({
          type: 'success',
          title: 'Success...',
          text: 'Attachment uploaded!',
        })
      }
    },

    getFiles() {
      this.showLoadingAttachment = true
      const vm = this
      const temp_id = (this.form.id) ? this.form.id : this.form.temp_id;

      this.$axios.get(`/api/files`, {
        params: {
          type: this.formType,
          temp_id
        }
      })
        .then(res => {
          this.$emit('eventGetFiles', {
            total: res.data.data.total,
            row: res.data.data.rows
          })
          vm.showLoadingAttachment = false
        })
        .catch(err => {
          this.showLoadingAttachment = false
          this.$swal({
            type: 'error',
            title: 'Error...',
            text: err.response.message,
          })
        })
    },
  }
}
</script>
