<template>
    <app-layout title="Estadisticas">
      <div class="py-2">
        <div class="max-w-7xl mx-auto xs:px-2 sm:px-2 lg:px-2 mb-2">
          <div class="overflow-hidden px-2">
            <!-- Charts -->
            <h2
              class="
                my-6
                text-lg
                m:px-4
                lg:px-6
                font-semibold
                text-gray-700
                dark:text-gray-200
              "
            >
              Gráficos Estadísticas
            </h2>

            <div class="sm:px-4 lg:px-6 gap-6 mb-2 md:grid-cols-2">
              <div
                class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800"
              >
                <h4 class="font-semibold text-gray-800">Filtrar por</h4>
                <!--Filtro-->
                <div class="grid sm:px-4 lg:px-6 gap-6 mb-2 md:grid-cols-2">
                  <div>
                    <select
                      class="
                        border-gray-300
                        focus:ring focus:ring-indigo-200 focus:ring-opacity-50
                        shadow-sm
                        w-full
                        mt-1
                        py-2.5
                        px-4
                        text-gray-700
                        leading-tight
                        focus:border-indigo-300
                        rounded-lg
                        dark:border-gray-200
                        dark:border-none
                        dark:bg-gray-600
                        dark:text-white
                        dark:focus:border-blue-500
                        dark:focus:shadow-outline-blue
                      "
                      v-model="paramsFilter.office"
                    >
                      <option
                        v-for="(o, index) in offices"
                        :key="index"
                        :value="o.id"
                      >
                        {{ o.name }}
                      </option>
                    </select>
                  </div>
                  <div>
                    <select
                      class="
                        border-gray-300
                        focus:ring focus:ring-indigo-200 focus:ring-opacity-50
                        shadow-sm
                        w-full
                        mt-1
                        py-2.5
                        px-4
                        text-gray-700
                        leading-tight
                        focus:border-indigo-300
                        rounded-lg
                        dark:border-gray-200
                        dark:border-none
                        dark:bg-gray-600
                        dark:text-white
                        dark:focus:border-blue-500
                        dark:focus:shadow-outline-blue
                      "
                      @change="clearParams()"
                      v-model="paramsFilter.advances"
                    >
                      <option value="2">Evolución mensual</option>
                      <option value="1">Avance del día</option>
                      <option value="3">Fecha</option>
                      <option value="4">Recomendación</option>
                      <option value="5">Tarifas</option>
                    </select>
                  </div>
                </div>

                <!--Avance dia-->
                <div v-show="paramsFilter.advances === '1'">
                  <div
                    class="grid sm:px-4 lg:px-6 gap-6 mb-2 mt-4 md:grid-cols-1"
                  >
                    <div class="flex justify-center">
                      <div class="mb-4">
                        <h4 class="font-semibold text-gray-800">Dia</h4>
                        <select
                          class="
                            border-gray-300
                            focus:ring focus:ring-indigo-200 focus:ring-opacity-50
                            shadow-sm
                            w-auto
                            mt-1
                            py-2.5
                            px-4
                            text-gray-700
                            leading-tight
                            focus:border-indigo-300
                            rounded-lg
                            dark:border-gray-200
                            dark:border-none
                            dark:bg-gray-600
                            dark:text-white
                            dark:focus:border-blue-500
                            dark:focus:shadow-outline-blue
                          "
                          v-model="paramsFilter.daySelected"
                        >
                          <option disabled selected value="">Seleccionar</option>

                          <option
                            v-for="(d, index) in dayList"
                            :key="index"
                            :value="d"
                          >
                            {{ d }}
                          </option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>

                <!--Fecha-->
                <div v-show="paramsFilter.advances === '3'">
                  <div
                    class="grid sm:px-4 mt-4 lg:px-6 gap-6 mb-2 md:grid-cols-1"
                  >
                    <div class="flex justify-center">
                      <div class="mb-4">
                        <h4 class="font-semibold text-gray-800">
                          Seleccionar rango
                        </h4>
                        <DatePicker
                          class="mt-2"
                          v-model="dates"
                          mode="date"
                          :masks="masks"
                          is-range
                        >
                          <template
                            v-slot="{ inputValue, inputEvents, isDragging }"
                          >
                            <div
                              class="
                                flex flex-col
                                sm:flex-row
                                justify-start
                                items-center
                              "
                            >
                              <div class="relative flex-grow">
                                <svg
                                  class="
                                    text-gray-600
                                    w-4
                                    h-full
                                    mx-2
                                    absolute
                                    pointer-events-none
                                  "
                                  fill="none"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  viewBox="0 0 24 24"
                                  stroke="currentColor"
                                >
                                  <path
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                  ></path>
                                </svg>
                                <input
                                  class="
                                    flex-grow
                                    pl-8
                                    pr-2
                                    py-1
                                    bg-gray-100
                                    border
                                    rounded
                                    w-full
                                  "
                                  :class="
                                    isDragging ? 'text-gray-600' : 'text-gray-900'
                                  "
                                  :value="inputValue.start"
                                  v-on="inputEvents.start"
                                />
                              </div>
                              <span class="flex-shrink-0 m-2">
                                <svg
                                  class="w-4 h-4 stroke-current text-gray-600"
                                  viewBox="0 0 24 24"
                                >
                                  <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"
                                  />
                                </svg>
                              </span>
                              <div class="relative flex-grow">
                                <svg
                                  class="
                                    text-gray-600
                                    w-4
                                    h-full
                                    mx-2
                                    absolute
                                    pointer-events-none
                                  "
                                  fill="none"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  viewBox="0 0 24 24"
                                  stroke="currentColor"
                                >
                                  <path
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                  ></path>
                                </svg>
                                <input
                                  class="
                                    flex-grow
                                    pl-8
                                    pr-2
                                    py-1
                                    bg-gray-100
                                    border
                                    rounded
                                    w-full
                                  "
                                  :class="
                                    isDragging ? 'text-gray-600' : 'text-gray-900'
                                  "
                                  :value="inputValue.end"
                                  v-on="inputEvents.end"
                                />
                              </div>
                            </div>
                          </template>
                        </DatePicker>
                      </div>
                    </div>
                  </div>
                </div>
                <!--Recomendacion-->
                <div v-show="paramsFilter.advances === '4'">
                  <div
                    class="grid sm:px-4 mt-4 lg:px-6 gap-6 mb-2 md:grid-cols-1"
                  >
                    <div class="flex justify-center">
                      <div class="mb-4">
                        <h4 class="font-semibold text-gray-800">
                          Seleccionar rango
                        </h4>
                        <select
                          class="
                            border-gray-300
                            focus:ring focus:ring-indigo-200 focus:ring-opacity-50
                            shadow-sm
                            w-full
                            mt-1
                            py-2.5
                            px-4
                            text-gray-700
                            leading-tight
                            focus:border-indigo-300
                            rounded-lg
                            dark:border-gray-200
                            dark:border-none
                            dark:bg-gray-600
                            dark:text-white
                            dark:focus:border-blue-500
                            dark:focus:shadow-outline-blue
                          "
                          v-model="paramsRecomendation.recommendations"
                        >
                          <option disabled selected value="">Seleccionar</option>

                          <option
                            v-for="(r, index) in recommendation"
                            :key="index"
                            :value="r.id"
                          >
                            {{ r.recommendation }}
                          </option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>

                <!--Tarifas-->
                <div v-show="paramsFilter.advances === '5'">
                  <div
                    class="grid sm:px-4 lg:px-6 gap-6 mb-2 mt-4 md:grid-cols-3"
                  >
                    <div class="grid">
                      <h4 class="font-semibold text-gray-800">Familia</h4>
                      <dropdown
                        class="b"
                        v-model="paramsFilter.family_id"
                        :options="
                          Object.fromEntries(families.map((x) => [x.id, x.name]))
                        "
                        @changed="getSubfamilies"
                        searchable
                      />
                    </div>

                    <div class="grid">
                      <h4 class="font-semibold text-gray-800">Subfamilia</h4>
                      <dropdown
                        v-model="paramsFilter.subfamily_id"
                        :options="
                          Object.fromEntries(
                            subfamilies.map((x) => [x.id, x.name])
                          )
                        "
                        @changed="getRates"
                        searchable
                      />
                    </div>

                    <div class="grid">
                      <h4 class="font-semibold text-gray-800">Tarifa</h4>
                      <dropdown
                        v-model="paramsFilter.rate_id"
                        :options="
                          Object.fromEntries(
                            rates.map((x) => [
                              x.id,
                              '$' + x.price + ' - ' + x.name,
                            ])
                          )
                        "
                        @changed="selectRate"
                        searchable
                      />
                    </div>
                  </div>
                </div>
                <!--loading-->
                <div
                  v-if="loadingSearch"
                  class="grid grid-cols-1 md:grid-cols-1 md:gap-8"
                >
                  <div
                    class="
                      w-full
                      bg-gray-200
                      dark:bg-gray-800
                      rounded-full
                      h-1.5
                      mt-2
                      mb-4
                    "
                  >
                    <div
                      class="bg-green-400 dark:bg-purple-600 h-1.5 rounded-full"
                      style="width: 99%"
                    ></div>
                  </div>
                </div>
                <!--Button-->
                <div class="grid sm:px-4 lg:px-6 gap-6 mb-2 md:grid-cols-1">
                  <div class="flex justify-center sm:justify-center">
                    <div>
                      <JetButton
                        type="button"
                        class="my-1"
                        @click.prevent="searchFilter"
                      >
                        Buscar
                        <span class="ml-2 text-white" aria-hidden="true">
                          <svg
                            class="h-4 w-4 fill-current"
                            xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink"
                            version="1.1"
                            id="Capa_1"
                            x="0px"
                            y="0px"
                            viewBox="0 0 56.966 56.966"
                            style="enable-background: new 0 0 56.966 56.966"
                            xml:space="preserve"
                            width="512px"
                            height="512px"
                          >
                            <path
                              d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z"
                            />
                          </svg>
                        </span>
                      </JetButton>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="grid sm:px-4 lg:px-6 gap-6 mb-2 md:grid-cols-2">
              <div
                class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800"
              >
                <div class="flex justify-between">
                  <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
                    Venta en Soles (SERVICIOS CONSUMIDOS)
                  </h4>

                  <h4
                    :class="[
                      {
                        'text-green-400 mb-4 font-semibold': salesPorcentaje > 0,
                      },
                      { 'text-red-400 mb-4 font-semibold': salesPorcentaje < 0 },
                    ]"
                  >
                    {{ salesPorcentaje }}%
                  </h4>
                </div>
                <div v-if="showS" class="flex justify-center">
                  <h3 class="mb-4 font-semibold text-red-500">
                    No se encontró información
                  </h3>
                </div>
                <div v-else id="chart">
                  <apexchart
                    type="bar"
                    height="430"
                    :options="chartOptionsSales"
                    :series="seriesSales"
                  ></apexchart>
                </div>
              </div>

              <div
                class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800"
              >
                <div class="flex justify-between">
                  <h4 class="mb-4 font-semibold text-gray-800">
                    Nro. de Servicios
                  </h4>
                  <h4
                    :class="[
                      {
                        'text-green-400 mb-4 font-semibold':
                          nServicesPorcentaje > 0,
                      },
                      {
                        'text-red-400 mb-4 font-semibold':
                          nServicesPorcentaje < 0,
                      },
                    ]"
                  >
                    {{ nServicesPorcentaje }}%
                  </h4>
                </div>
                <div v-if="showNS" class="flex justify-center">
                  <h3 class="mb-4 font-semibold text-red-500">
                    No se encontró información
                  </h3>
                </div>

                <div v-else id="chart">
                  <apexchart
                    type="line"
                    height="430"
                    :options="chartOptionsNservices"
                    :series="seriesNservices"
                  ></apexchart>
                </div>
              </div>
            </div>

            <div class="grid sm:px-4 lg:px-6 gap-6 mb-2 md:grid-cols-2">
              <div
                class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800"
              >
                <div class="flex justify-between">
                  <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
                    Clientes Únicos
                  </h4>

                  <h4
                    :class="[
                      {
                        'text-green-400 mb-4 font-semibold':
                          patientPorcentaje > 0,
                      },
                      {
                        'text-red-400 mb-4 font-semibold': patientPorcentaje < 0,
                      },
                    ]"
                  >
                    {{ patientPorcentaje }}%
                  </h4>
                </div>
                <div v-if="showP" class="flex justify-center">
                  <h3 class="mb-4 font-semibold text-red-500">
                    No se encontró información
                  </h3>
                </div>
                <div v-else id="charts">
                  <apexchart
                    type="bar"
                    height="430"
                    :options="chartOptionsPatient"
                    :series="seriesPatient"
                  ></apexchart>
                </div>
              </div>

              <div
                class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800"
              >
                <div class="flex justify-between">
                  <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
                    Ticket Promedio
                  </h4>
                  <h4
                    :class="[
                      {
                        'text-green-400 mb-4 font-semibold': ticketPorcentaje > 0,
                      },
                      { 'text-red-400 mb-4 font-semibold': ticketPorcentaje < 0 },
                    ]"
                  >
                    {{ ticketPorcentaje }}%
                  </h4>
                </div>
                <div v-if="showT" class="flex justify-center">
                  <h3 class="mb-4 font-semibold text-red-500">
                    No se encontró información
                  </h3>
                </div>
                <div v-else id="chart">
                  <apexchart
                    type="area"
                    height="460"
                    :options="chartOptionsTicket"
                    :series="seriesTicket"
                  ></apexchart>
                </div>
              </div>
            </div>

            <div class="grid sm:px-4 lg:px-6 gap-6 mb-2 md:grid-cols-2">
              <div
                class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800"
              >
                <div class="flex justify-between">
                  <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
                    Dinero recaudado en BRUTO (NO dinero consumido)
                  </h4>

                  <h4
                    :class="[
                      {
                        'text-green-400 mb-4 font-semibold': salesPorcentajeBruto > 0,
                      },
                      { 'text-red-400 mb-4 font-semibold': salesPorcentajeBruto < 0 },
                    ]"
                  >
                    {{ salesPorcentajeBruto }}%
                  </h4>
                </div>
                <div v-if="showSB" class="flex justify-center">
                  <h3 class="mb-4 font-semibold text-red-500">
                    No se encontró información
                  </h3>
                </div>
                <div v-else id="chart">
                  <apexchart
                    type="bar"
                    height="430"
                    :options="chartOptionsSalesBruto"
                    :series="seriesSalesBruto"
                  ></apexchart>
                </div>
              </div>
          </div>

            <!-- EXCEL -->
            <h2
              class="
                my-6
                text-lg
                m:px-4
                lg:px-6
                font-semibold
                text-gray-700
                dark:text-gray-200
              "
            >
              Reporte Excel
            </h2>
            <div class="sm:px-4 lg:px-6 gap-6 mb-2 md:grid-cols-2">
              <div
                class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800"
              >
                <h4 class="font-semibold text-gray-800">Clientes atendidos</h4>

                <!--Fecha-->
                <div>
                  <div>
                    <div
                      class="grid sm:px-4 mt-4 lg:px-6 gap-6 mb-2 md:grid-cols-1"
                    >
                      <div class="flex justify-center">
                        <select
                          class="
                            border-gray-300
                            focus:ring focus:ring-indigo-200 focus:ring-opacity-50
                            shadow-sm
                            w-full
                            mt-1
                            py-2.5
                            px-4
                            text-gray-700
                            leading-tight
                            focus:border-indigo-300
                            rounded-lg
                            dark:border-gray-200
                            dark:border-none
                            dark:bg-gray-600
                            dark:text-white
                            dark:focus:border-blue-500
                            dark:focus:shadow-outline-blue
                          "
                          v-model="officeExcel"
                        >
                          <option
                            :value="0"
                            selected
                          >
                            TODAS
                          </option>
                          <option
                            v-for="(o, index) in offices"
                            :key="index"
                            :value="o.id"
                          >
                            {{ o.name }}
                          </option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div
                    class="grid sm:px-4 mt-4 lg:px-6 gap-6 mb-2 md:grid-cols-1"
                  >
                    <div class="flex justify-center">
                      <div class="mb-4">
                        <h4 class="font-semibold text-gray-800">
                          Seleccionar rango o día específico
                        </h4>
                        <DatePicker
                          class="mt-2"
                          v-model="datesExcel"
                          mode="date"
                          :model-config="{
                            type: 'string',
                            mask: 'YYYY-MM-DD',
                          }"
                          :masks="{
                            L: 'YYYY-MM-DD',
                          }"
                          is-range
                        >
                          <template
                            v-slot="{ inputValue, inputEvents, isDragging }"
                          >
                            <div
                              class="
                                flex flex-col
                                sm:flex-row
                                justify-start
                                items-center
                              "
                            >
                              <div class="relative flex-grow">
                                <svg
                                  class="
                                    text-gray-600
                                    w-4
                                    h-full
                                    mx-2
                                    absolute
                                    pointer-events-none
                                  "
                                  fill="none"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  viewBox="0 0 24 24"
                                  stroke="currentColor"
                                >
                                  <path
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                  ></path>
                                </svg>
                                <input
                                  class="
                                    flex-grow
                                    pl-8
                                    pr-2
                                    py-1
                                    bg-gray-100
                                    border
                                    rounded
                                    w-full
                                  "
                                  :class="
                                    isDragging ? 'text-gray-600' : 'text-gray-900'
                                  "
                                  :value="inputValue.start"
                                  v-on="inputEvents.start"
                                />
                              </div>
                              <span class="flex-shrink-0 m-2">
                                <svg
                                  class="w-4 h-4 stroke-current text-gray-600"
                                  viewBox="0 0 24 24"
                                >
                                  <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"
                                  />
                                </svg>
                              </span>
                              <div class="relative flex-grow">
                                <svg
                                  class="
                                    text-gray-600
                                    w-4
                                    h-full
                                    mx-2
                                    absolute
                                    pointer-events-none
                                  "
                                  fill="none"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  viewBox="0 0 24 24"
                                  stroke="currentColor"
                                >
                                  <path
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                  ></path>
                                </svg>
                                <input
                                  class="
                                    flex-grow
                                    pl-8
                                    pr-2
                                    py-1
                                    bg-gray-100
                                    border
                                    rounded
                                    w-full
                                  "
                                  :class="
                                    isDragging ? 'text-gray-600' : 'text-gray-900'
                                  "
                                  :value="inputValue.end"
                                  v-on="inputEvents.end"
                                />
                              </div>
                            </div>
                          </template>
                        </DatePicker>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="flex justify-center">
                  <JetButton
                    type="button"
                    class="my-1"
                    @click.prevent="searchExcel"
                  >
                    Descargar

                    <span class="ml-2 text-white" aria-hidden="true">
                      <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                        ></path>
                      </svg>
                    </span>
                  </JetButton>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </app-layout>
  </template>

  <script>
  import AppLayout from "@/Layouts/AppLayout.vue";
  import JetInput from "@/Jetstream/Input";
  import JetButton from "@/Jetstream/Button";
  import axios from "axios";
  import Dropdown from "@/Shared/Dropdown/Dropdown";
  import { DatePicker } from "v-calendar";

  export default {
    props: {
      offices: Object,
      recommendation: Object,
      patient: Object,
      sales: Object,
      salesBruto: Object,
      ticket: Object,
      nServices: Object,
      modelValue: Object,
    },

    emits: ["update:modelValue"],
    components: {
      AppLayout,
      JetInput,
      JetButton,
      Dropdown,
      DatePicker,
    },

    data() {
      return {
        dates: {
          start: new Date().toISOString().slice(0, 10),
          end: new Date().toISOString().slice(0, 10),
        },

        masks: {
          input: "YYYY-MM-DD",
        },
        paramsRecomendation: {
          recommendations: "",
        },
        dayList: [],
        paramsFilter: {
          office: this.$props.offices[0].id,
          advances: "2",
          daySelected: "",
          family_id: "",
          subfamily_id: "",
          rate_id: "",
          rates_id: this.modelValue,
        },

        families: [],
        subfamilies: [],
        rates: [],

        //PATIENT
        patientPorcentaje: null,
        showP: Boolean,
        seriesPatient: [],
        chartOptionsPatient: {},
        //SALES
        salesPorcentaje: null,
        showS: Boolean,
        seriesSales: [],
        chartOptionsSales: {},
        //BRUTOSALES
        salesPorcentajeBruto: null,
        showSB: Boolean,
        seriesSalesBruto: [],
        chartOptionsSalesBruto: {},
        //TICKET
        ticketPorcentaje: null,
        showT: Boolean,
        seriesTicket: [],
        chartOptionsTicket: {},
        //NUMBER OF SERVICES
        nServicesPorcentaje: null,
        showNS: Boolean,
        seriesNservices: [],
        chartOptionsNservices: {},
        //
        loadingSearch: false,
        //EXCEL
        officeExcel:this.$props.offices[0].id,
        datesExcel: {

          start: new Date().toISOString().slice(0, 10),
          end: new Date().toISOString().slice(0, 10),

        },
      };
    },
    created() {
      //call dashboard
      this.showPatient(this.$props.patient);
      this.showSalesBruto(this.$props.salesBruto);
      this.showSales(this.$props.sales);
      this.showTicket(this.$props.ticket);
      this.showNservices(this.$props.nServices);

      //fill day list
      this.dayList = [...Array(31).keys()].map((foo) => foo + 1);
    },
    mounted() {
      const url = route("productSelect.families");
      axios.get(url).then((res) => (this.families = res.data));
    },
    methods: {
      //patient
      showPatient(patient) {
        patient != "" ? (this.showP = false) : (this.showP = true);

        let fecha = [];
        let recurrentes = [];
        let nuevos = [];
        let totalGeneral = [];
        this.patientPorcentaje = "";

        Object.keys(patient).forEach((e) => {
          fecha.push(patient[e].Fecha);
          nuevos.push(patient[e].Nuevos);
          recurrentes.push(patient[e].Recurrentes);
          totalGeneral.push(patient[e].TotalGeneral);
        });
        var a = totalGeneral[totalGeneral.length - 2];
        var b = totalGeneral[totalGeneral.length - 1];
        if (totalGeneral.length > 1 && a > 0) {
          this.patientPorcentaje = Math.round((+b / a - 1) * 100);
        }

        this.seriesPatient = [
          {
            name: "Nuevos",
            data: nuevos,
          },
          {
            name: "Recurrentes",
            data: recurrentes,
          },
        ];

        this.chartOptionsPatient = {
          chart: {
            type: "bar",
            height: 430,
            stacked: true,
          },
          colors: ["#2983FF", "#FF9800"],
          plotOptions: {
            bar: {
              horizontal: true,
              borderRadius: 10,
            },
          },
          responsive: [
            {
              breakpoint: 480,
              options: {
                legend: {
                  position: "bottom",
                  offsetX: -10,
                  offsetY: 0,
                },
              },
            },
          ],

          stroke: {
            show: true,
            width: 1,
            colors: ["#fff"],
          },
          tooltip: {
            shared: true,
            intersect: false,
          },
          xaxis: {
            categories: fecha,
          },
          legend: {
            position: "top",
            horizontalAling: "left",
            offsetX: 40,
          },
          fill: {
            opacity: 1,
          },
        };
      },

      //Sales
      showSales(sales) {
        sales != "" ? (this.showS = false) : (this.showS = true);
        let fecha = [];
        let recurrentes = [];
        let nuevos = [];
        let totalGeneral = [];
        this.salesPorcentaje = "";

        Object.keys(sales).forEach((e) => {
          fecha.push(sales[e].Fecha);
          nuevos.push(sales[e].ValorEjecutadoNuevos);
          recurrentes.push(sales[e].ValorEjecutadoRecurrentes);
          totalGeneral.push(sales[e].TotalGeneral);
        });
        var a = totalGeneral[totalGeneral.length - 2];
        var b = totalGeneral[totalGeneral.length - 1];

        if (totalGeneral.length > 1 && a > 0) {
          this.salesPorcentaje = Math.round((+b / a - 1) * 100);
        }
        this.seriesSales = [
          {
            name: "Nuevos",
            data: nuevos,
          },
          {
            name: "Recurrentes",
            data: recurrentes,
          },
        ];
        this.chartOptionsSales = {
          chart: {
            type: "bar",
            height: 350,
            stacked: true,
            toolbar: {
              show: true,
            },
            zoom: {
              enabled: true,
            },
          },
          colors: ["#2983FF", "#FF9800"],
          responsive: [
            {
              breakpoint: 480,
              options: {
                legend: {
                  position: "bottom",
                  offsetX: -10,
                  offsetY: 0,
                },
              },
            },
          ],
          plotOptions: {
            bar: {
              horizontal: false,
              borderRadius: 10,
            },
          },
          xaxis: {
            categories: fecha,
          },
          legend: {
            position: "top",

            horizontalAling: "left",
            offsetX: 40,
          },
          fill: {
            opacity: 1,
          },
        };
      },
      //SalesBruto
      showSalesBruto(salesBruto) {
        salesBruto != "" ? (this.showSB = false) : (this.showSB = true);
        let fecha = [];
        let recurrentes = [];
        let nuevos = [];
        let totalGeneral = [];
        this.salesPorcentajeBruto = "";

        Object.keys(salesBruto).forEach((e) => {
          fecha.push(salesBruto[e].Fecha);
          nuevos.push(salesBruto[e].ValorEjecutadoNuevos);
          recurrentes.push(salesBruto[e].ValorEjecutadoRecurrentes);
          totalGeneral.push(salesBruto[e].TotalGeneral);
        });
        var a = totalGeneral[totalGeneral.length - 2];
        var b = totalGeneral[totalGeneral.length - 1];

        if (totalGeneral.length > 1 && a > 0) {
          this.salesPorcentajeBruto = Math.round((+b / a - 1) * 100);
        }
        this.seriesSalesBruto = [
          {
            name: "Nuevos",
            data: nuevos,
          },
          {
            name: "Recurrentes",
            data: recurrentes,
          },
        ];
        this.chartOptionsSalesBruto = {
          chart: {
            type: "bar",
            height: 350,
            stacked: true,
            toolbar: {
              show: true,
            },
            zoom: {
              enabled: true,
            },
          },
          colors: ["#2983FF", "#FF9800"],
          responsive: [
            {
              breakpoint: 480,
              options: {
                legend: {
                  position: "bottom",
                  offsetX: -10,
                  offsetY: 0,
                },
              },
            },
          ],
          plotOptions: {
            bar: {
              horizontal: false,
              borderRadius: 10,
            },
          },
          xaxis: {
            categories: fecha,
          },
          legend: {
            position: "top",

            horizontalAling: "left",
            offsetX: 40,
          },
          fill: {
            opacity: 1,
          },
        };
      },
      //ticket
      showTicket(ticket) {
        ticket != "" ? (this.showT = false) : (this.showT = true);
        let fecha = [];
        let ticketrecurrentes = [];
        let ticketnuevos = [];
        let totalGeneral = [];
        this.ticketPorcentaje = "";

        Object.keys(ticket).forEach((e) => {
          fecha.push(ticket[e].Fecha);
          ticketrecurrentes.push(ticket[e].TicketPromedioRecurrentes);
          ticketnuevos.push(ticket[e].TicketPromedioNuevos);
          totalGeneral.push(ticket[e].TotalGeneral);
        });

        var a = totalGeneral[totalGeneral.length - 2];
        var b = totalGeneral[totalGeneral.length - 1];

        if (totalGeneral.length > 1 && a > 0) {
          this.ticketPorcentaje = Math.round((+b / a - 1) * 100);
        }

        this.seriesTicket = [
          {
            name: "Nuevos",
            data: ticketnuevos,
          },
          {
            name: "Recurrentes",
            data: ticketrecurrentes,
          },
        ];
        this.chartOptionsTicket = {
          chart: {
            type: "area",
            height: 350,
            stacked: true,
            toolbar: {
              show: true,
            },
            zoom: {
              enabled: true,
            },
          },
          colors: ["#2983FF", "#FF9800"],
          responsive: [
            {
              breakpoint: 480,
              options: {
                legend: {
                  position: "bottom",
                  offsetX: -10,
                  offsetY: 0,
                },
              },
            },
          ],
          plotOptions: {
            bar: {
              horizontal: false,
              borderRadius: 10,
            },
          },
          xaxis: {
            categories: fecha,
          },
          legend: {
            position: "bottom",
            horizontalAling: "left",
            offsetX: 20,
          },
          fill: {
            opacity: 1,
          },
        };
      },
      //number of services
      showNservices(nServices) {
        nServices != "" ? (this.showNS = false) : (this.showNS = true);
        let fecha = [];
        let recurrentes = [];
        let nuevos = [];
        let totalGeneral = [];
        this.nServicesPorcentaje = "";

        Object.keys(nServices).forEach((e) => {
          fecha.push(nServices[e].Fecha);
          recurrentes.push(nServices[e].ServiciosRecurrentes);
          nuevos.push(nServices[e].ServiciosNuevos);
          totalGeneral.push(nServices[e].TotalGeneral);
        });

        var a = totalGeneral[totalGeneral.length - 2];
        var b = totalGeneral[totalGeneral.length - 1];

        if (totalGeneral.length > 1 && a > 0) {
          this.nServicesPorcentaje = Math.round((+b / a - 1) * 100);
        }

        this.seriesNservices = [
          {
            name: "Nuevos",
            data: nuevos,
          },
          {
            name: "Recurrentes",
            data: recurrentes,
          },
        ];
        this.chartOptionsNservices = {
          chart: {
            height: 350,
            type: "line",
            stacked: false,
            toolbar: {
              show: true,
            },
            zoom: {
              enabled: true,
            },
          },
          colors: ["#2983FF", "#FF9800"],
          stroke: {
            curve: "smooth",
          },
          responsive: [
            {
              breakpoint: 480,
              options: {
                legend: {
                  position: "bottom",
                  offsetX: -10,
                  offsetY: 0,
                },
              },
            },
          ],
          plotOptions: {
            bar: {
              horizontal: false,
              borderRadius: 10,
            },
          },
          xaxis: {
            categories: fecha,
          },
          legend: {
            position: "bottom",
            horizontalAling: "left",
            offsetX: 20,
          },
          fill: {
            opacity: 1,
          },
        };
      },
      //families
      getSubfamilies(id) {
        const url = route("productSelect.subfamilies", id);
        axios.get(url).then((res) => (this.subfamilies = res.data));
        this.paramsFilter.subfamily_id = "";
      },

      getRates(id) {
        const url = route("productSelect.rates", id);
        axios.get(url).then((res) => (this.rates = res.data));
        this.paramsFilter.rate_id = "";
      },

      selectRate(id) {
        const product = this.rates.find((x) => x.id == id);

        if (product) {
          this.$emit("update:modelValue", product);
        }
      },
      //clear inputs
      clearParams() {
        this.dates.start = new Date();
        this.dates.end = new Date();
        this.paramsRecomendation.recommendations = "";
        this.paramsFilter.daySelected = "";
        this.paramsFilter.family_id = null;
        this.paramsFilter.subfamily_id = null;
        this.paramsFilter.rates_id = null;
      },

      //search filter
      searchFilter() {
        this.loadingSearch = true;

        const url = route("statistic", {
          params: this.paramsFilter,
          paramsRecommendations: this.paramsRecomendation,
          dates: this.dates,
        });
        axios
          .post(url)
          .then((response) => {
            this.showTicket(response.data.tickets);
            this.showPatient(response.data.patients);
            this.showNservices(response.data.nServices);
            this.showSales(response.data.sales);
            this.showSalesBruto(response.data.salesBruto);
          })
          .catch((err) => {
            console.log(err);
          })
          .finally(() => (this.loadingSearch = false));
      },
      searchExcel() {

        let params={
          start:this.datesExcel.start,
          end:this.datesExcel.end,
          office:this.officeExcel
        };


        let paramString = new URLSearchParams(params);

        window.open(`excel?${paramString.toString()}`);
      },
    },
  };
  </script>
