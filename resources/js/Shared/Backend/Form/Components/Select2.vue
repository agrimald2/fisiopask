<template>
  <div>
    <multiselect
      v-model="selected"
      :options="options">
    </multiselect>
  </div>
</template>

<script>
  import Multiselect from '@suadelabs/vue3-multiselect'
  export default {
    components: { Multiselect },
    props:{
        users:Array,
    },
    data () {
      return {
        selected: null,
        options: this.users,
      }
    }
  }
</script>

<style src="@suadelabs/vue3-multiselect/dist/vue3-multiselect.css"></style>