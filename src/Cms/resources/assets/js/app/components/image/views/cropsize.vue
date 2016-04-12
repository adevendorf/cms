<template>

  <div class="modal-body"> 

    <div class="row">
      <div class="col-sm-12">

      <div class="row" v-if="visible == 'edit'">
        <div class="form-group">
          <label for="type" class="control-label">Name</label>
          <input class="form-control" name="name" type="text" v-model="item.name" />
        </div>
        <div class="form-group">
          <label for="key" class="control-label">Aspect Ratio </label>
          <input class="form-control" name="aspect_ratio" type="text" v-model="item.aspect_ratio" />
        </div>
        <div class="form-group">
          <label for="value" class="control-label">Max Pixels</label>
          <input class="form-control" name="max_dimension" type="text" v-model="item.max_dimension" />
        </div>
        <div class="form-group">
          <button class="btn btn-success" @click="saveItem"><i class="fa fa-check"></i> Save</button>
        </div>
      </div>


      <div class="row" v-if="visible == 'list'">


        <div class="col-sm-12">

          <div class="loading" v-if="!items"><div class="spinner"></div></div>
          <table class="table" v-if="items">
          <thead>
            <tr>
              <th></th>
              <th>Name</th>
              <th>Aspect Ratio</th>
              <th>Max Pixels</th>
              <th class="text-center"></th>
            </tr>
          </thead>
          <tr v-for="item in items.data">
            <td>
              <a @click="selectItem($event, item)" class="btn btn-info btn-sm">Edit</a>
            </td>
            <td>
              <a href="#" @click="selectItem($event, item)">{{ item.name }}</a>
            </td>
            <td>
              {{ item.aspect_ratio }}
            </td>
            <td>
              {{ item.max_dimension }}
            </td>
            <td class="text-center">
              <a class="btn btn-sm btn-danger" @click="confirmRemoval(item.id)">Remove</a>
            </td>
            </tr>
          </table>  

          
          <nav v-if="item.total_pages > 1">
            <ul class="pagination">
              <li>
                <a href="#" @click="prevPage">
                  <span>&laquo;</span>
                </a>
              </li>
              <li>
                  <span>{{ item.current_page }} of {{ item.total_pages }}</span>
              <li>
                <a href="#" @click="nextPage">
                  <span>&raquo;</span>
                </a>
              </li>
            </ul>
          </nav>   
        </div>
      </div><!-- /list -->






      </div><!-- /col -->
    </div><!-- /row -->

    <confirm v-on:confirm="removeItem"></confirm>
  </div>

</template>

<script>
/**
 * Components/Image/ManagedCrops
 */
import menu from '../partials/menu.vue';


export default {

  components: {
    menu,
  },

  data() {
    return {
      visible: 'list',
      items: {
        data: [],
        total_pages: 1,
        current_page: 1,
        last_page: 1
      },
      item: false,
      resource: null
    } 
  },

  methods: {
    confirmRemoval(id) {
      this.$broadcast('confirm::ask', id);
    },

    removeItem(id) {
      this.resource.delete({id: id}).then((response) => {
        this.clearItemFromList(id);
      });
    },

    clearItemFromList(id) {
      for (var i = 0; i < this.items.data.length; i++) {
        if (this.items.data[i].id == id) {
          this.items.data.splice(i, 1);
          break;
        }
      }
    },    

    manager(which) {
      this.$dispatch('image::manager::view', which);
    },

    nextPage() {
      if (this.items.current_page == this.items.last_page) return;
      this.getItems( this.items.current_page + 1);
    },

    prevPage() {
      if (this.items.current_page == 1) return;
      this.getItems( this.items.current_page - 1);
    },

    selectItem(event, item) {
      event.preventDefault();
      this.item = item;
      this.visible = 'edit';
    },

    saveItem() {
      this.resource.update({id: this.item.id}, this.item).then((response) =>  {
        this.getItems(1);
        this.visible = 'list';
      });
    },

    addSize() {
      this.resource.save({
        name: 'New Crop',
        aspect_ratio: '16:9',
        max_dimension: 640
      }).then((response) =>  {
        this.item = response.data
        this.visible = 'edit';
      });
    },
  

    getItems(pageNumber) {
      var options = {
        page: pageNumber ? pageNumber : 1
      };

      this.resource.get(options).then((response) => {
        this.$set('items', response.data);
      });
    }
  },

  events: {
    'confirm::confirmed'(id) {
      this.removeItem(id);
      return false;
    }
  },  

  ready() {
    var resource = this.$resource('cropsize{/id}');
    this.$set('resource', resource);
    this.getItems(1);
  }  
}
</script>