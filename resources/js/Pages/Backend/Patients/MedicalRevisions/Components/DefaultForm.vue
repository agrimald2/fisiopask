<template>
  <JetFormSection @submitted="onSubmitted">
    <template #title>
      Crear una Revisión Médica
    </template>

    <template #form>
      <template v-for="attribute in attributes" :key="attribute.id">
        <div class="col-span-6 sm:col-span-4">
          <jet-label
            :for="attribute.input_name"
            :value="attribute.input_name"
          />
          <div v-if="attribute.input_type == 0">
            <jet-input
              :id="attribute.input_name"
              type="text"
              class="mt-1 block w-full"
              v-model="form.attributes[attribute.id]"
              @input="updateValue($event, attribute.id, $event.target.value)"
            />
          </div>
          <div v-else-if="attribute.input_type == 1">
            <jet-input
              :id="attribute.input_name"
              type="numeric"
              class="mt-1 block w-full"
              v-model="form.attributes[attribute.id]"
              @input="updateValue($event, attribute.id, $event.target.value)"
            />
          </div>
          <div v-else-if="attribute.input_type == 2">
            <input-select
              :id="attribute.input_name"
              type="select"
              :options="options(attribute.related_model)"
              class="mt-1 block w-full"
              v-model="form.attributes[attribute.id]"
              @input="updateValue($event, attribute.id, $event.target.value)"
            />
          </div>
          <div v-else-if="attribute.input_type == 3">
            <Multiselect
              mode="tags"
              :close-on-select="false"
              :searchable="true"
              :create-option="false"

              :id="attribute.input_name"
              :options="options(attribute.related_model)"


              @input="updateMulti($event, attribute.id)"
            />
          </div>
        </div>
        <!--div v-else-if="attribute.input_type == 4" class="col-span-6 sm:col-span-4">
          <input
            :id="attribute.input_name"
            type="checkbox"
            class="mt-1 block w-full"
            v-model="form.attributes[attribute.input_name]"
            @input="updateValue($event, attribute.input_name, $event.target.value)"
          />
        </div-->
      </template>
    </template>

    <template #actions>
      <JetActionMessage
        :on="form.recentlySuccessful"
        class="mr-3"
      >
        Guardado.
      </JetActionMessage>

      <JetButton
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
      >
        Guardar
      </JetButton>
    </template>

    
  </JetFormSection>
</template>

<script>
import Multiselect from '@vueform/multiselect'
import JetLabel from "@/Jetstream/Label";

import JetInput from "@/Jetstream/Input";
import JetButton from "@/Jetstream/Button.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";

import FormInput from "@/Shared/Backend/Form/Input";
import InputSelect from "@/Shared/Backend/Form/Components/SelectElement.vue";

export default {
  props: ["history_group", "diagnostics", "treatments", "analysis", "affected_areas", "checkedNames", "attributes"],

  components: {
    InputSelect,
    JetLabel,
    JetInput,
    JetActionMessage,
    JetButton,
    JetFormSection,
    JetSecondaryButton,

    FormInput,
    Multiselect,
  },

  data() {
    return {
      form: this.$inertia.form({
        _method: "POST",

        patient_id: null,
        doctor_id: null,
        history_group_id: null,

        attributes: {},
      }),
    };
  },

  methods: {
    onSubmitted() {
      this.form.patient_id = this.history_group.patient_id;
      this.form.doctor_id = this.history_group.doctor_id;
      this.form.history_group_id = this.history_group.id;

      const url = route("medicalrevision.store");

      this.form._method = "POST";

      this.form.post(url, {
        // errorBag: "",
        preserveScroll: true,
        onSuccess: this.onSuccess,
      });
    },
    updateValue(e, i, v) {
      this.form.attributes[i] = v;
    },
    updateMulti(e, i) {
      this.form.attributes[i] = e;
    },
    options(id) {
      if(id == 1) return this.affected_areas;
      else if(id == 2) return this.diagnostics;
      else if(id == 3) return this.treatments;
      else if (id == 10) 
      {
        let arr = ["Eutímico", "Distímico"];
        return arr;
      }
      else
      {
        let arr = ["Aumentado", "Conservado", "Disminuido"];
        return arr;
      }
      
      return null;
    },
  },
};
</script>
<style src="@vueform/multiselect/themes/default.css"></style>
