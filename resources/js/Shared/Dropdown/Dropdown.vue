<template>
  <div
    class="border relative"
    tabindex="0"
    @blur="onBlur"
  >
    <!-- Label -->
    <div
      class="p-2 hover:bg-gray-100 cursor-pointer relative"
      @click="toggle"
    >
      {{ label }}
      <i class="absolute right-3 top-3 text-gray-500 fas fa-angle-down"></i>
    </div>
    <!-- Popup -->
    <div
      class="absolute border bg-white z-40 inset-x-0 shadow-lg"
      v-show="is_expanded"
    >
      <!-- Searchbar -->
      <div
        v-if="searchable || this.api.url"
        class="px-2 py-2 shadow relative z-10"
      >
        <input
          ref="inputSearch"
          type="text"
          class="w-full border border-gray-400 p-2"
          placeholder="Search"
          v-model="input.search"
          @blur="onBlur"
          @keydown.esc="hide()"
          @input="onSearchInput"
        />
      </div>

      <!-- Mutliple -->
      <template v-if="multiple && selectAll && !api.url">
        <div class="p-2 text-center">
          <div
            class="p-2 rounded border bg-gray-50 hover:bg-gray-100 cursor-pointer select-none"
            @click="onToggleAllClicked"
          >
            Seleccionar todos
          </div>
        </div>
      </template>

      <!-- Items -->
      <div
        class="overflow-y-auto"
        style="max-height: 250px"
      >
        <div
          v-for="(option, key) in filter(renderOptions)"
          :key="key"
          class="px-2 py-1 border-t border-gray-100 cursor-pointer hover:bg-gray-100"
          @click="onOptionClicked(key, option)"
        >
          <template v-if="multiple || isOptionSelected(key)">
            <i
              class="mr-2"
              :class="{
                'fa fa-check-square': isOptionSelected(key),
                'far fa-square text-gray-400': !isOptionSelected(key),
              }"
            ></i>
          </template>
          {{ option }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  props: {
    modelValue: null,
    options: { type: Object, default: {} },
    searchable: { type: Boolean },
    multiple: { type: Boolean },
    selectAll: { type: Boolean, default: true },
    apiUrl: null,
  },

  emits: ["update:modelValue", "changed"],

  setup() {
    return { apiTimeout: null };
  },

  mounted() {
    this.fetchApiIds();
  },

  data() {
    return {
      is_expanded: false,
      input: {
        search: null,
      },
      api: {
        url: this.apiUrl,
        options: {},
        optionsCache: {},
      },
    };
  },

  computed: {
    label() {
      if (!this.modelValue) return "Seleccionar";

      if (this.multiple && Array.isArray(this.modelValue)) {
        if (this.modelValue.length == 1) {
          return this.getOptionLabelByKey(this.modelValue[0]);
        }

        return `${this.modelValue.length} seleccionados`;
      }

      return this.getOptionLabelByKey(this.modelValue);
    },

    renderOptions() {
      if (this.api.url) {
        const options = { ...this.api.options };

        let values = this.modelValue;
        if (!Array.isArray(values)) values = [values];
        values.forEach((key) => {
          options[key] = this.getOptionLabelByKey(key);
        });

        return options;
      }

      return this.options;
    },
  },

  methods: {
    //

    // Visibility
    hide() {
      this.is_expanded = false;
    },
    show() {
      this.is_expanded = true;

      if (this.searchable) {
        this.$nextTick(() => {
          this.$refs.inputSearch.focus();
        });
      }
    },
    toggle() {
      if (this.is_expanded) this.hide();
      else this.show();
    },

    //
    // Hooks
    onBlur() {
      const els = [this.$refs.inputSearch, this.$el];

      setTimeout(() => {
        if (!els.some((x) => x == document.activeElement)) {
          this.hide();
        }
      }, 100);
    },
    onOptionClicked(key, option) {
      if (this.multiple) {
        let value = this.modelValue;

        if (!Array.isArray(value)) {
          value = [];
        }

        this.toggleOptionFromArray(value, key);

        this.changeValue(value);
      } else {
        this.changeValue(key);
        this.hide();
      }
    },
    onToggleAllClicked() {
      let value = [];
      if (
        Array.isArray(this.modelValue) &&
        this.modelValue.length != Object.keys(this.options).length
      ) {
        value = Object.keys(this.options);
      }
      this.changeValue(value);
    },

    //
    // Api
    fetchApiIds() {
      if (!this.api.url) return;

      const url = this.api.url + "/ids";
      const ids = Array.isArray(this.modelValue)
        ? this.modelValue
        : [this.modelValue];

      axios.post(url, { ids }).then((res) => {
        this.api.optionsCache = res.data;
      });

      this.onSearchInput();
    },

    onSearchInput() {
      if (!this.api.url) return;

      const search = this.input.search;

      if (this.apiTimeout) clearTimeout(this.apiTimeout);

      this.apiTimeout = setTimeout(() => {
        axios.post(this.api.url, { search }).then((res) => {
          this.api.options = res.data;

          this.api.optionsCache = {
            ...this.api.optionsCache,
            ...res.data,
          };
        });
      }, 600);
    },

    //
    // Search
    filter(options) {
      let query = this.input.search;

      if (!query || this.api.url) return options;
      query = query.toLowerCase();

      const entries = Object.keys(options)
        .filter((key) => options[key].toLowerCase().includes(query))
        .map((key) => [key, options[key]]);

      return Object.fromEntries(entries);
    },

    //
    // Logic helpers
    getOptionLabelByKey(key) {
      if (this.api.url && this.api.optionsCache.hasOwnProperty(key)) {
        return this.api.optionsCache[key];
      }
      if (this.options.hasOwnProperty(key)) {
        return this.options[key];
      }
      return key;
    },
    isOptionSelected(key) {
      if (this.multiple && Array.isArray(this.modelValue)) {
        return this.modelValue.some((x) => x == key);
      }

      return this.modelValue == key;
    },

    //
    // Action helpers
    toggleOptionFromArray(array, key) {
      if (array.some((x) => x == key)) {
        const index = array.findIndex((x) => x == key);
        array.splice(index, 1);
      } else {
        array.push(key);
      }
    },
    changeValue(value) {
      this.$emit("changed", value);
      this.$emit("update:modelValue", value);
    },
  },
};
</script>
