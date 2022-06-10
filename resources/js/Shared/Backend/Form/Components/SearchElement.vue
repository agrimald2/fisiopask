<template>
  <multiselect
        :id="name"
        :options="options"
        :value="modelValue"
        placeholder="Seach"
        @search-change="onSearch"
        @input="$emit('update:modelValue', $event.target.value)"
        class="mt-1 block w-full"
      >
      </multiselect>
</template>


<script>
export default {
  props: ["modelValue", "options"],

  emits: ["update:modelValue"],

  methods: {
    focus() {
      this.$refs.input.focus();
    },
  },
  mounted: function() {  
  var vm = this;
  $(this.$el)
    // init select2
    .select2({ data: this.options })
    .val(this.value)
    .trigger("change")
    // emit event on change.
    .on("change", function() {
      vm.$emit("input", this.value);
    });
  },
  watch: {
    value: function(value) {
      // update value
      $(this.$el)
        .val(value)
        .trigger("change");
    },
    options: function(options) {
      // update options
      $(this.$el)
        .empty()
        .select2({ data: options });
    }
  },
  destroyed: function() {
    $(this.$el)
      .off()
      .select2("destroy");
  } 
  };
</script>