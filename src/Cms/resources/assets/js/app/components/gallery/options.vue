<template>
  <div class="well">
    <p>Options</p>
    <div class="form-group">
        <label for="size" class="control-label">Max Size Constraint: </label>
        <input type="text" name="sizew" v-model="size" v-on="keyup: buildJson($event)" />
    </div>
    <div class="form-group">
      <label for="caption" class="control-label">Caption: </label>
       <input type="text" name="caption" v-model="caption" v-on="keyup: buildJson($event)" />
    </div>
  </div>   
</template>

<script>
export default {

  data() {
    return {
      size: '',
      caption: ''
    }
  },


  methods: {


    buildJson: function() {

      var obj = {};

      try {
        if (this.$data.size != '') obj.size = this.$data.sizew;
      } catch(e) {}
      if (this.$data.caption != '' ) obj.caption = this.$data.caption;


      var stringed = JSON.stringify(obj);

      this.$parent.$data.content.data = stringed == "{}" ? "" : stringed;
    },

    setValues: function() {

      var json = this.$parent.$data.content.data;

      if (json && json.length) {
        try {
          json = JSON.parse(json);
        } catch(e) {
          console.log('styling value is not valid');
        }

        if (json.size) {
          // this.$data.sizew = json.size.split('x')[0];
          // this.$data.sizeh = json.size.split('x')[1];
          this.$data.size = json.size;
        }
        if (json.caption) {
          this.$data.caption = json.caption;
        }
      
      }
    }

  },

  ready() {
    this.setValues();
  }  
}
</script>