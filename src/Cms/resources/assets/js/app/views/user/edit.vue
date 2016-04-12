<template>
 
<div class="content">

  <loader :show="!item"></loader>

  <div class="card">
    <div class="card-block" v-if="item">
      <div class="form-group">
        <label for="name" class="control-label">Name</label>
        <input class="form-control" name="name" type="text" v-model="item.name" @blur="saveItem" />
      </div>
      <div class="form-group">
        <label for="email" class="control-label">E-mail</label>
        <input class="form-control" name="email" type="text" v-model="item.email" @blur="saveItem" />
      </div>
      <div class="form-group">
        <label for="user_level" class="control-label">User Level</label>
        <select class="form-control" name="user_level" v-model="item.user_level" @change="saveItem">
          <option v-for="option in availableUserLevels" v-bind:value="option.value">
            {{ option.text }}
          </option>
        </select>
      </div>

      <div class="form-group">
        <label for="slug" class="control-label">Profile Image</label>
        <div v-if="item">

          <component is="controller" :parent.sync="item"></component>
        </div>
      </div>
    </div>
  </div>
</div>

</template>

<script>
import controller from '../../components/image/controller.vue';

export default {

  data() {
    return {
      availableUserLevels: [
        {
          text: 'Super Admin',
          value: 'cms_super'
        },
        {
          text: 'Site Admin',
          value: 'cms_admin'
        },
        {
          text: 'Site User',
          value: 'cms_user'
        },
        {
          text: 'User',
          value: 'user'
        }
      ],
      item: null,
      resource: null,
      id: null
    }
  }, 

  components: {
    controller,
  },

  methods: {


    findInOpts(key) {
      for (var i = 0; i < this.$data.optsUserLevels.length; i++) {
        if (this.$data.optsUserLevels[i].value == key) return this.$data.optsUserLevels[i];
      }
    },

    saveItem() {
      this.resource.update({id: this.id}, this.item).then((response) => {
        this.$set('item', response.data);
      });
    },

    getItem() {
      this.resource.get({id: this.id}).then((response) => {
        this.$set('item', response.data);
      }); 
    },

  },

  events: {
    'image::set'() {
      this.saveItem();
      this.rid = Math.random().toString(36).substring(7);
      return false;
    }
  },

  ready() {
    var resource = this.$resource('user{/id}');
    this.$set('resource', resource);
    this.$set('id', parseInt(this.$route.params.id));
    this.getItem();

  }  
}
</script>