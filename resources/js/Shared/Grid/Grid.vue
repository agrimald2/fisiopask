<template>
  <table class="text-center w-full bg-white">
    <tr>
      <grid-th
        v-for="(cell, cell_key) in colNames"
        :key="cell_key"
      >
        {{ cell }}
      </grid-th>
    </tr>
    <tr
      v-for="(row, row_key) in rows"
      :key="row_key"
    >
      <grid-td
        v-for="(col, cell_key) in cols"
        :key="cell_key"
      >
        <component
          :is="getCellElement(col)"
          :value="getCellValue(row[cell_key], col)"
          :context="col.context"
        />
      </grid-td>
    </tr>
  </table>
</template>


<script>
import GridTd from "./Table/Td";
import GridTh from "./Table/Th";
import GridCell from "./Table/Cell";

export default {
  props: ["cols", "rows"],

  components: {
    GridTh,
    GridTd,
  },

  data() {
    return {
      //
    };
  },

  computed: {
    colNames() {
      return this.cols.map((x) => {
        if (x.hasOwnProperty("name")) return x.name;
        return x;
      });
    },
  },

  methods: {
    getCellValue(value, col) {
      if (col.hasOwnProperty("format")) value = col.format(value);

      return value;
    },

    getCellElement(col) {
      if (col.hasOwnProperty("element")) return col.element;

      return GridCell;
    },
  },
};
</script>
