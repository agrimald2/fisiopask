<template>
  <div class="libraryFilter mt-4">
    <hr class="mb-4 mt-2" style="height: 4vh; background-color: cadetblue" />
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      TOTAL - S/. {{ total.toLocaleString() }}
    </h2>

    <hr class="mb-4 mt-2" style="height: 4vh; background-color: cadetblue" />
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      SUBFAMILIAS
    </h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 libraryStats">
      <div
        class="col-span-6"
        v-for="(SumOfSubFamilie, index) in SumOfSubFamilies"
        :key="index"
      >
        <div class="accordion accordion-flush" id="accordionFlushExample">
          <div
            class="accordion-item border-t-0 border-l-0 border-r-0 rounded-none bg-white border border-gray-200"
          >
            <h2 class="accordion-header mb-0" id="flush-headingOne">
              <button
                class="accordion-button relative flex items-center w-full py-4 px-5 text-base text-gray-800 text-left bg-white border-0 rounded-none transition focus:outline-none"
                type="button"
                data-bs-toggle="collapse"
                aria-expanded="false"
                data-bs-target="#flush-collapseOne"
                aria-controls="flush-collapseOne"
              >
                <div class="row">
                  <span
                    class="pb-2 mb-2 inline-block px-6 py-2.5 bg-gray-800 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-gray-900 hover:shadow-lg focus:bg-gray-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-900 active:shadow-lg transition duration-150 ease-in-out"
                  >
                    {{ SumOfSubFamilie.bills_sub_family.billsfamily.name }} -
                    {{ SumOfSubFamilie.bills_sub_family.name }}
                  </span>
                  <br>
                  <span class="amount"
                    >S/{{ SumOfSubFamilie.total_quantity.toLocaleString() }}
                  </span>
                </div>
              </button>
            </h2>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ["SumOfSubFamilies", "total"],
  setup() {},
};
</script>
<style scoped>
#libraries {
  padding: 10px 25px;
}

.relative {
}

li {
  margin-bottom: 2px;
}

i {
  font-size: 2rem;
}

.libraryStats div {
  margin-bottom: 1rem;
}

.libraryStats h2 {
  font-size: 1.3rem;
}

.ISBNFilter {
}

.ISBNFilter img {
  max-width: 60%;
  margin-left: auto;
  margin-right: auto;
  margin-top: 1rem;
}

.indications {
  padding: 7px;
}

#filters {
  padding: 5px;
}
.amount {
  font-weight: bolder;
  font-size: 1.2rem;
}

.subamount {
  font-weight: bolder;
  font-size: 1.2rem;
}
</style>
