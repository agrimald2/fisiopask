<template>
<div style="display:inline-block">
  <div class="form-group required">
    <div class="col-sm-12">
      <fieldset :id="starfield">
        <input class="star star-5 fas fa-star" :value="5" :id="'star-5-'+starfield" type="radio" name="star" @input="$emit('update:modelValue', $event.target.value)"/>
          <label class="star star-5 fas fa-star" :for="'star-5-'+starfield"></label>
        <input class="star star-5 fas fa-star" :value="4" :id="'star-4-'+starfield" type="radio" name="star" @input="$emit('update:modelValue', $event.target.value)"/>
          <label class="star star-5 fas fa-star" :for="'star-4-'+starfield"></label>
        <input class="star star-5 fas fa-star" :value="3" :id="'star-3-'+starfield" type="radio" name="star" @input="$emit('update:modelValue', $event.target.value)"/>
        <label class="star star-5 fas fa-star" :for="'star-3-'+starfield"></label>
        <input class="star star-5 fas fa-star" :value="2" :id="'star-2-'+starfield" type="radio" name="star" @input="$emit('update:modelValue', $event.target.value)"/>
          <label class="star star-5 fas fa-star" :for="'star-2-'+starfield"></label>
        <input class="star star-5 fas fa-star" :value="1" :id="'star-1-'+starfield" type="radio" name="star" @input="$emit('update:modelValue', $event.target.value)"/>
          <label class="star star-5 fas fa-star" :for="'star-1-'+starfield"></label>
      </fieldset>
    </div>
  </div>
</div>
</template>

<script>
export default {
    emits: ["update:modelValue", "starfield"],
    props: {
      starfield : null,
    },

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
  red;
  transition: all .25s;
}

input.star-5:checked ~ label.star:before {
  color: 
#a6d3d3;
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