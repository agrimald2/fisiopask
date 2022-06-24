<template>
  <app-layout title="Añadir un tipo de Test">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <span
          class="underline cursor-pointer"
          @click="$inertia.visit(route('testTypes.index'))"
        >Tipos de Tests</span>
        <i class="fas fa-angle-right mx-4"></i>
        {{ model ? "Editar " : "Crear un nuevo" }} tipo de Test
      </h2>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

        <!-- Default form -->
        <DefaultForm
          class="mt-10 sm:mt-0"
          :model="model"
          :types="types"
        />

        <div v-if="model">
          <jet-section-border />
            <ResultsForm
              :model="model"
            />
        </div>  
        <div class="flex">
          <div v-for="result in resultStrings" :key="result" class="results_div">
            {{result}}
          </div>
        </div> 
      </div>
    </div>
  </app-layout>
</template>
<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import JetSectionBorder from "@/Jetstream/SectionBorder.vue";

import DefaultForm from "./Components/Form";

import ResultsForm from "./Components/ResultsForm";

export default {
  props: ["model", "results", "types"],

  components: {
    AppLayout,
    JetSectionBorder,

    DefaultForm,

    ResultsForm,
  },

  computed: {
      resultStrings() {
        let arr = [];
        if(this.results)
        {
          this.results.forEach(element => {
            arr.push(element.result);
          });
        }

        return arr;
      },
  },
};
</script>
<style scoped>
  .results_div{
    margin: 5px 10px;
    padding: 2px 5px;
    background-color: darkcyan;
    border-radius: 10%;
    font-size: 1.1rem;
    color: white;
    font-weight: bolder;
  }
</style>