<template>
  <JetFormSection @submitted="onSubmitted">
    <template #title>
      {{ model ? 'Editar' : 'Crear' }} un Área Afectada
    </template>

    <template #form>
      <form-input 
        label="Nombre"
        name="name"
        v-model="form.name"
        :form="form"
      />
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
import JetButton from "@/Jetstream/Button.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";

import FormInput from "@/Shared/Backend/Form/Input";

export default {
    props: ["model"],

    components: {
        JetActionMessage,
        JetButton,
        JetFormSection,
        JetSecondaryButton,

        FormInput,
    },

    data() {
        return {
        form: this.$inertia.form({
            _method: "POST",

            name: null,

            ...this.model,
        }),
        };
    },

    methods: {
        onSubmitted() {
            const model = this.model;
            let url = route('hc.store');

            if (model) {
                url = route("books.update", model.id);
                this.form._method = "PUT";
            }

            this.form.post(url, {
                preserveScroll: true,
                onSuccess: this.onSuccess,
            });
        },
    },
};
</script>
