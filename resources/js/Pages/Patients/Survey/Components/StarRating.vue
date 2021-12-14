<template>
<div style="display:inline-block">
  <div class="form-group required">
    <div class="col-sm-12">
      <fieldset :id="starfield">
        <input class="star star-5 fas fa-star" :value="5" id="star-5" type="radio" name="star" @input="$emit('update:modelValue', $event.target.value)"/>
        <label class="star star-5 fas fa-star" for="star-5"></label>
        <input class="star star-4 fas fa-star" :value="4" id="star-4" type="radio" name="star" @input="$emit('update:modelValue', $event.target.value)"/>
        <label class="star star-4 fas fa-star" for="star-4"></label>
        <input class="star star-3 fas fa-star" :value="3" id="star-3" type="radio" name="star" @input="$emit('update:modelValue', $event.target.value)"/>
        <label class="star star-3 fas fa-star" for="star-3"></label>
        <input class="star star-2 fas fa-star" :value="2" id="star-2" type="radio" name="star" @input="$emit('update:modelValue', $event.target.value)"/>
        <label class="star star-2 fas fa-star" for="star-2"></label>
        <input class="star star-1 fas fa-star" :value="1" id="star-1" type="radio" name="star" @input="$emit('update:modelValue', $event.target.value)"/>
        <label class="star star-1 fas fa-star" for="star-1"></label>
      </fieldset>
    </div>
  </div>
</div>
</template>

<script>
export default {
    emits: ["update:modelValue"],

    data() {
        return {
            value: 0,
        };
    },
}
</script>

<style scoped>
/** rating **/
div.stars {
  display: inline-block;
}

input.star { display: none; }

label.star {
  float: right;
  padding: 10px;
  font-size: 20px;
  color: 
#444;
  transition: all .2s;
}

input.star:checked ~ label.star:before {
  color: 
#f3de26;
  transition: all .25s;
}

input.star-5:checked ~ label.star:before {
  color: 
#ffe600;
  text-shadow: 0 0 5px 
#7f8c8d;
}

input.star-1:checked ~ label.star:before { color: 
#ddce43; }

label.star:hover { transform: rotate(-15deg) scale(1.3); }

.horline > li {
  font-weight: bold;
  color: 
#7f8c8d;

}
</style>