<template>
  <div class="col-span-6 sm:col-span-4">
    <jet-label
      :for="name"
      :value="label"
    />
    <div v-if="type === 'select'">
      <input-select
        :id="name"
        :list="name"
        :options="options"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        class="mt-1 block w-full"
    />
    </div>   
    <div v-else-if="type === 'checkbox'">
      <Multiselect
          mode="tags"
          :close-on-select="false"
          :searchable="true"
          :create-option="false"

          :id="name"
          :options="options"

          :value="modelValue"
          @input="$emit('update:modelValue', $event.target.value)"
      />
    </div>  
    <div v-else>
      <jet-input
        :id="name"
        :type="type"
        :min ="min"
        :max ="max"
        :step ="step"
        :onkeyup ="onkeyup"
        :onkeypress ="onkeypress"
        class="mt-1 block w-full"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        autocomplete="${{name}}"
      />
    </div>
    <jet-input-error
      :message="form.errors[name]"
      class="mt-2"
    />
  </div>
</template>


<script>
import InputSelect from "./Components/SelectElement";
import Multiselect from '@vueform/multiselect'


import JetInput from "@/Jetstream/Input";
import JetLabel from "@/Jetstream/Label";
import JetInputError from "@/Jetstream/InputError";

export default {
  props: {
    modelValue: null,
    form: null,
    name: String,
    label: String,
    type: {
      default: "text",
    },
    options: null,
    min : null,
    max : null,
    step : null,
    onkeypress: String,
    onkeyup: String,
    
  },

  components: {
    JetInput,
    JetInputError,
    JetLabel,
    InputSelect,
    Multiselect
  },
};
</script>
<style src="@vueform/multiselect/themes/default.css"></style>
