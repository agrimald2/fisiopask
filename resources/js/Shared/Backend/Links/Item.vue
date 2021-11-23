<template>
  <div>
    <div class="rounded flex items-center
  transform transition-transform hover:scale-105 cursor-pointer
  border border-transparent
  group hover:bg-gra-100 hover:text-green-400 hover:border-green-400
  text-gray-700
  rounded-xl py-3
  shadow bg-white">
      <div class="text-lg sm:text-3xl pl-3 pr-4 xl:pr-3">
        <i
          class="group-hover:translate-x-2 group-hover:-rotate-6 transition-transform transform fas"
          :class="icon"
        ></i>
      </div>
      <div class="select-none group-hover:translate-x-2 transition transform sm:text-xl">
        {{ label }}
      </div>
    </div>
  </div>

</template>


<script>
export default {
  props: ["label", "icon", "text"],
};
</script>
